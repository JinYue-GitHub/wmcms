<?php
/**
* 图片操作类
*
* @version        $Id: img.class.php 2015年8月9日 16:38 weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime 		  2015年11月27日 16:19 weimeng
* 文字生成图片需要自动换行ToImg
*/
class img {
	//如果使用new该类则无需调用获取字体路径，
	//使用静态方法则需要调用
	//如果需要自定义字体，请给参数设置路径即可
	private static $fontPath = '';
	
	//构造函数，new的时候自动设置路径
	function __construct()
	{
		self::$fontPath=self::GetFontPath();
	}

	/**
	 * 获得字体路径地址
	 * 返回值，路径的url
	 */
	private static function GetFontPath(){
		//如果字体文件已经设置了路径
		if(self::$fontPath == '')
		{
			return WMROOT.'files/fonts/cn_lanting.ttf';
		}
		else
		{
			return self::$fontPath;
		}
	}
	
	
	//获得图片的信息
    static function Info($img) {
		//判断图片是否存在
		if( file_exists($img) || file_exists(WMROOT.$img)){
			if( !file_exists($img) )
			{
				$img = WMROOT.$img;
			}
			$imageInfo = getimagesize($img);
			if ( $imageInfo !== false )
			{
				$imageType = strtolower(substr(image_type_to_extension($imageInfo[2]), 1));
				$imageSize = filesize($img);
				$info = array(
					"width" => $imageInfo[0],
					"height" => $imageInfo[1],
					"type" => $imageType,
					"size" => $imageSize,
					"mime" => $imageInfo['mime']
				);
				return $info;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
    }
	

    /**
     * 生成图片验证码
     * @param 参数1，选填，生成验证码的长度，默认为4
     * @param 参数2，选填，图片验证码的字体大小，默认20
     * @param 参数3，选填，图片的宽度，默认根据字体自动调整
     * @param 参数4，选填，图片的高度，默认根据字体自动调整
     * @param 参数5，选填，是否返回base64代码
     * 直接输出图片验证码。
     */
    static function ImgCode($len=4 , $size = 20 , $width = 0 , $height = 0 , $base64 = 0){
    	//随机数
    	$randCode = str::RandStr();
    	
		//设置session
		$sessionCode = Session('form_code/a');
		$url = md5(C('config.api.system.api_apikey').$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
		if( isset($_GET['id']) && $_GET['id'] != '' )
		{
			$sessionCode[$_GET['id']] = $randCode;
		}
		else
		{
			$sessionCode[$url] = $randCode;
		}
		Session('form_code',$sessionCode);
		
    	//字体路径
    	$fontPath = self::GetFontPath();
    	
    	//宽度和高度
    	!$width && $width = $len * $size * 4 / 5 + 5;
		!$height && $height = $size + 10;
		
		// 画图像
		$im = imagecreatetruecolor($width, $height);
		// 定义要用到的颜色
		$backColor = imagecolorallocate($im, 235, 236, 237);
		$boerColor = imagecolorallocate($im, 118, 151, 199);
		$textColor = imagecolorallocate($im, mt_rand(0, 200), mt_rand(0, 120), mt_rand(0, 120));
		// 画背景
		imagefilledrectangle($im, 0, 0, $width, $height, $backColor);
		// 画边框
		imagerectangle($im, 0, 0, $width-1, $height-1, $boerColor);
		// 画干扰线
		for($i = 0;$i < 5;$i++)
		{
			$fontColor = imagecolorallocate($im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
			imagearc($im, mt_rand(- $width, $width), mt_rand(- $height, $height), mt_rand(30, $width * 2), mt_rand(20, $height * 2), mt_rand(0, 360), mt_rand(0, 360), $fontColor);
		}
		// 画干扰点
		for($i = 0;$i < 50;$i++)
		{
			$fontColor = imagecolorallocate($im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
			imagesetpixel($im, mt_rand(0, $width), mt_rand(0, $height), $fontColor);
		}
		
		// 画验证码
		@imagefttext($im, $size , 0, 5, $size + 3, $textColor, $fontPath, $randCode);
		
		//是否返回base64
		if( $base64 == 1 )
		{
			ob_start();
			imagepng($im);
			imagedestroy($im);
			$imageData = ob_get_contents();
			ob_end_clean();
			return "data:image/png;base64,". base64_encode($imageData);
		}
		else
		{
			header("Cache-Control: max-age=1, s-maxage=1, no-cache, must-revalidate");
			header("Content-type: image/png;charset=utf-8");
			imagepng($im);
			imagedestroy($im);
			exit;
		}
    }
    
    
	/**
	 * 图片剪裁
	 * @param 参数1，必填，原始图片的地址
	 * @param 参数2，选填，剪裁后的图片宽度，如果宽度和高度同时为0，表示需要读取网站基本配置
	 * @param 参数3，选填，剪裁后的图片高度，如果宽度和高度同时为0，表示需要读取网站基本配置
	 * @param 参数4，选填，是否以新的文件保存，否则覆盖原图
	 */
	static function ImgCut($imgPath, $newWidth = '0', $newHeight = '0',$newPath = '')
	{
		//设置图片类型
		$imgType = '';
		//如果是远程地址就不进行操作。
		if( strpos($imgPath,'ttps://') !== false || strpos($imgPath,'ttp://') !== false)
		{
			return false;
		}
		else
		{
			//图片信息，获得宽度、高度和图片的类型信息
			$imgInfo   = getimagesize($imgPath);
			$imgWidth  = $imgInfo[0];
			$imgHeight = $imgInfo[1];
			$imgMine   = $imgInfo['mime'];
			
			//如果没有设置图片剪裁后的宽高就进行此操作。
			if( $newWidth == '0' || $newHeight == '0' )
			{
				//是否剪裁图片
				$uploadCut = C('config.web.upload_cut');
				//上传图片达到多少宽度需要进行剪裁
				$cutWidth = C('config.web.upload_cutwidth');
				$cutHeight = C('config.web.upload_cutheight');
				//剪裁后的大小
				$newWidth = C('config.web.upload_cutwidth');
				$newHeight = C('config.web.upload_cutheight');
	
				//如果图片宽高任意一项低于了剪裁设置就不进行剪裁操作。
				if( $uploadCut != '1' || $imgWidth < $cutWidth || $imgHeight < $cutHeight )
				{
					return false;
				}
			}
		}
		
		//原始图片比例和剪裁后的图片比例
		$imgRatio  = $imgHeight / $imgWidth;
		$newRatio  = $newHeight / $newWidth;
		
	 
		//如果原图的比例大于新图的比例
		if ($imgRatio > $newRatio)
		{
			$croppedWidth  = $imgWidth;
			$croppedHeight = $imgWidth * $newRatio;
			$sourceX = 0;
			$sourceY = ($imgHeight - $croppedHeight) / 2;
		}
		//如果原图的比例小于新图的比例
		elseif ($imgRatio < $newRatio)
		{
			$croppedWidth  = $imgHeight / $newRatio;
			$croppedHeight = $imgHeight;
			$sourceX = ($imgWidth - $croppedWidth) / 2;
			$sourceY = 0;
		}
		else
		{
			$croppedWidth  = $imgWidth;
			$croppedHeight = $imgHeight;
			$sourceX = 0;
			$sourceY = 0;
		}
	 
		switch ($imgMine)
		{
			case 'image/gif':
				$imgType = imagecreatefromgif($imgPath);
				break;
	 
			case 'image/jpeg':
				$imgType = imagecreatefromjpeg($imgPath);
				break;
	 
			case 'image/png':
				$imgType = imagecreatefrompng($imgPath);
				break;
	 
			default:
				return false;
				break;
		}
	 
		//设置新图的宽度和高度句柄
		$targetImage  = imagecreatetruecolor($newWidth, $newHeight);
		$croppedImage = imagecreatetruecolor($croppedWidth, $croppedHeight);
		
		
		//设置图片的背景颜色;
		$alpha = imagecolorallocatealpha($targetImage, 0, 0, 0, 127);
		imagefill($targetImage, 0, 0, $alpha);
		$alpha = imagecolorallocatealpha($croppedImage, 0, 0, 0, 127);
		imagefill($croppedImage, 0, 0, $alpha);
		
		//裁剪
		imagecopy($croppedImage, $imgType, 0, 0, $sourceX, $sourceY, $croppedWidth, $croppedHeight); 
		//缩放
		imagecopyresampled($targetImage, $croppedImage, 0, 0, 0, 0, $newWidth, $newHeight, $croppedWidth, $croppedHeight);
		
		//设置为透明的背景颜色
		imagesavealpha($targetImage, true);
		imagesavealpha($croppedImage, true);
		
		//是否保存为新的图片
		if( trim($newPath) != '' ){
			$savePath = $newPath;
		}else{
			$savePath = $imgPath;
		}
		switch ($imgMine)
		{
			case 'image/png':
				imagepng($targetImage,$savePath);
				break;
			default:
				imagejpeg($targetImage,$savePath);
				break;
		}
		
		//销毁图像
		imagedestroy($imgType);
		imagedestroy($targetImage);
		imagedestroy($croppedImage);
		
		return true;
	}

	
	/**
	 * 图片剪裁
	 * @param 参数1，必填，原始图片的地址
	 * @param 参数2，选填，剪裁后的图片宽度，如果宽度和高度同时为0，表示需要读取网站基本配置
	 * @param 参数3，选填，剪裁后的图片高度，如果宽度和高度同时为0，表示需要读取网站基本配置
	 */
	static function Simg($imgPath, $newWidth = '0', $newHeight = '0')
	{
		//设置图片类型
		$imgType = '';
		//图片信息，获得宽度、高度和图片的类型信息
		$imgInfo   = getimagesize($imgPath);
		$imgWidth  = $imgInfo[0];
		$imgHeight = $imgInfo[1];
		$imgMine   = $imgInfo['mime'];
		
		//如果没有设置图片缩略图的宽高就进行此操作。
		if( $newWidth == '0' || $newHeight == '0' )
		{
			//是否自动生成缩略图
			$uploadCut = C('config.web.upload_simg');
			//上传图片达到多少宽度需要进行缩略图
			$cutWidth = C('config.web.upload_simg_width');
			$cutHeight = C('config.web.upload_simg_height');
			//缩略图的大小
			$newWidth = C('config.web.upload_simgwidth');
			$newHeight = C('config.web.upload_simgheight');

			//如果图片宽高任意一项低于了缩略图设置就不进行缩略图操作。
			if( $uploadCut != '1' || $imgWidth < $cutWidth || $imgHeight < $cutHeight )
			{
				return false;
			}
		}
		$savePath = SImg($imgPath,true);
		return self::ImgCut($imgPath,$newWidth,$newHeight,$savePath);
	}
	
	
	
	/**
	 * 将文字转换成图片输出
	 * @param unknown $str
	 * @param string $savePath
	 * @param number $imgWidth
	 * @param string $size
	 * @param unknown $bg
	 * @param unknown $fbg
	 */
    static function ToImg($str , $savePath = '', $imgWidth = 900, $size = '15' , $bg = array(0,0,0) , $fbg = array(255,255,255) )
    {
    	require_once dirname(__FILE__).'\str.class.php';
    	
    	$str = str::ToTxt($str);
    	
    	//绘制的字体
		$font = self::GetFontPath();
		
		// 旋转角度
		$rot = 0;
		
		// 距离两边的距离
		$pad = 10;
		
		// 文字透明度.
		$transparent = 10; 
		
		// 设置背景颜色
		$red = $bg[0];
		$grn = $bg[1];
		$blu = $bg[2];
		
		// 设置文字颜色
		$bg_red = $fbg[0]; 
		$bg_grn = $fbg[1];
		$bg_blu = $fbg[2];
		
		$width = 0;
		$height = 0;
		$offset_x = 0;
		$offset_y = 0;
		
		$bounds = array();
		//
		$image = '';
		$newStr = '';
		
		// 确定文字高度.
		$bounds = ImageTTFBBox($size, $rot, $font, "W");
		if ($rot < 0) {
			$font_height = abs($bounds[7]-$bounds[1]);
		} else if ($rot > 0) {
			$font_height = abs($bounds[1]-$bounds[7]);
		} else {
			$font_height = abs($bounds[7]-$bounds[1]);
		}
		// 确定边框高度.
		$bounds = ImageTTFBBox($size, $rot, $font, $str);
		if ($rot < 0)
		{
			$width = abs($bounds[4]-$bounds[0]);
			$height = abs($bounds[3]-$bounds[7]);
			$offset_y = $font_height;
			$offset_x = 0;
		}
		else if ($rot > 0)
		{
			$width = abs($bounds[2]-$bounds[6]);
			$height = abs($bounds[1]-$bounds[5]);
			$offset_y = abs($bounds[7]-$bounds[5])+$font_height;
			$offset_x = abs($bounds[0]-$bounds[6]);
		}
		else
		{
			$width = abs($bounds[4]-$bounds[6]);
			$height = abs($bounds[7]-$bounds[1]);
			$offset_y = $font_height;;
			$offset_x = 0;
		}
		
		$image = imagecreate($width+($pad*2)+1,$height+($pad*2)+1);
		$background = ImageColorAllocate($image, $bg_red, $bg_grn, $bg_blu);
		$foreground = ImageColorAllocate($image, $red, $grn, $blu);
		
		
		if ($transparent) ImageColorTransparent($image, $background);
		ImageInterlace($image, false);
		// 画图.
		ImageTTFText($image, $size, $rot, $offset_x+$pad, $offset_y+$pad, $foreground, $font, $str);
		
		// 如果保存地址为空则向浏览器直接输出
		if($savePath == '')
		{
			Header("Content-type: image/png");
			imagePNG($image);
		}
		//否则保存图片为文件
		else
		{
			imagePNG($image,$savePath);
		}
	}
	
	


	/**
	 * 图片水印 (水印支持图片或文字)
	 * @param 参数1，必须，原图地址。
	 * @param 参数2，选填，水印位置。
	 * @param 参数3，选填，水印图片地址。
	 * @param 参数4，选填，水印文字内容，三和四选填一个，如果都不填就进行读取配置操作。
	 * @param 参数5，选填，文字大小，默认为10
	 * @param 参数6，选填，文字颜色。默认为黑色。
	 */
	static function WaterMark($groundImage,$waterPos=9,$waterImage="",$waterText="",$textFont=10,$textColor="#000000")
	{
		//如果是远程地址就不进行操作。
		if( strpos($groundImage,'ttps://') !== false || strpos($groundImage,'ttp://') !== false)
		{
			return false;
		}
		
		//如果水印图片和水印文字同时为空，就进行读取配置操作
		if( $waterImage == '' && $waterText == '' )
		{
			//是否开启水印
			$waterMark = C('config.web.watermark_open');
			//启用水印后，需要图片宽高大于多少才进行增加水印
			$WMWidth = C('config.web.watermark_width');
			$WMHeight = C('config.web.watermark_height');
			//水印类型
			$waterType = C('config.web.watermark_type');
			//水印位置
			$waterPos = C('config.web.watermark_location');
			//图片信息，获得宽度、高度和图片的类型信息
			$imgInfo   = getimagesize($groundImage);
			$imgWidth  = $imgInfo[0];
			$imgHeight = $imgInfo[1];
			
			//如果缩略图不为空才执行水印
			if ( $waterMark == '1' && $imgWidth > $WMWidth  && $imgHeight > $WMHeight )
			{
				//定义图片水印位置
				if( $waterType == 'img' )
				{
					//水印图片地址
					$waterImage = WMROOT.'files/images/watermark.png';
				}
				else
				{
					//文字水印内容
					$waterText = C('config.web.watermark_text');
					//文字大小
					$textFont = C('config.web.watermark_size');
					//文字颜色
					$textColor = C('config.web.watermark_color');
				}
			}
			else
			{
				return false;
			}
		}
		
		
		$isWaterImage = FALSE;
		$formatMsg = "暂不支持该文件格式，请用图片处理软件将图片转换为GIF、JPG、PNG格式。";
		
		$fontPath = self::GetFontPath();
		//字体大小
		$textFont = ($textFont)*4;

		
		//判断水印是否用图片模式，并且读取水印文件
		if(!empty($waterImage) && file_exists($waterImage)){
			$isWaterImage = TRUE;
			$water_info = getimagesize($waterImage);
			$water_w = $water_info[0];//取得水印图片的宽
			$water_h = $water_info[1];//取得水印图片的高 
			//取得水印图片的格式 
			switch($water_info[2]){
				case 1:$water_im = imagecreatefromgif($waterImage);break;
				case 2:$water_im = imagecreatefromjpeg($waterImage);break;
				case 3:$water_im = imagecreatefrompng($waterImage);break;
				default:die($formatMsg);
			}
		}

		//读取背景图片
		if(!empty($groundImage) && file_exists($groundImage)) {
			$ground_info = getimagesize($groundImage);
			$ground_w = $ground_info[0];//取得背景图片的宽
			$ground_h = $ground_info[1];//取得背景图片的高
			//取得背景图片的格式
			switch($ground_info[2]){
				case 1:$ground_im = imagecreatefromgif($groundImage);break;
				case 2:$ground_im = imagecreatefromjpeg($groundImage);break;
				case 3:$ground_im = imagecreatefrompng($groundImage);break;
				default:die($formatMsg);
			}
		}else{
			die("需要加水印的图片不存在！");
		}

		//图片水印
		if($isWaterImage)
		{
			$w = $water_w;
			$h = $water_h;
			$label = "图片的";
		}
		//文字水印 
		else
		{
			//取得使用 TrueType 字体的文本的范围
			$temp = imagettfbbox($textFont,0,$fontPath,$waterText);
			$w = $temp[2] - $temp[6];
			$h = $temp[3] - $temp[7];
			unset($temp);
			$label = "文字区域";
		}
		
		switch($waterPos){
			case 0://随机
				$posX = rand(0,($ground_w - $w));
				$posY = rand(0,($ground_h - $h));
				break;
			case 1://1为顶端居左
				$posX = 0;
				$posY = 0;
				break;
			case 2://2为顶端居中
				$posX = ($ground_w - $w) / 2;
				$posY = 0;
				break;
			case 3://3为顶端居右
				$posX = $ground_w - $w;
				$posY = 0;
				break;
			case 4://4为中部居左
				$posX = 0;
				$posY = ($ground_h - $h) / 2;
				break;
			case 5://5为中部居中
				$posX = ($ground_w - $w) / 2;
				$posY = ($ground_h - $h) / 2;
				break;
			case 6://6为中部居右
				$posX = $ground_w - $w;
				$posY = ($ground_h - $h) / 2;
				break;
			case 7://7为底端居左
				$posX = 0;
				$posY = $ground_h - $h;
				break;
			case 8://8为底端居中
				$posX = ($ground_w - $w) / 2;
				$posY = $ground_h - $h;
				break;
			case 9://9为底端居右
				$posX = $ground_w - $w - 10;   // -10 是距离右侧10px 可以自己调节
				$posY = $ground_h - $h - 20;   // -10 是距离底部10px 可以自己调节
				break;

			default://随机
				$posX = rand(0,($ground_w - $w));
				$posY = rand(0,($ground_h - $h));
				break;

		}

		//设定图像的混色模式
		imagealphablending($ground_im, true);

		if($isWaterImage){//图片水印
			imagecopy($ground_im, $water_im, $posX, $posY, 0, 0, $water_w,$water_h);//拷贝水印到目标文件 
		}else{//文字水印
			if(!empty($textColor) && (strlen($textColor)==7) ){
				$R = hexdec(substr($textColor,1,2));
				$G = hexdec(substr($textColor,3,2));
				$B = hexdec(substr($textColor,5));
			}else{
				die("水印文字颜色格式不正确！");
			}

			ImageTTFText($ground_im,$textFont,0, $posX, $posY,imagecolorallocate($ground_im, $R, $G, $B), $fontPath,$waterText);
		}
		
		
		//生成水印后的图片
		unlink($groundImage);
		switch($ground_info[2]){//取得背景图片的格式
			case 1:imagegif($ground_im,$groundImage);break;
			case 2:imagejpeg($ground_im,$groundImage);break;
			case 3:imagepng($ground_im,$groundImage);break;
			default:die($errorMsg);
		}

		//释放内存
		if(isset($water_info)) unset($water_info);
		if(isset($water_im)) imagedestroy($water_im);
		unset($ground_info);
		imagedestroy($ground_im);
	}
	
	
	/**
	 * 将图片转为圆形的png图片
	 * @param 参数1，必填，原图的地址
	 * @param 参数2，选填，是否另存为
	 */
	static function ToRound($imgPath,$savePath='')
	{
		$ext     = pathinfo($imgPath);
		

		$imgInfo   = getimagesize($imgPath);
		$imgMine   = $imgInfo['mime'];
		$src_img = null;
		switch ($imgMine)
		{
			case 'image/gif':
				$src_img = imagecreatefromgif($imgPath);
				break;
	 
			case 'image/jpeg':
				$src_img = imagecreatefromjpeg($imgPath);
				break;
	 
			case 'image/png':
				$src_img = imagecreatefrompng($imgPath);
				break;
	 
			default:
				return false;
				break;
		}
		$wh  = getimagesize($imgPath);
		$w   = $wh[0];
		$h   = $wh[1];
		$w   = min($w, $h);
		$h   = $w;
		$img = imagecreatetruecolor($w, $h);
		//这一句一定要有
		imagesavealpha($img, true);
		//拾取一个完全透明的颜色,最后一个参数127为全透明
		$bg = imagecolorallocatealpha($img, 255, 255, 255, 127);
		imagefill($img, 0, 0, $bg);
		$r   = $w / 2; //圆半径
		$y_x = $r; //圆心X坐标
		$y_y = $r; //圆心Y坐标
		for ($x = 0; $x < $w; $x++)
		{
			for ($y = 0; $y < $h; $y++)
			{
				$rgbColor = imagecolorat($src_img, $x, $y);
				if (((($x - $r) * ($x - $r) + ($y - $r) * ($y - $r)) < ($r * $r)))
				{
					imagesetpixel($img, $x, $y, $rgbColor);
				}
			}
		}
		//另存的地址不为空就设置
		if( $savePath != '' )
		{
			$imgPath = $savePath;
		}
		imagepng($img,$imgPath); //保存的路径
		return true;
	}
}
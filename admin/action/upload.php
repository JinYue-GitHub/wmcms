<?php
/**
* 后台上传文件处理文件
*
* @version        $Id: upload.php 2016年4月8日 9:54  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime 		  2016年4月25日 16:35 weimeng
*
*/
$uploadSer = NewClass('upload' , isset($_FILES['file'])?$_FILES['file']:array() );
//上传的路径
$uploadPath = '../upload/';

//上传预设的模版
if( $type == 'templates' )
{
	$uploadPath .= 'templates/';
	$uploadSer->set_extention( 'html' );
}
//上传图片类文件
else if( $type == 'img' )
{
	$uploadPath .= 'images/';
	$ext = str::ArrToStr(str::IsImg());
	$uploadSer->set_extention( $ext );
}
//上传LOGO
else if( $type == 'logo' )
{
	$uploadPath = '../files/images/';
	$ext = str::ArrToStr(str::IsImg());
	//不需要子目录
	$uploadSer->set_no_directory();
	$uploadSer->set_datetime( Request('name') );
	$uploadSer->set_extention( $ext );
}
//上传静态资源文件
else if( $type == 'static' )
{
	$id = Request('id');
	$path = Request('path');
	$uploadPath = '../files/static/'.$id.'/';

	//不需要子目录
	$uploadSer->set_no_directory();
	$uploadSer->set_extention( 'zip' );
}
//文件上传
else if( $type == 'file' )
{
	$uploadPath .= 'files/';
	$uploadSer->set_extention( $C['config']['web']['upload_type'] );
}
//微信素材类文件
else if( $type == 'weixin_media' )
{
	$uploadPath .= 'images/';
	$ext = str::ArrToStr(str::IsImg());
	$uploadSer->set_extention( $ext );
}


//文件存储根目录名称
$uploadSer->set_base_directory($uploadPath);
//保存文件
$result = $uploadSer->save();


//上传完成后的操作
if ( $result['code'] == '500')
{
	Ajax( $result['msg'] , 300 );
}
else
{
	//绝对定位
	$result['abspath'] = str_replace('../', '/', $result['path']);
	
	//检查是否存在模块分类
	$module = Request('module','system');
	$uType = Request('utype',$type);
	$cid = Request('cid/i',0);
	if ( $module != '' )
	{
		//如果是图片就检测图片宽高
		if( str::IsImg($result['ext']) )
		{
			$imgSer = NewClass('img');
			$imgInfo = $imgSer->Info(WMROOT.$result['abspath']);
			$data['upload_width'] = $imgInfo['width'];
			$data['upload_height'] = $imgInfo['height'];
			
			$imgSer = NewClass('img');
			//图片生成缩略图
			$imgSer->Simg( WMROOT.$result['abspath']);
			//图片裁剪
			$imgSer->ImgCut( WMROOT.$result['abspath'] , '0' , '0');
			//图片加水印
			$imgSer->WaterMark( WMROOT.$result['abspath'] );
		}
		//检测数据,如果存在模块就进行入库
		$data['upload_module'] = $module;
		$data['upload_type'] = $uType;
		$data['upload_cid'] = $cid;
		$data['upload_alt'] = $result['name_noext'];
		$data['upload_ext'] = $result['ext'];
		$data['upload_simg'] = str_replace('../', '/', $result['simg']);
		$data['upload_img'] = $result['abspath'];
		$data['upload_size'] = $result['size'];
		$data['upload_time'] = time();
		//文件id
		$result['fileid'] = WMSql::Insert('@upload', $data);
	}
	
	//如果是上传的静态资源文件就进行解压
	if( $type == 'static' )
	{
		$zip = NewClass('pclzip',$result['path']);
		//解压缩到当前id文件夹下面
		if ($zip->extract(PCLZIP_OPT_PATH, $uploadPath.$path) == 0)
		{
			Ajax( $zip->errorInfo(true) , 300);
		}
		//删除压缩文件
		file::DelFile(WMROOT.$result['path']);
	}
	
	Ajax( '上传成功！' , '200' , $result);
}
?>
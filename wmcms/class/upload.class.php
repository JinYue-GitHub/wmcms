<?php
/**
* 附件上传类
*
* @version        $Id: upload.class.php 2015年8月20日 21:50  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime 		  2016年1月16日 11:47 weimeng
*
*/
/*
   include("upload.class.php");	//加入类文件
   $f_upload = new upload_file(); //创建对象
   $f_upload->set_file_type($_FILES['src']['type']);   //获得文件类型
   $f_upload->set_file_name($_FILES['src']['name']);   //获得文件名称
   $f_upload->set_file_size($_FILES['src']['size']);   //获得文件尺寸
   $f_upload->set_upfile($_FILES['src']['tmp_name']);  //服务端储存的临时文件名
   $f_upload->set_size(100); //设置最大上传KB数
   $f_upload->set_base_directory("upload2"); //文件存储根目录名称
   $f_upload->set_url("index.html"); //文件上传成功后跳转的文件
   $f_upload->save(); //保存文件
*/
//腾讯云命名空间
use qcloudcos\Cosapi;
//七牛云命名空间
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
//阿里云命名空间
use OSS\OssClient;
use OSS\Core\OssException;

class upload {
	/**声明**/
	var $error,$upfile_type,$upfile_size,$upfile_name,$upfile_name_noext,$upfile;
	var $d_alt,$extention_list,$tmp,$arri;
	var $filename,$date,$simg_path;
	var $filestr,$ext,$check;
	var $flash_directory,$extention,$file_path,$base_directory,$root_path; 
	var $url;
	var $saveType,$api,$savePath;
	public $noDir = false;

	/**构造函数**/
	/**
	 * 构造函数
	 * @param 参数1，选填，文件数组。
	 * @param 参数2，选填，指定文件名字。
	 */
	function __construct( $file = '' , $name = '' )
	{
		global $C;
		$this->saveType = $C['config']['web']['upload_savetype'];
		$this->savePath = $C['config']['web']['upload_savepath'];
		$this->upoadSize = $C['config']['web']['upload_size'];
		if( $this->saveType != '0' )
		{
			$this->api = $C['config']['api'][$this->saveType];
			$this->api['api_option'] = unserialize(str::ToHtml(str::Escape($this->api['api_option'],'d'),true));
		}
		$error = '0';
		if( isset($file['error']) )
		{
			$error = $file['error'];	
		}
		//初始化上传文件KB限制，如果为0就设置为10g
		if( $this->upoadSize <=0 )
		{
			$this->upoadSize = '102400000';
		}
		$this->set_datetime($name); //设置文件名称前缀;
		$this->set_size($this->upoadSize); //初始化上传文件KB限制;
		$this->set_error($error);//设置错误代码
		$this->set_file_type( $file['type'] );   //获得文件类型
		$this->set_file_name( $file['name'] );   //获得文件名称
		$this->set_file_size( $file['size'] );   //获得文件尺寸
		$this->set_upfile( $file['tmp_name'] );  //服务端储存的临时文件名
		//取得文件扩展名;
		$this->extention = preg_replace('/.*\.(.*[^\.].*)*/iU','\\1',$this->upfile_name);
	}

	/**
	 * 检查上传错误
	 * @param 参数1，必须错误代码
	 */
	function check_error()
	{
		switch($this->error)
		{
			case '1':
				return $this->showerror('上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值。');
				break;
				
			case '2':
				return $this->showerror('上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值。');
				break;
				
			case '3':
				return $this->showerror('文件只有部分被上传。');
				break;
				
			case '4':
				return $this->showerror('没有文件被上传。');
				break;
				
			case '0':
				return true;
				break;
		}
	}

	/**文件类型**/
	function set_error($error)
	{
		$this->error = $error; //取得错误代码;
	}
	/**文件类型**/
	function set_file_type($upfile_type)
	{
		$this->upfile_type = $upfile_type; //取得文件类型;
	}
  
	/**获得文件名**/
	function set_file_name($upfile_name)
	{
		$this->upfile_name = $upfile_name;//取得文件名称;
		//文件名字不含后缀
		$pathinfo = pathinfo($upfile_name);
		$this->upfile_name_noext = $pathinfo['filename'];
	}
  
	/**获得文件**/
	function set_upfile($upfile)
	{
		$this->upfile = $upfile; //取得文件在服务端储存的临时文件名;
	}

	/**获得文件大小**/
	function set_file_size($upfile_size)
	{
		$this->upfile_size = $upfile_size; //取得文件尺寸;
	}

	/**设置文件上传成功后跳转路径**/
	function set_url($url)
	{
		$this->url = $url;	//设置成功上传文件后的跳转路径;
	}
  
	/**获得文件扩展名**/
	function get_extention()
	{
		$this->extention = preg_replace('/.*\.(.*[^\.].*)*/iU','\\1',$this->upfile_name); //取得文件扩展名;
	} 

	/**
	 * 设置文件名称
	 * @param 参数1，选填，指定文件的名字
	 */
	function set_datetime( $name = '' )
	{
		if( $name == '' )
		{
			$fileName = date("YmdHis").GetMtime().rand(100000, 999999);
		}
		else
		{
			$fileName = $name;
		}
		if( $this->saveType != 0 )
		{
			$fileName = md5(sha1($fileName));
		}
		$this->filename = $fileName; //按时间生成文件名;
	}


	/**初始化允许上传文件类型**/
	function set_extention( $type ){
		$this->extention_list = $type; //读取网站允许上传类型;
		//$this->extention_list = "jpg,png,bmp,doc,xls"; //读取网站允许上传类型;
	}  

	/**设置最大上传KB限制**/
	function set_size($size)
	{
		$this->size = $size; //设置最大允许上传的文件大小;
	}

	/**初始化文件存储根目录**/
	function set_base_directory($directory)
	{
		//如果没有指定子目录
		if($directory=="../upload/" || $directory=="../../upload/"){
			if($_FILES['file']['type']=='image/gif' || $_FILES['file']['type']=='image/jpeg' || $_FILES['file']['type']=='image/png'){
				$directory=$directory.'image/';
			}else{
				$directory=$directory.'file/';
			}
		}
		//指定子目录。20150704格式。

		//如果没有设置不建立子目录
		if ( !$this->noDir )
		{
			$directory = $directory.date("Ymd",time()).'/';
		}
		$this->base_directory = $directory; //生成文件存储根目录;
	}

	/**初始化文件存储子目录**/
	function set_flash_directory()
	{
		$this->flash_directory = $this->base_directory; //生成文件存储子目录;
	}

	/**错误处理**/
	function showerror($errstr="未知错误！"){
		$arr['code'] = 500;
		$arr['msg'] = $errstr;
		return $arr;
	}

	/**如果根目录没有创建则创建文件存储目录**/
	function mk_base_dir()
	{
		if (! file_exists($this->base_directory)){ //检测根目录是否存在;
			mkdir($this->base_directory,0777); //不存在则创建;
		}
	} 

	/**如果子目录没有创建则创建文件存储目录**/
	function mk_dir()
	{
		if (! file_exists($this->flash_directory)){ //检测子目录是否存在;
			mkdir($this->flash_directory,0777); //不存在则创建;
		}
	}  

	/**以数组的形式获得分解后的允许上传的文件类型**/
	function get_compare_extention()
	{
		$this->ext = explode(",",$this->extention_list); //以","来分解默认扩展名;
	}

	/**检测扩展名是否违规**/
	function check_extention()
	{
		$this->check = false;
		//遍历数组;
		foreach ($this->ext as $k=>$v)
		{
			if(strtolower($this->ext[$k]) == strtolower($this->extention)) //比较文件扩展名是否与默认允许的扩展名相符;
			{
				$this->check = true; //相符则标记;
				break;
			}
		}
		//不符则警告
		if( $this->check === false )
		{
			return $this->showerror("对不起,只允许上传".$this->extention_list."！");
		}
		else
		{
			return true;
		}
	}

	/**检测文件大小是否超标**/
	function check_size()
	{
		if($this->upfile_size > round($this->size*1024)) //文件的大小是否超过了默认的尺寸;
		{
			return $this->showerror("附件大小不能超过".$this->size."KB"); //超过则警告;
		}
		else
		{
			return true;
		}
	}

	/**文件完整访问路径**/
	function set_file_path(){
		$this->file_path = $this->flash_directory.$this->filename.".".$this->extention; //生成文件完整访问路径;
		$this->simg_path = $this->flash_directory.$this->filename."_s.".$this->extention; //生成文件完整访问路径;
		
		$this->root_path = str_replace('../../', '/', $this->file_path);
		$this->root_path = str_replace('../', '/', $this->root_path);
	}

	/**上传文件**/
	function copy_file()
	{
		//检查文件
		if( !$this->CheckHex() )
		{
			return $this->showerror("文件未通过安全检测！");
		}
		//本地上传
		if( $this->saveType == '0' )
		{
			if(copy($this->upfile,$this->file_path)){ //上传成功;
				$file['code'] = 200;
				$file['file'] = $this->root_path;
				$file['path'] = $this->file_path;
				$file['simg'] = $this->simg_path;
				$file['size'] = $this->upfile_size;
				$file['name'] = $this->upfile_name;
				$file['name_noext'] = $this->upfile_name_noext;
				$file['filename'] = $this->filename.'.'.$this->extention;
				$file['simgname'] = $this->filename.'_s.'.$this->extention;
				$file['ext'] = strtolower($this->extention);
				return $file;
			}
			//上传失败;
			else
			{
				return $this->showerror("意外错误，请重试！");
			}
		}
		//如果为远程上传
		else
		{
			//本地文件位置
			$localPath = $this->upfile;
			//保存的文件路径
			$save = $this->base_directory.$this->filename.'.'.$this->extention;
			$save = str_replace('../', '', $save);
			$savePath = $save;
			//远程保存路径前缀
			if( $this->savePath != '' )
			{
				$savePath = $this->savePath.'/'.$save;
			}
			//api信息
			$open = $this->api['api_open'];

			define('OSS_APP_ID',GetKey($this->api,'api_appid'));
			define('OSS_API_KEY',GetKey($this->api,'api_apikey'));
			define('OSS_SC_KEY',GetKey($this->api,'api_secretkey'));
			$bucket = GetKey($this->api,'api_option,bucket,value');
			$point = GetKey($this->api,'api_option,point,value');
			$domain = GetKey($this->api,'api_option,domain,value');
			if( $open == 0 )
			{
				return $this->showerror("对不起，远程上传接口已经关闭！");
			}
			else if( $bucket == '' )
			{
				return $this->showerror("对不起，bucket不能为空！");
			}

			include WMAPI.'oss/'.$this->saveType.'/autoload.php';
			switch ($this->saveType)
			{
				//腾讯云存储
				case 'cos':
					//设置COS所在的区域，对应关系如下：
					Cosapi::setRegion($point);
					$ret = Cosapi::upload($bucket, $localPath , $savePath);
					if( $ret['code'] != '0' )
					{
						return $this->showerror($ret['message']);
					}
					$fileUrl = $ret['data']['access_url'];
					break;
					
				//七牛云存储
				case 'qiniu':
					// 上传到七牛后保存的文件名
					$savePath = md5($this->filename).'.'.$this->extention;
					if( $this->savePath != '' )
					{
						$savePath = $this->savePath.'-'.md5($this->filename).'.'.$this->extention;
					}
					// 构建鉴权对象
					$auth = new Auth(OSS_API_KEY, OSS_SC_KEY);
					$token = $auth->uploadToken($bucket);
					// 初始化 UploadManager 对象并进行文件的上传。
					$uploadMgr = new UploadManager();
					list($ret , $err) = $uploadMgr->putFile($token, $savePath, $localPath);
					if( $err !== null)
					{
						return $this->showerror($err->error);
					}
					$fileUrl = $domain.'/'.$ret['key'];
					break;
					
				//新浪云
				case 'scs':
					define('AccessKey', OSS_API_KEY);
					define('SecretKey', OSS_SC_KEY);
					$scs = new SCS(AccessKey, SecretKey);
					// 上传文件
					$res = $scs->putObjectFile($localPath, $bucket,$savePath, SCS::ACL_PUBLIC_READ);
					if( !$res )
					{
						return $this->showerror('SCS::putObjectFile(): Failed to copy file');
					}
					//获得地址保存
					$fileUrl = SCS::getAuthenticatedURL($bucket, $savePath, 86400000);
					break;
					
				//阿里云
				case 'oss':
					try
					{
						$ossClient = new OssClient(OSS_APP_ID, OSS_SC_KEY, $point);
						$ret = $ossClient->uploadFile($bucket, $savePath ,$localPath);
						$fileUrl = $ret['info']['url'];
					}
					catch (OssException $e)
					{
						return $this->showerror($e->getMessage());
					}
					break;
				
				default:
					return $this->showerror("对不起，没有的远程存储！");
					break;
			}
			$file['code'] = 200;
			$file['file'] = $file['path'] = $file['simg'] = $fileUrl;
			$file['filename'] = $this->filename.'.'.$this->extention;
			$file['name_noext'] = $this->upfile_name_noext;
			$file['name'] = $this->upfile_name;
			$file['size'] = $this->upfile_size;
			$file['ext'] = $this->extention;
			return $file;
		}
	}
	
	/**设置是否需要子目录**/
	function set_no_directory()
	{
		$this->noDir = true; //生成文件存储子目录;
	}
	
	/**完成保存**/
	function save()
	{
		//检查上传错误
		$errorResult = $this->check_error();
		if( $errorResult !== true)
		{
			return $errorResult;
		}
		
		//$this->get_extention();     //获得文件扩展名;
		$this->get_compare_extention(); //以","来分解默认扩展名;
		//检测文件扩展名是否违规;
		$result = $this->check_extention();
		if ( $result !== true )
		{
			return $result;
		}
		//检测文件大小是否超限;
		$result = $this->check_size();
		if ( $result !== true )
		{
			return $result;
		}
		
		//如果是保存到本地就设置
		if( $this->saveType == '0' )
		{
			$this->set_flash_directory();  //初始化文件上传子目录名;
			$this->mk_base_dir();      //如果根目录不存在则创建；
			$this->mk_dir();        //如果子目录不存在则创建;
		}
		
		$this->set_file_path();     //生成文件完整访问路径;
		return $this->copy_file();       //上传文件;
	}
	
	
	/**
	 * 检查上传的文件哈希值，判断是否是包含图片木马特征。
	 * @return number
	 */
	private function CheckHex()
	{
        if (file_exists($this->upfile))
        {
        	//文件文本内容
        	$fileText = file_get_contents($this->upfile);
			//文件大小
        	$fileSize = $this->upfile_size;
            //若文件大于521B文件取头和尾的哈希值
			if ($fileSize > 2048)
            {
				$hexCode = bin2hex(file_get_contents($this->upfile,null,null,rand(0,1024),1024));
				$hexCode .= bin2hex(file_get_contents($this->upfile,null,null,$fileSize-1024,1024));
            }
            //取全部内容的哈希值
            else
            {
                $hexCode = bin2hex($fileText);
            }
			
            /* 匹配16进制中的 <% ( ) %> */
            /* 匹配16进制中的 <? ( ) ?> */
            /* 匹配16进制中的 <script | /script> 大小写亦可*/
            if (preg_match("/(3c25.*?28.*?29.*?253e)|(3c3f.*?28.*?29.*?3f3e)|(3C534352495054)|(2F5343524950543E)|(3C736372697074)|(2F7363726970743E)/is", $hexCode) )
			{
				return false;
			}
            /* 匹配16进制中的 <% %> */
            /* 匹配16进制中的 <?  ?> */
            /* 匹配16进制中的 <script /script> 大小写亦可*/
			else if (preg_match("/(3c25.*?253e)|(3c3f.*?3f3e)|(3C534352495054)|(2F5343524950543E)|(3C736372697074)|(2F7363726970743E)/is", $hexCode) )
            {
            	return false;
            }
			//匹配文件内容是否包含php
			else if (preg_match("/<\?php/is", $fileText) || preg_match("/<\?=/is", $fileText))
            {
            	return false;
            }
			//匹配文件内容是否包含script
			else if (preg_match("/<script/is", $fileText) || preg_match("/<\% /is", $fileText))
            {
            	return false;
            }
            else
            {
            	return true;
            }
        }
        else
        {
            return false;
        }
    }
}
?>
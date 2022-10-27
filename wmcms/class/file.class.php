<?php
/**
* 文件操作类
*
* @version        $Id: file.class.php 2015年8月9日 16:38  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime 		  2017年5月3日 21:23 weimeng
*
*/
class file{
	private static $savePath = '/upload/';
	static $fileList = array();
	
	static function GetPath()
	{
		return WMROOT.'upload/';
	}
	
	/**
	* 下载远程图片到本地服务器
	* @param 参数1，远程图片的路径
	* @param 参数2，是否下载，默认是下载
	* 返回值，保存的路径或者false
	*/
	static function GetImg($url , $isDown = '1')
	{
		if( $isDown != '1' )
		{
			return $url;
		}
		$savePath = self::GetPath();
		if( strpos($url,'ttp://') || strpos($url,'ttps://'))
		{
			$extName = strrchr($url,'.'); //获取图片的扩展名
			if( $extName != '.gif' && $extName != '.jpg' && $extName != '.bmp' && $extName != '.png' && $extName != '.jpeg')
			{
				return false; //不是正确的地址就返回默认地址
			}
			else
			{
				//获得不要.的后缀文件名字
				$extArr = explode('.', $extName);
				$ext = $extArr[count($extArr)-1];
				
				//设置保存位置
				$Path = $savePath.'images/'.date("Ymd").'/';
				//设置文件名
				$fileName='images/'.date("Ymd").'/'.self::GetFileName($ext);
				//判断文件夹是否存在，并且创建。
				self::CreateFolder($Path);
				
				$curl = curl_init($url);
				curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
				// 跳过证书检查
    			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    			// 从证书中检查SSL加密算法是否存在
    			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
				$imageData = curl_exec($curl);
				curl_close($curl);
				
				//判断文件是否存在并且下载
				if(strpos($imageData,'/404/'))
				{
					return '远程图片不存在';
				}
				else
				{
					$tp = fopen($savePath.$fileName, 'a');
					fwrite($tp, $imageData);
					fclose($tp);
					$filePath = self::$savePath.$fileName;
					
					//如果是以oss形式保存文件
					if( C('config.web.upload_savetype') != '0' )
					{
						//绝对地址
						$absPath = str_replace('//', '/', WMROOT.$filePath);
						$imgInfo = getimagesize($absPath);
						$fileInfo['type'] = $imgInfo['mime'];
						$fileInfo['size'] = filesize($absPath);
						$fileInfo['name'] = $fileName;
						$fileInfo['tmp_name'] = $absPath;
						$uploadSer = NewClass('upload' , $fileInfo);
						$uploadSer->set_base_directory('upload/images/');
						$result = $uploadSer->copy_file();
						if( $result['code'] == '200' )
						{
							unlink($absPath);
							return $result['file'];
						}
						else
						{
							return $result['msg'];
						}
					}
					else
					{
						return $filePath;
					}
				}
			}
		}
		else
		{
			return $url;
		}
	}


	/**
	 * 下载远程文件
	 * @param 参数1，必填，远程文件地址。
	 * @param 参数2，选填，默认为1，系统自动命名，2为原始名字，其他为自定义名字。
	 * @param 参数3，选填，保存的路径
	 * @param 参数4，选填，Cookie保存的前缀
	 * @param 参数5，选填，文件的后缀名
	 * 返回值，文件的内容或者为空。
	 */
	static function DownloadFile($fileUrl,$file='1',$folder='',$cookPre='',$fileExt='')
	{
		$savePath = self::GetPath();
		if( strpos($fileUrl,'ttp://') )
		{
			//设置网页超时为300秒
			@set_time_limit(300);
			
			if( empty($fileExt) )
			{
				$fileExt = str::GetLast($fileUrl,'.');
			}
			//判断文件的类型
			if( str::IsImg($fileExt) )
			{
				$fileType = 'images';
			}
			else
			{
				$fileType = 'file';
			}

			//判断文件的命名方式
			switch ($file)
			{
				//系统命名
				case '1':
					$fileName = self::GetFileName($fileExt);
					$filePath = date("Ymd").'/'.self::GetFileName($fileExt);
					break;
				//原始文件名字
				case '2':
					$filePath = str::GetLast($fileUrl,'/');
					list($fileName,$fileExt) = explode('.', $filePath);
					if( $folder == '' )
					{
						$filePath = date("Ymd").'/'.$fileName.'.'.$fileExt;
					}
					else
					{
						$filePath = $fileName.'.'.$fileExt;
					}
					break;
				//自定义名字
				default:
					$fileName = $file;
					$filePath = $file.'.'.$fileExt;
					break;
			}
	
			//如果设置自定义保存路径
			if( $folder != '' )
			{
				//设置保存位置
				$Path = $folder;
				//设置本地保存文件
				$filePath = $folder.'/'.$filePath;
			}
			//系统自动保存
			else
			{
				//设置保存位置
				$Path = $savePath.$fileType.'/'.date("Ymd");
				//设置本地保存文件
				$filePath = $fileType.'/'.$filePath;
			}
			//判断文件夹是否存在，并且创建。
			self::CreateFolder($Path);

			$fileRb = fopen ($fileUrl, "rb");
			if ($fileRb)
			{
				//获取文件大小
				$filesize = -1;
				$headers = get_headers($fileUrl, 1);
				if ( !array_key_exists("Content-Length", $headers) )
				{
					$htmlCode = file_get_contents($fileUrl);
					$rs = @json_decode($htmlCode,true);
					if( empty($rs) )
					{
						return $htmlCode;
					}
					else
					{
						return $rs;
					}
				}
				else
				{
					$filesize = $headers["Content-Length"];
				}
				
				$newf = fopen ($filePath, "wb");
				$downLen = 0;
				if ($newf)
				{
					while(!feof($fileRb)) {
						//默认获取8K
						$data = fread($fileRb, 1024 * 8 );
						//累计已经下载的字节数
						$downLen += strlen($data);
						//设置已经下载的大小
						if( $cookPre != '' )
						{
							$_SESSION[$cookPre.'_downLen'] = $downLen;
						}
						else
						{
							$_SESSION['downLen'] = $downLen;
						}
						fwrite($newf, $data, 1024 * 8 );
					}
				}
				if ($fileRb)
				{
					fclose($fileRb);
				}
				
				if ($newf)
				{
					fclose($newf);
				}
				return $savePath.$fileName;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return $fileUrl;
		}
	}
	
	
	/**
	 * 获得生成的文件名
	 * @param 参数1，选填，生成的文件后缀名
	 */
	static function GetFileName($extName='')
	{
		return date("YmdHis").GetMtime().rand(100000,999999).'.'.$extName;
	}
	
	
	/**
	* 读取文件内容
	* @param 参数1，文件路径。
	* @param 参数2，选填，是否转为html格式
	* 返回值，文件的内容或者为空。
	*/
	static function GetFile($patch,$tohtml = false)
	{
		if(file_exists($patch))
		{
			$content = file_get_contents($patch);
			
			$enCode = 'utf-8';
			$strEncode = mb_detect_encoding($content, array("ASCII",'UTF-8',"GB2312","GBK",'BIG5'));
			if( $strEncode != $enCode )
			{
				$content = mb_convert_encoding( $content , $enCode , $strEncode );
			}
			//$content = mb_convert_encoding( $content , 'utf-8' , 'gb2312' );
			
			if( $tohtml != false)
			{
				$content = str::tohtml($content);
			}
		}
		else
		{
			$content='文件'.$patch.'不存在';
		}
		
		return $content;
	}


	/**
	* 清空目录
	* @param 参数1，需要删除的目录地址
	* @param 参数2，选填，是否是文件
	*/
	static function DelDir($dir , $isFile = 0)
	{
		if($isFile == '1')
		{
			$arr = explode('/',$dir);
			$dir = str_replace($arr[count($arr)-1],'',$dir);
		}
		
		//先删除目录下的文件：
		if( file_exists($dir) )
		{
			$dh=opendir($dir);
			while ($file=readdir($dh))
			{
				if($file!="." && $file!="..")
				{
					$fullpath=$dir."/".$file;
					if(!is_dir($fullpath))
					{
						unlink($fullpath);
					}
					else
					{
						self::DelDir($fullpath);
					}
				}
			}
			closedir($dh);
			//删除当前文件夹：
			rmdir($dir);
		}
	}
	
	/**
	 * 删除文件
	 * @param 参数1，必须，需要删除文件路径
	 */
	static function DelFile( $file )
	{
		$file = str_replace('//','/',$file);
		clearstatcache();
		if( file_exists($file) && is_file($file) )
		{
			unlink($file);
			return true;
		}
		else
		{
			return false;
		}
	}


	/**
	 * 移动文件夹
	 * @param 参数1，旧的文件夹路径或者文件路径
	 * @param 参数2，新的文件路径
	 * @param 参数3，文件名字。
	 */
	static function MoveFolder($oldPath , $newPath)
	{
		$fileList = self::FileList($oldPath);
		//存在文件就拷贝所有文件
		if( $fileList !== false && !empty($fileList))
		{
			foreach ($fileList as $k=>$v)
			{
				if( $v['file'] != '' )
				{
					self::MoveFile($oldPath , $newPath , $v['file']);
				}
			}
		}
		//删除旧目录
		self::DelDir($oldPath);
		return true;
	}
	
	
	/**
	 * 移动文件
	 * @param 参数1，旧的文件夹路径或者文件路径
	 * @param 参数2，新的文件路径
	 * @param 参数3，文件名字。
	 */
	static function MoveFile($oldPath , $newPath , $fileName = '')
	{
		//先创建文件夹
		if( $newPath != '' )
		{
			self::CreateFolder($newPath);
		}
		if( $fileName != '' )
		{
			$fileName = str::EnCoding($fileName);
		}

		//拷贝到新目录
		if( $fileName == '' )
		{
			self::MoveFolder($oldPath, $newPath);
		}
		else
		{
			copy($oldPath.'/'.$fileName , $newPath.'/'.$fileName);
			//删除旧目录下的文件
			unlink($oldPath.'/'.$fileName);
		}
	}
	
	
	/**
	 * 拷贝文件
	 * @param 参数1，旧的文件夹路径或者文件路径
	 * @param 参数2，新的文件路径
	 * @param 参数3，文件名字。
	 * @param 参数4，新的文件名字。
	 */
	static function CopyFile($oldPath , $newPath , $fileName = '' , $newFileName = '')
	{
		//先创建文件夹
		if( $newPath != '' )
		{
			self::CreateFolder($newPath);
		}
		//文件名字是否为空
		if( $fileName != '' )
		{
			$fileName = mb_convert_encoding($fileName,'gb2312','utf-8');
		}
		//新的文件名字是否为空
		if( $newFileName != '' )
		{
			$newFileName = mb_convert_encoding($newFileName,'gb2312','utf-8');
		}
		
		if($fileName == '' )
		{
			$dir = opendir($oldPath);
			while(false !== ( $file = readdir($dir)) )
			{
				if (( $file != '.' ) && ( $file != '..' ))
				{
					if ( is_dir($oldPath . '/' . $file) )
					{
						self::CopyFile($oldPath . '/' . $file,$newPath . '/' . $file);
					}
					else
					{
						$newFilePath = str_replace('//','/',$newPath . '/' . $file);
						@copy($oldPath . '/' . $file, $newFilePath );
					}
				}
			}
		}
		else
		{
			//如果新的文件名字不为空
			if( $newFileName != '' )
			{
				$newFilePath = $newPath.'/'.$newFileName;
			}
			else
			{
				$newFilePath = $newPath.'/'.$fileName;
			}
			$newFilePath = str_replace('//','/',$newFilePath);
			@copy($oldPath.'/'.$fileName , $newFilePath);
		}
		
	}
	
	
	/**
	* 创建文件夹
	* @param 参数1，可以是文件夹路径或者文件路径
	* @param 参数2，如果参数1为文件路径，这个数值必须为1。
	*/
	static function CreateFolder($floderpatch,$isfile='0')
	{
		$floderpatch = str_replace('//','/',$floderpatch);
		//如果传入的是文件完整路径
		if($isfile == '1')
		{
			$arr = explode('/',$floderpatch);
			$filename = $arr[count($arr)-1];
			$floderpatch = str_replace($arr[count($arr)-1],'',$floderpatch);
			//提取出最后的文件夹位置，调用删除文件夹
			self::CreateFolder($floderpatch);
		}
		else
		{
			if(!file_exists($floderpatch))
			{
				mkdir($floderpatch, 0777, true);
				//chmod($floderpatch, 0777);
			}
		}
	}
	
	
	/**
	 * 创建文件
	 * @param 参数1，必填，可以是文件夹路径或者文件路径
	 * @param 参数2，必填，如果参数1为文件路径，这个数值必须为1。
	 * @param 参数3，选填，默认为追加，1为重新写入
	 * @param 参数4，选填，内容是否转码
	 */
	static function CreateFile( $fileName , $fileContent , $flags = '', $encode=true)
	{	
		//先创建文件夹
		self::CreateFolder( $fileName , '1' );

		//转码
		$fileName = mb_convert_encoding($fileName,'gb2312','utf-8');
		if( $encode == true )
		{
		    $fileContent = str::EnCoding($fileContent,'utf-8');
		}

		//创建文件
		switch ($flags)
		{
			case '1':
				file_put_contents( $fileName  , $fileContent);
				break;
		
			default:
				file_put_contents( $fileName  , $fileContent, FILE_APPEND);
				break;
		}
		return true;
	}
	
	
	/**
	* 获取文件目录列表
	* @param 参数1，必填，文件夹位置。
	* @param 参数2，选填，数组格式数据，排除哪些文件夹
	* 返回值，数组。
	*/
	static function FloderList($dir , $arr = '')
	{
		$dirArray = array();
		if (false != ($handle = opendir ( $dir )))
		{
			$i=0;
			while ( false !== ($file = readdir ( $handle )) )
			{
				//去掉"“.”、“..”以及带“.xxx”后缀的文件
				if ($file != "." && $file != ".." && !strpos($file,".") && substr($file, 0, 1) != '.')
				{
					if( ( is_array($arr) && !in_array($file,$arr) ) || $arr == '')
					{
						$dirArray[$i] = array (
							'file'=>str::EnCoding($file),
							'createtime'=>date('Y-m-d H:i:s',filectime($dir.'/'.$file)),
							'uptime'=>date('Y-m-d H:i:s',filemtime($dir.'/'.$file)),
						);
						$i++;
					}
				}
			}
			//关闭句柄
			closedir($handle);
			return $dirArray;
		}
	}
	
	
	/**
	* 获取文件列表
	* @param 参数1，文件夹位置。
	* 返回值，数组。
	*/
	static function FileList($dir)
	{
		$fileArray = array();
		$dir = str_replace('//', '/', $dir);
		if( is_dir($dir) )
		{
			if ($handle = opendir ( $dir ))
			{
				$i=0;
				while ( $file =readdir($handle) )
				{
					//去掉"“.”、“..”以及带“.xxx”后缀的文件
					if ($file != "." && $file != ".." && strpos($file,"."))
					{
						$filetype=explode('.',$file);
						$fileArray[$i] = array (
							'file'=>str::EnCoding($file),
							'filetype'=>end($filetype),
							'createtime'=>date('Y-m-d H:i:s',filectime($dir.'/'.$file)),
							'uptime'=>date('Y-m-d H:i:s',filemtime($dir.'/'.$file)),
							'size'=>ceil(filesize($dir.'/'.$file)/1000),
							'byte'=>filesize($dir.'/'.$file),
						);
						if($i==10000){
							break;
						}
						$i++;
					}
				}
				//关闭句柄
				closedir($handle);
				return $fileArray;
			}
		}
		return false;
	}
	
	
	/**
	 * 获得某个目录下面的所有文件
	 * @param 参数1，必须，文件路径
	 */
	static function FileAll($basedir)
	{
		if($dh = opendir($basedir))
		{
			while (($file = readdir($dh)) !== false)
			{
				if ($file != '.' && $file != '..')
				{
					if(!is_dir($basedir."/".$file))
					{
						$fileUpTime = filemtime($basedir.'/'.$file);
						self::$fileList[$fileUpTime.'_'.md5($basedir.'/'.$file)] = array(
							'uptime'=>$fileUpTime,
							'path'=>$basedir,
							'file'=>$file,
							'filepath'=>$basedir.'/'.$file,
						);
					}
					else
					{
						$dirname = $basedir."/".$file;
						$dirname = str_replace('//','/',$dirname);
						self::FileAll($dirname);
					}
				}
			}
			closedir($dh);
		}
	}
	
	
	/**
	 * 将xml转换成数组
	 * @param 参数1，必须，xml的文件位置
	 */
	static function XmlToArr( $file )
	{
		if( file_exists($file) )
		{
			//打开xml
			$xmlFile = file_get_contents( $file );
			//将xml内容转换为对象
			$ob = simplexml_load_string( $xmlFile );
			//将对象转换成json格式
			$json = json_encode($ob);
			//将jsOn转换成数组
			$xmlData = json_decode($json, true);
			
			return $xmlData;
		}
		else
		{
			return array();
		}
	}
}
?>
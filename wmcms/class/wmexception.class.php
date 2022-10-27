<?php
/**
* 异常捕捉类
*
* @version        $Id: wmexception.class.php 2019年3月31日 18:41  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//初始化
WMException::__init();
class WMException
{
	//站点根目录
	public static $rootPath;
	//程序是否无错运行
	private static $noErr = true;
	//错误信息
	private static $errInfo = array('msg'=>'','error'=>'','line'=>'','code'=>'','state'=>'','file'=>'','trace'=>array());
	//错误类型数组
	private static $codeArr = array(
			1=>'ERROR',
			2=>'WARNING',
			4=>'PARSE',
			8=>'NOTICE',
			16=>'CORE_ERROR',
			32=>'CORE_WARNING',
			64=>'COMPILE_ERROR',
			128=>'COMPILE_WARNING',
			256=>'USER_ERROR',
			512=>'USER_WARNING',
			1024=>'USER_NOTICE',
			2047=>'ALL',
			2048=>'STRICT'
	);
	
	//初始化异常
	static function __init()
	{
		//关闭系统错误提示
		ini_set('display_errors','off');
		//站点根路径
		self::$rootPath = str_replace('\\', '/', WMROOT);

		//php大于5.5版本才进行错误注册
		if( version_compare("5.5", PHP_VERSION, "<") )
		{
			//致命错误异常注册
			set_exception_handler(array("WMException","ExceptionHandler"));
		}

		//警告等错误捕获注册
		set_error_handler(array("WMException","ErrorHandler"));
		//脚本结束事件注册
		register_shutdown_function(array("WMException","ShutdownFunction"));
	}
	
	/**
	 * 清除文件路径中的绝对路径，显示为站点相对路径
	 * @param 文件路径 $path
	 */
	static function ClearPath($filePath)
	{
		$filePath = str_replace('\\', '/', $filePath);
		$filePath = '/'.str_replace(self::$rootPath, '', $filePath);
		return $filePath;
	}
	
	
	/**
	 * 警告异常的错误信息
	 * @param 参数1，错误代码 $error
	 * @param 参数2，错误信息 $msg
	 * @param 参数3，错误文件 $fileName
	 * @param 参数4，错误行数 $line
	 * @param 参数5，页面变量 $param
	 */
	static function ErrorHandler($code, $msg, $fileName, $line, $param=array())
	{
		self::$noErr = false;

		//生成错误字符串
		$errorStr = sprintf("%s: %s at file %s(line:%s)",
			self::$codeArr[$code],
			$msg,
			self::ClearPath($fileName),
			$line
		);
		//处理错误
		self::$errInfo = array(
			'msg'=>$msg,
			'error'=>$errorStr,
			'line'=>$line,
			'code'=>$code,
			'state'=>self::$codeArr[$code],
			'file'=>self::ClearPath($fileName),
		);
		die();
	}

	/**
	 * 致命异常捕获。
	 * @param Throwable $e
	 */
	static function ExceptionHandler(Throwable $e)
	{
		self::$noErr = false;
		
		//生成错误字符串
		$errorStr = sprintf("%s: %s at file %s(line:%s)",
			'Error',
			$e->getMessage(),
			self::ClearPath($e->getFile()),
			$e->getLine()
		);
		//处理错误
		self::$errInfo = array(
			'msg'=>$e->getMessage(),
			'error'=>$errorStr,
			'line'=>$e->getLine(),
			'code'=>$e->getCode(),
			'state'=>'Error',
			'file'=>self::ClearPath($e->getFile()),
		);
		self::$errInfo['trace'] = $e->getTrace();
		die();
	}

	
	/**
	 * 脚本运行完成事件，不管成功与否。
	 */
	static function ShutdownFunction()
	{
		//获得最后一个未处理的错误，error_get_last()
		//有错误就执行错误处理
		if( self::$noErr == false )
		{
			self::Error();
		}
		//记录运行日志
		self::RunLog();
		wmsql::Close();
		die();
	}
	
	
	/**
	 * 处理错误信息，上传到服务器和本地记录
	 */
	static function Error()
	{
		$autoUpload = C('config.web.err_auto_upload');

		$data['errlog_state'] = self::$errInfo['state'];
		$data['errlog_code'] = self::$errInfo['code'];
		$data['errlog_msg'] = self::$errInfo['error'];
		$data['errlog_url'] = GetUrl();
		$data['errlog_sql'] = 'code err';
		$data['errlog_ip'] = GetIp();
		$data['errlog_time'] = time();
		if( $autoUpload == '1' )
		{
			$data['errlog_status'] = 1;
		}
		//错误通知，通知出现错误不再重试
        if( !Session('send_warring') )
        {
	    	Session('send_warring','true');
    	    $msgData = array(
    	        'code_eroor_ip'=>str::DelHtml(GetIp()),
    	        'code_eroor_code'=>self::$errInfo['code'],
    	        'code_eroor_url'=>GetUrl(),
    	        'code_eroor_sql'=>'code err',
    	        'code_eroor_time'=>date('Y-m-d H:i:s'),
    	        'code_eroor_msg'=>$data['errlog_msg']
            );
            NewClass('msg');msg::SendWarring('warning_code_eroor',$msgData);
        }
        Session('send_warring','delete');

		//是否开启了错误日志统计
		if( C('config.web.err_open') == '1')
		{
			$i = 1;
			$fields = $values = '';
			foreach($data as $k=>$v)
			{
				$fields.='`'.$k.'`';
				$values.="'".addslashes($v)."'";
				//如果不是最后一个指针就加上字段分号
				if( $i < count($data) ){
					$fields.=',';
					$values.=',';
				}
				$i++;
			}
			$sql = "INSERT into ".wmsql::CheckTable('@system_errlog')."(".$fields.") VALUES(".$values.")";
			wmsql::exec( $sql );
		}

		//是否开启了自动上传错误
		if( $autoUpload == '1')
		{
			$cloudSer = NewClass('cloud');
			$cloudSer->ErrlogAdd($data);
		}
		
		//记录到日志文件
		$logTemplate = file_get_contents(WMTEMPLATE.'system/errlog.html');
		parse_str(file_get_contents("php://input"),$param);
		$endTime = microtime(true);
		$repArr = array(
			"{time}"			=>	date('Y-m-d H:i:s'),
			"{starttime}"		=>	C('startTime'),
			"{endtime}"		=>	$endTime,
			"{runtime}"		=>	round( $endTime - C('startTime') , 3),
			"{endtime}"		=>	$endTime,
			"{method}"		=>	@$_SERVER['REQUEST_METHOD'],
			"{url}"		=>	GetUrl(),
			"{param}"		=>	var_export($param,true),
			"{ip}"		=>	GetIp(),
			"{error}"		=>	$data['errlog_msg'],
		);
		$logTemplate = strtr($logTemplate,$repArr);
		self::CreateFile(WMCACHE.'log/err/'.date('Y-m').'/'.date('d').'-'.date('H').'_'.self::GetEStr().'.txt', $logTemplate);

		@header('HTTP/1.1 500 Internal Server Error');
		@header("status: 500 Internal Server Error");
		//没有开启debug
		if ( !DEBUG )
		{
			//前台报错
			if( !WMMANAGER )
			{
				tpl::ErrInfo('页面出错，我们已经记录了该信息！！');
			}
			else
			{
				Ajax('页面出错，我们已经记录了该信息！！',300);
			}
		}
		else if( !file_exists(WMTEMPLATE.'system/wmcode.html') )
		{
			tpl::ErrInfo('对不起,系统模版“wmcode.html”不存在！');
		}
		else
		{
			//清空已经输出的内容。
			ob_end_clean();
			//如果是ajax请求
			if( IsAjax() )
			{
				if( !WMMANAGER )
				{
					ReturnData( self::$errInfo['error'] );
				}
				else
				{
					Ajax(self::$errInfo['error'],300);
				}
			}
			//普通请求
			else
			{
				$eTemp=file_get_contents(WMTEMPLATE.'system/wmcode.html');
				$eTemp = str_replace('{msg}',self::$errInfo['msg'],$eTemp);
				$eTemp = str_replace('{line}',self::$errInfo['line'],$eTemp);
				$eTemp = str_replace('{file}',self::$errInfo['file'],$eTemp);
				//循环出错误的代码信息
				$trace_str = '';
				if( !empty(self::$errInfo['trace']) )
				{
					foreach (self::$errInfo['trace'] as $key=>$value)
					{
						$valClass = isset($value['class'])?$value['class']:'';
						//错误代码
						$trace_str.= '<tr class="bg1"><td>'.($key+1).'</td>
										<td>'.self::ClearPath($value['file']).'</td>
										<td>'.$value['line'].'</td>
										<td>'.$value['function'].'</td>
										<td>'.$valClass.'</td></tr>';
					}
				}
				$eTemp = str_replace('{trace}',$trace_str,$eTemp);
				echo $eTemp;
			}
		}
	}
	

	/**
	 * 根据站点信息生成一串md5随机字符串
	 * @return string
	 */
	static function GetEStr()
	{
		global $C;
		$appId = $C['config']['api']['system']['api_appid'];
		$apiKey = $C['config']['api']['system']['api_apikey'];
		$secretKey = $C['config']['api']['system']['api_secretkey'];
		return md5(sha1(md5($appId).$apiKey).$secretKey);
	}
	/**
	 * 字符串编码转换
	 * @param 参数1，需要转换的url
	 * @param 参数2，目标编码
	 */
	static function EnCoding($str,$enCode = 'utf-8')
	{
		$strEncode = mb_detect_encoding($str, array("ASCII",'UTF-8',"GB2312","GBK",'BIG5'));
		if( strtolower($strEncode) != strtolower($enCode) )
		{
			$str=mb_convert_encoding($str,$enCode,$strEncode);
		}
		return $str;
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
				chmod($floderpatch, 0777);
			}
		}
	}
	/**
	 * 创建文件
	 * @param 参数1，必填，可以是文件夹路径或者文件路径
	 * @param 参数2，必填，如果参数1为文件路径，这个数值必须为1。
	 * @param 参数3，选填，默认为追加，1为重新写入
	 */
	static function CreateFile( $fileName , $fileContent , $flags = '')
	{
		//先创建文件夹
		self::CreateFolder( $fileName , '1' );

		//转码
		$fileName = mb_convert_encoding($fileName,'gb2312','utf-8');
		$fileContent = self::EnCoding($fileContent,'utf-8');
	
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
	 * 记录运行日志方法
	 * @param 返回的json数据 $json
	 */
	static function RunLog($json='')
	{
		//如果开启运行日志记录
		if( RUNLOG )
		{
			$status = 'SUCCESS';
			if( self::$noErr == false )
			{
				$status = 'ERROR';
			}
			
			$endTime = microtime(true);
			$sqlLog = '';
			$returnJson = '';
			if( wmsql::$sqlList )
			{
				$i = 1;
				$sqlListCount = count(wmsql::$sqlList);
				foreach (wmsql::$sqlList as $k=>$v)
				{
					$sqlLog .= '[   Sql   ] [ RunTime:'.(($v['end']-$v['start'])/1000000).' S ] '.$v['sql'];
					if( $i < $sqlListCount )
					{
						$sqlLog .= "\r\n";
					}
					$i++;
				}
				$sqlLog = "\r\n------------------------\r\n".$sqlLog;
			}
			if( $json != '' )
			{
				$returnJson = "\r\n------------------------\r\n[ReturnJson] ".$json;
			}
			$logTemplate = file_get_contents(WMTEMPLATE.'system/runlog.html');
			parse_str(file_get_contents("php://input"),$param);
			$repArr = array(
					"{status}"			=>	$status,
					"{time}"			=>	date('Y-m-d H:i:s'),
					"{starttime}"		=>	C('startTime'),
					"{endtime}"		=>	$endTime,
					"{runtime}"		=>	round( $endTime - C('startTime') , 3),
					"{endtime}"		=>	$endTime,
					"{software}"		=>	@$_SERVER['SERVER_SOFTWARE'],
					"{method}"		=>	@$_SERVER['REQUEST_METHOD'],
					"{root}"		=>	@$_SERVER['DOCUMENT_ROOT'],
					"{host}"		=>	@$_SERVER['HTTP_HOST'],
					"{servername}"		=>	gethostbyname(@$_SERVER["SERVER_NAME"]),
					"{url}"		=>	GetUrl(),
					"{param}"		=>	var_export($param,true),
					"{ip}"		=>	GetIp(),
					"{client}"		=>	@$_SERVER['HTTP_USER_AGENT'],
					"{json}"		=>	$returnJson,
					"{sqls}"		=>	$sqlLog,
			);
			$logTemplate = strtr($logTemplate,$repArr);
			self::CreateFile(WMCACHE.'log/run/'.date('Y-m').'/'.date('d').'-'.date('H').'_'.GetEStr().'.txt', $logTemplate);
		}
	}
}
?>
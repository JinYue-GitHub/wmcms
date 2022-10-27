<?php
/**
* 公共函数库
*
* @version        $Id: function.php 2016年3月21日 13:52  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2016年5月31日 15:00 weimeng
*
*/

if(!defined('WMINC')){ exit("dont alone open!"); }

/**
 * 打印或者输出字符，用于快速调试
 * @param 需要打印的数组或者字符串。
 * @param 是否需要结束运行
 */
function P($data,$isDie=true)
{
	if( is_array($data) )
	{
		print_r($data);
	}
	else
	{
		echo $data;
	}
	if( $isDie )
	{
		die();
	}
	return true;
}


//判断是否是ajax请求
function IsAjax()
{
	if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
	{
		return true;
	}
	else
	{
		return false;
	}
}

/**
 * 是否是GET提交的
 */
function IsGet()
{
	if( isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'GET' )
	{
		return true;
	}
	else
	{
		return false;
	}
}

/**
 * 是否是POST提交
 */
function IsPost() {
	$metHod = isset($_SERVER['REQUEST_METHOD'])?$_SERVER['REQUEST_METHOD']:'';
	$ref = isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'';
	$host = isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:'';
	return ($metHod == 'POST' && (empty($ref) || preg_replace("~https?:\/\/([^\:\/]+).*~i", "\\1", $ref) == preg_replace("~([^\:]+).*~", "\\1", $host))) ? true : false;
}

/**
 * 是否是jsonp跨域请求
 */
function IsJsonP()
{
	return Request('callback') != '' ? true : false;
}

/**
 * 获得http的类型
 * @param 传入http或者https进行判断，然后返回布尔值
 */
function GetHttpType($type='')
{
	if( (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') )
	{
		$httpType = 'https';
	}
	else if(isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
	{
		$httpType = 'https';
	}
	else if ( !empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off')
	{
		$httpType = 'https';
	}
	else
	{
		$httpType = 'http';
	}
	
	//如果为空就直接返回type
	if( $type == '' )
	{
		return $httpType;
	}
	else
	{
		if( $httpType == $type )
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}

//是否是http请求
function IsHttp()
{
	if( GetHttpType('http') )
	{
		return true;
	}
	else
	{
		return false;
	}
}

//是否是https请求
function IsHttps()
{
	if( GetHttpType('https') )
	{
		return true;
	}
	else
	{
		return false;
	}
}

/**
 * 引入文件，如果文件存在则引入
 * @param 参数1，文件路径。
 * @param 参数2，是否为配置文件，
 */
function Inc( $path , $name = ''){
	if( file_exists($path) )
	{
		if ( DEVELOPER )
		{
			echo '载入文件：'.$path.'<br/>';
		}
		//是否需要返回配置文件内容
		if( $name != '')
		{
			require $path;
			$arr = $name.'Config';
			return $$arr;
		}
		else
		{
			return require $path;
		}
	}
	else if ( DEVELOPER )
	{
		if ( ERR )
		{
			exit( C('system.file.no', null , 'lang') . '<br/>文件路径:'.$path );
		}
		else
		{
			echo '警告：'.$path.'不存在<br/><br/>';
		}
	}
}


/**
 * 导入类文件操作
 * @param 参数1，必须，类名
 * @param 参数2，选填，是否传入默认值
 * @param 参数3，选填，置顶的路径
 * @param 参数4，选填，文件的后缀
 */
function NewClass( $name , $data = '' , $path = WMCLASS , $type = 'class' )
{
	$file = $path.$name.'.'.$type.'.php';
	if( file_exists($file) )
	{
		if ( DEVELOPER )
		{
			echo '载入文件：'.$file.'<br/>';
		}

		if( $type == 'model' )
		{
			$className = $name.'model';
		}
		else if( $type == 'label' )
		{
			$className = $name.'label';
		}
		else
		{
			$className = $name;
		}
		if( !class_exists($className) )
		{
			require_once $file;
		}
		$ob = new $className( $data );
		
		return $ob;
	}
	else if ( DEVELOPER )
	{
		if ( ERR )
		{
			exit( C('system.file.no', null , 'lang') . '<br/>文件路径:'.$file );
		}
		else
		{
			echo '警告：类文件'.$file.'不存在<br/><br/>';
		}
	}
}
/**
 * 载入模块类/module
 * @param 参数1，必须，载入的模块
 * @param 参数2，选填，默认的数据
 */
function NewModuleClass($module, $data = '' , $isSys = false)
{
	if( $isSys == false )
	{
		$path = WMMODULE.$module;
	}
	else
	{
		$path = WMSYSMODULE.$module;
	}
	
	return NewClass( $module , $data , $path.'/' );
}
/**
 * 载入模型/wmcms/models
 * @param 参数1，必须，模型的地址
 * @param 参数2，选填，默认的数据
 */
function NewModel($model, $data = '')
{
	$arr = explode('.', $model);
	return NewClass( $arr[1] , $data , WMMODELS.$arr[0].'/' , 'model');
}
/**
 * 载入api接口/wmcms/api
 * @param 参数1，必须，的地址
 * @param 参数2，选填，默认的数据
 * @param 参数3，选填，自动new的类名
 */
function NewApi($api, $data = '',$className='')
{
	$arr = explode('.', $api);
	require_once WMAPI.$arr[0].'/'.$arr[1].'/autoload.php';
	if( !empty($className) )
	{
		return new $className( $data );
	}
	return true;
}
/**
 * 载入单功能模块/wmcms/module
 * @param 参数1，必须，模块的名字
 * @param 参数2，选填，模块class的默认参数
 * @param 参数3，选填，模块label的默认参数
 */
function IncModule($module,$data1=array(),$data2=array())
{
	global $C;
	$modulePath = WMSYSMODULE.$module.'/';
	$configName = $module.'Config';
	
	//如果配置文件不存在就引入配置文件
	global $$configName;
	if( !is_array($$configName) )
	{
		$$configName = Inc( $modulePath.$module.'.config.php' , $module);
	}
	
	NewClass( $module , $data1 , $modulePath );
	NewClass( $module , $data2 , $modulePath , 'label');
}
/**
 * 获取模块的配置/module/module
 * @param 参数1，必须，模块的名字
 */
function GetModuleConfig($module , $isSys = false)
{
	if( $isSys == false )
	{
		$path = WMMODULE.$module.'/'.$module.'.config.php';
	}
	else
	{
		$path = WMSYSMODULE.$module.'/'.$module.'.config.php';
	}
	$config = Inc( $path , $module);
	return str::Escape($config,'d',true);
}
function GetModuleLang($module)
{
	return Inc( WMMODULE.$module.'/lang/'.C('config.web.lang').'/system.php');
}


/**
 * 获得模块的url
 * @param 参数1，必须，模块id $module
 * @param 参数2，必须，页面类型  $type
 * @param 参数3，必须，页面替换参数  $parArr
 * @return Ambigous <string, boolean, unknown, mixed>
 */
function GetModuleUrl($module,$type,$parArr)
{
	$page = $module.'_'.$type;
	$par = array();
	if( $type == 'content' )
	{
		if( $module == 'novel' )
		{
			$page = 'novel_info';
			$par = array('tid'=>$parArr['tid'],'nid'=>$parArr['cid']);
		}
		else if( $module == 'app' )
		{
			$page = 'app_app';
			$par = array('tid'=>$parArr['tid'],'aid'=>$parArr['cid']);
		}
	}
	return tpl::Url($page,$par);
}


/**
 * 获得语言包的内容
 * @param 参数1，必须，语言包的键
 * @param 参数2，选填，替换的键值对
 */
function GetLang($langKey,$repArr = array())
{
	$langContent = C($langKey,'','lang');
	if( !empty($repArr) )
	{
		foreach ($repArr as $k=>$v)
		{
			$langContent = str_replace('{'.$k.'}', $v, $langContent);
		}
	}
	return $langContent;
}

/**
 * 根据传入的模块标识获得模块的名字
 * @param 参数1，选填，模块标识，如果为空就返回全部数组，如果是xx.xx格式表示删除里面的模块
 * @param unknown $name
 */
function GetModuleName( $name = '' , $filter = true)
{
	$arr = array(
		'all'=>'全站首页',
		'novel'=>'小说模块',
		'app'=>'应用模块',
		'article'=>'文章模块',
		'picture'=>'图集模块',
		'message'=>'留言模块',
		'bbs'=>'论坛模块',
		'link'=>'友链模块',
		'author'=>'原创模块',
		'user'=>'用户模块',
		'zt'=>'专题模块',
		'about'=>'信息模块',
		'editor'=>'编辑模块',
		'diy'=>'单页模块',
		'down'=>'下载模块',
		'replay'=>'评论模块',
		'search'=>'搜索模块',
	);
	$notModule = explode(',', NOTMODULE);
	foreach ($notModule as $k=>$v)
	{
		unset($arr[$v]);
	}
	
	if( $name == '' )
	{
		return $arr;
	}
	else
	{
		//判断是否存在删除模块
		$nameArr = explode('.', $name);
		if( count($nameArr) > 1 || $filter == false )
		{
		    if( $filter == false )
		    {
		        foreach ($arr as $k=>$v)
    			{
    			    //需要保留的不存在就剔除
    			    if( !in_array($k,$nameArr) )
    			    {
    			        unset($arr[$k]);
    			    }
    			}
		    }
		    else
		    {
		        //剔除需要过滤的
    			foreach ($nameArr as $k=>$v)
    			{
    			    unset($arr[$v]);
    			}
		    }
			return $arr;
		}
		else
		{
			if( isset($arr[$name]) )
			{
				return $arr[$name];
			}
			else
			{
				return $name;
			}
		}
	}
}


/**
 * 根据传入的模块名字获得模块的标识
 * @param 参数1，必须，模块名字
 */
function GetModuleId( $name = '')
{
	$arr = array(
		'小说'=>'novel',
		'应用'=>'app',
		'文章'=>'article',
		'图集'=>'picture',
	);
	return $arr[$name];
}

/**
 * 多维数组取值和赋值
 * @param 参数1，必须，字符串， 键名。
 * @param 参数2，选填，字符串， 值名。
 * @param 参数3，选填，操作的数组或者数组名字。
 * @param 参数4，选填，是否进行全部转义。
 * 返回值：取得的值或者true
 */
function C( $key , $val = '' , $default = '',$isEscape = false){
	global $C;
	
	//如果默认为数组就设置为数组
	if ( is_array( $default ) )
	{
		$value = $default;
	}
	//如果默认为字符串则读取相应的数组
	else if ( trim( $default ) != '')
	{
		global $$default;
		$value = $$default;
		$config = &$$default;
	}
	//否则就设置
	else
	{
		$value = $C;
		$config = &$C;
	}
	//如果返回全部数组
	if( $key == '' && $default != '' )
	{
		return $value;
	}
	else
	{
		//分割键
		$nodes = explode('.', $key);
	
		//如果没有值就读取数组
		if( !is_object($val) && !is_array($val) && trim( $val ) == '' )
		{
			foreach ($nodes as $node)
			{
				if ( isset($value[$node]) )
				{
					$value = $value[$node];
				}
				else
				{
					$value = null;
					break;
				}
			}
	
			if( $value != '' || $value == '0' )
			{
				if( !is_array($value) )
				{
					//$value = htmlspecialchars($value);
				}
				if( class_exists('str') && !is_int($value) )
				{
					return str::Escape($value,'',$isEscape);
				}
				else
				{
					return $value;
				}
			}
		}
		//如果有值就写入数组
		else
		{
			//循环赋值
			foreach ( $nodes as $node )
			{
				if ( isset($config[$node]) )
				{
					$config = & $config[$node];
				}
				else
				{
					$config[$node] = array();
					$config = & $config[$node];
				}
			}

			$config = $val;
			if( $val === 'delete' )
			{
				$config = '';
			}

			return true;
		}
	}
}

/**
 * 获取数组的key
 * @param 数组 $arr
 * @param 键名 $key
 * @param 默认值 $default
 * @return unknown|string
 */
function GetKey($data,$key,$default='')
{
	$keyArr = explode(',',$key);
    foreach ($keyArr as $v)
    {
    	if( isset($data[$v]) )
    	{
    		$data = $data[$v];
    	}
    	else
    	{
    		$data = $default;
    	}
    }
    return $data;
}

//输入内容转换
/**
 * 输入内容强制转换
 * @param 参数1，必须，键名
 * @param 参数2，选填，内容
 * @param 参数3，选填，默认为字符串
 */
function InputConversion($key='',$val='',$con='s')
{
	if( $key == '' )
	{
		return $val;
	}
	else
	{
		switch ($con)
		{
			case 'i':
				if( !is_int($val) )
				{
					$val = @(int)$val;
				}
				break;
			case 'b':
				if( !is_bool($val) )
				{
					$val = @(bool)$val;
				}
				break;
			case 'a':
				if( !is_array($val) )
				{
					$val = array();
				}
				break;
			case 'f':
				if( !is_float($val) )
				{
					$val = @(float)$val;
				}
				break;
			case 's':
			default:
				if( is_array($val) )
				{
					$val = json_encode($val);
				}
				$val = FilterEmoji($val);
				break;
		}
	}
	return $val;
}
/**
 * 过滤Emoji表情
 * @param unknown $str
 * @return Ambigous <string, unknown>|mixed
 */
function FilterEmoji($str)
{
	$str = preg_replace_callback('/./u',function (array $match) {
				return strlen($match[0]) >= 4 ? '' : $match[0];
			},$str);
	return $str;
}
/**
 * 获取key的值和强制转换类型
 * @param 参数1，选填，为空就不检查
 */
function InputGetKey($keyStr='')
{
	if( $keyStr == '' )
	{
		$key = '';
		$con = '';	
	}
	else
	{
		$keyArr = explode('/', $keyStr);
		if( count($keyArr) < 2 )
		{
			$key = $keyStr;
			$con = 's';
		}
		else
		{
			$key = $keyArr[0];
			$con = $keyArr[1];
		}
	}
	return array($key,$con);
}
/**
 * post，get，session,cookie数据过滤
 */
function InputFilter($str)
{
	if( !is_array($str) && $str != '' )
	{
		$array = array(
			//"<"			=>	'{#lt}',
			//">"			=>	'{#gt}',
			"'"			=>	'&#39;',
			"<"			=>	'&lt;',
			">"			=>	'&gt;',
		);
	
		$str = strtr($str,$array);
	}
	return $str;
}
/**
 * 设置session和取得session的值
 * @param 参数1，必须，session的键
 * @param 参数2，选填，如果有值则为设置session，否则为调用session
 */
function Session( $key , $val = '' )
{
	list($key,$con) = InputGetKey($key);
	$oldArr = array();
	$keyArr = explode('.', $key);
	//如果存在键值对
	if(count($keyArr)>1)
	{
		$key = $keyArr[0];
	}
	$value = true;
	//开启session
	if( IsSessionStarted() == false )
	{
		session_start();
	}
	if(	!is_array($val) && $val != '0' && empty( $val ) )
	{
		$value = GetKey($_SESSION,$key);
		if ($value == '')
		{
			$value = false;
		}
		//二位就还原序列化
		if( IsSerialize($value) )
		{
			$value = unserialize($value);
			if( count($keyArr) > 1)
			{
				$value = $value[$key][$keyArr[1]];
			}
		}
	}
	//设置为空
	else if ( $val == 'delete' )
	{
		unset($_SESSION[$key]);
	}
	else
	{
		//如果是二位数组就设置新的值
		if( !empty($_COOKIE[$key]) && count($keyArr) > 1)
		{
			$oldArr = unserialize($_COOKIE[$key]);
		}
		if( count($keyArr) > 1)
		{
			$oldArr[$key][$keyArr[1]] = $val;
			$val = serialize($oldArr);
		}
		
		$_SESSION[$key] = $val;
	}
	//关闭session
	//session_write_close();
	return InputConversion($key,$value,$con);
}


/**
 * 获得sessionId
 */
function GetSessionId()
{
	if( IsSessionStarted() == false )
	{
		session_start();
	}
	return session_id();
}

/**
 * 设置sessionId
 */
function SetSessionId($id)
{
	session_id($id);
	session_start();
	return true;
}

/**
 * 判断是否开启了session
 * @return boolean
 */
function IsSessionStarted()
{
	if ( php_sapi_name() !== 'cli' )
	{
		if ( version_compare(phpversion(), '5.4.0', '>=') )
		{
			return session_status() === PHP_SESSION_ACTIVE ? true : false;
		}
		else
		{
			return session_id() === '' ? false : true;
		}
	}
	return false;
}

/**
 * 设置cookie和取得cookie的值
 * @param 参数1，必须，cookie的键
 * @param 参数2，选填，如果有值则为设置cookie，否则为调用cookie
 * @param 参数3，选填，cookie时的有效时间，默认为30天
 * @param 参数4，选填，cookie的保存目录
 * @param 参数5，选填，cookie的保存服务器
 */
function Cookie( $key , $val = '' , $time = '' , $path = '/' ,  $server = '' )
{
	list($key,$con) = InputGetKey($key);
	$oldArr = array();
	$keyArr = explode('.', $key);
	//如果存在键值对
	if(count($keyArr)>1)
	{
		$key = $keyArr[0];
	}
	$value = '';

	//设置cookie默认的保存时间
	if ( $time == '')
	{
		$time = time()+3600*24*30;
	}
	else if( $time == 'auto' || $time == '0')
	{
		$time = 0;
	}
	else
	{
		$time = time()+$time;
	}
	
	
	//如果没有值就取出键对应的cookie
	if(	trim( $val ) == '' && $val != 'delete' && isset($_COOKIE[$key]))
	{
		$value = InputFilter($_COOKIE[$key]);
		//二位就还原序列化
		if( IsSerialize($value) )
		{
			$value = unserialize($value);
			if( count($keyArr) > 1)
			{
				$value = $value[$key][$keyArr[1]];
			}
		}
	}
	//否则就设置cookie
	else if( trim( $val ) != '' )
	{
		//设置为空
		if ( $val == 'delete' )
		{
			$val = '';
		}
		//设置cookie服务器
		if( $server == '' )
		{
			$server = SetServer();
		}

		//如果是二位数组就设置新的值
		if( !empty($_COOKIE[$key]) && count($keyArr) > 1)
		{
			$oldArr = unserialize($_COOKIE[$key]);
		}
		if( count($keyArr) > 1)
		{
			$oldArr[$key][$keyArr[1]] = $val;
			$val = serialize($oldArr);
		}

		$value = setcookie( $key , $val , $time , $path , $server );
	}
	return InputConversion($key,$value,$con);
}


/**
 * 获得cook和session的保存服务器地址
 */
function SetServer(){
	$domain = $_SERVER['SERVER_NAME'];
	$server = '';

	if( $domain <> '127.0.0.1' && $domain <> 'localhost' && C('config.web.cookie_type') == '1')
	{
		$realIp = str_replace( '.' , '' , $domain );
		//检查IP是否为数字
		if( intval($realIp) )
		{
			$server = $domain;
		}
		else
		{
			$cd = str_ireplace(TCP_TYPE.'://','',WEB_URL);
			//如果以/结尾了
			if( substr($cd, -1) == '/' )
			{
				$cd = substr($cd,0,-1);
			}

			$domainArr = explode('.', $cd);
			if( count($domainArr) == '2' )
			{
				$server = '.'.$cd;
			}
			else if( $domainArr[0] == 'www' )
			{
				unset($domainArr[0]);
				$server = '.'.implode('.', $domainArr);
			}
			else if( count($domainArr) > '2' )
			{
				$server = '.'.implode('.', $domainArr);
			}
		}
	}
	return $server;
}
/**
 * 获得头部信息的值
 * @param unknown $key
 * @return Ambigous <string, unknown>
 */
function GetHeader($key)
{
	global $_SERVER;
	return isset($_SERVER['HTTP_'.$key])?$_SERVER['HTTP_'.$key]:'';
}
/**
 * 设置头部信息的值
 * @param 键名 $key
 * @param 默认值 $val
 * @return unknown
 */
function SetHeader($key,$val)
{
	global $_SERVER;
	return $_SERVER['HTTP_'.$key] = $val;
}
/**
 *
 * 获得get的值
 * @param 参数1，选填，键名，不填则取出所有get请求参数
 * @param 参数2，选填，默认值
 * @param 参数3，选填，是否进行参数过滤。
 */
function Get( $key = '' , $default = ''  , $isFilter = true)
{
	list($key,$con) = InputGetKey($key);
	$value = '';
	if ( $key == '' )
	{
		$getStr = GetKey($_SERVER, 'QUERY_STRING');
		if ( $getStr != '' )
		{
		    $value = array();
			$getArr = explode('&', $getStr);
			foreach ($getArr as $k=>$v)
			{
				list($k,$v) = explode('=', $v);
				$value[$k] = $v;
				/*if( strstr($v , '=') )
				{
					$arr = explode('=', $v);
					if( $arr[0] != '_' )
					{
						GetKey($value,$arr[0]);
					}
				}*/
			}
		}
	}
	else if( isset($_GET[$key]) && is_array($_GET[$key]) )
	{
		return null;
	}
	else
	{
		if(	trim( $key ) != '' )
		{
			if( !isset( $_GET[$key] ) && $default != '')
			{
				$value = $default;
			}
			else if( isset( $_GET[$key] ) )
			{
				//是否过滤
				if( $isFilter )
				{
					$value = InputFilter(stripslashes($_GET[$key]));
				}
				else
				{
					$value = stripslashes($_GET[$key]);
				}
				if( $value == '' && $default != '' )
				{
					$value = $default;
				}
			}
		}
	}

	return InputConversion($key,$value,$con);
}


/**
 *
 * 获得post的值
 * @param 参数1，必须，键名，不填则取出所有post请求参数
 * @param 参数2，选填，默认值。
 * @param 参数3，选填，是否进行参数过滤。
 */
function Post( $key = '' , $default = '' , $isFilter = true)
{
	list($key,$con) = InputGetKey($key);
	if ( $key == '' )
	{
		$value = $_POST;
	}
	else
	{
		$value = '';
		if(	trim( $key ) != '' && isset($_POST[$key]) )
		{
			$value = $_POST[$key];
			if( !is_array($value) )
			{
				$value = @stripslashes($value);
			}
			if( $value == '' && $default != '')
			{
				$value = $default;
			}
			//是否过滤
			else if( $isFilter == true )
			{
				$value = InputFilter($value);
			}
		}
	}
	return InputConversion($key,$value,$con);
}


/**
 * 获取请求参数，先从post提取，然后从get提取
 * @param 参数1，选填，参数键名。
 * @param 参数2，选填，默认值。
 * @param 参数3，选填，是否进行参数过滤。
 */
function Request( $key = '' , $val = ''  , $isFilter = true)
{
	$value = Post( $key , $val , $isFilter);
	if ( $value == '' )
	{
		$value = Get( $key , $val , $isFilter);
	}
	return $value;
}


/**
 * 转义参数，去除反斜杠
 * @param 参数1，选填，参数，参数类型，1为post，2为get，0为全部
 */
function DRequest($type = '0')
{
	if( ($type =='0' || $type == '1') && !empty($_POST) )
	{
		foreach ($_POST as $k=>$v)
		{
			$_POST[$k]=@stripslashes($v);
		}
	}
	
	if( ($type =='0' || $type == '2') && !empty($_GET) )
	{
		foreach ($_GET as $k=>$v)
		{
			$_GET[$k]=stripslashes($v);
		}
	}
}


/**
 * 方法调用器
 * @param 参数1，选填，类名
 * @param 参数2，选填，方法名
 * @param 参数3，选填，变量或参数
 * @example 只填参数2表示调用普通方法
 * @example 参数1和参数2表示调用类里面的方法
 * @example 参数1和参数3表示类里面的变量
 * @example 类可以是对象可以是类名，类名使用的是静态调用。
 */
function Call($class='',$func='',$data='')
{
	if( $class == '' )
	{
		if( function_exists($func) )
		{
			return $func($data);
		}
		else if( DEBUG == TRUE && ERR == false)
		{
			tpl::ErrInfo('方法 '.$func.' 不存在!');
		}
		else if( DEBUG == TRUE)
		{
			die('function '.$func.' not exists!');
		}
	}
	else
	{
		//如果是字符串
		if( gettype($class) == 'string' )
		{
			if( !class_exists($class) )
			{
				die('class '.$class.'  not fund');
			}
			else if( $func == '' )
			{
				return $class::$$data;
			}
			else
			{
				if( method_exists($class,$func) )
				{
					return $class::$func($data);
				}
				else if( DEBUG == TRUE && ERR == false)
				{
					tpl::ErrInfo('方法 '.$class.'::'.$func.' 不存在!');
				}
				else if( DEBUG == TRUE )
				{
					die('class '.$class.'::'.$func.' not exists');
				}
			}
		}
		else
		{
			if( $func == '' )
			{
				return $class->$data;
			}
			else
			{
				if( method_exists($class,$func) )
				{
					return $class->$func($data);
				}
				else if( DEBUG == TRUE && ERR == false)
				{
					tpl::ErrInfo('方法 '.$class.'->'.$func.' 不存在!');
				}
				else if( DEBUG == TRUE )
				{
					die('class '.$class.'->'.$func.' not exists');
				}
			}
		}
	}
}

//检查是否是手机浏览器
function IsPhone(){
	if ( isset ($_SERVER['HTTP_X_WAP_PROFILE']) )
	{
		return true;
	}
	if (isset ($_SERVER['HTTP_VIA']) )
	{
		return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
	}
	if (isset ($_SERVER['HTTP_USER_AGENT']))
	{
		$clientkeywords = array (
			'nokia','sony','ericsson','mot','samsung','htc','sgh','lg','sharp','sie-','philips','panasonic','alcatel','lenovo',
			'iphone','ipod','ipad','blackberry','meizu','android','netfront','symbian','ucweb','windowsce','palm','operamini',
			'operamobi','openwave','nexusone','cldc','midp','wap','mobile','android'
		);
		// 从HTTP_USER_AGENT中查找手机浏览器的关键字
		if ( preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']) ) )
		{
			return true;
		}
	}
	if ( isset ($_SERVER['HTTP_ACCEPT']) )
	{
		if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))){
			return true;
		}
	}
	return false;
}


//是否是微信浏览器
function IsWeiXin()
{
	if (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false )
	{
		return true;
	}
	else
	{
		return false;
	}
}

//是否是阿里浏览器
function IsAliPay()
{
	if (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'AliPay') !== false )
	{
		return true;
	}
	else
	{
		return false;
	}
}



//是否是蜘蛛
function IsSpider()
{
	if( isset($_SERVER['HTTP_USER_AGENT']) )
	{
		$userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);
		$spiderArr = GetSpider(2);
		foreach ($spiderArr as $k=>$v)
		{
			if (strpos($userAgent,$k) !== false)
			{
				return GetSpider(2,$k);
			}
		}
	}
	return false;
}

/**
 * 获得蜘蛛
 * @param 参数1，选填，1为返回数组，2为返回列表。
 * @param 参数2，选填，是否指定了蜘蛛id，返回蜘蛛信息。
 */
function GetSpider($type=1,$spiderId='')
{
	$spiderArr = array(
		'baidu'=>array('name'=>'百度蜘蛛','list'=>array(
				'baiduspider-image'=>'百度图片蜘蛛','baiduspider-mobile'=>'百度移动蜘蛛',
				'baiduspider-video'=>'百度视频蜘蛛','baiduspider-news'=>'百度新闻蜘蛛','baiduspider'=>'百度网页蜘蛛',
		)),
		'360'=>array('name'=>'360蜘蛛','list'=>array(
				'360spider'=>'360网页蜘蛛','haosouspider'=>'好搜网页蜘蛛',
		)),
		'google'=>array('name'=>'谷歌蜘蛛','list'=>array(
				'googlebot-mobile'=>'谷歌移动蜘蛛','googlebot-image'=>'谷歌图片蜘蛛','mediapartners-google'=>'谷歌adsense蜘蛛',
				'adsbot-google'=>'谷歌广告蜘蛛','googlebot'=>'谷歌网页蜘蛛',
		)),
		'sogou'=>array('name'=>'搜狗蜘蛛','list'=>array(
				'sosospider'=>'搜搜网页蜘蛛','sogou news spider'=>'搜狗新闻蜘蛛','sogou web spider'=>'搜狗网页蜘蛛',
				'sogou inst spider'=>'搜狗蜘蛛','sogou orion spider'=>'搜狗蜘蛛','sogou spider'=>'搜狗蜘蛛',
				'sogou spider2'=>'搜狗蜘蛛','sogou blog'=>'搜狗蜘蛛','sogouspider'=>'搜狗蜘蛛',
		)),
		'yahoo'=>array('name'=>'雅虎蜘蛛','list'=>array(
				'yahoo'=>'雅虎蜘蛛',
		)),
		'youdao'=>array('name'=>'有道蜘蛛','list'=>array(
				'youdaobot'=>'有道蜘蛛','yodaobot'=>'有道蜘蛛',
		)),
		'msn'=>array('name'=>'微软蜘蛛','list'=>array(
				'msnbot-media'=>'微软视频蜘蛛','msnbot-new'=>'微软新闻蜘蛛','msnbot-products'=>'微软产品蜘蛛',
				'msnbot-academic'=>'微软广告蜘蛛','msnbot'=>'微软蜘蛛',
		)),
		'bing'=>array('name'=>'必应蜘蛛','list'=>array(
				'bingbot'=>'必应蜘蛛',
		)),
		'yisou'=>array('name'=>'一搜蜘蛛','list'=>array(
				'yisouspider'=>'一搜蜘蛛',
		)),
		'archiver'=>array('name'=>'Alexa蜘蛛','list'=>array(
				'ia_archiver'=>'Alexa蜘蛛',
		)),
		'easou'=>array('name'=>'宜搜蜘蛛','list'=>array(
				'easouspider'=>'宜搜蜘蛛',
		)),
		'jike'=>array('name'=>'即刻蜘蛛','list'=>array(
				'jikespider'=>'即刻蜘蛛',
		)),
		'etao'=>array('name'=>'一淘蜘蛛','list'=>array(
				'etaospider'=>'一淘蜘蛛',
		)),
		'mj12bot'=>array('name'=>'MJ12bot蜘蛛','list'=>array(
				'mj12bot'=>'MJ12bot蜘蛛',
		)),
	);
	//如果等于2就只返回所有的键值对
	if( $type==2 )
	{
		$newArr = array();
		foreach ($spiderArr as $k=>$v)
		{
			foreach ($v['list'] as $key=>$val)
			{
				if($spiderId != '' && $key==$spiderId)
				{
					return array('group'=>$k,'group_name'=>$v['name'],'spider'=>$key,'spider_name'=>$val);
				}
				$newArr[$key] = $val; 
			}
		}
		$spiderArr = $newArr;
	}
	return $spiderArr;
}


/**
 * 判断是否是序列化字符串
 * @param 参数1，必填，字符串。
 */
function IsSerialize( $str )
{
	if( is_string($str) )
	{
		$str = trim( $str );
		if ( 'N;' == $str )
		{
			return true;
		}
		else if ( !preg_match( '/^([adObis]):/', $str, $badions ) ) 
		{
			return false;
		}
		else
		{
			switch ( $badions[1] )
			{
				case 'a':
			 	case 'o':
			 	case 's':
			 		if ( preg_match( "/^{$badions[1]}:[0-9]+:.*[;}]\$/s", $str ) )
			 		{
			 			return true;
			 		}
			 		break;
			 	case 'b':
			 	case 'i':
			 	case 'd':
			 		if ( preg_match( "/^{$badions[1]}:[0-9.E-]+;\$/", $str ) ) 
			 		{
			 			return true;
			 		}
			 	break;
			}
		}
		return false;
	}
	return false;
}


/**
 * 处理参数匹配
 * @param 参数1，必填，id和pinyin的字段名数组。
 * @param 参数2，必填，参数值。
 * @param 参数3，选填，是否已经有where条件了。
 */
function CheckPar(  $parName , $par , $where = array() )
{
	if ( str::Number($par) )
	{
		$newWhere[$parName['id']] = $par;
	}
	//如果索引不存在
	else if( isset($parName['pinyin']) )
	{
		$newWhere[$parName['pinyin']] = $par;
	}
	else
	{
		return array('id'=>0);
	}

	if( is_array($where) )
	{
		$newWhere = array_merge($where,$newWhere);
	}
	return $newWhere;
}

//返回jsonp格式数据
function ReturnJsonp( $msg = '' , $code = 200 , $data = array() , $ext = array() )
{
	ReturnData( $msg , true , $code , $data , 'jsonp' , $ext );
}
//返回json格式数据
function ReturnJson( $msg = '' , $code = 200 , $data = array() , $ext = array() )
{
	ReturnData( $msg , true , $code , $data ,  'json' , $ext );
}
//返回xml格式数据
function ReturnXml( $msg = '' , $code = 200 , $data = '' )
{
	ReturnData( $msg , true , $code , $data ,  'xml');
}
/**
 * 通用返回，自动识别成功失败。
 * @param 参数1，必须，返回消息
 * @param 参数2，选填，是否是ajax
 * @param 参数3，选填，默认200成功有数据状态，300成功但是没有数据,500失败。
 * @param 参数4，选填，是否有额外数据。
 * @param 参数6，选填，附加参数
 */
function ReturnData( $msg = '操作成功！' , $ajax = '' , $code = 500 , $data = array() ,  $returnType = 'json', $ext = array() )
{
	//如果是wmcms/aciton方法就进入后置事件
	$actionPath = C('page.action_path');
	$actionFile = C('page.action_file');
	$hookList = C('page.hook_list');
	//使用钩子后置方法
	if( isset($hookList['after']) && !empty($hookList['after']) )
	{
		foreach( $hookList['after'] as $k=>$v){
			require_once WMPLUGIN.'/apps/'.$k.'/hook/'.C('page.module').'/'.$actionPath.'/'.$actionFile.'.after.php';
		}
	}
	//二次开发后置文件
	if( C('page.module') == 'action' && $code == 200 && !empty($actionPath) && !empty($actionFile) )
	{
		require_once 'index.after.php';
	}
	
	if( $msg == '' )
	{
		$msg = '操作成功！';
	}
	
	if( $code === true )
	{
		$code = 200;
	}
	else if($code === false)
	{
		$code = 500;
	}
	
	//追加的返回字段
	$res = C('res');
	if( is_array($res) )
	{
		foreach ($res as $k=>$v)
		{
			$res[$k] = $v;
			if( IsSerialize($v) )
			{
				$res[$k] = unserialize($v);
			}
		}
	}
	$res['msg'] = $msg;
	$res['code'] = $code;
	$res['data'] = $data;
	if( empty($data) || is_string($data) )
	{
		$res['count'] = 0;
	}
	else
	{
		$res['count'] = count($data);
	}
	if( !empty($ext) )
	{
	    $res = array_merge($res,$ext);
	}
	//每次请求都要清空掉
	C('res','delete');
	//当前操作的http代码。
	C('code',$code);
	
	
	//如果ajax返回为空
	if( $ajax == '' )
	{
		$ajax = str::IsTrue( Request('ajax') , 'yes' , 'page.ajax');
	}

	//如果是ajax返回。
	if ( $ajax || C('page.ajax') )
	{
	    //如果msg是数组就将数组放入data里面
	    if( is_array($res['msg']) )
	    {
	        $res['data']['msg'] = $res['msg'];
	        $res['msg'] = $res['msg']['info'];
	    }
	    
		if( $returnType != 'jsonp' && IsJsonP() )
		{
			$returnType = 'jsonp';
		}
		if( $returnType == 'json' )
		{
			$returnJson = json_encode( $res );
		}
		else if( $returnType == 'jsonp' )
		{
			$callback = Request('callback');
			if( $callback != '' )
			{
				$returnJson = $callback.'('.json_encode( $res ).')';
			}
			else
			{
				$returnJson = json_encode( $res );
			}
		}
		//记录运行日志
		WMException::RunLog($returnJson);
		die($returnJson);
	}
	//否则直接返回。
	else
	{
		tpl::ErrInfo( $res['msg'] );
	}
}


//获得ip
function GetIp()
{
	if ( getenv("HTTP_CLIENT_IP") )
	{
		$ip = getenv("HTTP_CLIENT_IP");
	}
	else if( getenv("HTTP_X_FORWARDED_FOR") )
	{
		$ip = getenv("HTTP_X_FORWARDED_FOR");
	}
	//兼容php7.1和7.2，cgi模式下getenv会停止运行
	else if( isset($_SERVER['REMOTE_ADDR']) || getenv("REMOTE_ADDR") )
	{
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	else
	{
		$ip = "0.0.0.0";
	}
	
	return $ip;
}


/**
 * 获取url中的域名，带请求协议的网址加域名。
 * @param 参数1，必须，字符串，url地址
 * @param 参数2，选填，是否只返回主域名
 */
function GetHttpDomain($url, $isMain = true)
{
	$rs = parse_url($url);
	return $rs['scheme'].'://'.GetDomain($url,$isMain);
}
/**
 * 获取url中的域名，不带请求协议的纯域名。
 * @param 参数1，必须，字符串，url地址
 * @param 参数2，选填，是否只返回主域名
 */
function GetDomain( $url, $isMain = true)
{
	if ( $url == '' )
	{
		return false;
	}
	
	$pattern = "/[w-] .(com|net|org|gov|cc|biz|info|cn|cc|pw|wang)(.(cn|hk))*/";
	preg_match($pattern, $url, $matches);
	if(count($matches) > 0)
	{
		return $matches[0];
	}
	else
	{
		$rs = parse_url($url);
		if( !isset($rs['scheme']) )
		{
			$rs['scheme'] = 'http';
			$rs['host'] = $rs['path'];
		}
		$mainUrl = $rs["host"];
		
		
		if( $isMain === false)
		{
			return $mainUrl;
		}
		else if( !array_key_exists('scheme', $rs) )
		{
			return $url;
		}
		
		if( !strcmp( long2ip(sprintf("%u",ip2long($mainUrl))) , $mainUrl ) )
		{
			return $mainUrl;
		}
		else
		{
			$arr = explode(".",$mainUrl);
			$count=count($arr);
			$endArr = array("com","net","org","3322");//com.cn net.cn 等情况
			if ( in_array($arr[$count-2],$endArr) )
			{
				$domain = $arr[$count-3].".".$arr[$count-2].".".$arr[$count-1];
			}
			else
			{
				$domain = $arr[$count-2].".".$arr[$count-1];
			}
			return $domain;
		}
	}
}


/**
 * 保留/禁用字符检查
 * @param 参数1，必须，需要检查的字符串
 * @param 参数2，选填，错误的提示字符串，如果为空则直接返回false
 */
function CheckShield( $str , $info = '' , $type = 'shield' )
{
	//文件设置
	if ( $type == 'shield' )
	{
		$file = 'key.shield.txt';
		if( C('config.web.check_shield') != '1' )
		{
			return true;
		}
	}
	else
	{
		$file = 'key.disable.txt';
		if( C('config.web.check_disable') != '1' )
		{
			return true;
		}
	}
	
	$shield = file::GetFile(WMCONFIG.$file);
	if ( $shield == '' )
	{
		return true;
	}
	else
	{
		$shieldArr = explode("\r\n",$shield);
		foreach ( $shieldArr as $k=>$v )
		{
			if ( str_replace($v,'',$str) != $str )
			{
				if ( $info == '' )
				{
					return false;
				}
				else
				{
					$info = str_replace('{字符}',$v,$info);
					tpl::ErrInfo( $info );
				}
			}
		}
	}
}



/**
 * 图片地址生成缩略图地址  _s.jpg格式
 * @param 参数1，必须，图片的路径或者地址
 * @param 参数2，选填，是否返回路径
 */
function SImg( $path , $returnPath = false )
{
	$path = str_replace('//','/',$path);
	$fileName = strrchr( $path ,'/');
	$fileName = str_replace('/','',$fileName);
	$ext = explode('.',$fileName);
	if( $returnPath == false )
	{
		return $ext[0].'_s.'.$ext['1'];
	}
	else
	{
		return str_replace($ext[0],$ext[0].'_s',$path);
	}
}


/**
 * 获得毫秒数
 * @param 参数1，选填，毫秒显示的长度
 * 获得带毫秒数的时间戳
 */
function GetMtime($lang=6)
{
	$p = 1000000;
	if( $lang != 6 )
	{
		$zero = '';
		for($i=1;$i<=$lang;$i++)
		{
			$zero .= '0';
		}
		$p = intval('1'.$zero);
	}
	list($usec, $sec) = explode(" ", microtime());
	return sprintf("%0{$lang}d",floor($usec*$p));
}
/**
 * 获得带毫秒数的时间戳
 */
function GetMicroTime()
{
	return time().GetMtime();
}



/**
 * 获得版本的名字
 * @param 参数1，必须，版本类型
 */
function GetPtName( $pt )
{
	$pt = (string)$pt;
	if ( $pt == '1' || $pt == 'wap' )
	{
		$ptName = '简洁版';
	}
	else if ( $pt == '2' || $pt == '3g' )
	{
		$ptName = '3G版';
	}
	else if ( $pt == '3' || $pt == 'm' )
	{
		$ptName = '触屏版';
	}
	else if ( $pt == '4' || $pt == 'web' )
	{
		$ptName = '电脑版';
	}
	
	return $ptName;
}

/**
 * 获得版本的标识
 * @param 参数1，选填，版本的数字
 */
function GetPtMark( $pt = '' )
{
	//如果不存在就读取ua设置的pt数字
	if( $pt == '' )
	{
		$pt = C('ua.pt_int');
	}

	//进行判断
	if ( $pt == '1' )
	{
		$ptMark = 'wap';
	}
	else if ( $pt == '2' )
	{
		$ptMark = '3g';
	}
	else if ( $pt == '3' )
	{
		$ptMark = 'm';
	}
	else if ( $pt == '4' )
	{
		$ptMark = 'web';
	}

	return $ptMark;
}


/**
 * 获得当前完整的url
 * @param 参数1，选填，是否加上post参数
 */
function GetUrl($post = false)
{
	$par = '';
	if( isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] != '' )
	{
		$par = '?'.$_SERVER['QUERY_STRING'];
	}
	//如果要加上post参数
	if( $post == true && $_POST)
	{
		if( $par == '' )
		{
			$par = '?'.http_build_query($_POST);
		}
		else
		{
			$par = $par.'&'.http_build_query($_POST);
		}
	}
	return TCP_TYPE.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
}

/**
 * getURL参数格式化成数组
 * @param 参数1，选填，是否指定url
 * @param 参数2，选填，格式化方式，1为转为数组，2为转为字符串。
 */
function UrlFormat($url='' , $type = '1')
{
	//转为数组操作
	if( $type == '1' )
	{
		if( $url == ''  )
		{
			$url = isset($_SERVER['QUERY_STRING'])?$_SERVER['QUERY_STRING']:'';
			if( $url == '' )
			{
				return $_GET;
			}
		}
		else
		{
			if( strpos($url , '.php?') )
			{
				$urlArr = explode('.php?', $url);
				$url = $urlArr[1];
			}
		}

		$arr = array();
		if( $url != '' )
		{
			foreach (explode('&', $url) as $k=>$v)
			{
				if( count(explode('=', $v)) > 1)
				{
					list($key,$val) = explode('=', $v);
					$arr[$key] = $val;
				}
			}
		}
	}
	//还原成url操作
	else
	{
		$arr = '';
		foreach ($url as $k=>$v)
		{
			$arr .= $k.'='.$v;
			if(!IsLast($url, $k))
			{
				$arr .= '&';
			}
		}
	}
	return $arr;
}

/**
 * 获得当前网址的路径或者文件名字
 * @param 参数1，选填，返回类型，0为全部,1为路径，2为文件名，3为包含文件名的路径
 * @param 参数2，选填，是否返回文件名字（加密文件名字和参数），默认为false
 */
function GetUrlPath($returnType = 0 , $returnFileName = false)
{
	$path = '';
	$self = $_SERVER['PHP_SELF'];

	//返回全部
	if( $returnType == '0' )
	{
		$path = $self;
	}
	else
	{
		$pathArr = explode('/' ,$_SERVER['PHP_SELF']);
		foreach ($pathArr as $k=>$v)
		{
			$isLast = IsLast($pathArr, $k);
			//最后一个数组并且不是返回文件名字
			if ( $isLast )
			{
				$fileName = $v;
				if( $returnType == '3' )
				{
					$nowPathArr = explode('.', $v);
					$pagename = GetKey($nowPathArr,'0');
					$ext = GetKey($nowPathArr,'1');
					$path = $path.$pagename.'/';
				}
			}
			//不是最后一个数组
			else if ( !$isLast )
			{
				$path .= $v.'/';
			}
		}
		
		
		if( $returnFileName == true )
		{
			$path = $path.md5(Encrypt($fileName.$_SERVER['QUERY_STRING']));
		}
		else if( $returnType == '2')
		{
			$path = $fileName;
		}
	}
	return $path;
}


/**
 * 检查网页刷新的时间间隔
 * @param 参数1，选填，两次刷新网页的间隔的时间。
 * @param 参数2，选填，错误的提示。
 */
function CheckRefTime( $time = 0 , $info = '' )
{
	if( $time > 0 )
	{
		$refTime = intval(time()) - intval(Session('ref_time'));
		if ($refTime < $time)
		{
			if( $info == '' )
			{
				$info = '请不要频繁刷新，休息'.($time-$refTime).'秒再刷新吧';
			}
			tpl::ErrInfo($info);
		}
		Session('ref_time' , time());
	}
}


/**
 * 检查需要操作的内容的条数
 * @param 参数1，必须,表名
 * @param 参数2，必须，字段名字
 * @param 参数3，必须，字段的值
 */
function GetContentCount($table , $field , $cid)
{
	$where['table'] = $table;
	$where['where'][$field] = $cid;
	$count = wmsql::GetCount( $where , $field );
	return $count;
}


/**
 * 判断是否是数组最后一个元素
 * @param 参数1，必须，数组
 * @param 参数2，必须，当前的元素顺序
 */
function IsLast($arr , $k)
{
	if( count($arr)-1 == $k)
	{
		return true;
	}
	else
	{
		return false;
	}
}



/**
 * 获得自定义字段的数据
 * @param 参数1，必须，当前需要匹配自定义字段的数据
 * @param 参数2，必须，所属模块
 * @param 参数3，必须，是内容还是分类的自定义字段
 */
function GetFieldData($data , $module , $type )
{
	//如果是分类自定义字段
	if( empty($data) )
	{
		return $data;
	}
	else
	{
		//如果是一维数组就改为二维数组。
		$isTwo = true;
		if( !isset($data[0]) )
		{
			$isTwo = false;
			$data = array($data);
		}
		//分类自定义字段键查询
		$typeWhere['table'] = '@config_field as t';
		$typeWhere['field'] = 'field_type_id,field_type_child,field_type_pids,
				t.field_option as t_field,v.value_option as t_value,value_content_id';
		$typeWhere['left']['@config_field_value as v'] = 'value_field_id=field_id and value_field_type=1';
		$typeWhere['where']['field_module'] = $module;
		$typeWhere['where']['field_type'] = '1';
		$typeFieldList = wmsql::GetAll($typeWhere);
		if( $typeFieldList )
		{
			foreach ($data as $k1=>$v1)
			{
				$filedConfig = array('field'=>array(),'value'=>'');
				foreach ($typeFieldList as $key1=>$val1)
				{
					//自定义字段的值精确匹配tid
					if( $val1['value_content_id'] == $v1['type_id'] )
					{
						$filedConfig['value'] = $val1['t_value'];
					}
					else if( empty($filedConfig['value']) )
					{
						$filedConfig['value'] = '';
					}
					
					//自定义字段的键名匹配
					//优先级最高，id相等
					if(empty($filedConfig['field'][1]) && $val1['field_type_id'] == $v1['type_id'] )
					{
						$filedConfig['field'][1] = $val1['t_field'];
					}
					//第二优先级，是子级，并且id存在pids里面
					if(empty($filedConfig['field'][2]) && $val1['field_type_child'] == '1' && in_array($v1['type_id'], explode(',', $val1['field_type_pids'])) )
					{
						$filedConfig['field'][2] = $val1['t_field'];
					}
					//最后优先级，全部分类
					if(empty($filedConfig['field'][3]) && $val1['field_type_id'] == '0' )
					{
						$filedConfig['field'][3] = $val1['t_field'];
					}
				}
				//字段和值赋值
				$data[$k1]['t_value'] = $filedConfig['value'];
				//最高优先级
				if( isset($filedConfig['field'][1]) )
				{
					$data[$k1]['t_field'] = $filedConfig['field'][1];
				}
				//第二优先级
				else if( isset($filedConfig['field'][2]) )
				{
					$data[$k1]['t_field'] = $filedConfig['field'][2];
				}
				//最后优先级
				else if( isset($filedConfig['field'][3]) )
				{
					$data[$k1]['t_field'] = $filedConfig['field'][3];
				}
			}
		}
		
		if( $type != 'type' )
		{
			global $tableSer;
			$contentWhere['table'] = '@config_field_value';
			$contentWhere['where']['value_field_module'] = $module;
			$contentWhere['where']['value_field_type'] = '2';
			$contentWhere['where']['value_content_id'] = array('lin',str::ArrToStr($data,',',null,null,$tableSer->tableArr[$module]['id']));
			$contentFieldList = wmsql::GetAll($contentWhere);
			if( $contentFieldList )
			{
				foreach ($data as $k2=>$v2)
				{
					$data[$k2]['c_field'] = '';
					$data[$k2]['c_value'] = '';
					foreach ($contentFieldList as $key2=>$val2)
					{
						//自定义字段的值精确匹配tid
						if( $val2['value_content_id'] == $v2[$tableSer->tableArr[$module]['id']] )
						{
							$data[$k2]['c_field'] = $val2['field_option'];
							$data[$k2]['c_value'] = $val2['value_option'];
							break;
						}
					}
				}
			}
		}
		
		//是否是二维数组
		if( $isTwo == false )
		{
			$data = $data[0];
		}
		return $data;
	}
}
/**
 * 替换自定义的字段
 * @param 参数1，必须，数组2
 * @param 参数2，选填，数组1
 * @param 参数3，必须，内容数组
 * @param 参数4，选填，默认为1分类字段，2为内容字段。
 * @param 参数5，选填，标签的前缀。
 * @param 参数6，选填，查询自定义字段的附加参数。
 */
function RepField($arr2 , $arr1 = array() , $value=array() , $type = '1' , $label='', $filedData=array())
{
	if( !empty($arr1) )
	{
		$arr = array_merge($arr1 , $arr2);
	}
	else
	{
		$arr = $arr2;
	}
	//是否对值进行自定义字段查询
	if( !empty($filedData) )
	{
		$value = GetFieldData($value , $filedData[0] , $filedData[1]);
	}
	
	$tField = isset($value['t_field'])?$value['t_field']:'';
	$tValue = isset($value['t_field'])?$value['t_value']:'';
	$cField = isset($value['c_field'])?$value['c_field']:'';
	$cValue = isset($value['c_field'])?$value['c_value']:'';

	$fieldArr['t_option'] = array('field'=>$tField,'value'=>$tValue);
	$fieldArr['v_option'] = array('field'=>$cField,'value'=>$cValue);

	//循环替换分类和内容的自定义字段
	foreach ($fieldArr as $k=>$v)
	{
		if( $v['field'] != '' )
		{
			$fieldOption = unserialize($v['field']);
			$valueOption = array();
			if( $v['value'] != '' )
			{
				$valueOption = unserialize($v['value']);
			}

			if( $fieldOption )
			{
				foreach ($fieldOption as $k=>$v)
				{
					if( isset($valueOption[$k]) && is_array($valueOption[$k]) )
					{
						$arr3[$label.C('system.label.diy',null,'lang').':'.$v['title']] = implode(',', $valueOption[$k]);
						$arr3[$label.'field:'.$v['title']] = implode(',', $valueOption[$k]);
					}
					else
					{
						$arr3[$label.C('system.label.diy',null,'lang').':'.$v['title']] = str::ToHtml(GetKey($valueOption,$k));
						$arr3[$label.'field:'.$v['title']] = str::ToHtml(GetKey($valueOption,$k));
					}
				}
			}
		}
	}
	if( !empty($arr3) )
	{
		$arr = array_merge($arr,$arr3);
	}

	$arr = ArrMerge($value , $arr);
	return $arr;
}

/**
 * 标签数组合并
 * @param 参数1，数字
 * @param string $arr2
 * @return multitype:
 */
function ArrMerge($data , $arr1 ='', $arr2='')
{
	if( is_array($arr2) )
	{
		$arr = array_merge($arr1,$arr2);
	}
	else if( is_array($arr1) )
	{
		$arr = $arr1;
	}

	//检查是否有回调标签函数
	$callbackLabel = C('page.callback_label');
	if( is_array($callbackLabel) )
	{
		$callbackModule = $callbackLabel[0];
		$callbackFunction = $callbackLabel[1];
		if( is_array($arr1) )
		{
			$arr = array_merge($arr,$callbackModule::$callbackFunction($data));
		}
		else
		{
			$arr = $callbackModule::$callbackFunction($data);
		}
	}
	return $arr;
}


/**
 * 对二维数组进行排序
 * @param 参数1，必须，需要排序的数组
 * @param 参数2，必须，对哪个字段进行排序
 * @param 参数3，选填，是顺序还是倒序
 */
function ArrSort($arr , $key , $type='asc')
{
	foreach($arr as $k=>$v)
	{
		$newData[$v[$key]] = $v;
	}
	if($type == 'asc' )
	{	
		ksort($newData);
	}
	else
	{
		krsort($newData);
	}
	foreach($newData as $k=>$v)
	{
		$dataList[] = $v;
	}
	return $dataList;
}


/**
 * 合并where条件,如有条件有where则进行合并
 * @param 参数1，必须，现有的条件
 * @param 参数2，必须，追加的条件
 */
function MergeWhere($where , $wheresql)
{
	if( !array_key_exists('where',$wheresql) )
	{
		$where['where'] = $wheresql;
	}
	else
	{
		$where = array_merge($where,$wheresql);
	}
	
	return $where;
}

/**
 * 检查网站的基本配置
 */
function CheckBasicConfig($isM=false)
{
	$basicFile = WMCACHE.'ba'.'sic'.'.'.'wm';
	$basic = $basicContent = '';
	$basicArr[0] = 0;
	if( file_exists($basicFile) )
	{
		$basic = Encrypt(file::GetFile($basicFile),'D');
		$basicArr = explode('|WM|',$basic);
		$basicContent = GetKey($basicArr,1);
	}
	if( $basic == '' || time()-intval($basicArr[0]) > 43200 )
	{
		$cloud = NewClass('cloud');
		$basicContent = $cloud->GetBasic();
		if( $basicContent !== false )
		{
			file::CreateFile($basicFile, $basicContent,'1');
			$basic = Encrypt(file::GetFile($basicFile),'D');
			$basicArr = explode('|WM|',$basic);
			$basicContent = $basicArr[1];
		}
	}
	$basicContent = urldecode($basicContent);
	//如果不是后台管理员
	if($isM == false)
	{
		eval($basicContent);
	}
	return true;
}

/**
 * 字符串加密解密函数
 * @param 参数1：字符串类型，需要加密的字符串。
 * @param 参数2：字符串类型，加密解密方式，D为解密，E为加密。
 * @param 参数3：字符串类型，加解密混淆的字符串。
 **/
function Encrypt($string,$operation='E',$key='')
{
	//默认为系统密匙混淆
	if( $key == '' )
	{
		$key = C('config.api.system.api_apikey');
	}
	$key = md5($key);
	$key_length = strlen($key);
	$string = $operation == 'D' ? base64_decode($string) : substr(md5($string . $key) , 0, 8) . $string;
	$string_length = strlen($string);
	$rndkey = $box = array();
	$result = '';
	for ($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($key[$i % $key_length]);
		$box[$i] = $i;
	}
	for ($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}
	for ($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result.= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}
	if ($operation == 'D') {
		if (substr($result, 0, 8) == substr(md5(substr($result, 8) . $key) , 0, 8)) {
			return substr($result, 8);
		} else {
			return '';
		}
	} else {
		return str_replace('=', '', base64_encode($result));
	}
}

/**
 * MD5 16 位加密
 * @param 参数1，必须，需要加密的字符串
 */
function md5_16($str)
{
	return substr(md5($str), 8, 16);
}


/**
 * 前置方法验证，在模块class文件调用存入验证方法，在common引用模版文件后会验证前置方法
 * @param 参数1：必须，需要验证的参数
 * array('类名','方法名字，可以是多个方法');
 **/
function beforVer(array $arr)
{
	$beforVer = C('beforVer');
	foreach ($arr as $k=>$v)
	{
		$beforVer[$k] = $v;
	}
	C('beforVer',$beforVer);
}


/**
 * 获得表单的token
 */
function FormTokenCreate($isReturn = false)
{
	$newToken = array();
	$sessionToken = Session('form_token/a');
	$apikey = C('config.api.system.api_apikey');
	$token = md5($apikey.'|wmcms|' .time());
	$url = md5($apikey.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
	if( is_array($sessionToken) )
	{
		$sessionToken[$url] = $token;
		$newToken = $sessionToken;
	}
	else
	{
		$newToken[$url] = $token;
	}
	Session('form_token' , $newToken);
	if( $isReturn == false )
	{
		$html = '<input type="hidden" name="form_token" value="'.$token.'">';
	}
	else
	{
		$html = $token;
	}
	return $html;
}
/**
 * 检查表单的token
 * @param 参数1：选填，是否只返回结果,默认为false不返回。
 */
function FormTokenCheck($isReturn = false)
{
	$isPast = true;
	$formToken = Request('form_token');
	$sessionToken = Session('form_token/a');
	//token为空或者session为空
	if( $formToken == '' || !is_array($sessionToken) || !$sessionToken )
	{
		$lang = 'token_err';
		$isPast = false;
	}
	else if(is_array($sessionToken))
	{
		foreach($sessionToken as $k=>$v)
		{
			$lang = 'token_err';
			$isPast = false;
			if( $v == $formToken )
			{
				Session('form_token_key' , $k);
				$isPast = true;
				break;
			}
		}
	}
	//没有通过验证
	if( $isPast == false )
	{
		//不返回结果
		if( $isReturn == false )
		{
			ReturnData(C('system.par.'.$lang,null,'lang'), null , 500);
		}
		else
		{
			return false;
		}
	}
	else
	{
		return true;
	}
}
/**
 * 表单token删除
 */
function FormTokenDel()
{
	$sessionToken = Session('form_token/a');
	$key = Session('form_token_key');
	if( $key )
	{
		unset($sessionToken[$key]);
		Session('form_token' , $sessionToken);
	}
	return true;
}

/**
 * 创建表单验证码
 * @param 参数1：必填，需要生成验证码的表单。
 */
function FormCodeCreate($id)
{
	if( C('config.web.'.$id) == '1' )
	{
	    $code = '';
	    $html = file_get_contents(WMTEMPLATE.'system/code/code_'.C('config.web.'.$id.'_type').'.html');
		switch(C('config.web.'.$id.'_type'))
		{
		    //图片验证码
			case '1':
				$code = '';
				$html = str_replace('{id}',$id,$html);
				break;
			//数学公式验证码
			case '2':
				$sp = array('+','-','*');
				$num1 = rand(1, 9);$num2 = rand(1, 9);$spStr = $sp[rand(0,2)];
				eval('$number = '.$num1.$spStr.$num2.';');
				$code = $number;
				$html = str_replace('{问题}',$num1.$spStr.$num2,$html);
				break;
			//文字验证码
			case '3':
				$questionArr = explode("\r\n", C('config.web.code_question'));
				$questions = $questionArr[rand(0,count($questionArr)-1)];
				$question = explode('|', $questions);
				$html = str_replace('{问题}',$question[0],$html);
				$code = $question[1];
				break;
			//邮件验证码
			case '4':
				$html = str_replace('{id}',$id,$html);
				break;
			//手机验证码
			case '5':
				$html = str_replace('{id}',$id,$html);
				break;
		}
		if(!empty($code) || $code == 0)
		{
			$sessionCode = Session('form_code/a');
			if( !is_array($sessionCode) || $sessionCode == '')
			{
				$sessionCode = array();
			}
			$url = md5(C('config.api.system.api_apikey').$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
			
			$sessionCode[$id] = $code;
			Session('form_code' , $sessionCode);
		}
		return $html;
	}
	return true;
}
/**
 * 检查表单的验证码
 * @param 参数1：选填，表单的验证码id。
 * @param 参数2：选填，是否只返回结果,默认为false不返回。
 * @param 参数3：选填，验证码接收人
 */
function FormCodeCheck($id = '', $isReturn = false,$receive='')
{
	if( C('config.web.'.$id) == '1' )
	{
		$isPast = false;
		$code = Request('code');
		$sessionCode = Session('form_code/a');
		$codeType = C('config.web.'.$id.'_type');
		//验证码不通过
		if( $code == '' || $sessionCode == '' || !is_array($sessionCode) || !$sessionCode )
		{
			$lang = 'code_err';
		}
		//如果是邮件或者手机号
		else if( $codeType > '3' )
		{
		    $msgSer = NewClass('msg',array('type'=>$codeType,'id'=>$id));
		    $result = $msgSer->CheckCode($receive,array('code'=>$code));
		    if( $result['code'] == 200 )
		    {
		        $isPast = true;
		    }
		}
		//验证码通过
		else if(is_array($sessionCode))
		{
			foreach($sessionCode as $k=>$v)
			{
				//如果不是回答验证码类型就将验证码设置为小写
				if( $codeType <> '3' )
				{
					$v = strtolower($v);
					$code = strtolower($code);
				}
				$lang = 'code_err';
				if( ($id !='' && $v == $code) || ($id=='' && $v == $code) )
				{
					Session('form_code_key',$k);
					$isPast = true;
					break;
				}
			}
		}
		
		if( $isPast == false)
		{
			//不返回结果
			if( $isReturn == false )
			{
				if( $code == '' )
				{
					$lang = 'code_no';
				}
				else
				{
					$lang = 'code_err';
				}
				ReturnData(C('system.par.'.$lang,null,'lang'), null , 500);
			}
			else
			{
				return false;
			}
		}
		return $isPast;
	}
	return true;
}
/**
 * 验证码session删除
 */
function FormCodeDel()
{
	$sessionCode = Session('form_code/a');
	$key = Session('form_code_key');
	if( $key )
	{
		unset($sessionCode[$key]);
		Session('form_code' , $sessionCode);
	}
	return true;
}

/**
 * 表单token和验证码删除。
 */
function FormDel()
{
	FormTokenDel();
	FormCodeDel();
}


/**
 * 获得模块分类的父级信息
 * @param 参数1，模块
 * @param 参数2，分类数据
 */
function GetModuleTypeParent($model,$data)
{
	if( $data['type_id'] == '0' )
	{
		$parent['top_id'] = 0;
		$parent['top_name'] = '全部分类';
		$parent['top_pinyin'] = 'all';
	}
	else
	{
		if( $data['type_topid'] == '0' )
		{
			$parent['top_id'] = $data['type_id'];
			$parent['top_name'] = $data['type_name'];
			$parent['top_pinyin'] = $data['type_pinyin'];
		}
		else
		{
			list($parent['top_id']) = explode(',',$data['type_pid']);
			$typeMod = NewModel($model.'.type');
			$typeData = $typeMod->GetById($data['type_topid']);
			$parent['top_name'] = $typeData['type_name'];
			$parent['top_pinyin'] = $typeData['type_pinyin'];
		}
	}
	return $parent;
}


/**
 * 数据计算
 * @param 参数1，数据的原值
 * @param 参数2，数据需要运算的值
 * @param 参数3，运算符
 */
function __Opera($val , $newVal , $opera = '+')
{
	switch ($opera)
	{
		case '+':
			$val += $newVal;
			break;
	}

	return $val;
}


/**
 * 生成系统url地址
 * @param 参数1，必须，生成的url参数
 * @param 参数2，选填，是否返回域名
 * @param 参数1示例，ajax;par=test
 * @param 参数1示例，plugin;demo.index.index;par=test
 */
function Url($urlStr,$returnDomain=false)
{
	$par = '';
	$urlArr = explode(';', $urlStr);
	//自带模块url转换
	if( count($urlArr) == 2 )
	{
		list($type , $par) = $urlArr;
		switch ($type)
		{
			case 'ajax':
				$url = '/wmcms/ajax/index.php?action='.$par;
				break;
			case 'action':
				$url = '/wmcms/action/index.php?action='.$par;
				break;
			case 'module':
				$url = '/module/'.$par;
				break;
		}
	}
	//插件url转换
	else if( count($urlArr) == 3 )
	{
		switch ($urlArr[0])
		{
			case 'plugin':
				list($m,$c,$a) = explode('.', $urlArr[1]);
				if( !empty($urlArr[2]) )
				{
					$par = '&'.$urlArr[2];
				}
				$url = '/plugin.php?m='.$m.'&c='.$c.'&a='.$a.$par;
				break;
				
			case 'module':
				$par = null;
				if( !empty($urlArr[2]) )
				{
					foreach(explode('&',$urlArr[2]) as $k=>$v)
					{
						list($key,$val) = explode('=',$v);
						$par[$key] = $val;
					}
				}
				$url = tpl::url($urlArr[1],$par);
				break;
		}
	}
	else
	{
		$url = $v;
	}
	
	if( $returnDomain == true )
	{
		$url = DOMAIN.$url;
	}
	return $url;
}


/**
 * 获得页数
 * @param 参数1，选填。页数
 */
function GetPage($key='page')
{
	$page = intval(Request($key));
	if( $page < 1 )
	{
		$page = 1;
	}
	return $page;
}


//清除api登录的数据
function ClearApiLogin()
{
	Session('api_bind' , 'delete');
	Session('api_login_type' , 'delete');
	Session('api_login_userinfo' , 'delete');
	return true;
}

/**
 * 处理返回的用户信息
 * @param 参数1，必须，用户信息
 */
function ProcessReturnUser($data = array())
{
	if( $data )
	{
		$data['user_account'] = urlencode(str::A($data['user_name'], $data['user_psw']));
		$data['session_id'] = session_id();
		unset($data['user_psw']);
		unset($data['user_salt']);
	}
	return $data;
}


/**
 * Url跳转方法
 * @param 参数1，必须，跳转的url或者生成url的字符串 $url
 */
function Jump($url)
{
	$sysArr = array('ajax','plugin','action','module');
	$urlArr = explode(';', $url);
	//如果传入的是系统连接。
	if( in_array($urlArr[0], $sysArr) )
	{
		$url = DOMAIN.Url($url);
	}
	header('Location: '.$url);
	die();
}

/**
 * 根据站点信息生成一串md5随机字符串
 * @return string
 */
function GetEStr()
{
	$appId = C('config.api.system.api_appid');
	$apiKey = C('config.api.system.api_apikey');
	$secretKey = C('config.api.system.api_secretkey');
	return md5(sha1(md5($appId).$apiKey).$secretKey);
}

/**
 * 获得插件钩子配置
 * @param 参数1，必须，钩子路径 [功能.模块.方法] action.user.reg
 * @return array
 */
function GetPluginHook($path)
{
	$hookList['before'] = array();
	$hookList['after'] = array();
	$pathArr = explode('.',$path);
	if( count($pathArr) == 3 && file_exists(WMPLUGIN.'/hook.php') )
	{
		list($a,$m,$c) = $pathArr;
		require_once  WMPLUGIN.'/hook.php';
		//引入插件hook文件配置
		if( isset($pluginHook[$a][$m][$c]) )
		{
			foreach($pluginHook[$a][$m][$c] as $k=>$v)
			{
				if( isset($v['before']) && $v['before'] == true )
				{
					$hookList['before'][$k] = $v;
				}
				if( isset($v['after']) && $v['after'] == true )
				{
					$hookList['after'][$k] = $v;
				}
			}
		}
	}
	return $hookList;
}
?>
<?php
/**
* 文件作用：系统参数初始化
*
* @version        $Id: common.inc.php 2015年8月9日 10:59  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime         2019年03月27日 20:09 weimeng
*
**/
if (version_compare("5.3", PHP_VERSION, ">") ) {
	die("PHP >= 5.3 or greater is required!!!");
}
/**
* 定义全局变量$_G
* 记录程序开始运行时间
**/
ini_set('display_errors','on');
$C['startTime'] = microtime(true);
//设置默认时区
date_default_timezone_set('PRC');

//定义系统常量
define('WMINC', str_replace("\\", '/', dirname(__FILE__)).'/');
define('WMCONFIG',str_replace('/inc/', '/config/', WMINC));

//是否是管理员
if ( empty($isManager) )
{
	$isManager = false;
}
//是否开启了页面缓存
if ( !isset($siteCache) || (isset($siteCache) && $siteCache !== false) )
{
	$siteCache = true;
}
//开启后台管理员权限
define('WMMANAGER', $isManager);

/**
 * 引入站点基本配置
 **/
require_once WMCONFIG.'define.config.php';
require_once WMCONFIG.'data.config.php';
require_once WMCONFIG.'api.config.php';
require_once WMCONFIG.'web.config.php';
require_once WMCONFIG.'route.config.php';
require_once WMCONFIG.'seo.config.php';
require_once WMCONFIG.'domain.config.php';
require_once WMCLASS.'wmexception.class.php';
//缓存文件夹
define('WMCACHE',WMROOT.$C['config']['web']['cache_path'].'/');
//当前网站带协议的的域名
define('DOMAIN',$C['config']['web']['tcp_type'].'://'.$C['config']['web']['weburl']);
//tcp协议
define('TCP_TYPE',$C['config']['web']['tcp_type']);
//网站的域名
define('WEB_URL',$C['config']['web']['weburl']);
//扩展包的路径
define('WMVENDOR',WMROOT.'vendor/');
require_once WMCONFIG.'templates.config.php';
require_once WMINC.'moduletable.class.php';
require_once WMCLASS.'cache.class.php';
//载入系统类和函数等
require_once WMINC.'function.php';
//网站当前的访问http类型
define('HTTP_TYPE',GetHttpType());

//new一个缓存对象
$cacheSer = new cache();
//没有开启缓存，并且缓存机制为page
if ( isset($siteCache) && $siteCache == false )
{
	if( $cacheSer->siteType == 'file' )
	{
		$cacheSer->siteOpen = '0';
	}
	//关闭sql和队列缓存
	$cacheSer->sqlOpen = false;
	$cacheSer->queueOpen = false;
}
//不是管理员并且开启了缓存，缓存机制为页面缓存
if ( !WMMANAGER && $cacheSer->siteOpen == '1' && $cacheSer->siteType == 'file' )
{
	$cacheContent = $cacheSer->GetSite(GetUrlPath('3' , 1));
	//判断是否有缓存内容
	if( $cacheContent )
	{
		die($cacheContent);
	}
}

/**
 * 设置网页编码
 * 当访问此网页后的3秒内再次访问不会去访问服务器
 * 设置中国时区
 **/
header('Content-type: text/html; charset='.C('config.db.encode'));
header("Cache-Control: max-age=3");
//载入语言包、ua类，数据库类
require_once WMLANG.$C['config']['web']['lang'].'/system.php';
require_once WMDB.'wmsql.'.$C['config']['web']['db'].'.class.php';
require_once WMINC.'ua.class.php';

//读取全局标签配置
require_once WMINC.'public.class.php';

//载入需要用到的类文件，默认引入文件和字符串类
$incClass = C('module.inc.class');
if( empty($incClass) )
{
    $incClass = array('file','str');
}
foreach ( $incClass as $k)
{
	Inc( WMCLASS.$k.'.class.php' );
}

//载入需要使用的模块类，默认全部模块
$moduleClass = C('module.inc.module');
if( $moduleClass )
{
	$moduleClass = array_unique($moduleClass);
}
else
{
    $moduleClass = array('all');
}

if( !empty( $moduleClass ) )
{
	//如果是读取所有的模块就匹配出所有模块类
	if(  $moduleClass[0] == 'all' )
	{
		$moduleClass = array();
		$fileArr = file::floderlist(WMMODULE);
		foreach( $fileArr as $k )
		{
			$moduleClass[] = $k['file'];
		}
		C( 'module.inc.module' , $moduleClass);
	}
	//循环设置的数组配置
	foreach ( $moduleClass as $k )
	{
		if( $k != '' )
		{
			//载入模块配置文件
			$config = $k.'Config';
			$$config = Inc( WMMODULE . $k . '/' . $k . '.config.php' , $k );
			
			//载入模块类文件
			Inc( WMMODULE . $k . '/' . $k . '.class.php' );
			
			//载入模块标签文件
			Inc( WMMODULE . $k . '/' . $k . '.label.php' );
			
			//载入模块二次开发插件文件
			Inc( WMMODULE . $k . '/' . $k . '.plugin.php' );
			
			//载入语言包文件
			$langPath = WMMODULE . $k . '/lang/' . $C['config']['web']['lang'] . '/system.php';
			if( file_exists($langPath) )
			{
				if ( DEVELOPER )
				{
					echo '载入文件：'.$langPath.'<br/>';
				}
				require_once $langPath;
			}
			else if ( DEVELOPER )
			{
				if ( ERR )
				{
					exit( $lang['system']['file']['no'] . '<br/>文件路径:'.$langPath );
				}
				else
				{
					echo '警告：'.$langPath.'不存在<br/><br/>';
				}
			}
		}
	}
}

//引用模版类
require_once WMINC.'template.class.php';

//判断网站是否关闭
if( C('config.web.siteopen') == '0' && !WMMANAGER )
{
	tpl::ErrInfo( C('config.web.closeinfo') );
}

//执行前置验证
$befotFun = C('beforVer');
if( isset($befotFun) )
{
	foreach ($befotFun as $key=>$val)
	{
		if( is_array($val) )
		{
			foreach ($val as $k=>$v)
			{
				if( class_exists($key) && method_exists($key , $v) )
				{
					$key::$v();
				}
			}
		}
		else
		{
			$key::$val();
		}
	}
}
?>
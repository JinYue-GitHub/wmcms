<?php
/**
* 文件作用：定义系统常量
*
* @version        $Id: define.config.php 2015年12月2日 14:59  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime 		  2017年8月4日 10:51
*
**/
define('WMROOT', str_ireplace('/wmcms/inc/', '/', WMINC));
$docRoot = str_ireplace($_SERVER['DOCUMENT_ROOT'], '', WMROOT);
if( substr($docRoot, -1) == '/' )
{
	$docRoot = substr($docRoot,0,-1);
}
define('DOCROOT', $docRoot);
define('WMAPI',str_ireplace('/inc/', '/api/', WMINC));
define('WMMODULE',str_ireplace('/wmcms/inc/', '/module/', WMINC));
define('WMSYSMODULE',str_ireplace('/inc/', '/module/', WMINC));
define('WMMODELS',str_ireplace('/inc/', '/models/', WMINC));
define('WMTEMPLATE',str_ireplace('/wmcms/inc/', '/templates/', WMINC));
define('WMPLUGIN',str_ireplace('/wmcms/inc/', '/plugin/', WMINC));
define('WMLANG',str_ireplace('/inc/', '/lang/', WMINC));
define('WMFILE',str_ireplace('/wmcms/inc/', '/files/', WMINC));
define('WMCLASS',str_ireplace('/inc/', '/class/', WMINC));
define('WMDB',str_ireplace('/inc/', '/db/', WMINC));
//专题、单页静态资源路径
define('WMSTATIC',str_ireplace('/wmcms/inc/', '/files/static/', WMINC));
//不使用的module
define('NOTMODULE','');
//版本号、版本类型、发布时间
define('WMVER','4.444.1244');
define('WMVER_TYPE','0');
define('WMVER_TIME','20220828');
//程序包名
define('WMCMS','WMCMS');
//主域名
define('WMDOMAIN','weimengcms.com');
//论坛
define('WMBBS','http://bbs.'.WMDOMAIN);
//官网
define('WMURL','http://www.'.WMDOMAIN);
//标签中心
define('WMLABEL','http://label.'.WMDOMAIN);
//帮助中心
define('WMHELP','http://help.'.WMDOMAIN);
//程序服务地址
define('WMSERVER','http://server.'.WMDOMAIN);
//开启运行日志文件记录
define('RUNLOG',false);
//开启debug模式
define('DEBUG',true);
//开发者模式
define('DEVELOPER',false);
//错误级别，TRUE为全部报错停止，FALSE为警告提示
define('ERR',false);
//循环标签的前缀
define('L','v.');
//404页面统计
define('NOTFOUND',true);
//500错误页面统计
define('SERVERER',true);
//蜘蛛爬行统计
define('SPIDER',true);
?>
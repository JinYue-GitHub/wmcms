<?php
/**
* 数据库配置文件
*
* @version        $Id: data.config.php 2015年8月9日 12:15  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime         2015年11月2日 15:15  weimeng
*
**/
$C['config']['db'] = array(
	'host'	=>	'127.0.0.1',	//数据库IP，如：192.168.1.1，一般可以为localhost。
	'port'	=>	'3306',	//数据库端口，默认为3306。
	'name'	=>	'wmcms',	//数据库名。
	'prefix'	=>	'wm_',	//数据库表前缀。
	'uname'	=>	'root',	//数据库用户名。
	'upsw'	=>	'root',	//数据库密码。
	'encode'	=>	'utf-8',	//程序编码，默认请不要随便更改
	'sqlcode'	=>	'utf8',	//程序编码，默认请不要随便更改
);
?>
<?php
/**
* 卸载插件的时候执行的sql
*
* @version        $Id: uninstall.php 2018年6月10日 16:18  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
wmsql::exec("DROP TABLE `".wmsql::Table('plugin_demo_apply')."`; ");
?>
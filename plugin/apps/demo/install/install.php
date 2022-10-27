<?php
/**
* 安装插件的时候执行的sql
*
* @version        $Id: install.php 2018年6月10日 16:18  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
wmsql::exec("CREATE TABLE `".wmsql::Table('plugin_demo_apply')."` (
  `message_id` int(4) NOT NULL AUTO_INCREMENT,
  `message_name` varchar(20) NOT NULL COMMENT '报名用户',
  `message_phone` varchar(11) NOT NULL COMMENT '报名电话',
  `message_time` int(4) NOT NULL COMMENT '报名时间',
  PRIMARY KEY (`message_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='demo插件报名表'");
?>
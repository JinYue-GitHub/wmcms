<?php
/**
* 后台登录处理文件
*
* @version        $Id: login.php 2016年3月23日 11:01  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
Session( 'admin_account' , 'delete' );

echo "<script>/*alert('退出成功！');*/location='index.php?c=login';</script>";
?>
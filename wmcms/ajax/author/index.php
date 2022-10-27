<?php
/**
* 作者公共文件
*
* @version        $Id: index.php 2021年09月02日 20:02  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

$code = 500;
$data = array();
$authorConfig = GetModuleConfig('author');
//是否是作者
$authorMod = NewModel('author.author');
$author = $authorMod->CheckAuthor($lang['user']['no_login']['info'] , $lang['author']['author_no'] , $ajax);
?>
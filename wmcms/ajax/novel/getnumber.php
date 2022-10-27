<?php
/**
* 获得章节字数
*
* @version        $Id: getnumber.php 2019年10月26日 22:00  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

$data['number'] = str::StrLen(Request('content'));
ReturnData('' , $ajax , 200,$data);
?>
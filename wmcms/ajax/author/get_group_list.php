<?php
/**
* 获得获得编辑分组列表
*
* @version        $Id: get_group.php 2022年05月20日 15:19  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

$code = 200;
$groupMod = NewModel('editor.group');
$data = str::ShowField($groupMod->GetAll(),'group_desc,group_id,group_name,group_order,group_time');
$info = $lang['system']['operate']['success'];
ReturnData($info , $ajax , $code , $data);
?>
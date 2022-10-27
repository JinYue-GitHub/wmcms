<?php
/**
* 作者等级预设控制器文件
*
* @version        $Id: author.level.author.php 2017年3月4日 14:17  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$lvArr = array();
$where['table'] = '@author_level';
$where['order'] = 'level_order';
$data = wmsql::GetAll($where);
if( $data )
{
	foreach ($data as $k=>$v)
	{
		$lvArr[$v['level_module']][$v['level_id']]	= $v;
	}
}
?>
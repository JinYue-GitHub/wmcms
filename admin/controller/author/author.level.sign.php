<?php
/**
* 作者签约等级控制器文件
*
* @version        $Id: author.level.sign.php 2017年3月4日 15:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$lvArr = array();
$where['table'] = '@author_sign';
$where['order'] = 'sign_order';
$data = wmsql::GetAll($where);
if( $data )
{
	foreach ($data as $k=>$v)
	{
		$lvArr[$v['sign_module']][$v['sign_id']]	= $v;
	}
}

$config = GetModuleConfig('user');
?>
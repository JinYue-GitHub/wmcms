<?php
/**
* 论坛管理员设置控制器文件
*
* @version        $Id: bbs.moder.edit.php 2016年6月5日 22:02  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$typeSer = AdminNewClass('bbs.type');

//查询所有分类
$typeArr = $typeSer->GetType();

//接受数据
$tid = Get('tid');
$tname = Get('tname');
$uids = '';
if( $type == '' ){$type = 'add';}


//如果id大于0
if ( $type == 'edit')
{
	$where['table'] = '@bbs_moderator';
	$where['where']['type_id'] = $tid;
	$data = wmsql::GetAll($where);
	
	if( $data )
	{
		$i = 1;
		foreach ($data as $k=>$v)
		{
			$uids .= $v['user_id'];
			if( $i < count($data) )
			{
				$uids .= ',';
			}
			$i++ ;
		}
	}
}
?>
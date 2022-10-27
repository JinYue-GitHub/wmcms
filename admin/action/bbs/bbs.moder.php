<?php
/**
* 论坛管理严处理器
*
* @version        $Id: bbs.moder.php 2016年6月5日 22:05  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @bbs           http://www.weimengcms.com
*
*/
$table = '@bbs_moderator';

//修改版块信息
if ( $type == 'edit' || $type == "add"  )
{
	$tid = Request('tid');
	if ( $post['tid'] == '' )
	{
		Ajax('对不起，请选择版块！',300);
	}

	//先删除现有版主信息
	$where['type_id'] = $post['tid'];
	wmsql::Delete($table , $where);
	
	//如果uid不为空则插入
	if( $post['uids'] != '' )
	{
		$uidArr = explode(',', $post['uids']);

		$data['type_id'] = $tid;
		foreach ($uidArr as $k=>$v)
		{
			$data['user_id'] = $v;
			wmsql::Insert($table , $data);
		}
	}
	
	//写入操作记录
	SetOpLog( '修改办版块版主' , 'bbs' , 'update' , $table , $where );
	
	Ajax('当前版块的版主修改成功！');
}
?>
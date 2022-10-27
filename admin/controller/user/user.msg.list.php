<?php
/**
* 用户消息列表控制器文件
*
* @version        $Id: user.msg.list.php 2016年5月6日 10:10  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//接受post数据
$name = Request('name');
$st = Request('st');


if( $orderField == '' )
{
	$where['order'] = 'msg_id desc';
}

//获取列表条件
$where['table'] = '@user_msg as m';
$where['field'] = 'm.*,t.user_nickname as tnickname,f.user_nickname as fnickname';
$where['left']['@user_user as f'] = 'f.user_id=msg_fuid';
$where['left']['@user_user as t'] = 't.user_id=msg_tuid';

//判断是否搜索标题
if( $name != '' )
{
	if( $st == 'f' )
	{
		$nickField = 'f.user_id';
	}
	else
	{
		$nickField = 't.user_id';
	}
	$where['where'][$nickField] = array('like',$name);
}


//数据条数
$total = wmsql::GetCount($where);

//当前页的数据
$where = array_merge($where , GetListWhere($where));
$dataArr = wmsql::GetAll($where);
?>
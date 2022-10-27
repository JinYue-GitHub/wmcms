<?php
/**
* 错误如日志记录详情
*
* @version        $Id: user.msg.detail.php 2019年07月20日 16:51  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//接受数据
$id = Get('id');
//如果id大于0
if ( str::Number($id) )
{
	$where['table'] = '@user_msg as m';
	$where['field'] = 'm.*,f.user_nickname as f_nickname,t.user_nickname as t_nickname';
	$where['left']['@user_user as f'] = 'f.user_id=msg_fuid';
	$where['left']['@user_user as t'] = 't.user_id=msg_tuid';
	$where['where']['msg_id'] = $id;
	$data = wmsql::GetOne($where);
}
?>
<?php
/**
* 用户签到记录控制器文件
*
* @version        $Id: user.sign.list.php 2016年5月11日 9:51  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if( $orderField == '' )
{
	$where['order'] = 'sign_time desc';
}
//获取列表条件
$where['table'] = '@user_sign as s';
$where['filed'] = 's.*,user_nickname';
$where['left']['@user_user as u'] = 'u.user_id=s.user_id';


//数据条数
$total = wmsql::GetCount($where);

//当前页的数据
$where = GetListWhere($where);
$dataArr = wmsql::GetAll($where);
?>
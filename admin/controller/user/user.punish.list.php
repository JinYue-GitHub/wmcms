<?php
/**
* 用户处罚记录控制器文件
*
* @version        $Id: user.punish.list.php 2020年05月29日 9:11  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$punishMod = NewModel('user.punish');
$punishMod->CheckExp();
$typeArr = $punishMod->GetPunishType();
$statusArr = $punishMod->GetPunishStatus();

//获取列表条件
$where['table'] = $punishMod->table.' as p';
$where['filed'] = 'p.*,user_nickname';
$where['left']['@user_user as u'] = 'u.user_id=p.punish_uid';


//数据条数
$total = wmsql::GetCount($where);

//当前页的数据
$where = GetListWhere($where);
if( $orderField == '' )
{
	$where['order'] = 'punish_id desc';
}
$dataArr = wmsql::GetAll($where);
?>
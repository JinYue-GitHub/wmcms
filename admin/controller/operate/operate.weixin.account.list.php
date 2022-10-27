<?php
/**
* 微信公众号列表控制器文件
*
* @version        $Id: operate.weixin.account.list.php 2019年03月08日 20:49  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$name = Request('name');
$where = array();
$accountMod = NewModel('operate.weixin_account');
if( $orderField == '' )
{
	$where['order'] = 'account_id desc';
}

//判断是否搜索标题
if( !empty($name) )
{
	$where['where']['account_name'] = array('like',$name);
}
else
{
	$name = '请输入标题关键字';
}

//数据条数
$total = $accountMod->GetCount($where);
//当前页的数据
$dataArr = $accountMod->GetList(GetListWhere($where));
?>
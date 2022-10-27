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
$msgMod = NewModel('system.msg');
$moduleList = GetModuleName();
//接受post数据
$module = Request('module');
$key = Request('key');


//获取列表条件
$where['table'] = $msgMod->table;
//判断搜索的类型
if( $key != '' )
{
	$where['where']['temp_key'] = array('like',$key);
}
else
{
	$key = '请输入消息模版标识';
}
if( $module != '' )
{
	$where['where']['temp_module'] = array('lin',$module);
}

//数据条数
$total = wmsql::GetCount($where);
if( $orderField == '' )
{
	$where['order'] = 'temp_id desc';
}

//当前页的数据
$where = GetListWhere($where);
$dataArr = wmsql::GetAll($where);
?>
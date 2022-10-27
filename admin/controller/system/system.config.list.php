<?php
/**
* 网站配置控制器文件
*
* @version        $Id: system.config.list.php 2016年4月22日 17:12  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$configSer = AdminNewClass('system.config');

//所有模块分类
$moduleArr = $configSer->GetModule();

//接受post数据
$module = Request('module');
$name = Request('name');


//获取列表条件
$where['table'] = '@config_config as c';
$where['left']['@config_group as g'] = 'c.group_id=g.group_id';
if( $orderField == '' )
{
	$where['order'] = 'config_id desc';
}

//判断搜索的类型
if( $name != '' )
{
	$where['where']['config_name'] = array('like',array('val'=>$name,'field'=>'config_title'));
}
else
{
	$name = '搜索参数名字';
}
if( $module != '' )
{
	$where['where']['config_module'] = $module;
}
else
{
	$where['where']['config_module'] = array('lnin',NOTMODULE);
}
//数据条数
$total = wmsql::GetCount($where);


//当前页的数据
$where = array_merge($where , GetListWhere($where));
$configArr = wmsql::GetAll($where);
?>
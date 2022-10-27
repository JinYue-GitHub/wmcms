<?php
/**
* 内容标签列表
*
* @version        $Id: system.api.ttsvoicet.list.php 2022年04月21日 16:11  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$typeId = Request('type_id');
$voicetMod = NewModel('system.apittsvoicet');
//判断分类
if( $typeId != '' )
{
	$where['where']['voicet_api_id'] = $typeId;
}
//获取列表条件
if( $orderField == '' )
{
	$where['order'] = 'voicet_order asc';
}
//数据条数
$total = $voicetMod->GetCount($where);
//当前页的数据
$list = $voicetMod->GetAll(GetListWhere($where));

//接口数据
$apiMod = NewModel('system.api');
$typeData = $apiMod->GetByType(11);
?>
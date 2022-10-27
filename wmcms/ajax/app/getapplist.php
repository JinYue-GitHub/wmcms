<?php
/**
* 获得应用列表
*
* @version        $Id: gettype.php 2019年05月19日 13:55  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

//tid和分页基本条件
$tid = str::Int(Request('tid'));
$limit = str::Int(Request('limit'));
$page = GetPage()==1?2:GetPage();
$tidWhere = 'type_pid=[and-or->rin:'.$tid.'||t.type_id:'.$tid.'];page='.$page.';limit='.$limit.';';
//筛选条件
$reMod = NewModel('system.retrieval');
$reWhere = $reMod->GetWhere('app',null);
if ( $reWhere == '' || !str::in_string('排序=',$reWhere) )
{
	$reWhere .= '排序=app_addtime desc;';
}
//解析条件为数组
$where = tpl::GetWhere($tidWhere.$reWhere);
$appMod = NewModel('app.app');
$data = $appMod->GetAll($where);

if( $data )
{
	foreach($data as $k=>$v)
	{
		$data[$k]['app_tags'] = explode(',',$v['app_tags']);
		$data[$k]['app_imgs'] = common::GetData('@upload','upload_module=app;数量=3;upload_cid='.$v['app_id']);
	}
}
ReturnData('' ,true,200, $data);
?>
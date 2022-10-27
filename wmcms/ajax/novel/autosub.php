<?php
/**
* 自动订阅操作
*
* @version        $Id: autosub.php 2021年08月15日 15:06  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

//判断用户是否登录了
$uid = user::GetUid();
str::EQ( $uid , 0 , $lang['system']['user']['no_login'], $ajax );
//接受参数
$module = Request('module');
$nid = str::Int( Request('nid') , $lang['system']['par']['err']);

//查询内容是否存在
$novelData = $tableSer->GetData('novel',$nid);
if( !$novelData )
{
	ReturnData( $lang['system']['content']['no'] , $ajax );
}
//小说并且是上架的
else if($module=='novel' && $novelData['novel_sell'] == 1)
{
	//查询是否存在
	$collData['coll_module'] = 'novel';
	$collData['coll_type'] = 'sub';
	$collData['user_id'] = $uid;
	$collData['coll_cid'] = $nid;
	$collMod = NewModel('user.coll');
	$data = $collMod->GetOne($collData);
	//存在订阅就删除
	if( $data )
	{
		$collMod->collId = $data['coll_id'];
		$collMod->data = $data;
		$collMod->DelOne();
		//返回信息
		$result = array('autosub'=>0);
		$msg = $lang['novel']['operate']['unautosub']['success'];
		ReturnData( $lang['novel']['operate']['unautosub']['success'] , $ajax , 200,array('autosub'=>0));
	}
	//不存在订阅就新增
	else
	{
		$collMod->Insert($collData);
		//返回信息
		$result = array('autosub'=>1);
		$msg = $lang['novel']['operate']['autosub']['success'];
	}
	ReturnData( $msg , $ajax , 200, $result);
}
?>
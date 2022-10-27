<?php
/**
* 全系统收藏、书架、推荐等互动操作
*
* @version        $Id: coll.php 2015年8月15日 10:31  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime 		  2016年5月25日 20:54  weimeng
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

//引入配置并且检测
$config = GetModuleConfig($module);
if( GetKey($config,$type.'_open') == '' )
{
	$config[$type.'_open'] = 0;
}
$uid = user::GetUid();
//判断用户是否登录了
str::EQ( $uid, 0 , $lang['coll'][$type.'_login'] , $ajax );
//操作的系统是否关闭了
str::EQ( $config[$type.'_open'], 0 , $lang['coll'][$type.'_open'] , $ajax );


//new一个模型类
$collMod = NewModel('user.coll');
//查询内容是否存在
$data = $collMod->GetContent($module , $type ,$cid);
//如果操作的内容不存在
if ( !$data )
{
	ReturnData( $lang['coll']['content_no'] , $ajax );
}
//内容存在并且收藏的信息存在
else if ( str::Number($data['coll_id']) )
{
	//如果是收藏和和书架就直接删除
	if( in_array($type, array('coll','shelf')) )
	{
		$collMod->collId = $data['coll_id'];
		//如果收藏等存在
		if ( $collMod->DelOne() )
		{
			ReturnData( $lang['operate']['del']['success'] , $ajax);
		}
		else
		{
			ReturnData( $lang['operate']['del']['fail'] , $ajax);
		}
	}
	else
	{
		ReturnData( $lang['coll'][$type.'_exists'] , $ajax );
	}
}
//数据正常
else
{
	$count = $collMod->GetCount($module , $type ,$cid , $uid);
	
	//查询等级可以获得操作数量
	$lv = user::GetLV();
	//如果已经收藏过的数量大于等级的设置量
	str::RTEQ( $count, $lv['level_'.$type] , $lang['coll'][$type.'_count'] , $ajax );

	
	//增加收藏等记录
	$collData['coll_module'] = $module;
	$collData['coll_type'] = $type;
	$collData['user_id'] = $uid;
	$collData['coll_cid'] = $cid;
	$result = $collMod->Insert($collData);
	//更新内容表字段信息
	$collMod->UpdateContent($module , $type , $cid);

	
	//返回结果
	if ( $result )
	{
		ReturnData( $lang['operate'][$type]['success'] , $ajax , 200 );
	}
	//写入失败
	else
	{
		ReturnData( $lang['operate'][$type]['fail'] , $ajax );
	}
	
}
?>
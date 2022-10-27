<?php
/**
* 删除收藏等操作处理
*
* @version        $Id: delcoll.php 2016年5月28日 21:37  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

//是否登录了
str::EQ( user::GetUid() , 0 , $lang['user']['no_login'] );
$cid = str::Int( Request('cid') , $lang['user']['cid_err'] );

//查询
$collMod = NewModel('user.coll');
$collMod->collId = $cid;
$data = $collMod->GetOne();

//如果收藏等存在
if ( $data )
{
 	$result = $collMod->DelOne();
 	
	if( $result )
	{
		$info = GetInfo($lang['user']['operate']['delcoll'] , 'user_coll' , array('page'=>1,'module'=>$data['coll_module'],'type'=>$data['coll_type']));
		ReturnData( $info , $ajax , 200);
	}
	else
	{
		ReturnData( $lang['user']['operate']['delcoll']['fail'] , $ajax);
	}
}
//没有数据
else
{
	ReturnData( $lang['user']['coll_no'] , $ajax);
}
?>
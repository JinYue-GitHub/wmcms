<?php
/**
* 全系统顶功能请求处理
*
* @version        $Id: dingcai.php 2015年5月23日 19:31  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime         2015年5月24日 22:35  weimeng
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

//new一个模型类
$data['module'] = $module;
$data['type'] = $type;
$data['operate'] = array('or','ding,cai');
$data['cid'] = $cid;
$operateMod = NewModel('operate.operate' , $data);
$config = GetModuleConfig($module);

//顶踩的系统是否关闭了
str::EQ( $config['dingcai_open'], 0 , $lang['dingcai']['dingcai_close'] , $ajax );
//顶踩是否需要登录
if( isset($config['dingcai_login']) && $config['dingcai_login'] > 0 )
{
	str::EQ( user::GetUid() , 0 , $lang['dingcai']['dingcai_login'] , $ajax );
}


//获取模块配置
$config = GetModuleConfig($module);
if( GetKey($config,'dingcai_count') == '' )
{
	$config['dingcai_count'] = 0;
}

//检查内容的是否存在
if ( $operateMod->GetContentCount($module , $cid) == 0 )
{
	ReturnData( $lang['dingcai']['dingcai_no'] , $ajax );
}

//今日顶踩的条数大于限定的每日顶踩条数
if ( $operateMod->CheckOperateCount() >= $config['dingcai_count'] && $config['dingcai_count'] > 0 )
{
	ReturnData( str_replace('{次数}', $config['dingcai_count'], $lang['dingcai']['dingcai_count']) , $ajax );
}

//插入操作记录
$result = $operateMod->Insert();
//修改操作记录
$operateMod->ContentInc();

//返回结果
if ( $result )
{
	ReturnData( $lang['operate']['dingcai']['success'] , $ajax , 200 );
}
//写入失败
else
{
	ReturnData( $lang['operate']['dingcai']['fail'] , $ajax );
}
?>
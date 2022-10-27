<?php
/**
* 编辑请求处理器
*
* @version        $Id: index.php 2016年12月19日 19:31  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

$uid = user::GetUid();
//没有登录
str::EQ( $uid , 0 , $lang['user']['no_login'] );

//是否是编辑
$editorMod = NewModel('editor.editor');
$editor = $editorMod->GetByUid($uid);
//不是编辑
if( !$editor )
{
    tpl::ErrInfo($lang['editor']['editor_no']);
}
//状态不对
else if( $editor['editor_status'] == '0' )
{
    tpl::ErrInfo($lang['editor']['editor_status_0']);
}
//正确
else
{
    if( file_exists('editor/'.$type.'.php') )
    {
    	require_once $type.'.php';
    }
    else
    {
    	tpl::ErrInfo($lang['system']['action']['no_file']);
    }
}
?>
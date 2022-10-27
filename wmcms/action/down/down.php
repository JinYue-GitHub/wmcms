<?php
/**
* 全系统下载功能请求处理
*
* @version        $Id: down.php 2017年4月28日 11:31  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

$did = str::IsEmpty( Get('did') , $lang['down']['did_err']);
$parArr = $downMod->D($did);
//下载参数错误
if( !$parArr || count($parArr) != 4 || $parArr['module']=='' || time()-$parArr['time']>300)
{
	ReturnData( $lang['down']['time_err'] , $ajax );
}

$config = GetModuleConfig($parArr['module']);
//检查下载是否关闭
if ( GetKey($config,'down_open') == '0' )
{
	tpl::ErrInfo( $lang['down']['close'] );
}
//是否需要登录
else if ( GetKey($config,'down_login') == '1' && $uid == 0 )
{
	tpl::ErrInfo( $lang['down']['login'] );
}

//下载文件的信息
$downInfo = $downMod->GetDownInfo( $parArr['module'] , $parArr['cid'] , $parArr['fid'] , '1');
//文件名字
$name = str::EnCoding($downInfo['name']);
//文件的地址
$file = $downInfo['file'];
//是否是本地文件
$isLocal = $downInfo['is_local'];

//本地文件
if( $isLocal == true )
{
	if ( !file_exists($file) )
	{
		tpl::ErrInfo( $lang['down']['file_no'] );
	}
	else
	{
		//临时设置php内存大小
		@ini_set("memory_limit","128M");
		ob_start();
		//开始下载文件
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream;text/plain;charset=utf-8');
		header('Content-Disposition: attachment; filename='.$name.'.'.$downInfo['ext']);
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Content-Length: ' . filesize($file));
		ob_clean();
		flush();
		readfile($file);
		Session('down_time','delete');
	}
}
//远程文件
else
{
	Session('down_time','delete');
	header("Location: ".$file);
}
?>
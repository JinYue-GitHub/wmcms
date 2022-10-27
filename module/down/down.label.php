<?php
/**
* 下载标签处理类
*
* @version        $Id: down.label.php 2017年4月28日 10:00  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
class downlabel extends down
{
	static public $lcode;
	static public $data;
	static public $CF = array('down'=>'GetData');

	function __construct()
	{
		tpl::labelBefore();
	}
	
	/**
	* 下载页标签替换
	**/
	static function DownLabel()
	{
		$module = C('page.module');
		$cid = C('page.cid');
		$fid = C('page.fid');
		
		$downMod = NewModel('down.down');
		$downInfo = $downMod->GetDownInfo( $module , $cid , $fid );
		if( !$downInfo || $downInfo['name'] == '' || $downInfo['file'] == ''  )
		{
			ReturnData(C('down.par.err',null,'lang') , C('page.ajax') );
		}
		
		//加密下载参数
		$did = $downMod->E($module, $cid, $fid);
		
		//设置自定义中文标签
		tpl::rep(array(
			'下载内容'=>$downInfo['name'],
			'下载链接'=>common::GetUrl( 'down.down', array('did'=>$did) ),
		));
	}
}
?>
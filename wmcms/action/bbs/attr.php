<?php
/**
* 主题属性请求处理
*
* @version        $Id: attr.php 2016年5月29日 17:55  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime 		  2016年2月22日 13:29  weimeng
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

//提取参数
$bid = str::Int( Request('bid') , $lang['bbs']['bid_err'] );
$key = str::IsEmpty( Request('key') , $lang['bbs']['key_no']  );
$val = str::Int( Request('val') , $lang['bbs']['val_err']  );


//检查主题是否存在
$bbsData = $bbsMod->GetOne( $bid );

if( !$bbsData )
{
	ReturnData( $lang['system']['content']['no'] , $ajax );
}
else
{
	//检查是否是版主
	$isModer = $bbsMod->CheckModerator($bbsData['type_id']);

	//版主修改
	if( $isModer == true)
	{
		switch ($key)
		{
			case 'status':
				$data['bbs_status'] = $val;
				break;
			case 'rec':
				$data['bbs_rec'] = $val;
				break;
			case 'es':
				$data['bbs_es'] = $val;
				break;
			case 'top':
				$data['bbs_top'] = $val;
				break;
		}
		//修改主题信息
		$result = $bbsMod->Save($data , array('bbs_id'=>$bid));
		if( $result )
		{
			ReturnData( $lang['bbs']['operate']['attr']['success'] , $ajax , 200 );
		}
		else
		{
			ReturnData( $lang['bbs']['operate']['attr']['fail'] , $ajax );
		}
		
	}
	//不是版主就是提示错误
	else
	{
		ReturnData( $lang['bbs']['comp_err'] , $ajax );
	}
}
?>
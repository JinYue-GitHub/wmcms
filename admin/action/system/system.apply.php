<?php
/**
* 申请记录处理器
*
* @version        $Id: system.apply.php 2017年1月14日 20:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$module = Request('module');
$mt = Request('type');
$result['info'] = $result['table'] = $result['where'] = $result['data'] = '';

//拒绝操作
if ( $type == 'refuse_'.$module.'_'.$mt )
{
	$remark = Post('remark',null,false);
	$uid = Request('uid/i');
	$cid = Request('cid/i');
	if( $remark == '' )
	{
		Ajax('对不起，拒绝原因不能为空！',300);
	}
	else if($module == '' || $mt == '' || !str::Number($uid) || !str::Number($cid) || !str::Number($uid) )
	{
		Ajax('对不起，参数错误！',300);
	}
	else
	{
		//审核类文件
		$applySer = AdminNewClass('system.apply');
		$applyMod = NewModel('system.apply' , $module);
		$applyId = $applyMod->HandleApply($mt , $uid , $cid, 2 ,$remark);

		//如果操作回调方法存在就调用
		$callBack = $mt.'RefuseCallBack';
		if( method_exists($applySer,$callBack) )
		{
			$result = $applySer->$callBack($uid , $cid);
		}

		//修改编辑器上传的内容id
		$uploadMod = NewModel('upload.upload');
		$uploadMod->UpdateCid( 'editor',$module.'_'.$mt.'_refuse', $cid);
		
		//写入操作记录
		SetOpLog( $result['info'] , $module , $type , $result['table'] , $result['where'] , $result['data'] );
		Ajax('拒绝申请成功！' , 200);
	}
}
?>
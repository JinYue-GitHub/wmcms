<?php
/**
* 小说签约处理器
*
* @version        $Id: novel.sign.php 2017年3月12日 15:31  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$conSer = AdminNewClass('system.config');
$novelMod = NewModel('novel.novel');
$signMod = NewModel('novel.sign');

$curModule = 'novel';
$table = '@novel_sign';
$id = Request('nid');
$novelData = $novelMod->GetOne($id);
if( !$novelData )
{
	Ajax('对不起，小说不存在！',300);
}
//签约操作
if ( $type == "add"  )
{
	$nid = Request('nid');
	$copy = Request('copy');
	$sid = Request('sid');
	$signData = $signMod->GetLastOne($nid);

	if((str::Int($nid) == 0 || str::Int($copy) == 0) && GetKey($signData,'sign_status') != 1)
	{
		Ajax('对不起，所有项必须选择！',300);
	}
	else if($copy == 1 && $sid == 0  && GetKey($signData,'sign_status') != 1)
	{
		Ajax('对不起，请选择签约等级！',300);
	}
	else
	{
		if( GetKey($signData,'sign_status') == 1)
		{
			//插入签约数据
			$data['sign_status'] = 0;
			$copy = $sid = 0;
			//修改小说签约数据
			$signMod->SetNovelSign($nid,$copy,$sid);
			
			$opTxt = '小说《'.$novelData['novel_name'].'》取消签约成功！';
			$setTxt = '恭喜您，小说取消签约成功！';
		}
		else
		{
			$opTxt = '小说《'.$novelData['novel_name'].'》签约成功！';
			$setTxt = '恭喜您，小说签约成功！';
		}

		//插入签约数据
		$data['sign_novel_id'] = $nid;
		$data['sign_manager_id'] = Session('admin_id');
		$data['sign_type'] = $copy;
		$data['sign_sign_id'] = $sid;
		$where['sign_id'] = $signMod->Insert($data);
		//修改小说签约数据
		$signMod->SetNovelSign($nid,$copy,$sid);
			
		//写入操作记录
		SetOpLog( $opTxt , $curModule , 'insert' , $table , $where , $data );
		Ajax($setTxt);
	}
}
?>
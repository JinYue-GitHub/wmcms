<?php
/**
* 章节编辑审核处理器
*
* @version        $Id: author.chpater.apply.php 2017年1月26日 10:56  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$table = '@system_apply';
$applyMod = NewModel('system.apply' ,'author');
$chapterMod = NewModel('novel.chapter');
$novelMod = NewModel('novel.novel');


//删除数据
if ( $type == 'del' )
{
	//配置文件，删除数据方式是否是直接删除
	$where['apply_id'] = GetDelId();
	$where['apply_module'] = 'author';
	$where['apply_type'] = 'novel_editchapter';
	//获得数据
	$data = $applyMod->GetAll($where);
	if( $data )
	{
		//写入操作记录
		SetOpLog( '删除了章节修改申请' , 'system' , 'delete' , $table , $where);
		$applyMod->Delete($where);
	}
	
	Ajax('章节修改申请删除成功!');
}
//清空记录
else if ( $type == 'clear' )
{
	$where['apply_module'] = 'author';
	$where['apply_type'] = 'novel_editchapter';
	$applyMod->Delete($where);
	//写入操作记录
	SetOpLog( '清空了章节申请记录' , 'system' , 'delete');
	Ajax('所有章节申请记录成功清空！');
}
//审核数据
else if ( $type == 'status' )
{
	$status = Request('status/i');
	if( $status == 0)
	{
		Ajax('对不起，不能变更为未审核状态！');
	}
	else if( $status == 1)
	{
        $result = $applyMod->ApplyHandle(GetDelId(),1);
		//写入操作记录
		$msg = '取消审核';
		if( Request('status') == '1')
		{
			$msg = '审核通过';
		}
		SetOpLog( $msg.'了小说修改申请' , 'system' , 'update' , $table , array('apply_id'=>GetDelId()));
		Ajax('小说修改申请'.$msg.'成功!');
	}
}
?>
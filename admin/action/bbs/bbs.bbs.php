<?php
/**
* 主题处理器
*
* @version        $Id: bbs.bbs.php 2016年5月15日 9:53  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$table = '@bbs_bbs';

//删除数据
if ( $type == 'del' )
{
	$replayMod = NewModel('replay.replay',array('module'=>'bbs'));
	$uploadMod = NewModel('upload.upload');
	
	$where['table'] = $table;
	$where['where']['bbs_id'] = GetDelId();
	$data = wmsql::GetAll($where);
	if( $data )
	{
		$uploadSer = AdminNewClass('upload');
		
		foreach ($data as $k=>$v)
		{
			//删除相关的评论
			$replayMod->cid = $v['bbs_id'];
			$replayMod->Del();
			//解绑主题附件
			$uploadMod->module = 'bbs';
			$uploadMod->cid = $v['bbs_id'];
			$uploadMod->mid = $v['bbs_id'];
			$uploadMod->UnFileBind();
			//删除主题记录
			$wheresql['bbs_id'] = $v['bbs_id'];
			wmsql::Delete($table , $wheresql);
			
			$uploadSer->DelUpload('bbs',$v['bbs_id']);
		}
	}

	SetOpLog( '删除了主题' , 'bbs' , 'delete' , $table , $where['where']);
	Ajax('主题删除成功!');
}
//审核数据
else if ( $type == 'status' )
{
	$data['bbs_status'] = Request('status');
	$where['bbs_id'] = GetDelId();

	if( Request('status') == '1')
	{
		$msg = '审核通过';
	}
	else
	{
		$msg = '取消审核';
	}
	//写入操作记录
	SetOpLog( $msg.'了主题' , 'bbs' , 'update' , $table , $where);
	
	wmsql::Update($table, $data, $where);
	Ajax('主题'.$msg.'成功!');
}
//移动数据
else if ( $type == 'move' )
{
	$data['type_id'] = Request('tid');
	$where['bbs_id'] = GetDelId();

	//写入操作记录
	SetOpLog( '移动了主题' , 'bbs' , 'update' , $table , $where);
	
	wmsql::Update($table, $data, $where);
	Ajax('主题移动成功!');
}
//属性操作
else if ( $type == 'attr' )
{
	$data['bbs_'.$post['attr']] = $post['val'];
	$where['bbs_id'] = GetDelId();
	
	switch($post['attr'])
	{
		case "rec":
			$msg = "推荐";
			break;
			
		case "es":
			$msg = "精华";
			break;
			
		case "top":
			$msg = "置顶";
			break;
	}
	if( $post['val'] == '1' )
	{
		$msg .= '设为';
	}
	else
	{
		$msg .= '取消';
	}

	//写入操作记录
	SetOpLog( $msg.'了主题' , 'bbs' , 'update' , $table , $where);

	wmsql::Update($table, $data, $where);
	Ajax($msg.'了主题!');
}
?>
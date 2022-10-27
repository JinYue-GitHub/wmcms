<?php
/**
* 评论处理器
*
* @version        $Id: operate.replay.php 2016年5月6日 15:54  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$table = '@replay_replay';


//审核数据
if ( $type == 'status' )
{
	$data['replay_status'] = Request('status/i');
	$where['replay_id'] = GetDelId();

	if( Request('status') == '1')
	{
		$msg = '审核通过';
	}
	else
	{
		$msg = '取消审核';
	}
	//写入操作记录
	SetOpLog( $msg.'了评论' , 'replay' , 'update' , $table , $where);
	
	wmsql::Update($table, $data, $where);
	Ajax('评论'.$msg.'成功!');
}
//删除请求记录
else if ( $type == 'del' )
{
	$where['table'] = $table;
	$where['where']['replay_id'] = GetDelId();
	$data = wmsql::GetAll($where);
	if( $data )
	{
		$uploadSer = AdminNewClass('upload');
		foreach ($data as $k=>$v)
		{
			//删除回帖记录
			$wheresql['replay_id'] = $v['replay_id'];
			wmsql::Delete($table , $wheresql);
			//删除可能有的附件
			$uploadSer->DelUpload('bbs',$v['replay_id']);
		}
	}
	
	SetOpLog( '删除了评论记录' , 'replay' , 'delete' , $table , $where);
	Ajax('评论删除成功!');
}
//清空请求记录
else if ( $type == 'clear' )
{
	if( Request('module') != '' )
	{
		$where['replay_module'] = Request('module');
	}
	else
	{
		$where['replay_module'] = array('<>' , 'bbs' );
	}

	wmsql::Delete($table , $where);
	
	//写入操作记录
	SetOpLog( '清空了评论记录' , 'replay' , 'delete');
	Ajax('所有评论成功清空！');
}
?>
<?php
/**
* 小说限时免费处理器
*
* @version        $Id: novel.timelimit.php 2018年8月27日 21:36  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$limitMod = NewModel('novel.timelimit');
$table = '@novel_timelimit';

if( $type == 'edit' || $type == "add" )
{
	$data = str::Escape( $post['timelimit'], 'e' );
	
	//默认id条件
	$where['timelimit_id'] = $post['timelimit_id'];
	//默认数据时间转换
	$data['timelimit_starttime'] = strtotime(GetKey($data,'timelimit_starttime'));
	$data['timelimit_endtime'] = strtotime(GetKey($data,'timelimit_endtime'));
	
	//检查参数
	if( !str::Number($data['timelimit_order']) )
	{
		Ajax('对不起，小说排序只能为数字！',300);
	}
	else if( !str::Number($data['timelimit_nid']) )
	{
		Ajax('对不起，小说id不能为空！',300);
	}
	else if( $data['timelimit_starttime']>$data['timelimit_endtime'] )
	{
		Ajax('对不起，开始时间不能大于结束时间！',300);
	}
	
	//添加限时免费
	if( $type == 'add' )
	{
		$limitData = $limitMod->GetByNid($data['timelimit_nid']);
		if ( $limitData )
		{
			Ajax('对不起，该小说的已经设置了限时免费！',300);
		}
		else
		{
			$where['timelimit_id'] = $limitMod->Insert($data);
			$info = '恭喜您，小说限时免费添加成功！';
			//写入操作记录
			SetOpLog( '添加了限时免费', $curModule , 'insert' , $table , $where , $data );
		}
	}
	else
	{
		wmsql::Update($table, $data, $where);
		$info = '恭喜您，小说限时免费修改成功！';
		//写入操作记录
		SetOpLog( '修改了限时免费', $curModule , 'update' , $table , $where , $data );
	}
	
	Ajax($info);
}
//删除推荐
else if ( $type == 'del' )
{
	$where['timelimit_id'] = GetDelId();
	//写入操作记录
	SetOpLog( '删除了小说限时免费' , 'novel' , 'delete' , $table , $where);
	//删除分类
	wmsql::Delete($table, $where);
	
	Ajax('小说限时免费删除成功!');
}
?>
<?php
/**
* 小说粉丝处理器
*
* @version        $Id: novel.fans.php 2017年3月29日 22:04  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$rewardTable = '@user_finance_log';
$ticketTable = '@user_ticket_log';
$subTable = '@novel_sublog';
$levelTable = '@fans_module_level';

//删除打赏记录
if ( $type == 'delreward'  )
{
	$where['log_id'] = GetDelId();
	$where['log_status'] = '2';
	$where['log_module'] = 'novel';
	$where['log_type'] = 'reward_consume';
	//写入操作记录
	SetOpLog( '删除了打赏记录！' , 'novel' , 'delete' , $rewardTable , $where);
	wmsql::Delete($rewardTable , $where);
	
	Ajax('打赏记录批量删除成功!');
}
//清空数据打赏记录
else if ( $type == 'clearreward')
{
	//写入操作记录
	$where['log_status'] = '2';
	$where['log_module'] = 'novel';
	$where['log_type'] = 'reward_consume';
	SetOpLog( '清空了所有打赏记录！' , 'novel' , 'delete' , $rewardTable , $where);
	wmsql::Delete($rewardTable , $where);
	Ajax('打赏记录全部清空成功！');
}

//删除推荐记录
else if ( $type == 'delticket'  )
{
	$where['log_id'] = GetDelId();
	$where['log_status'] = '2';
	$where['log_module'] = 'novel';
	//写入操作记录
	SetOpLog( '删除了推荐记录！' , 'novel' , 'delete' , $ticketTable , $where);
	wmsql::Delete($ticketTable , $where);
	
	Ajax('推荐记录批量删除成功!');
}
//清空数据推荐记录
else if ( $type == 'clearticket')
{
	//写入操作记录
	$where['log_status'] = '2';
	$where['log_module'] = 'novel';
	SetOpLog( '清空了所有打赏记录！' , 'novel' , 'delete' , $ticketTable , $where);
	wmsql::Delete($ticketTable , $where);
	Ajax('打赏记录全部清空成功！');
}

//删除订阅记录
else if ( $type == 'delsub'  )
{
	$where['log_id'] = GetDelId();
	//写入操作记录
	SetOpLog( '删除了订阅记录！' , 'novel' , 'delete' , $subTable , $where);
	wmsql::Delete($subTable , $where);
	
	Ajax('订阅记录批量删除成功!');
}
//清空数据推荐记录
else if ( $type == 'clearsub')
{
	//写入操作记录
	SetOpLog( '清空了所有订阅记录！' , 'novel' , 'delete' , $subTable);
	wmsql::Delete($subTable);
	Ajax('订阅记录全部清空成功！');
}

//修改粉丝等级信息
else if ( $type == "leveladd" || $type == "leveledit" )
{
	//新增数据
	if( $type == 'leveladd' )
	{
		$data = str::Escape( GetKey($post,'level'), 'e' );
		if( !$data )
		{
			Ajax('对不起，请添加数据后再点击保存!',300);
		}
		foreach ($data as $k=>$v)
		{
			if( $v['level_name'] != '' )
			{
				$where['level_id'] = wmsql::Insert($levelTable, $v);
				//写入操作记录
				SetOpLog( '新增了粉丝等级' , 'novel' , 'insert' , $levelTable , $where , $v );
			}
		}
		$info = '恭喜您，用户等级添加成功！';
	}
	else if( $type == 'leveledit' )
	{
		if( $post['level'] )
		{
			foreach ($post['level'] as $k=>$v)
			{
				wmsql::Update($levelTable, $v['data'], $v['id']);
				//写入操作记录
				SetOpLog( '修改了粉丝等级' , 'novel' , 'update' , $levelTable , $v['id'] , $v['data'] );
			}
		}
		$info = '恭喜您，用户等级修改成功！';
	}

	Ajax($info);
}
//删除数据和永久删除数据
else if ( $type == 'leveldel')
{
	$where['level_id'] = GetDelId();
	wmsql::Delete($levelTable , $where);
	
	//写入操作记录
	SetOpLog( '删除了小说粉丝等级' , 'novel' , 'delete' , $levelTable , $where);
	
	Ajax('小说粉丝等级删除成功!');
}
?>
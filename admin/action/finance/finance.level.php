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
$levelTable = '@finance_level';

//修改充值等级信息
if ( $type == "leveladd" || $type == "leveledit" )
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
			if( $v['level_money'] != '' )
			{
				$where['level_id'] = wmsql::Insert($levelTable, $v);
				//写入操作记录
				SetOpLog( '新增了充值等级' , 'finance' , 'insert' , $levelTable , $where , $v );
			}
		}
		$info = '恭喜您，充值等级添加成功！';
	}
	else if( $type == 'leveledit' )
	{
		if( $post['level'] )
		{
			foreach ($post['level'] as $k=>$v)
			{
				wmsql::Update($levelTable, $v['data'], $v['id']);
				//写入操作记录
				SetOpLog( '修改了充值等级' , 'finance' , 'update' , $levelTable , $v['id'] , $v['data'] );
			}
		}
		$info = '恭喜您，充值等级修改成功！';
	}

	Ajax($info);
}
//删除数据和永久删除数据
else if ( $type == 'leveldel')
{
	$where['level_id'] = GetDelId();
	wmsql::Delete($levelTable , $where);
	
	//写入操作记录
	SetOpLog( '删除了充值等级' , 'finance' , 'delete' , $levelTable , $where);
	
	Ajax('充值等级删除成功!');
}
?>
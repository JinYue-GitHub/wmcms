<?php
/**
* 广告处理器
*
* @version        $Id: operate.ad.php 2016年5月8日 10:14  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$adSer = AdminNewClass('operate.ad');

$table = '@ad_ad';

//修改分类信息
if ( $type == 'edit' || $type == "add"  )
{
	
	$data = str::Escape( $post['ad'], 'e' );
	$where = $post['id'];
	
	if ( $data['ad_name'] == '')
	{
		Ajax('对不起，广告标题必须填写！',300);
	}
	$data['ad_start_time'] = strtotime($data['ad_start_time']);
	$data['ad_end_time'] = strtotime($data['ad_end_time']);
	
	//新增数据
	if( $type == 'add' )
	{
		$data['ad_time'] = time();
		$info = '恭喜您，广告添加成功！';
		$where['ad_id'] = wmsql::Insert($table, $data);
		
		//写入操作记录
		SetOpLog( '新增了广告'.$data['ad_name'] , 'system' , 'insert' , $table , $where , $data );
	}
	//修改分类
	else
	{
		$info = '恭喜您，广告修改成功！';
		wmsql::Update($table, $data, $where);
		
		//写入操作记录
		SetOpLog( '修改了广告'.$data['ad_name'] , 'system' , 'update' , $table , $where , $data );
	}
	
	//创建广告js文件
	$data['ad_id'] = $where['ad_id'];
	$adSer->CreateAdFile( $data );
	
	Ajax($info);
}
//删除数据
else if ( $type == 'del' )
{
	$where['ad_id'] = GetDelId();
	//写入操作记录
	SetOpLog( '删除了广告' , 'system' , 'delete' , $table , $where);
	wmsql::Delete($table , $where);

	//删除广告文件
	$adSer->DelAdFile(GetDelId());
	
	Ajax('广告删除成功!');
}
//清空请求记录
else if ( $type == 'clear' )
{
	wmsql::Delete($table);
	//删除广告文件夹
	$adSer->DelAdDir();
	
	//写入操作记录
	SetOpLog( '清空了广告' , 'system' , 'delete' , $table);
	Ajax('所有广告成功清空！');
}
//审核数据
else if ( $type == 'status' )
{
	$data['ad_status'] = Request('status');
	$where['ad_id'] = GetDelId();

	if( Request('status') == '1')
	{
		$msg = '通过审核';
	}
	else
	{
		$msg = '取消审核';
	}
	//写入操作记录
	SetOpLog( $msg.'了广告' , 'system' , 'update' , $table , $where);
	
	wmsql::Update($table, $data, $where);
	Ajax('广告'.$msg.'成功!');
}
?>
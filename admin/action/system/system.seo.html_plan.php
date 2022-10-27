<?php
/**
* 静态计划处理器
*
* @version        $Id: system.seo.html.php 2019年02月27日 14:42  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$planMod = NewModel('system.plan');
//添加计划
if ( $type == 'add' )
{
	$data = $post['data'];
	
	if( $data['plan_name'] == '' || $data['plan_url'] == '' || $data['plan_path'] == '' )
	{
		Ajax('对不起，字段填写不完整!',300);
	}
	else if( !str::CheckUrl($data['plan_url']) )
	{
		Ajax('url格式不正确，请填写完整的url，如：http://www.weimengcms.com!',300);
	}
	//保存文件格式检测
	else if( count(explode('.', $data['plan_path'])) < 2 )
	{
		Ajax('对不起，保存格式错误，必须写入文件后缀。',300);
	}
	//保存路径重复检测
	else if( $planMod->GetOne(array('plan_path'=>$data['plan_path'])) )
	{
		Ajax('对不起，该保存路径已经存在！',300);
	}
	//计划名字重复检测
	else if( $planMod->GetOne(array('plan_name'=>$data['plan_name'])) )
	{
		Ajax('对不起，该静态计划名字已经存在！',300);
	}
	else
	{
		$planMod->Insert($data);
		//写入操作记录
		SetOpLog( '添加了静态计划'.$data['plan_name'] , 'system' , 'insert');
		Ajax('静态计划添加成功！');
	}
}
//删除计划
else if ( $type == 'del' )
{
	$planMod->Delete(GetDelId());
	//写入操作记录
	SetOpLog( '删除了静态计划' , 'system' , 'delete');
	Ajax('静态计划删除成功！');
}
//运行静态计划
else if( $type == 'run' )
{
	$id = Request('id');
	$data = $planMod->GetById($id);
	if( !$data )
	{
		Ajax('对不起，该计划不存在！',300);
	}
	else
	{
		$httpSer = NewClass('http');
		//保存文件
		$html = $httpSer->GetUrl($data['plan_url'],json_decode($data['plan_data'], true));
		file::CreateFile(WMROOT.$data['plan_path'], $html , 1);
		//修改最后运行时间
		$planMod->UpLastTime($id);
		
		//写入操作记录
		SetOpLog( '运行了静态计划'.$data['plan_path'] , 'system' , 'insert');
		Ajax('成功运行静态，文件保存到'.$data['plan_path'].'');
	}
}
?>
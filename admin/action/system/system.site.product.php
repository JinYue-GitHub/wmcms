<?php
/**
* 站群配置处理器
*
* @version        $Id: system.site.product.php 2017年6月11日 15:00  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$table = '@site_product';
$siteMod = NewModel('system.site');

//修改、测试配置信息
if ( $type == 'edit' || $type == "add" )
{
	$data = str::Escape($post['data'] , 'e');
	$where['product_id'] = Request('product_id');
	foreach ($data as $k=>$v)
	{
		if( $v == '' )
		{
			Ajax('对不起，所有项都不能为空！' , 300);
		}
	}
	
	//小说名字检查
	$wheresql['product_id'] = array('<>',$where['product_id']);
	$wheresql['product_domain'] = $data['product_domain'];
	if( $siteMod->ProGetOne($wheresql) )
	{
		Ajax('对不起，该域名站点已经存在！' , 300);
	}
	//如果是新增
	else if ( $type == 'add' )
	{
		//插入记录
		$where['product_id'] = $siteMod->ProInsert($data);
		//写入操作记录
		SetOpLog( '新增了站外站点'.$data['product_title'] , 'system' , 'insert' , $table , $where , $data );
		Ajax('站外站点新增成功!');
	}
	//如果是增加页面
	else
	{
		//写入操作记录
		SetOpLog( '修改站外站点' , 'system' , 'update' , $table  , $where , $data );
		//修改数据
		$siteMod->ProUpdate($data,$where);
		Ajax('站外站点修改成功！');
	}
}
//测试站外站点通讯
else if ( $type == 'test' )
{
	$data = str::Escape($post['data'] , 'e');
	foreach ($data as $k=>$v)
	{
		if( $v == '' )
		{
			Ajax('对不起，所有项都不能为空！' , 300);
		}
	}
	$siteSer = AdminNewClass('system.site');
	$rs = $siteSer->GetJson($data);
	if( GetKey($rs,'statusCode') == '200' )
	{
		Ajax('恭喜您，后台通讯成功！');
	}
	else
	{
		if(!is_array($rs) )
		{
			Ajax('通讯测试失败，错误原因：后台地址错误或者程序不是wmcms！' , 300);
		}
		else
		{
			Ajax('通讯测试失败，错误原因：'.$rs['message'] , 300);
		}
	}
}
//删除站外站点
else if ( $type == 'del' )
{
	$where['product_id'] = GetDelId();
	$siteMod->ProDel($where);
	//写入操作记录
	SetOpLog( '删除了站外站点' , 'system' , 'delete' , $table , $where);
	Ajax('站外站点删除成功!');
}
//清空站外站点
else if ( $type == 'clear' )
{
	$siteMod->ProDel();
	//写入操作记录
	SetOpLog( '清空了站外站点' , 'system' , 'delete' , $table , $where);
	Ajax('站外站点清空成功!');
}
//使用禁用站点
else if ( $type == 'status' )
{
	$data['product_status'] = Request('status');
	$where['product_id'] = GetDelId();

	if( Request('status') == '1')
	{
		$msg = '使用成功';
	}
	else
	{
		$msg = '禁用成功';
	}
	$siteMod->ProUpdate($data,$where);
	
	//写入操作记录
	SetOpLog( '站点'.$msg , 'system' , 'update' , $table , $where);
	Ajax('站点'.$msg);
}
?>
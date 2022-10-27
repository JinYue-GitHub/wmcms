<?php
/**
* 后台新增预设模版处理器
*
* @version        $Id: system.templates.templates.php 2016年4月8日 13:13  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$table = '@system_templates';

if ( $type == 'edit' || $type == "add" )
{
	$where = $post['id'];
	$temp = str::Escape($post['temp'] , 'e');
	
	if ( $temp['temp_name'] == '' )
	{
		Ajax('对不起，模版名字必须填写！',300);
	}
	
	//新增菜单
	if( $type == 'add' )
	{
		//查询账号是否存在
		$wheresql['table'] = $table;
		$wheresql['where']['temp_name'] = $temp['temp_name'];
		$wheresql['where']['temp_module'] = $temp['temp_module'];
		$wheresql['where']['temp_type'] = $temp['temp_type'];
		$count = wmsql::GetCount($wheresql);
		if ( $count > 0 )
		{
			Ajax('对不起，该模版已经存在了！',300);
		}
		
		$data['id'] = WMSql::Insert($table, $temp);
		$data['name'] = $temp['temp_name'];

		//写入操作记录
		SetOpLog( '新增了预设模版' , 'system' , 'insert' , $table , array('temp_id'=>$data['id']) , $data );
		Ajax('模版新增成功!',null,$data);
	}
	//修改菜单
	else
	{
		//写入操作记录
		SetOpLog( '修改了预设模版' , 'system' , 'update' , $table , $where , $temp );
		WMSql::Update($table, $temp, $where);
		Ajax('模版修改成功!');
	}
}
//ajax获取每个模块的分类
else if( $type == 'gettype' )
{
	$val = Get('val');
	$tempSer = AdminNewClass('system.templates');
	//选中的类型
	$dataArr = $tempSer->GetTempType($val);
	
	Ajax(null,null,$dataArr);
}
//删除数据
else if ( $type == 'del' )
{
	$where['temp_id'] = GetDelId();
	//写入操作记录
	SetOpLog( '删除了预设模版' , 'system' , 'delete' , $table , $where);
	wmsql::Delete($table , $where);
		
	Ajax('预设模版删除成功!');
}
//清空请求记录
else if ( $type == 'clear' )
{
	wmsql::Delete($table);

	//写入操作记录
	SetOpLog( '清空了预设模版' , 'system' , 'delete');
	Ajax('所有预设模版成功清空！');
}
//删除静态资源操作
else if ( $type == 'delstatic' )
{
	$id = Request('id');
	$path = str::ClearPath(Request('path'));
	if( $id == '' || $path == '' )
	{
		Ajax('对不起，预设模版的id和静态资源类型不能为空！' , 300);
	}
	else if( str::in_string('../',$path,1) || str::in_string('..',$path,1))
	{
		Ajax('对不起，静态资源路径错误！' , 300);
	}
	file::DelDir(WMSTATIC.$id.'/'.$path);
	//写入操作记录
	SetOpLog( '删除了ID='.$id.'预设模版的静态资源' , 'system' , 'delete');
	Ajax('预设模版的静态资源删除成功！');
}
?>
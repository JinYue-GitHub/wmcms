<?php
/**
* 专题分类处理器
*
* @version        $Id: operate.zt.type.php 2018年8月13日 21:39  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$seoSer = AdminNewClass('system.seo');
$conSer = AdminNewClass('system.config');

$table = '@zt_type';

//修改分类信息
if ( $type == 'type_edit' || $type == "type_add"  )
{
	$data = str::Escape($post['type'] , 'e');
	$where['type_id'] = $post['type_id'];
	
	if ( $data['type_name'] == '' )
	{
		Ajax('对不起，分类名字必须填写！',300);
	}
	else if( !str::Number($data['type_order']) )
	{
		Ajax('对不起，分类排序必须为数字！',300);
	}
	else if( !str::Number($data['type_topid']) )
	{
		Ajax('对不起，所属分类必须选择！',300);
	}

	//专题分类名字检查
	$wheresql['table'] = $table;
	$wheresql['where']['type_id'] = array('<>',$where['type_id']);
	$wheresql['where']['type_name'] = $data['type_name'];
	if ( wmsql::GetCount($wheresql) > 0 )
	{
		Ajax('对不起，该分类名已经存在！',300);
	}
	
	//查询上级所有id
	$data['type_pid'] = GetPids( $table , $data['type_topid'] );
	
	//新增数据
	if( $type == 'type_add' )
	{
		$info = '恭喜您，专题分类添加成功！';
		$where['type_id'] = wmsql::Insert($table, $data);
		
		//写入操作记录
		SetOpLog( '新增了专题分类'.$data['type_name'] , 'zt' , 'insert' , $table , $where , $data );
	}
	//修改分类
	else
	{
		$info = '恭喜您，专题分类修改成功！';
		wmsql::Update($table, $data, $where);
		
		//写入操作记录
		SetOpLog( '修改了专题分类'.$data['type_name'] , 'zt' , 'update' , $table , $where , $data );
	}
	
	//插入或者修改html规则
	$seoSer->SetTypeHtml('zt' , $post['html'] , $where['type_id']);
	
	Ajax($info);
}
//删除分类
else if ( $type == 'type_del' )
{
	function TypeDelCallBack($tid)
	{
		//删除当前分类的文章
		wmsql::Delete('@zt_zt', array('type_id'=>$tid));
	}
	DelType('zt');
	

	//写入操作记录
	$where['type_id'] = GetDelId();
	SetOpLog( '删除了专题分类' , 'zt' , 'delete' , $table , $where);
	Ajax('专题分类删除成功!');
}
?>
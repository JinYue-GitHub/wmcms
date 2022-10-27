<?php
/**
* 图集分类处理器
*
* @version        $Id: picture.type.php 2016年5月15日 9:53  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @picture           http://www.weimengcms.com
*
*/
$seoSer = AdminNewClass('system.seo');
$conSer = AdminNewClass('system.config');

$table = '@picture_type';

//修改分类信息
if ( $type == 'edit' || $type == "add"  )
{
	$data = str::Escape($post['type'] , 'e');
	$where = $post['id'];
	
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

	//分类名字检查
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
	if( $type == 'add' )
	{
		$info = '恭喜您，图集分类添加成功！';
		$where['type_id'] = wmsql::Insert($table, $data);
		
		//写入操作记录
		SetOpLog( '新增了图集分类'.$data['type_name'] , 'picture' , 'insert' , $table , $where , $data );
	}
	//修改分类
	else
	{
		$info = '恭喜您，图集分类修改成功！';
		wmsql::Update($table, $data, $where);
		
		//写入操作记录
		SetOpLog( '修改了图集分类'.$data['type_name'] , 'picture' , 'update' , $table , $where , $data );
	}

	//写入自定义字段
	$fieldArr['module'] = $curModule;
	$fieldArr['option'] = GetKey($post,'field');
	$fieldArr['tid'] = $where['type_id'];
	$fieldArr['pid'] = $data['type_topid'];
	$conSer->SetFieldOption($fieldArr);
	
	//插入或者修改html规则
	$seoSer->SetTypeHtml('picture' , $post['html'] , $where['type_id']);
	
	Ajax($info);
}
//删除分类
else if ( $type == 'del' )
{
	function TypeDelCallBack($tid)
	{
		//删除当前分类的文章
		wmsql::Delete('@picture_picture', array('type_id'=>$tid));
	}
	DelType();

	//写入操作记录
	$where['type_id'] = GetDelId();
	SetOpLog( '删除了图集分类' , 'picture' , 'delete' , $table , $where);
	Ajax('图集分类删除成功!');
}
?>
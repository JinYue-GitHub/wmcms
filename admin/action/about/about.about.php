<?php
/**
* 信息处理器
*
* @version        $Id: about.about.php 2016年5月13日 17:55  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$conSer = AdminNewClass('system.config');
$table = '@about_about';

//修改分类信息
if ( $type == 'edit' || $type == "add"  )
{
	$data = str::Escape( $post['about'], 'e' );
	$where = $post['id'];
	$data['about_time'] = strtotime($data['about_time']);

	if ( $data['about_name'] == '' )
	{
		Ajax('对不起，信息标题必须填写！',300);
	}
	else if( !str::Number($data['type_id']) )
	{
		Ajax('对不起，信息分类必须选择！',300);
	}

	//信息名字检查
	$wheresql['table'] = $table;
	$wheresql['where']['about_id'] = array('<>',$where['about_id']);
	$wheresql['where']['about_name'] = $data['about_name'];
	if ( wmsql::GetCount($wheresql) > 0 )
	{
		Ajax('对不起，该信息已经存在！',300);
	}
	
	//新增数据
	if( $type == 'add' )
	{
		$info = '恭喜您，信息添加成功！';
		$where['about_id'] = wmsql::Insert($table, $data);
		
		//写入操作记录
		SetOpLog( '新增了信息'.$data['about_name'] , 'about' , 'insert' , $table , $where , $data );
	}
	//修改分类
	else
	{
		$info = '恭喜您，信息修改成功！';
		wmsql::Update($table, $data, $where);
		
		//写入操作记录
		SetOpLog( '修改了信息'.$data['about_name'] , 'about' , 'update' , $table , $where , $data );
	}

	//写入自定义字段
	$fieldArr['module'] = $curModule;
	$fieldArr['option'] = GetKey($post,'field');
	$fieldArr['tid'] = $data['type_id'];
	$fieldArr['cid'] = $where['about_id'];
	$fieldArr['ft'] = '2';
	$conSer->SetFieldOption($fieldArr);
	
	//修改编辑器上传的内容id
	$uploadMod = NewModel('upload.upload');
	$uploadMod->UpdateCid( 'editor',$curModule , $where['about_id']);
	
	Ajax($info);
}
//删除数据和永久删除数据
else if ( $type == 'del' )
{
	$where['about_id'] = GetDelId();
	//写入操作记录
	SetOpLog( '删除了信息' , 'about' , 'delete' , $table , $where);
	wmsql::Delete($table , $where);
	
	Ajax('信息删除成功!');
}
//移动数据
else if ( $type == 'move' )
{
	$data['type_id'] = Request('tid');
	$where['about_id'] = GetDelId();

	//写入操作记录
	SetOpLog( '移动了信息' , 'about' , 'update' , $table , $where);
	
	wmsql::Update($table, $data, $where);
	Ajax('信息移动成功!');
}
?>
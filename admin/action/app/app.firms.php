<?php
/**
* 厂商厂商处理器
*
* @version        $Id: app.firms.php 2016年5月17日 17:30  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @app           http://www.weimengcms.com
*
*/
$table = '@app_firms';

//修改分类厂商
if ( $type == 'edit' || $type == "add"  )
{
	$firmsSer = AdminNewClass('app.firms');
	$data = str::Escape( $post['firms'], 'e' );
	$where = $post['id'];

	if ( $data['firms_name'] == '' )
	{
		Ajax('对不起，厂商名字必须填写！',300);
	}
	else if( $data['firms_type'] == '' )
	{
		Ajax('对不起，厂商类型必须选择！',300);
	}
	
	//检查厂商是否存在
	$firmsWhere['firms_name'] = $data['firms_name'];
	$firmsWhere['firms_id'] = array('<>',$where['firms_id']);
	if ( $firmsSer->CheckName($firmsWhere) !== false )
	{
		Ajax('对不起，该厂商已经存在！',300);
	}
	
	//新增数据
	if( $type == 'add' )
	{
		$data['firms_addtime'] = time();
		$where['firms_id'] = wmsql::Insert($table, $data);
		
		$info = '恭喜您，厂商添加成功！';
		//写入操作记录
		SetOpLog( '新增了厂商'.$data['firms_name'] , 'app' , 'insert' , $table , $where , $data );
	}
	//修改分类
	else
	{
		wmsql::Update($table, $data, $where);

		$info = '恭喜您，厂商修改成功！';
		//写入操作记录
		SetOpLog( '修改了厂商'.$data['firms_name'] , 'app' , 'update' , $table , $where , $data );
	}
	//修改编辑器上传的内容id
	$uploadMod = NewModel('upload.upload');
	$uploadMod->UpdateCid( 'editor',$curModule , $where['firms_id']);
	
	Ajax($info);
}
//删除数据
else if ( $type == 'del' )
{
	$where['firms_id'] = GetDelId();
	wmsql::Delete($table , $where);
			
	SetOpLog( '删除了厂商' , 'app' , 'delete' , $table , $where);
	Ajax('厂商删除成功!');
}
//厂商搜索操作
else if ( $type == 'search' )
{
	$st = Request('st');
	$keyword = Request('keyword');
	//返回类型。默认全部，否则是键值对
	$rt = Request('rt');
	
	$where['table'] = $table;
	//判断是否搜索标题
	switch ($st)
	{
		default:
			$where['where']['firms_name'] = array('like',$keyword);
			break;
	}
	$where['limit'] = '0,20';
	
	$data = wmsql::GetAll($where);
	if( $data && $rt == 'key')
	{
		foreach ($data as $k=>$v)
		{
			if( $v['firms_type'] == 'a')
			{
				$typeName = '开发商';
			}
			else if( $v['firms_type'] == 'o')
			{
				$typeName = '运营商';
			}
			else
			{
				$typeName = '自研自营';
			}
			$data[$k] = array('key'=>$v['firms_id'],'val'=>$typeName.' - '.$v['firms_name']);
		}
	}
	
	Ajax('查询成功!',null,$data);
}
?>
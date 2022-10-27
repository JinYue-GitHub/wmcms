<?php
/**
* 专题节点节点页面处理器
*
* @version        $Id: operate.zt.node.php 2016年5月10日 11:29  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$table = '@zt_node';

//修改分类信息
if ( $type == 'edit' || $type == "add"  )
{
	$data = str::Escape( $post['node'], 'e' );
	$data['node_label'] = $post['node']['node_label'];
	$where = $post['id'];
	
	if ( $data['node_name'] == '' || $data['node_pinyin'] == '')
	{
		Ajax('对不起，专题节点名字和专题节点标识不能为空！',300);
	}
	
	//节点标识检查
	$wheresql['table'] = $table;
	$wheresql['where']['node_zt_id'] = $data['node_zt_id'];
	$wheresql['where']['node_id'] = array('<>',$where['node_id']);
	$wheresql['where']['node_pinyin'] = $data['node_pinyin'];
	if ( wmsql::GetCount($wheresql) > 0 )
	{
		Ajax('对不起，当前节点标识已经使用了！',300);
	}
	
	
	//新增数据
	if( $type == 'add' )
	{
		$data['node_time'] = time();
		$info = '恭喜您，专题节点添加成功！';
		$where['node_id'] = wmsql::Insert($table, $data);
		
		//写入操作记录
		SetOpLog( '新增了专题节点'.$data['node_name'] , 'zt' , 'insert' , $table , $where , $data );
	}
	//修改分类
	else
	{
		$info = '恭喜您，专题节点修改成功！';
		wmsql::Update($table, $data, $where);
		
		//写入操作记录
		SetOpLog( '修改了专题节点'.$data['node_name'] , 'zt' , 'update' , $table , $where , $data );
	}
	//修改编辑器上传的内容id
	$uploadMod = NewModel('upload.upload');
	$uploadMod->UpdateCid( 'editor','operate_ztnode' , $where['node_id']);
	
	Ajax($info);
}
//删除数据
else if ( $type == 'del' )
{
	$where['node_id'] = GetDelId();
	//写入操作记录
	SetOpLog( '删除了专题节点' , 'zt' , 'delete' , $table , $where);
	wmsql::Delete($table , $where);
		
	Ajax('专题节点删除成功!');
}
//清空请求记录
else if ( $type == 'clear' )
{
	$zid = Request('zid');
	if( $zid == '' )
	{
		Ajax('对不起，必须选择节点所属的专题！',300);
	}
	$where['node_zt_id'] = $zid;
	wmsql::Delete( $table , $where);

	//写入操作记录
	SetOpLog( '清空了某个专题节点' , 'zt' , 'delete');
	Ajax('某个专题节点成功清空！');
}
?>
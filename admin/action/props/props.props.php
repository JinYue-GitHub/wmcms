<?php
/**
* 道具处理器
*
* @version        $Id: props.props.php 2017年3月5日 20:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$curModule = 'author';
$table = '@props_props';
$propsMod = NewModel('props.props');
$uploadSer = AdminNewClass('upload');

//修改分类信息
if ( $type == 'edit' || $type == "add"  )
{
	$data = str::Escape( $post['props'], 'e' );
	$where = $post['id'];
	$data['props_time'] = strtotime($data['props_time']);
	$data['props_option'] = serialize($post['option']);
	if ( $data['props_name'] == '' )
	{
		Ajax('对不起，道具名字必须填写！',300);
	}
	else if( !str::Number($data['props_type_id']) )
	{
		Ajax('对不起，道具分类必须选择！',300);
	}
	else if( !is_numeric($data['props_gold1']) || !is_numeric($data['props_gold2']) || !is_numeric($data['props_money']) )
	{
		Ajax('对不起，出售价格只能为数字！',300);
	}
	else if( $data['props_gold1'] == 0 && $data['props_gold2'] == 0 && $data['props_money'] == 0 )
	{
		Ajax('对不起，出售价格不能全部为0！',300);
	}
	
	
	//新增数据
	if( $type == 'add' )
	{
		$info = '恭喜您，道具添加成功！';
		$where['props_id'] = wmsql::Insert($table, $data);
		//修改最后上传的图片数据
		$uploadSer->UpLast( 'author', 'props' , $where['props_id'] , '道具封面图');
		//写入操作记录
		SetOpLog( '新增了道具'.$data['props_name'] , $curModule , 'insert' , $table , $where , $data );
	}
	//修改分类
	else
	{
		$info = '恭喜您，道具修改成功！';
		wmsql::Update($table, $data, $where);
		
		//写入操作记录
		SetOpLog( '修改了道具'.$data['props_name'] , $curModule , 'update' , $table , $where , $data );
	}
	Ajax($info);
}
//永久删除数据
else if ( $type == 'del' )
{
	$where['props_id'] = GetDelId();
	//查询数据
	$data = $propsMod->GetAll($where);
	if( $data )
	{
		foreach ($data as $k=>$v)
		{
			//删除数据
			$propsMod->DeleteById($v['props_id']);
			//删除图片
			$uploadSer->DelUpload('props',$v['props_id']);
		}
		//写入操作记录
		SetOpLog( '删除了道具' , $curModule , 'delete' , $table , $where);
	}
	Ajax('道具删除成功!');
}
//审核数据
else if ( $type == 'status' )
{
	$data['props_status'] = Request('status');
	$where['props_id'] = GetDelId();

	if( Request('status') == '1')
	{
		$msg = '上架';
	}
	else
	{
		$msg = '下架';
	}
	//写入操作记录
	SetOpLog( $msg.'了道具' , $curModule , 'update' , $table , $where);
	
	wmsql::Update($table, $data, $where);
	Ajax('道具'.$msg.'成功!');
}
?>
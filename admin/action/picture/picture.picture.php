<?php
/**
* 图集处理器
*
* @version        $Id: picture.picture.php 2016年5月15日 9:53  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$conSer = AdminNewClass('system.config');
$table = '@picture_picture';

//修改分类图集
if ( $type == 'edit' || $type == "add"  )
{
	$where = $post['id'];
	$data = str::Escape( $post['picture'], 'e' );
	$data['picture_time'] = strtotime($data['picture_time']);
	$data['picture_imgs'] = '';
	$data['picture_count'] = 0;
	$picArr = GetKey($post,'pic');
	if( !empty($picArr) )
	{
		$data['picture_imgs'] = serialize($picArr);
		$data['picture_count'] = count($picArr['src']);
	}
	
	if ( $data['picture_name'] == '' )
	{
		Ajax('对不起，图集标题必须填写！',300);
	}
	else if( !str::Number($data['type_id']) )
	{
		Ajax('对不起，图集分类必须选择！',300);
	}

	//图集名字检查
	$wheresql['table'] = $table;
	$wheresql['where']['picture_id'] = array('<>',$where['picture_id']);
	$wheresql['where']['picture_name'] = $data['picture_name'];
	if ( wmsql::GetCount($wheresql) > 0 )
	{
		Ajax('对不起，该图集已经存在！',300);
	}
	
	//新增数据
	if( $type == 'add' )
	{
		//写入标签
		$mangerSer = AdminNewClass('manager');
		$mangerSer->SetTags('picture' , $data['picture_tags']);
		
		$info = '恭喜您，图集添加成功！';
		$where['picture_id'] = wmsql::Insert($table, $data);
		
		//写入操作记录
		SetOpLog( '新增了图集'.$data['picture_name'] , 'picture' , 'insert' , $table , $where , $data );
	}
	//修改分类
	else
	{
		$info = '恭喜您，图集修改成功！';
		wmsql::Update($table, $data, $where);

		//写入操作记录
		SetOpLog( '修改了图集'.$data['picture_name'] , 'picture' , 'update' , $table , $where , $data );
	}

	//写入自定义字段
	$fieldArr['module'] = $curModule;
	$fieldArr['option'] = GetKey($post,'field');
	$fieldArr['tid'] = $data['type_id'];
	$fieldArr['cid'] = $where['picture_id'];
	$fieldArr['ft'] = '2';
	$conSer->SetFieldOption($fieldArr);
	
	//修改编辑器上传的内容id
	$uploadMod = NewModel('upload.upload');
	$uploadMod->UpdateCid( 'editor',$curModule , $where['picture_id']);
	
	//上传的文件描述重新写入
	$uploadSer = AdminNewClass('upload');
	$uploadSer->UpUpload( $picArr , 'picture' , $where['picture_id']);
	
	Ajax($info);
}
//删除数据和永久删除数据
else if ( $type == 'del' )
{
	$where['table'] = $table;
	$where['where']['picture_id'] = GetDelId();
	$data = wmsql::GetAll($where);
	if( $data )
	{
		$uploadSer = AdminNewClass('upload');
		
		foreach ($data as $k=>$v)
		{
			//删除图集记录
			$wheresql['picture_id'] = $v['picture_id'];
			wmsql::Delete($table , $wheresql);
			
			$uploadSer->DelUpload('app',$v['picture_id']);
		}
	}

	SetOpLog( '删除了图集' , 'picture' , 'delete' , $table , $where['where']);
	Ajax('图集删除成功!');
}
//审核数据
else if ( $type == 'status' )
{
	$data['picture_status'] = Request('status');
	$where['picture_id'] = GetDelId();

	if( Request('status') == '1')
	{
		$msg = '审核通过';
	}
	else
	{
		$msg = '取消审核';
	}
	//写入操作记录
	SetOpLog( $msg.'了图集' , 'picture' , 'update' , $table , $where);
	
	wmsql::Update($table, $data, $where);
	Ajax('图集'.$msg.'成功!');
}
//移动数据
else if ( $type == 'move' )
{
	$data['type_id'] = Request('tid');
	$where['picture_id'] = GetDelId();

	//写入操作记录
	SetOpLog( '移动了图集' , 'picture' , 'update' , $table , $where);
	
	wmsql::Update($table, $data, $where);
	Ajax('图集移动成功!');
}
//属性操作
else if ( $type == 'attr' )
{
	$data['picture_'.$post['attr']] = $post['val'];
	$where['picture_id'] = GetDelId();
	
	switch($post['attr'])
	{
		case "rec":
			$msg = "推荐";
			break;
	}

	//写入操作记录
	SetOpLog( $msg.'了图集' , 'picture' , 'update' , $table , $where);

	wmsql::Update($table, $data, $where);
	Ajax($msg.'了图集!');
}
?>
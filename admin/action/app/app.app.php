<?php
/**
* 应用处理器
*
* @version        $Id: app.app.php 2016年5月15日 9:53  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$conSer = AdminNewClass('system.config');
$table = '@app_app';

//修改分类应用
if ( $type == 'edit' || $type == "add"  )
{
	$appSer = AdminNewClass('app.app');
	$firmsSer = AdminNewClass('app.firms');
	$htmlMod = NewModel('system.html' , array('module'=>$curModule));
	
	//接受参数
	$data = str::Escape( $post['app'], 'e' );
	$where = $post['id'];
	//厂商数据
	$firms = $post['firms'];
	$data['app_addtime'] = strtotime($data['app_addtime']);

	//检查
	if ( $data['app_name'] == '' )
	{
		Ajax('对不起，应用标题必须填写！',300);
	}
	else if( !str::Number($data['type_id']) )
	{
		Ajax('对不起，应用分类必须选择！',300);
	}
	else if( $data['app_lid']=='' || $data['app_cid']=='' || $data['app_paid']=='' )
	{
		Ajax('对不起，语言类型、应用资费和运行平台必须选择！',300);
	}

	//检查厂商是否存在
	$appWhere['app_name'] = $data['app_name'];
	$appWhere['app_id'] = array('<>',$where['app_id']);
	if ( $appSer->CheckName($appWhere) !== false)
	{
		Ajax('对不起，该应用已经存在！',300);
	}

	//检查数据
	$firmsData = $firmsSer->CheckFirms($firms);
	//开发商
	$data['app_aid'] = $firmsData['a'];
	//运营商
	$data['app_oid'] = $firmsData['o'];
	
	
	//新增数据
	if( $type == 'add' )
	{
		//写入标签
		$mangerSer = AdminNewClass('manager');
		$mangerSer->SetTags('app' , $data['app_tags']);
		
		$info = '恭喜您，应用添加成功！';
		$where['app_id'] = wmsql::Insert($table, $data);
		
		//写入操作记录
		SetOpLog( '新增了应用'.$data['app_name'] , 'app' , 'insert' , $table , $where , $data );
	}
	//修改分类
	else
	{
		$info = '恭喜您，应用修改成功！';
		wmsql::Update($table, $data, $where);
		
		//写入操作记录
		SetOpLog( '修改了应用'.$data['app_name'] , 'app' , 'update' , $table , $where , $data );
	}

	//写入自定义字段
	$fieldArr['module'] = $curModule;
	$fieldArr['option'] = GetKey($post,'field');
	$fieldArr['tid'] = $data['type_id'];
	$fieldArr['cid'] = $where['app_id'];
	$fieldArr['ft'] = '2';
	$conSer->SetFieldOption($fieldArr);

	//修改编辑器上传的内容id
	$uploadMod = NewModel('upload.upload');
	$uploadMod->UpdateCid( 'editor',$curModule , $where['app_id']);
	
	//上传的文件描述重新写入
	$uploadSer = AdminNewClass('upload');
	$uploadSer->UpUpload( GetKey($post,'pic') , 'app' , $where['app_id']);
	//创建HTML
	$htmlMod->CreateContentHtml($where['app_id']);
	
	Ajax($info);
}
//删除数据和永久删除数据
else if ( $type == 'del' )
{
	$where['table'] = $table;
	$where['where']['app_id'] = GetDelId();
	$data = wmsql::GetAll($where);
	if( $data )
	{
		$uploadSer = AdminNewClass('upload');
		
		foreach ($data as $k=>$v)
		{
			//删除应用记录
			$wheresql['app_id'] = $v['app_id'];
			wmsql::Delete($table , $wheresql);
			
			$uploadSer->DelUpload('app',$v['app_id']);
		}
	}

	SetOpLog( '删除了应用' , 'app' , 'delete' , $table , $where['where']);
	Ajax('应用删除成功!');
}
//审核数据
else if ( $type == 'status' )
{
	$data['app_status'] = Request('status');
	$where['app_id'] = GetDelId();

	if( Request('status') == '1')
	{
		$msg = '审核通过';
	}
	else
	{
		$msg = '取消审核';
	}
	//写入操作记录
	SetOpLog( $msg.'了应用' , 'app' , 'update' , $table , $where);
	
	wmsql::Update($table, $data, $where);
	Ajax('应用'.$msg.'成功!');
}
//移动数据
else if ( $type == 'move' )
{
	$data['type_id'] = Request('tid');
	$where['app_id'] = GetDelId();

	//写入操作记录
	SetOpLog( '移动了应用' , 'app' , 'update' , $table , $where);
	
	wmsql::Update($table, $data, $where);
	Ajax('应用移动成功!');
}
//属性操作
else if ( $type == 'attr' )
{
	$data['app_'.$post['attr']] = $post['val'];
	$where['app_id'] = GetDelId();
	
	switch($post['attr'])
	{
		case "rec":
			$msg = "推荐";
			break;
	}

	//写入操作记录
	SetOpLog( $msg.'了应用' , 'app' , 'update' , $table , $where);

	wmsql::Update($table, $data, $where);
	Ajax($msg.'了应用!');
}
?>
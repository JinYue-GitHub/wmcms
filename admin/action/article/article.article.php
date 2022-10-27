<?php
/**
* 文章处理器
*
* @version        $Id: article.article.php 2016年4月17日 16:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//配置文件，删除数据方式是否是直接删除
$articleConfig = AdminInc('article');
$conSer = AdminNewClass('system.config');
$articleMod = NewModel('article.article');
$table = '@article_article';

//修改分类信息
if ( $type == 'edit' || $type == "add"  )
{
	$htmlMod = NewModel('system.html' , array('module'=>$curModule));
	$data = str::Escape( $post['article'], 'e' );
	$where['article_id'] = $post['article_id'];
	$data['article_simg'] = file::GetImg($data['article_simg'] , GetKey($post,'down_simg'));
	$data['article_addtime'] = strtotime($data['article_addtime']);
	$data['article_editor_id'] = Session('admin_id');
	
	if ( $data['article_name'] == '' )
	{
		Ajax('对不起，文章标题必须填写！',300);
	}
	else if( !str::Number($data['type_id']) )
	{
		Ajax('对不起，文章分类必须选择！',300);
	}
	else if( $data['article_author'] == '' || $data['article_source'] == ''  )
	{
		Ajax('对不起，文章作者和来源不能为空！',300);
	}
	
	//获得文章简介
	$data['article_info']= str::GetContentInfo(GetKey($data,'article_content'),$data['article_info']);
	//获得文章缩略图
	$data['article_simg']= str::GetContentSimg(GetKey($data,'article_content'),$data['article_simg'],$articleConfig['default_simg']);

	//新增数据
	if( $type == 'add' )
	{
		if( $data['article_status'] == 1)
		{
			$data['article_examinetime'] = time();
		}
		//写入新文章作者和来源的数据
		$authorSer = AdminNewClass('article.author');
		$mangerSer = AdminNewClass('manager');
		//写入作者和来源
		$authorSer->SetData('a' , $data['article_author']);
		$authorSer->SetData('s' , $data['article_source']);
		$authorSer->SetData('e' , $data['article_editor']);
		//写入标签
		$mangerSer->SetTags('article' , $data['article_tags']);
		
		$info = '恭喜您，文章添加成功！';
		$where['article_id'] = $articleMod->Insert($data);

		//写入操作记录
		SetOpLog( '新增了文章'.$data['article_name'] , 'article' , 'insert' , $table , $where , $data );
	}
	//修改分类
	else
	{
		$info = '恭喜您，文章修改成功！';
		$articleMod->Update($data,$where);
		
		//写入操作记录
		SetOpLog( '修改了文章'.$data['article_name'] , 'article' , 'update' , $table , $where , $data );
	}

	//写入自定义字段
	$fieldArr['module'] = $curModule;
	$fieldArr['option'] = GetKey($post,'field');
	$fieldArr['tid'] = $data['type_id'];
	$fieldArr['cid'] = $where['article_id'];
	$fieldArr['ft'] = '2';
	$conSer->SetFieldOption($fieldArr);
	
	//修改编辑器上传的内容id
	$uploadMod = NewModel('upload.upload');
	$uploadMod->UpdateCid( 'editor',$curModule , $where['article_id']);
	
	//创建HTML
	$htmlMod->CreateContentHtml($where['article_id']);

	Ajax($info);
}
//删除数据和永久删除数据
else if ( $type == 'del' || $type == 'realdel' )
{
	//删除数据到回收站
	if( $type == 'del' && $articleConfig['admin_del'] == '0' )
	{
		$where['article_id'] = GetDelId();
		$data['article_status'] = 2;
		
		//写入操作记录
		SetOpLog( '删除了文章到回收站' , 'article' , 'delete' , $table , $where);
		wmsql::Update($table, $data, $where);
	}
	//永久删除数据
	else if( $type == 'realdel' || ( $type == 'del' && $articleConfig['admin_del'] == '1'  ))
	{
		$where['article_id'] = GetDelId();
		$articleMod->Delete($where);
		
		//解绑主题附件
		$uploadMod = NewModel('upload.upload');
		$uploadMod->module = 'author';
		$uploadMod->type = 'article';
		$uploadMod->cid = GetDelId();
		$uploadMod->UnFileBind();
		
		//写入操作记录
		SetOpLog( '彻底删除了文章' , 'article' , 'delete' , $table , $where);
		
		//删除申请记录
		$applyMod = NewModel('system.apply');
		$applyWhere['apply_cid'] = GetDelId();
		$applyWhere['apply_module'] = 'author';
		$applyWhere['apply_type'] = array('or','article_editarticle,article_cover');
		$applyMod->Delete($applyWhere);
	}
	Ajax('文章删除成功!');
}
//审核数据
else if ( $type == 'status' )
{
	$data['article_status'] = Request('status');
	$where['article_id'] = GetDelId();

	if( Request('status') == '1')
	{
		$msg = '审核通过';
	}
	else
	{
		$msg = '取消审核';
	}
	//写入操作记录
	SetOpLog( $msg.'了文章' , 'article' , 'update' , $table , $where);
	
	wmsql::Update($table, $data, $where);
	Ajax('文章'.$msg.'成功!');
}
//还原数据
else if( $type == 'reduction' )
{
	$data['article_status'] = 1;
	$where['article_id'] = GetDelId();
	//写入操作记录
	SetOpLog( '文章从回收站还原了' , 'article' , 'update' , $table , $where);
	
	wmsql::Update($table, $data, $where);
	Ajax('文章还原成功!');
}
//移动数据
else if ( $type == 'move' )
{
	$data['type_id'] = Request('tid');
	$where['article_id'] = GetDelId();

	//写入操作记录
	SetOpLog( '移动了文章' , 'article' , 'update' , $table , $where);
	
	wmsql::Update($table, $data, $where);
	Ajax('文章移动成功!');
}
//属性操作
else if ( $type == 'attr' )
{
	$data['article_'.$post['attr']] = $post['val'];
	$where['article_id'] = GetDelId();
	
	switch($post['attr'])
	{
		case "rec":
			$msg = "推荐/取消";
			break;
		  
		case "head":
			$msg = "头条/取消";
			break;
		  
		case "strong":
			$msg = "加粗/取消";
			break;
			
		case "display":
			$msg = "隐藏/显示";
			break;
	}

	//写入操作记录
	SetOpLog( $msg.'了文章' , 'article' , 'update' , $table , $where);

	wmsql::Update($table, $data, $where);
	Ajax($msg.'了文章!');
}
?>
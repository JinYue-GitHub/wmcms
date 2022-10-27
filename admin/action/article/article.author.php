<?php
/**
* 文章作者来源处理器
*
* @version        $Id: article.author.php 2016年4月22日 11:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$table = '@article_author';

//修改分类信息
if ( $type == 'edit' || $type == "add"  )
{
	$data = str::Escape($post['author'] , 'e');
	$where = Post('id/a');
	
	if ( $data['author_name'] == '' )
	{
		Ajax('对不起，标题必须填写！',300);
	}
	else if( !str::Number($data['author_data']) )
	{
		Ajax('对不起，数据条数必须是数字！',300);
	}
	
	//新增数据
	if( $type == 'add' )
	{
		$info = '恭喜您，文章作者或来源添加成功！';
		$where['author_id'] = wmsql::Insert($table, $data);
		
		//写入操作记录
		SetOpLog( '新增了文章来源或作者'.$data['author_name'] , 'article' , 'insert' , $table , $where , $data );
	}
	//修改分类
	else
	{
		$info = '恭喜您，文章作者或来源修改成功！';
		wmsql::Update($table, $data, $where);
		
		//写入操作记录
		SetOpLog( '修改了文章来源或作者'.$data['author_name'] , 'article' , 'update' , $table , $where , $data );
	}

	Ajax($info);
}
//删除数据
else if ( $type == 'del' )
{
	$where['author_id'] = GetDelId();

	//写入操作记录
	SetOpLog( '删除了文章作者或来源到回收站' , 'article' , 'delete' , $table , $where);
	
	wmsql::Delete($table , $where);
	Ajax('文章作者或来源删除成功!');
}
?>
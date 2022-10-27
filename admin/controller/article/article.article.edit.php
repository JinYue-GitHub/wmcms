<?php
/**
* 文章编辑控制器文件
*
* @version        $Id: article.article.edit.php 2016年4月16日 23:17  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$typeSer = AdminNewClass('article.type');
$articleMod = NewModel('article.article');
$articleSer = AdminNewClass('article.article');
$articleConfig = AdminInc('article');

//查询所有分类
$typeArr = $typeSer->GetType();
//所有属性
$attrArr = $articleSer->GetAttr();


//接受数据
$id = Get('id');
if( $type == '' ){$type = 'add';}


//如果id大于0
if ( $type == 'edit')
{
	$data = $articleMod->GetOne($id);
}
//不存在就设置默认值
else
{
	$where['table'] = '@article_author';
	$where['where']['author_type'] = 'a';
	$where['where']['author_default'] = '1';
	//查询默认作者
	$authorData = wmsql::GetOne($where);
	$data['article_author'] = $authorData['author_name'];
	$data['article_editor'] = Session('admin_name');
	
	//查询默认来源
	$where['where']['author_type'] = 's';
	$authorData = wmsql::GetOne($where);
	$data['article_source'] = $authorData['author_name'];
	//默认添加状态
	$data['article_status'] = $articleConfig['admin_add'];

	$data['article_weight'] = 99;
	$data['article_rec'] = $data['article_strong'] = $data['article_head'] = $data['article_cai'] = 
	$data['article_score'] = $data['article_read'] = $data['article_replay'] = $data['article_ding'] = '0';
	$data['article_display'] = '1';
	$data['article_addtime'] = time();
}
?>
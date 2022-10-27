<?php
/**
* 创建/编辑小说页面首页
*
* @version        $Id: novel_noveledit.php 2016年12月18日 15:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//引入模块公共文件
require_once 'author.common.php';

//检查作者的状态
author::CheckAuthor();

$id = str::Int( Get('nid') , null , 0);

//检查是否存在小说
$data = author::CheckContent('novel', $id);
//id大于0就检查小说作品的审核状态
if( $data && $id > 0 )
{
	$applyMod = NewModel('system.apply' , 'author');
	//检查待审核的封面
	$where['apply_status'] = 0;
	$where['apply_module'] = 'author';
	$where['apply_type'] = 'novel_cover';
	$where['apply_uid'] = user::GetUid();
	$where['apply_cid'] = $id;
	$data['cover_apply'] = $applyMod->GetOne($where);
}

//获得页面的标题等信息
C('page' ,  array(
	'pagetype'=>'author_novel_noveledit' ,
	'dtemp'=>'author/novel_noveledit.html',
	'id'=>$id,
	'data'=>$data,
	'label'=>'authorlabel',
	'label_fun'=>'NovelNovelEditLabel',
));

//设置seo信息
tpl::GetSeo();
//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>
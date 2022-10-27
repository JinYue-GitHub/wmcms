<?php
/**
* 创建/编辑小说分卷
*
* @version        $Id: novel_volumeedit.php 2017年1月4日 21:43  weimeng
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
$vid = str::Int( Get('vid') , null , 0);

//检查是否存在小说
$data = author::CheckContent('novel', $id , $lang['author']['par']['novel_no']);
//查询分卷
if($vid > 0)
{
	$where['volume_id'] = $vid;
	$where['volume_nid'] = $data['novel_id'];
	$volumeData = str::GetOne(novel::GetData( 'volume' , $where , $lang['system']['content']['no'] ));
	//合并数据
	$data = array_merge($data,$volumeData);
}

//获得页面的标题等信息
C('page' ,  array(
	'pagetype'=>'author_novel_volumeedit' ,
	'dtemp'=>'author/novel_volumeedit.html',
	'id'=>$id,
	'vid'=>$vid,
	'data'=>$data,
	'label'=>'authorlabel',
	'label_fun'=>'NovelVolumeEditLabel',
));

//设置seo信息
tpl::GetSeo();
//创建模版并且输出
$tpl=new tpl();
$tpl->display();
?>
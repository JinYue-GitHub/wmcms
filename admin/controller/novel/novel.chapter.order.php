<?php
/**
* 小说章节列表控制器文件
*
* @version        $Id: novel.chapter.list.php 2016年4月29日 10:24  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//接受参数
$cid = Request('cid');
$nid = Request('nid');


//获取列表条件
$where['table'] = '@novel_chapter';
$where['field'] = 'chapter_id,chapter_name,chapter_order';
$where['left']['@novel_novel as n'] = 'novel_id=chapter_nid';
$where['left']['@novel_volume'] = 'volume_id=chapter_vid';
$where['left']['@novel_type as t'] = 'n.type_id=t.type_id';
$where['where']['chapter_nid'] = $nid;
$where['order'] = 'chapter_status,chapter_order';
$data = wmsql::GetAll($where);
?>
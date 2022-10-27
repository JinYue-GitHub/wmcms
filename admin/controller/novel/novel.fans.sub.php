<?php
/**
* 粉丝订阅记录控制器文件
*
* @version        $Id: novel.fans.sub.php 2017年3月30日 21:59  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$config = GetModuleConfig('user');
$subMod = NewModel('novel.sublog');

//接受post数据
$name = Request('name');

//获取列表条件
$where['table'] = '@novel_sublog';
$where['field'] = '@novel_sublog.*,novel_name,chapter_name,user_nickname';
$where['left']['@novel_novel'] = 'log_nid=novel_id';
$where['left']['@user_user'] = 'log_uid=user_id';
$where['left']['@novel_chapter'] = 'log_cid=chapter_id';
if( $orderField == '' )
{
	$where['order'] = 'log_id desc';
}


//数据条数
$total = wmsql::GetCount($where);

//当前页的数据
$where = array_merge($where , GetListWhere($where));
$dataArr = wmsql::GetAll($where);
?>
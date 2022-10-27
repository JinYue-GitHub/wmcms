<?php
/**
* 评论列表控制器文件
*
* @version        $Id: operate.replay.list.php 2016年5月6日 15:10  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$replaySer = AdminNewClass('operate.replay');

//所有模块分类
$moduleArr = $replaySer->GetModule();

//接受post数据
$module = Request('module');


if( $orderField == '' )
{
	$where['order'] = 'replay_id desc';
}

//获取列表条件
$where['table'] = '@replay_replay';

//判断是否搜索标题
if( $module != '' )
{
	$where['where']['replay_module'] = $module;
}

//今日评论
if( $type == 'today' )
{
	$where['where']['replay_time'] = array('>',strtotime(date('Y-m-d')));
}
else if( $type == 'hot' &&  $orderField == '')
{
	$where['order'] = 'replay_ding desc';
}
$where['where']['replay_module'] = array('<>','bbs');



//数据条数
$total = wmsql::GetCount($where);

//当前页的数据
$where = GetListWhere($where);
$dataArr = wmsql::GetAll($where);
?>
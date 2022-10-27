<?php
/**
* 素材查找带回控制器
*
* @version        $Id: operate.weixin.media.lookup.php 2019年03月11日 22:04  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$aid = Get('aid/i');
//点击的文本框id和name
$st = Get('st');
$key = Request('key');

$where = array();
$mediaMod = NewModel('operate.weixin_media');
$wxSer = NewClass('weixin_platform');

if( $orderField == '' )
{
	$where['order'] = 'media_id desc';
}

//判断是否搜索标题
if( !empty($key) )
{
	$where['where']['media_filename'] = array('like',$key);
}
else
{
	$key = '请输入文件名字的关键字';
}

//数据条数
$total = $mediaMod->GetCount($where);
//当前页的数据
$dataArr = $mediaMod->GetList(GetListWhere($where));
?>
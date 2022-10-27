<?php
/**
* 微信公众号素材控制器文件
*
* @version        $Id: operate.weixin.media.list.php 2019年03月10日 20:20  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$where = array();
$mediaMod = NewModel('operate.weixin_media');
$wxSer = NewClass('weixin_platform');

if( $orderField == '' )
{
	$where['order'] = 'media_id desc';
}

$aid = Get('aid/i');
$name = Request('name');

//判断是否搜索标题
if( !empty($st) )
{
	//判断是否搜索标题
	if( !empty($name) )
	{
		if( $st == '1' )
		{
			$where['where']['media_account_id'] = $name;
		}
		else if( $st == '2' )
		{
			$where['where']['media_name'] = array('like',$name);
		}
	}
	else
	{
		$name = '请输入搜索关键字';
	}
}
else
{
	$st = 1;
	$name = '请输入搜索关键字';
}
//判断文件类型搜索类型
if( !empty($ft) )
{
	$where['where']['media_type'] = $ft;
}
else
{
	$ft = '';
}

//数据条数
$total = $mediaMod->GetCount($where);
//当前页的数据
$dataArr = $mediaMod->GetList(GetListWhere($where));
?>
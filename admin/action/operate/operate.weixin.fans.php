<?php
/**
* 微信公众号粉丝处理器
*
* @version        $Id: operate.weixin.fans.php 2019年03月12日 23:19  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$fansMod = NewModel('operate.weixin_fans');
$table = '@weixin_fans';

//删除数据
if ( $type == 'del' )
{
	$where['fans_id'] = GetDelId();
	$fansMod->Del($where);
	//写入操作记录
	SetOpLog( '删除了微信粉丝' , 'system' , 'delete' , $table , $where);
	Ajax('微信粉丝删除成功!');
}
?>
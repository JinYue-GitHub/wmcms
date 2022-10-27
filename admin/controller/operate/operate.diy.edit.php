<?php
/**
* diy页面控制器文件
*
* @version        $Id: operate.diy.edit.php 2016年5月7日 21:55  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$tempSer = AdminNewClass('system.templates');
$diySer = AdminNewClass('operate.diy');

//所有模块分类
$statusArr = $diySer->GetStatus();


//接受数据
$id = Get('id');
if( $type == '' ){$type = 'add';}


//如果id大于0
if ( $type == 'edit')
{
	$where['table'] = '@diy_diy';
	$where['where']['diy_id'] = $id;

	$data = wmsql::GetOne($where);
	
	//查询使用的模版信息
	$temp['cname'] = $tempSer->GetTemp( $data['diy_ctempid'] , 'temp_name' );
}
//不存在就设置默认值
else
{
	$data['diy_status'] = '1';
	$data['diy_read'] = '0';
}
?>
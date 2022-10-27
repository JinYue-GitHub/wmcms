<?php
/**
* 作者信息编辑控制器文件
*
* @version        $Id: author.author.edit.php 2017年1月14日 23:17  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//接受数据
$exp = array();
$id = Get('id','0');
if( $type == '' ){$type = 'add';}

//如果id大于0
if ( $type == 'edit')
{
	$where['table'] = '@author_author';
	$where['where']['author_id'] = $id;
	$data = wmsql::GetOne($where);
	
	$wheresql['table'] = '@author_exp';
	$wheresql['where']['exp_author_id'] = $data['author_id'];
	$expData = wmsql::GetAll($wheresql);
	if( $expData )
	{
		foreach ($expData as $k=>$v)
		{
			$exp[$v['exp_module']]['exp_number'] = $v['exp_number'];
		}
	}
}
//不存在就设置默认值
else
{
	$authorConfig = GetModuleConfig('author');
	$data['author_status'] = $authorConfig['apply_author_status'];
	$data['author_info'] = $authorConfig['author_default_info'];
	$data['author_notice'] = $authorConfig['author_default_notice'];
	$data['author_time'] = time();
}

//查询作者等级
$levelMod = NewModel('author.level');
if( $exp == '' && !is_array($exp))
{
	$exp['novel']['exp_number'] = $exp['article']['exp_number'] = 0;
}
foreach ($exp as $k=>$v)
{
	if( $v['exp_number'] == '' )
	{
		$v['exp_number'] = 0;
	}

	$exp[$k]['level_name'] = $levelMod->GetLevel($k , $v['exp_number']);
}
?>
<?php
/**
* 封面审核处理器
*
* @version        $Id: author.nove.cover.php 2017年1月15日 16:13  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$table = '@system_apply';
$applyMod = NewModel('system.apply' , 'author');

//删除数据
if ( $type == 'del' )
{
	//配置文件，删除数据方式是否是直接删除
	$where['apply_id'] = GetDelId();
	$where['apply_module'] = 'author';
	$where['apply_type'] = 'novel_cover';
	//获得数据
	$data = $applyMod->GetAll($where);
	if( $data )
	{
		//循环删除上传的文件
		foreach ($data as $k=>$v)
		{
			$option = $v['apply_option'];
			if( $option['file'] != '' )
			{
				file::DelFile(WMROOT.$option['file']);
			}
		}
		//写入操作记录
		SetOpLog( '删除了小说上传封面' , 'system' , 'delete' , $table , $where);
		$applyMod->Delete($where);
	}
	
	Ajax('小说封面申请删除成功!');
}
//审核数据
else if ( $type == 'status' )
{
	$status = Request('status/i');
	if( $status == 0)
	{
		Ajax('对不起，不能变更为未审核状态！');
	}
	else if( $status == 1)
	{
        $result = $applyMod->ApplyHandle(GetDelId(),1);
    	//写入操作记录
    	$msg = '取消审核';
    	if( Request('status') == '1')
    	{
    		$msg = '审核通过';
    	}
    	SetOpLog( $msg.'了小说上传封面' , 'system' , 'update' , $table , array('apply_id'=>GetDelId()));
    	Ajax('小说封面'.$msg.'成功!');
	}
}
?>
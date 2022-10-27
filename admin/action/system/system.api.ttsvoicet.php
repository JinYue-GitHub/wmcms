<?php
/**
* 发音人处理器
*
* @version        $Id: system.api.ttsvoicet.php 2022年04月21日 16:38  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$voicetMod = NewModel('system.apittsvoicet');
$table = $voicetMod->table;

//修改筛选信息
if ( $type == 'edit' || $type == "add" )
{
	$data = str::Escape($post['data'] , 'e');
	$where['voicet_id'] = Request('voicet_id');
	foreach ($data as $k=>$v)
	{
		if( $v == '' )
		{
			Ajax('对不起，所有项都不能为空！' , 300);
		}
	}
	
	//如果是新增
	if ( $type == 'add' )
	{
		//插入记录
		$where['voicet_id'] = $voicetMod->Insert($data);
		//写入操作记录
		SetOpLog( '新增了发音人'.$data['voicet_name'] , 'system' , 'insert' , $table , $where , $data );
		Ajax('发音人新增成功!');
	}
	//如果是增加页面
	else
	{
		//写入操作记录
		SetOpLog( '修改发音人' , 'system' , 'update' , $table  , $where , $data );
		//修改数据
		$voicetMod->Update($data,$where);
		Ajax('发音人修改成功！');
	}
}
//删除条件删选
else if( $type == 'del' )
{
	$where['voicet_id'] = GetDelId();
	$voicetMod->Del($where);
	//写入操作记录
	SetOpLog( '删除了发音人' , 'system' , 'delete' , $table , $where);
	Ajax('发音删除成功!');
}
?>
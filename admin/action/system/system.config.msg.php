<?php
/**
* 系统消息模版处理器
*
* @version        $Id: system.config.msg.php 2020年05月28日 11:07  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$msgMod = NewModel('system.msg');
$table = $msgMod->table;

//编辑消息模版
if ( $type == 'edit' || $type == "add"  )
{
	$data = $post['data'];
	$where = Post('id/a');
	
	if( $data['temp_name'] == '' || $data['temp_module'] == '' || $data['temp_key'] == '' || $data['temp_content'] == '' )
	{
		Ajax('对不起，所有选项必填！',300);	
	}
	
	//如果是新增
	if ( $type == 'add' )
	{
		if( $msgMod->GetByKey($data['temp_key']) )
		{
			Ajax('对不起，当前模版消息标识已经存在！',300);	
		}
		//插入记录
		$where['temp_id'] = $msgMod->Insert($data);
		//写入操作记录
		SetOpLog( '新增了消息模版'.$data['temp_name'] , 'system' , 'insert' , $table , $where , $data );
		Ajax('消息模版新增成功!');
	}
	//如果是增加页面
	else
	{
		//写入操作记录
		SetOpLog( '修改消息模版' , 'system' , 'update' , $table  , $where , $data );
		//修改数据
		$msgMod->Update($data,$where);
		Ajax('消息模版修改成功！');
	}
}
//删除消息模版
else if ( $type == 'del' )
{
	$where['temp_id'] = GetDelId();
	$msgMod->Delete($where);
	SetOpLog( '删除了消息模版' , 'system' , 'delete' , $table , $where);
	Ajax('消息模版删除成功!');
}
?>
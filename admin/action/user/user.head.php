<?php
/**
* 用户头像处理器
*
* @version        $Id: user.head.php 2016年5月5日 21:25  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$table = '@user_head';

//修改分类信息
if ( $type == "add"  )
{
	$data['head_src'] = Request('head_src');

	if ( $data['head_src'] == '' )
	{
		Ajax('对不起，文件地址不能为空！',300);
	}
	else if( !str::IsImg($data['head_src']) )
	{
		Ajax('对不起，只能保存图片格式文件！',300);
	}
	
	//新增数据
	if( $type == 'add' )
	{
		$info = '恭喜您，头像添加成功！';
		$where['head_id'] = wmsql::Insert($table, $data);
		
		//写入操作记录
		SetOpLog( '新增了头像' , 'user' , 'insert' , $table , $where , $data );
	}
	
	Ajax($info);
}
//删除数据和永久删除数据
else if ( $type == 'del')
{
	$where['table'] = $table;
	$where['where']['head_id'] = GetDelId();
	$data = wmsql::GetAll($where);
	
	if( $data )
	{
		foreach ($data as $k=>$v)
		{
			if( !str::IsImg($v['head_src']) )
			{
				Ajax('对不起，只能删除图片格式头像！',300);
			}
			else
			{
				//删除头像
				$wheresql['head_id'] = $v['head_id'];
				wmsql::Delete($table , $wheresql);
				//删除文件
				file::DelFile('..'.$v['head_src']);
			}
		}
	}
	//写入操作记录
	SetOpLog( '删除了头像' , 'user' , 'delete' , $table , $where);
	
	Ajax('头像删除成功!');
}
?>
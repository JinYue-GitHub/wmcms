<?php
/**
* 友链处理器
*
* @version        $Id: link.link.php 2016年5月13日 16:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$conSer = AdminNewClass('system.config');
$table = '@link_link';

//修改分类信息
if ( $type == 'edit' || $type == "add"  )
{
	$data = str::Escape( $post['link'], 'e' );
	$where = $post['id'];
	$data['link_lastintime'] = strtotime($data['link_lastintime']);
	$data['link_lastouttime'] = strtotime($data['link_lastouttime']);
	$data['link_jointime'] = strtotime($data['link_jointime']);

	if ( $data['link_name'] == '' || $data['link_url'] == '' )
	{
		Ajax('对不起，友链标题和回链地址必须填写！',300);
	}
	else if( !str::Number($data['type_id']) )
	{
		Ajax('对不起，友链分类必须选择！',300);
	}
	else if ( !str::IsUrl($data['link_url']) )
	{
		Ajax('对不起，回链格式错误！',300);
	}

	//友链名字检查
	$wheresql['table'] = $table;
	$wheresql['where']['link_id'] = array('<>',$where['link_id']);
	$wheresql['where']['link_name'] = $data['link_name'];
	if ( wmsql::GetCount($wheresql) > 0 )
	{
		Ajax('对不起，该友链已经存在！',300);
	}
	
	//新增数据
	if( $type == 'add' )
	{
		$info = '恭喜您，友链添加成功！';
		$where['link_id'] = wmsql::Insert($table, $data);
		
		//写入操作记录
		SetOpLog( '新增了友链'.$data['link_name'] , 'link' , 'insert' , $table , $where , $data );
	}
	//修改分类
	else
	{
		$info = '恭喜您，友链修改成功！';
		wmsql::Update($table, $data, $where);
		
		//写入操作记录
		SetOpLog( '修改了友链'.$data['link_name'] , 'link' , 'update' , $table , $where , $data );
	}

	//写入自定义字段
	$fieldArr['module'] = $curModule;
	$fieldArr['option'] = GetKey($post,'field');
	$fieldArr['tid'] = $data['type_id'];
	$fieldArr['cid'] = $where['link_id'];
	$fieldArr['ft'] = '2';
	$conSer->SetFieldOption($fieldArr);
	
	Ajax($info);
}
//删除数据和永久删除数据
else if ( $type == 'del' )
{
	$where['link_id'] = GetDelId();
	//写入操作记录
	SetOpLog( '删除了友链' , 'link' , 'delete' , $table , $where);
	wmsql::Delete($table , $where);
	
	Ajax('友链删除成功!');
}
//审核数据
else if ( $type == 'status' )
{
	$data['link_status'] = Request('status');
	$where['link_id'] = GetDelId();

	if( Request('status') == '1')
	{
		$msg = '审核通过';
	}
	else
	{
		$msg = '取消审核';
	}
	//写入操作记录
	SetOpLog( $msg.'了友链' , 'link' , 'update' , $table , $where);
	
	wmsql::Update($table, $data, $where);
	Ajax('友链'.$msg.'成功!');
}
//移动数据
else if ( $type == 'move' )
{
	$data['type_id'] = Request('tid');
	$where['link_id'] = GetDelId();

	//写入操作记录
	SetOpLog( '移动了友链' , 'link' , 'update' , $table , $where);
	
	wmsql::Update($table, $data, $where);
	Ajax('友链移动成功!');
}
//属性操作
else if ( $type == 'attr' )
{
	$data['link_'.$post['attr']] = $post['val'];
	$where['link_id'] = GetDelId();
	
	switch($post['attr'])
	{
		case "rec":
			$msg = "推荐了友链！";
			break;
			
		case "show":
			$msg = "设置显示成功！";
			break;
		  
		case "fixed":
			$msg = "固链了友链!";
			break;
	}

	//写入操作记录
	SetOpLog( $msg, 'link' , 'update' , $table , $where);

	wmsql::Update($table, $data, $where);
	Ajax($msg);
}
//seo信息检查
else if ( $type == 'checkseo' )
{
	$httpSer = NewClass('http');
	$domain = Request('domain');
	
	$html = $httpSer->GetUrl('http://seo.chinaz.com/?m=&host='.$domain);

	$data['domain'] = $domain;
	$data['img'] = str::GetBetween('<span>百度权重：<\/span><a href="{a}src="{*}"', $html);

	Ajax( null , null , $data );
}
//回链检查
else if ( $type == 'checkback' )
{
	$httpSer = NewClass('http');
	$domain = strtolower(Request('domain'));
	$html = $httpSer->GetUrl($domain);

	//默认没有回链
	$data['domain'] = $domain;
	$data['back'] = 0;
	if( str_replace(WEB_URL, '', $html) != $html )
	{
		$data['back'] = 1;
	}
	
	Ajax(null,null,$data);
}
?>
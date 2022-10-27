<?php
/**
* 小说分卷处理器
*
* @version        $Id: novel.volume.php 2016年4月28日 11:37  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$table = '@novel_volume';

//修改分类信息
if ( $type == 'edit' || $type == "add"  )
{
	//小说数据
	$data = str::Escape( $post['volume'], 'e' );
	//条件
	$where['volume_id'] = $post['volume_id'];
	
	//小说名字检查
	$wheresql['table'] = '@novel_novel';
	$wheresql['where']['novel_name'] = $post['name'];
	$novelList = wmsql::GetAll($wheresql);
	//不存在小说
	if( count($novelList) == 0 )
	{
		Ajax('对不起，该小说不存在！',300);
	}
	//只有一条小说
	else if( count($novelList) == 1 )
	{
		$novelData = $novelList[0];
	}
	//小说大于一本，并且作者为空
	else if( GetKey($post,'author') == '' )
	{
		Ajax('对不起，存在同名小说，您必须同时输入小说作者才能添加分卷！',300);
	}
	else
	{
		foreach ($novelList as $k=>$v)
		{
			if($v['novel_author']==$post['author'])
			{
				$novelData = $novelList[$k];
				break;
			}
		}
		if( empty($novelData) )
		{
			Ajax('对不起，当前小说不存在该作者，请检查是否输入错误！',300);	
		}
	}
	//设置分卷的小说id
	$data['volume_nid'] = $novelData['novel_id'];
	
	//分卷检查
	unset($wheresql);
	$wheresql['table'] = $table;
	$wheresql['where']['volume_id'] = array('<>',$where['volume_id']);
	$wheresql['where']['volume_name'] = $data['volume_name'];
	$wheresql['where']['volume_nid'] = $novelData['novel_id'];
	if ( wmsql::GetCount($wheresql) > 0 )
	{
		Ajax('对不起，该小说的分卷名已经存在！',300);
	}
	
	
	//新增数据
	if( $type == 'add' )
	{
		$data['volume_time'] = time();
		
		$info = '恭喜您，小说分卷添加成功！';
		$where['volume_id'] = wmsql::Insert($table, $data);
		
		//写入操作记录
		SetOpLog( '新增了小说分卷'.$data['volume_name'] , 'novel' , 'insert' , $table , $where , $data );
	}
	//修改分类
	else
	{
		wmsql::Update($table, $data, $where);
		$info = '恭喜您，小说分卷修改成功！';

		//写入操作记录
		SetOpLog( '修改了小说分卷'.$data['volume_name'] , 'novel' , 'update' , $table , $where , $data );
	}
	
	Ajax($info);
}
//删除数据
else if ( $type == 'del' )
{
	$where['volume_id'] = GetDelId();
	//写入操作记录
	SetOpLog( '删除了小说分卷' , 'novel' , 'delete' , $table , $where);
	wmsql::Delete($table , $where);

	Ajax('小说分卷删除成功!');
}
//获取分卷列表
else if ( $type == 'getvolume' )
{
	$where['table'] = $table;
	$where['where'] = Request();
	$where['order'] = 'volume_order desc';
	$data = wmsql::GetAll($where);
	Ajax('成功!',null,$data);
}
?>
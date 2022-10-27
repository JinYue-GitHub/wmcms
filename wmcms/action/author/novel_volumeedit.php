<?php
/**
* 新增/编辑分卷操作处理
*
* @version        $Id: novel_volumeedit.php 2016年12月25日 10:37  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

$authorMod = NewModel('author.author');
$volumeMod = NewModel('novel.volume');
$novelMod = NewModel('novel.novel');

//是否是作者
$author = $authorMod->CheckAuthor($lang['user']['no_login'] , $lang['author']['author_no'] , $ajax);

//参数获取
$vid = str::Int( Request('vid') , $lang['author']['novel_vid_err'] );
//如果小说id为0就提示错误
$nid = str::Int( Request('nid') , $lang['author']['novel_nid_err'] );
str::CheckElse($nid, 0 , $lang['author']['novel_nid_err']);

$order = str::Int( Request('order') );
$desc = Post('desc');
//分卷名字
$name = str::IsEmpty( Post('name') , $lang['author']['volume_name_err'] );
str::CheckLen( $name , '1,20' , $lang['author']['volume_name_len_err'] );


//检查小说是否存在
$where['novel_id'] = $nid;
$where['author_id'] = $author['author_id'];
$novelData = $novelMod->GetOne($where);
//小说不存在
if( !$novelData )
{
	ReturnData($lang['author']['novel_nid_err'] , $ajax);
}
else
{
	//设置公共数据
	$data['volume_name'] = $name;
	$data['volume_desc'] = $desc;
	$data['volume_order'] = $order;
	
	//新增分卷
	if( $vid == 0 )
	{
		$data['volume_nid'] = $nid;
		$data['volume_time'] = time();
	
		//插入数据
		$result = $volumeMod->Insert($data);
		if( $result )
		{
			ReturnData( $lang['author']['operate']['novel_volume_add']['success'] , $ajax , 200);
		}
		else
		{
			ReturnData( $lang['author']['operate']['novel_volume_add']['fail'] , $ajax);
		}
	}
	//编辑分卷
	else
	{
		//查询分卷是否存在
		$where['novel_id'] = $nid;
		$where['volume_id'] = $vid;
		$where['author_id'] = $author['author_id'];
		$volumeData = $volumeMod->GetOne($where);
		//数据不存在
		if( !$volumeData )
		{
			ReturnData( $lang['author']['volume_nid_err'] , $ajax);
		}
		else
		{
			//修改数据
			$result = $volumeMod->Update($data ,$vid);
			if( $result )
			{
				ReturnData( $lang['author']['operate']['novel_volume_edit']['success'] , $ajax , 200);
			}
			else
			{
				ReturnData( $lang['author']['operate']['novel_volume_edit']['fail'] , $ajax);
			}
		}
	}	
}
?>
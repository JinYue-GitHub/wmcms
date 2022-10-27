<?php
/**
* 获得子内容的评论列表
*
* @version        $Id: subset_list.php 2021年08月21日 08:57  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

//获取指定的参数
$module = str::IsEmpty( Request('module') , $lang['replay']['module_empty'] );
$cid = str::Int( Request( 'cid/i' ) );
$sid = str::Int( Request( 'sid/i' ) );

//数据模型
$replayMod = NewModel('replay.replay');
$where['table'] = $replayMod->table;
$where['field'] = 'replay_id,replay_segment_id,replay_content,replay_uid,u.user_head';
$where['left']['@user_user as u'] = 'replay_uid=u.user_id';
$where['where']['replay_module'] = $module;
$where['where']['replay_subset_id'] = $sid;
$where['where']['replay_cid'] = $cid;
$where['where']['replay_status'] = '1';
$where['order'] = 'replay_segment_id asc';
$data = wmsql::GetAll($where);
$result = array();
$count = 0;
if( $data )
{
	$data = $replayMod->Filter($data,'');
	foreach ($data as $val)
	{
		$result[$val['replay_segment_id']][] = array(
				'replay_content'=>$val['replay_content'],
				'user_head'=>$val['user_head'],
		);
		$count++;
	}
}

ReturnData( null , true , 200 , array('list'=>$result,'count'=>$count) );
?>
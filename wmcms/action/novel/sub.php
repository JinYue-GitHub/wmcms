<?php
/**
* 小说、章节订阅处理器
*
* @version        $Id: sub.php 2017年3月22日 19:58  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

//判断用户是否登录了
$uid = user::GetUid();
str::EQ( $uid , 0 , $lang['user']['no_login']['info'], $ajax );
//接受参数
$st = str::Int( Request('st' , 1) );
$stArr = array('1'=>'number','2'=>'all','3'=>'month');
$nid = str::Int( Request('nid') , $lang['system']['par']['err']);
$cid = str::Int( Request('cid') );
$auto = str::Int( Request('auto') );

//查询内容是否存在
$novelData = $tableSer->GetData('novel',$nid);
if( !$novelData )
{
	ReturnData( $lang['system']['content']['no'] , $ajax );
}
//小说是上架的
else if($novelData['novel_sell'] == 1)
{
	$subMod = NewModel('novel.sublog');
	$data['nid'] = $nid;
	$data['cid'] = $cid;
	$data['st'] = $st;
	$data['uid'] = $uid;
	$data['aid'] = $novelData['author_id'];
	$data['sid'] = $novelData['novel_sign_id'];
	$data['copy'] = $novelData['novel_copyright'];
	$data['gold1'] = user::GetGold1();
	$data['gold2'] = user::GetGold2();
	$data['auto'] = $auto;
	$data['form'] = 'sub';
	$result = $subMod->Sub($data);

	switch ($result)
	{
		case '201':
			ReturnData( $lang['novel']['action']['sell_'.$stArr[$st].'_no'] , $ajax );
			break;
		//金币不足
		case '202':
			$msg = tpl::Rep(
				array('金币'.$novelConfig['buy_gold_type'].'名字'=>$userConfig['gold'.$novelConfig['buy_gold_type'].'_name']),
				$lang['user']['gold'.$novelConfig['buy_gold_type'].'_no']
			);
			ReturnData( $msg , $ajax );
			break;
	}
	
	//返回信息
	ReturnData( $lang['novel']['operate']['sub']['success'] , $ajax , 200);
}
?>
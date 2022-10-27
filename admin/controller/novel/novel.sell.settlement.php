<?php
/**
* 小说结算统计控制器
*
* @version        $Id: novel.sell.settlement.php 2018年9月06日 20:12  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$welfareMod = NewModel('novel.welfare');
$novelMod = NewModel('novel.novel');
$chapterMod = NewModel('novel.chapter');
$applyMod = NewModel('finance.finance_apply');
$novelConfig = GetModuleConfig('novel');
$userConfig = GetModuleConfig('user');
//财务配置
$financeConfig = GetModuleConfig('finance' , true);

$goldName = $userConfig['gold'.$novelConfig['buy_gold_type'].'_name'];

//接受数据
$nid = intval(Get('nid'));
$year = Get('year');
$month = Get('month');
if( $year=='' || $month == '' )
{
	$year = date('Y');
	$month = date('n');
}
if( $month < 10 )
{
	$month = '0'.$month;
}

//小说数据
$novelData = $novelMod->GetOne($nid);

if( $novelData )
{
	//设置默认数据
	//出勤天数
	$fullCount = 0;
	//本月更新章节数量、更新字数
	$updateChapterCount = 0;
	$updateChapterNumber = 0;
	//本月更新VIP章节数量、更新VIP字数
	$updateChapterVipCount = 0;
	$updateChapterVipNumber = 0;
	//满足的奖励字数和金币2
	$finishNowNumer = 0;
	$finishNowGold2 = 0;
	//满足考勤奖励的天数和金币2
	$fullNowDay = 0;
	$fullNowGold2 = 0;
	//满足考勤奖励的天数和金币2
	$updateNowNumber = 0;
	$updateNowGold2 = 0;
	$updateLowDay = 0;
	
	//获得本月是否结算
	$applyData = $applyMod->GetByMonth($year.$month,'novel',$nid);

	//获得本月更新章节数量
	$where['field'] = "FROM_UNIXTIME(`chapter_time`,'%Y%m%d') AS `day`,chapter_number,chapter_ispay";
	$where['left']['@novel_content'] = 'chapter_cid=content_id';
	$where['where']['chapter_nid'] = $novelData['novel_id'];
	$where['where']['chapter_status'] = '1';
	$where['where']['chapter_time'] = array('string',"FROM_UNIXTIME(`chapter_time`,'%Y%m') = {$year}{$month}");
	$chapterList = $chapterMod->GetList($where);
	//存在章节再进行重新设置
	if( $chapterList )
	{
		$fullList = array();
		foreach ($chapterList as $k=>$v)
		{
			$fullList[$v['day']] = $v['day'];
			//设置更新字数
			$updateChapterNumber += $v['chapter_number'];
			//设置vip章节
			if( $v['chapter_ispay'] == 1 )
			{	
				$updateChapterVipCount++;
				$updateChapterVipNumber += $v['chapter_number'];
			}
		}
		//设置真实考勤天数
		$fullCount = count($fullList);
		//设置真实更新章节数量
		$updateChapterCount = count($chapterList);
	}
	
	//福利数据
	$welfareData = $welfareMod->GetByNid($nid);
	//存在福利
	if( $welfareData )
	{
		//存在完本福利
		if( isset($welfareData['welfare_finish']['where']) )
		{
			//完本最低字数和最低的奖励金币2
			$finishLowNumer = $welfareData['welfare_finish']['where'][0]*10000;
			$finishLowGold2 = $welfareData['welfare_finish']['val'][0];
			//对数组进行倒序
			$welfareData['welfare_finish']['where'] = array_reverse($welfareData['welfare_finish']['where']);
			$welfareData['welfare_finish']['val'] = array_reverse($welfareData['welfare_finish']['val']);
			foreach( $welfareData['welfare_finish']['where'] as $k=>$v)
			{
				if( $novelData['novel_wordnumber'] >= $v*10000 )
				{
					//满足的奖励字数和金币2
					$finishNowNumer = $v*10000;
					$finishNowGold2 = $welfareData['welfare_finish']['val'][$k];
					break;
				}
			}
		}
	
		//存在考勤福利
		if( isset($welfareData['welfare_full']['where']) )
		{
			//考勤最低满足天数和金币2
			$fullLowDay = $welfareData['welfare_full']['where'][0];
			$fullLowGold2 = $welfareData['welfare_full']['val'][0];
			//对数组进行倒序
			$welfareData['welfare_full']['where'] = array_reverse($welfareData['welfare_full']['where']);
			$welfareData['welfare_full']['val'] = array_reverse($welfareData['welfare_full']['val']);
			foreach( $welfareData['welfare_full']['where'] as $k=>$v)
			{
				//考勤天数大于当前数字或者考勤天数大于本月天数，并且考勤天数大于本月的天数
				if( $fullCount >= $v || ($v >= date('t') && $fullCount >= date('t')) )
				{
					//满足的奖励字数和金币2
					$fullNowDay = $v;
					$fullNowGold2 = $welfareData['welfare_full']['val'][$k];
					break;
				}
			}
		}

		//存在更新奖励
		if( isset($welfareData['welfare_update']['where']) )
		{
			//考勤最低满足天数和金币2
			$updateLowDay = $welfareData['welfare_update']['where'][0]*10000;
			$updateLowGold2 = $welfareData['welfare_update']['val'][0];
			//对数组进行倒序
			$welfareData['welfare_update']['where'] = array_reverse($welfareData['welfare_update']['where']);
			$welfareData['welfare_update']['val'] = array_reverse($welfareData['welfare_update']['val']);
			foreach( $welfareData['welfare_update']['where'] as $k=>$v)
			{
				//考勤天数大于当前数字或者考勤天数大于本月天数，并且考勤天数大于本月的天数
				if( $updateChapterNumber >= $v*10000 )
				{
					//满足的奖励字数和金币2
					$updateNowNumber = $v*10000;
					$updateNowGold2 = $welfareData['welfare_update']['val'][$k];
					break;
				}
			}
		}
	}

	//总计收入和真实收入
	$total = $finishNowGold2 + $fullNowGold2 + $updateNowGold2;
	$real = $total;
}
else
{
	die();
}
?>
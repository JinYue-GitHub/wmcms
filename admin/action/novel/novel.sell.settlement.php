<?php
/**
* 小说结算申请处理器
*
* @version        $Id: novel.sell.settlement.php 2018年9月8日 12:25  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$novelMod = NewModel('novel.novel');
$applyMod = NewModel('finance.finance_apply');
$authorMod = NewModel('author.author');

//处理结算申请操作
if ( $type == "apply"  )
{
	//申请数据
	$nid = $post['nid'];
	$total = $post['total'];
	$real = $post['real'];
	$year = $post['year'];
	$month = $post['month'];
	$bonusGold2 = GetKey($post,'bonus_gold2');
	$bonusRemark = GetKey($post,'bonus_remark');
	$deductGold2 = GetKey($post,'deduct_gold2');
	$deductRemark = GetKey($post,'deduct_remark');

	$novelData = $novelMod->GetOne($nid);
	$applyData = $applyMod->GetByMonth($year.$month,'novel',$nid);
	
	if( !$novelData )
	{
		Ajax('对不起，小说不存在！',300);
	}
	else if( $novelData['novel_copyright'] < 1 || $novelData['novel_sell'] < 1 )
	{
		Ajax('对不起，请先签约上架小说！',300);
	}
	else if($real != $total+$bonusGold2-$deductGold2 )
	{
		Ajax('结算金额出错！',300);
	}
	else if( $applyData && $applyData['apply_status'] == 0 )
	{
		Ajax('此月的结算申请正在处理中，请等待财务审核！',300);
	}
	else if( $applyData && $applyData['apply_status'] == 1 )
	{
		Ajax('此月已经结算过了，无需重复申请！',300);
	}
	else
	{
		$tUid = $authorMod->GetUidByAid($novelData['author_id']);
		
		//条件
		$where['apply_module'] = 'novel';
		$where['apply_month'] = $year.$month;
		$where['apply_cid'] = $nid;
		
		//数据
		$data = $where;
		$data['apply_manager_id'] = Session('admin_id');
		$data['apply_total'] = $total;
		$data['apply_bonus'] = $bonusGold2;
		$data['apply_bonus_remark'] = $bonusRemark;
		$data['apply_deduct'] = $deductGold2;
		$data['apply_deduct_remark'] = $deductRemark;
		$data['apply_bonus'] = $bonusGold2;
		$data['apply_remark'] = '小说《'.$novelData['novel_name'].'》 '.$year.'-'.$month.' 的财务结算申请！';
		$data['apply_to_user_id'] = $tUid;
		$data['apply_real'] = $real;
		$applyMod->Insert($data);
		
		//写入操作记录
		SetOpLog( '申请了小说结算' , 'finance' , 'insert' , '@finance_apply' , $where , $data );
		Ajax('结算申请成功！');
	}
}
?>
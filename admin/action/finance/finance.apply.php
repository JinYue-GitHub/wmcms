<?php
/**
* 财务申请处理器
*
* @version        $Id: finance.apply.php 2018年9月9日 9:19  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$applyTable = '@finance_apply';
$applyMod = NewModel('finance.finance_apply');
//财务配置
$financeConfig = GetModuleConfig('finance' , true);
//作者配置
$authorConfig = GetModuleConfig('author');

//删除记录
if ( $type == 'del'  )
{
	$where['apply_id'] = GetDelId();
	//写入操作记录
	SetOpLog( '删除了财务申请！' , 'finance' , 'delete' , $applyTable , $where);
	wmsql::Delete($applyTable , $where);
	
	Ajax('财务申请批量删除成功!');
}
//清空数据记录
else if ( $type == 'clear')
{
	SetOpLog( '清空了所有财务申请！' , 'finance' , 'delete' , $applyTable);
	wmsql::Delete($applyTable);
	Ajax('财务申请全部清空成功！');
}
//审核操作
else if ( $type == 'status')
{
	$id = $post['id'];
	$status = $post['status'];
	$remark = $post['remark'];
	$data = $applyMod->GetById($id);
	if( $data['apply_status'] != '0' )
	{
		Ajax('对不起，该财务申请已经处理过了！',300);
	}
	else
	{
		//财务申请模式
		$applyMode = 1;
		//如果存在设置并且模式为，不转入作者账户
		if( isset($authorConfig['author_finance_apply_type']) && $authorConfig['author_finance_apply_type']=='2' )
		{
			$applyMode = 2;
		}
		$where['apply_id'] = $id;
		$saveData['apply_status'] = $status;
		$saveData['apply_real_money'] = $applyMode==2?$data['apply_real']/$financeConfig['rmb_to_gold2']:0;
		$saveData['apply_handle_manager_id'] = Session('admin_id');
		$saveData['apply_handle_remark'] = $remark;
		$saveData['apply_handle_time'] = time();
		$applyMod->Update($saveData,$id);
		//指向用户id，并且是通过状态
		if( $data['apply_to_user_id'] > '0' && $status == '1')
		{
			//插入资金变更记录
			$userMod = NewModel('user.user');
			$logData['module'] = 'system';
			$logData['type'] = 'finance_apply';
			$logData['cid'] = $data['apply_cid'];
			$logData['remark'] = $data['apply_remark'];
			//不存在销售统计财务申请模式设置或者模式为1
			if( $applyMode==1 )
			{
				$userMod->CapitalChange( $data['apply_to_user_id'] , $logData , 0 , $data['apply_real'] );
			}

			//发送系统消息
			$userConfig = AdminInc('user');
			$msgMod = NewModel('user.msg');
			$msg = $data['apply_month'].'财务结算！<br/>结算总额：'.$data['apply_total'].$userConfig['gold2_name'].'，实际到账：'.$data['apply_real'].$userConfig['gold2_name'].'<br/>';
			if( $data['apply_bonus'] > '0' )
			{
				$msg .= '其中额外奖金：'.$data['apply_bonus'].$userConfig['gold2_name'].'，原因：'.$data['apply_bonus_remark'].$userConfig['gold2_name'].'<br/>';
			}
			if( $data['apply_deduct'] > '0' )
			{
				$msg .= '其中扣除奖金：'.$data['apply_deduct'].$userConfig['gold2_name'].'，原因：'.$data['apply_deduct_remark'].$userConfig['gold2_name'].'<br/>';
			}
			$msgMod->Insert($data['apply_to_user_id'] , $msg);
		}
		Ajax('财务申请处理成功！');
	}
}
?>
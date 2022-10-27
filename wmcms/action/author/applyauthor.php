<?php
/**
* 申请作者操作处理
*
* @version        $Id: apply.php 2016年12月19日 20:21  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

//笔名检查
$nickname = str::IsEmpty( Post('nickname') , $lang['author']['nickname_no'] );
//账号长度和账号格式
str::CheckLen( $nickname , '2,12' , $lang['author']['nickname_len']  );
//只能是字母和数字组合
str::LNC( $nickname, $lang['author']['nickname_err'] );

//姓名检查
$realName = str::LNC( Post('realname'), $lang['system']['par']['name_err'] , '2,10' );
//身份证检查
$cardId = str::CheckCardId( Post('cardid'), $lang['system']['par']['cardid_err'] );
//住址检查
$address = str::DelHtml(str::IsEmpty( Post('address'), $lang['system']['par']['address_err']));
//协议检查
if( !str::IsTrue( Post('agreement') , 1) )
{
	ReturnData( $lang['author']['apply_author_agreement_err'] , $ajax );
}

//查询账号是否被注册
$authorMod = NewModel('author.author');
$applyMod = NewModel('system.apply' , 'author');

$authorData = $authorMod->GetAuthor();
//获得作者的审核状态
if( is_array($authorData) && $authorData['author_status'] == 0 )
{
	ReturnData( $lang['author']['apply_author_status_0'] , $ajax );
}
//已经是作者了
else if( is_array($authorData) )
{
	ReturnData( $lang['author']['apply_author_exist'] , $ajax );
}
//笔名被注册了
else if( !$authorMod->CheckNickName( $nickname , $uid) )
{
	ReturnData( $lang['author']['nickname_exist'] , $ajax );
}
else
{
	//动态数据验证
	$data = $_POST;
	$data['uid'] = $uid;
	$result = $authorMod->ApplyAuthor($data , $authorData);
	//申请作者的默认状态
	$status = $authorConfig['apply_author_status'];
	if( $result )
	{
		//插入申请记录信息
		$applyData['apply_uid'] = $data['uid'];
		$applyData['apply_cid'] = $result;
		$applyData['apply_status'] = $status;
		$applyData['apply_module'] = 'author';
		$applyData['apply_type'] = 'apply';
		$applyData['apply_remark'] =  $lang['author']['apply_remark_'.$status];
		$applyMod->Insert($applyData , 0);
		
		//修改财务信息
		$financeData['finance_realname'] = $realName;
		$financeData['finance_cardid'] = $cardId;
		$financeData['finance_address'] = $address;
		$financeMod = NewModel('user.finance');
		$financeMod->UpdateFinance($financeData);

		//插入作者经验值
		$expMod = NewModel('author.exp');
		$expMod->Insert($result , 'novel');
		
		//默认注册状态
		$returnData['status'] = $status;
		//默认提示
		$info = GetInfo($lang['author']['operate']['apply_author_'.$status] , 'author_index');
		ReturnData(  $info , $ajax , 200 , $returnData);
	}
	else
	{
		ReturnData(  $lang['author']['operate']['apply_author_'.$status]['fail'] , $ajax);
	}
}
?>
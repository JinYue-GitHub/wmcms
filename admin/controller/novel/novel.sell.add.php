<?php
/**
* 小说签约控制器文件
*
* @version        $Id: novel.sell.add.php 2017年3月12日 13:17  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$userConfig = GetModuleConfig('user');
$novelConfig = GetModuleConfig('novel');
$goldName = $userConfig['gold'.$novelConfig['buy_gold_type'].'_name'];

$novelMod = NewModel('novel.novel');
$sellMod = NewModel('novel.sell');
$signMod = NewModel('author.sign');

//小说数据
$data = $novelMod->GetOne(Get('nid'));
if( $type == '' )
{
	$type = 'add';
}

//小说签约等级数据
$signData = $signMod->GetOne($data['novel_sign_id']);
if( $signData )
{
    //小说上架信息
    $sellData = $sellMod->GetLastOne($data['novel_id']);
    if( $sellData && $sellData['sell_status'] == 1)
    {
    	$type = 'remove';
    	$sellType = explode(',',$sellData['sell_type']);
    }
    else
    {
    	$sellData['sell_number'] = $signData['sign_gold'.$novelConfig['buy_gold_type']];
    	$sellData['sell_all'] = 0;
    	$sellData['sell_month'] = 0;
    	$sellType = array(1);
    }
}
?>
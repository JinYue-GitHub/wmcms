<?php
/**
* 小说福利设置控制器文件
*
* @version        $Id: novel.sell.welfare.php 2018年9月2日 11:24  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$welfareMod = NewModel('novel.welfare');
$novelMod = NewModel('novel.novel');
$novelConfig = GetModuleConfig('novel');
$userConfig = GetModuleConfig('user');
$goldName = $userConfig['gold'.$novelConfig['buy_gold_type'].'_name'];

//接受数据
$nid = Get('nid');
$type = 'edit';

//如果id大于0
if($nid > 0)
{
	$data = $welfareMod->GetByNid($nid);
	if( !$data )
	{
		$data['welfare_id'] = 0;
		$data['welfare_nid'] = $nid;
		$data["welfare_type"]["sub"] = 1;
		$data["welfare_type"]["prop"] = 1;
		$data["welfare_type"]["reward"] = 1;
	}
}
else
{
	die();
}

//小说数据
$novelData = $novelMod->GetOne($data['welfare_nid']);
?>
<?php
/**
* 小说签约处理器
*
* @version        $Id: novel.sign.php 2017年3月12日 15:31  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$conSer = AdminNewClass('system.config');
$novelMod = NewModel('novel.novel');
$sellMod = NewModel('novel.sell');

$curModule = 'novel';
$table = '@novel_sell';
$nid = $post['data']['sell_novel_id'];
$novelData = $novelMod->GetOne($nid);
if( !$novelData )
{
	Ajax('对不起，小说不存在！',300);
}
else if($novelData['novel_sign_id'] < 1 & $novelData['novel_copyright'] < 1)
{
	Ajax('对不起，小说上架前请先签约！',300);
}


//上架操作
if ( $type == "add"  )
{
	$data = $post['data'];
	if( !isset($data['sell_type']) )
	{
		Ajax('对不起，请至少选择一种收费方式！',300);
	}
	else
	{
		foreach ($data['sell_type'] as $k=>$v)
		{
			if($v == 1 && $data['sell_number'] == '' )
			{
				Ajax('对不起，请输入千字价格！',300);
			}
			else if($v == 2 && $data['sell_all'] == '' )
			{
				Ajax('对不起，请输入全本价格！',300);
			}
			else if($v == 3 && $data['sell_month'] == '' )
			{
				Ajax('对不起，请输入包月价格！',300);
			}
		}
	}
	$data['sell_type'] = implode(',', $data['sell_type']);

	$sellData = $sellMod->GetLastOne($nid);
	if( $sellData && $sellData['sell_status'] == 1)
	{
		Ajax('对不起，该小说已经上架了！',300);
	}
	else
	{
		//插入签约数据
		$where['sell_id'] = $sellMod->Insert($data);
		//修改小说签约数据
		$sellMod->SetNovelSell($nid);
		
		//写入操作记录
		SetOpLog( '小说《'.$novelData['novel_name'].'》上架成功！' , $curModule , 'insert' , $table , $where , $data );
		Ajax('恭喜您，小说上架成功！');
	}
}
else if($type == 'remove')
{
	$sellData['sell_novel_id'] = $novelData['novel_id'];
	$sellData['sell_status'] = 0;
	$where['sell_id'] = $sellMod->Insert($sellData);
	//写入操作记录
	SetOpLog( '小说《'.$novelData['novel_name'].'》下架成功！' , $curModule , 'insert' , $table , $where , $sellData );
	//修改小说签约数据
	$sellMod->SetNovelSell($nid , 0);
	Ajax('恭喜您，小说下架成功！');
}
?>
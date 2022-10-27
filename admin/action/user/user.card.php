<?php
/**
* 用户卡号处理器
*
* @version        $Id: user.card.php 2017年3月27日 18:25  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$table = '@user_card';

//添加卡号操作
if ( $type == 'create'  )
{
	//卡号类型
	$cType = Post('type');
	//生成条数
	$number = Post('number');
	//发布渠道
	$channel = Post('channel');
	//开头类型
	$start = Post('start');
	//开头字符串
	$startStr = Post('start_str');
	//卡号长度
	$length = Post('length');
	
	
	//充值卡，卡号金额
	$money = Post('money');
	$give  = str::Int(Post('give'));
	
	if( $cType == '' )
	{
		Ajax('对不起，请选择卡号类型！' , 300);
	}
	else if( $number == '' )
	{
		Ajax('对不起，请输入生成数量！' , 300);
	}
	else if( $channel == '' )
	{
		Ajax('对不起，请输入发布渠道！' , 300);
	}
	else if( $start == '1' && $startStr == '')
	{
		Ajax('对不起，请输入指定开头字符串！' , 300);
	}
	else if( $cType == 1 && $money == '' )
	{
		Ajax('对不起，请输入卡号金额！' , 300);
	}
	
	
	$apiKey = $C['config']['api']['system']['api_apikey'];
	$pre = $C['config']['db']['prefix'];
	$txtContent = $sqls = '';
	//循环生成的数量
	for ($i=1 ; $i <= $number ; $i++ )
	{
		//判断加密字符串的长度
		if( $length == '16' )
		{
			$card = md5_16(str::RandStr(16,'all',$startStr).$apiKey.time().GetMtime());
		}
		else
		{
			$card = md5(str::RandStr(32,'all',$startStr).$apiKey.time().GetMtime());
		}
		
		if( $start == 1 )
		{
			$replace = substr($card, 0, strlen($startStr));
			$card = str_replace($replace, $startStr, $card);
		}
		
		$txtContent .= $card."\r\n";
		$sqls .= 'insert into '.$pre.'user_card(card_type,card_channel,card_key,card_money,card_give,card_addtime) values(\''.$cType.'\',\''.$channel.'\',\''.$card.'\',\''.$money.'\',\''.$give.'\','.time().');';
	}

	//插入数据库
	wmsql::Exec($sqls);
	//生成文件
	$fileName = WMFILE.'card/'.date('Ymd').'/'.md5(time()).'.txt';
	file::CreateFile($fileName, $txtContent);
	
	//写入操作记录
	$info = '恭喜您，卡号生成成功！';
	SetOpLog( '批量生成了'.$number.'组卡号' , 'user' , 'update' , $table);
	
	Ajax($info , 200 , array('file'=>$fileName));
}
//删除数据
else if ( $type == 'del')
{
	$where['card_id'] = GetDelId();
	//写入操作记录
	SetOpLog( '删除了卡号！' , 'user' , 'delete' , $table , $where);
	wmsql::Delete($table , $where);
	
	Ajax('卡号批量删除成功!');
}
//清空数据
else if ( $type == 'clear')
{
	//写入操作记录
	SetOpLog( '清空了所有卡号！' , 'user' , 'delete' , $table);
	wmsql::Delete($table);
	Ajax('卡号全部清空成功！');
}
?>
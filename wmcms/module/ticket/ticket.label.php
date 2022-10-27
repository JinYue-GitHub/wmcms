<?php
/**
* 小说票类标签处理类
*
* @version        $Id: ticket.class.php 2022年03月20日 10:10  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
class ticketLabel extends ticket
{
	static public $lcode;
	static public $data;
	static public $CF = array('ticket'=>'GetData');
	
	function __construct()
	{
		self::PublicLabel();
	}
	
	/**
	* 标签公共标签替换
	**/
	static function PublicLabel()
	{
		$repFun['a']['ticketLabel'] = 'PublicTicket';
		tpl::Label('{推荐贡献:[s]}[a]{/推荐贡献}','ticket', self::$CF, $repFun['a']);
		$repFun['a']['ticketLabel'] = 'PublicTicketRank';
		tpl::Label('{推荐排名:[s]}[a]{/推荐排名}','ticket_rank', self::$CF, $repFun['a']);
		

		if( str::in_string('{推荐排名}') )
		{
			$arr['注册验证码'] = FormCodeCreate('code_user_reg');
		}
	}

	/**
	 * 公共推荐贡献替换
	 * @param 参数1，数组，需要进行操作的数组
	 * @param 参数2，字符串，需要进行替换的标签
	 **/
	static function PublicTicket($data,$blcode)
	{
		$code = '';
		$i = 1;
	
		//循环数据
		foreach ($data as $k => $v)
		{
			//没组数据循环，以字段名为标签名
			foreach ($v as $key => $val)
			{
				$arr1[L.$key]=$v[$key];
			}
			//每次循环重新调取原始标签
			$lcode = $blcode;
			//计数器标签和选中标签替换
			$lcode = tpl::I( $lcode , $i );

			//设置自定义中文标签
			$arr2=array(
				'i'=>$i,
				'推荐贡献票数量'=>$v['log_all'],
				'推荐贡献月票数量'=>$v['log_month'],
				'推荐贡献推荐票数量'=>$v['log_rec'],
				'推荐贡献用户昵称'=>$v['user_nickname'],
				'推荐贡献用户头像'=>$v['user_head'],
			);
			//合并两组标签
			$arr = array_merge($arr1 , $arr2);
			//替换标签
			$code .= tpl::rep($arr,$lcode);
			$i++;
		}
		//返回最后的结果
		return $code;
	}
	

	/**
	 * 公共推荐贡献替换
	 * @param 参数1，数组，需要进行操作的数组
	 * @param 参数2，字符串，需要进行替换的标签
	 **/
	static function PublicTicketRank($data,$blcode)
	{
		//默认数据
		$arr = array(
			'推荐排名名次'=>0,
			'推荐排名距离上级'=>0,
		);
		//排名第一的时候
		if( $data && count($data)==1 )
		{
			$arr['推荐排名名次'] = 1;
		}
		//其他排名
		else if( $data )
		{
			$arr['推荐排名名次'] = count($data);
			$arr['推荐排名距离上级'] = $data[count($data)-2]['rankField']-$data[count($data)-1]['rankField'];
		}
		//替换标签
		$code = tpl::rep($arr,$blcode);
		//返回最后的结果
		return $code;
	}
}
?>
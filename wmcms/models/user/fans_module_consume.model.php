<?php
/**
* 粉丝模块内容消费的模型
*
* @version        $Id: fans_module_consume.model.php 2017年3月18日 21:48  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class Fans_Module_ConsumeModel
{
	private $consumeTable = '@fans_module_consume';
	private $consumeId;
	
	/**
	 * 构造函数
	 */
	function __construct()
	{
	}
	

	/**
	 * 插入模块粉丝信息
	 * @param 参数1，必须，关注的模块
	 * @param 参数2，必须，关注的用户id
	 * @param 参数3，必须，关注的内容id
	 */
	function Insert( $module , $uid , $cid )
	{
		$data['consume_module'] = $module;
		$data['consume_user_id'] = $uid;
		$data['consume_cid'] = $cid;
		return wmsql::Insert($this->consumeTable, $data);
	}
	
	
	/**
	 * 更新模块粉丝经验值
	 * @param 参数1，选填，模块id
	 * @param 参数2，选填，用户id
	 * @param 参数3，必须，模块内容id
	 * @param 参数4，必须，消费的金币1
	 * @param 参数4，必须，消费的金币2
	 */
	function SetConsume( $module , $uid , $cid , $gold1 , $gold2)
	{
		if( $gold1 == '0' && $gold2 == '0' )
		{
			return false;
		}
		else
		{
			//获得粉丝的消费数据
			$consumeData = $this->GetConsume( $module , $uid , $cid );
			$config = C('',null,$module.'Config');
			$exp = $rec = $month = 0;
			$gold1Exp = $consumeData['consume_gold1_exp'] + $gold1;
			$gold2Exp = $consumeData['consume_gold2_exp'] + $gold2;
			$gold1Ticket = $consumeData['consume_gold1_ticket'] + $gold1;
			$gold2Ticket = $consumeData['consume_gold2_ticket'] + $gold2;

			//当前消费的金币1数量是否大于了经验值增加设定值
			if( $gold1Exp >= $config['fans_exp_gold1'] )
			{
				$multiple = 0;
				//粉丝经验值增加
				if( $module == 'novel' )
				{
					//获得倍数
					if( intval($config['fans_exp_gold1']) > 0 )
					{
						$multiple = floor($gold1Exp / $config['fans_exp_gold1']);
					}
					//去除倍数后剩余的当前消费金额
					if( $multiple > 0 )
					{
						$gold1Exp = $gold1Exp - ( $config['fans_exp_gold1'] * $multiple);
					}
					$exp += $multiple;
				}
			}
			//当前消费的金币2数量是否大于了经验值增加设定值
			if( $gold2Exp >= $config['fans_exp_gold2'] )
			{
				$multiple = 0;
				//粉丝经验值增加
				if( $module == 'novel' )
				{
					//获得倍数
					if( intval($config['fans_exp_gold1']) > 0 )
					{
						$multiple = floor($gold2Exp / $config['fans_exp_gold2']);
					}
					//去除倍数后剩余的当前消费金额
					if( $multiple > 0 )
					{
						$gold2Exp = $gold2Exp - ( $config['fans_exp_gold2'] * $multiple);
					}
					$exp += $multiple;
				}
			}

			//当前消费的金币1数量是否大于了票增加设定值
			if( $gold1Ticket >= $config['cons_gold1'] )
			{
				$multiple = 0 ;
				//小说推荐和月票
				if( $module == 'novel' )
				{
					//获得倍数
					if( intval($config['cons_gold1']) > 0 )
					{
						$multiple = floor($gold1Ticket / $config['cons_gold1']);
					}
					//去除倍数后剩余的当前消费金额
					if( $multiple > 0 )
					{
						$gold1Ticket = $gold1Ticket - ( $config['cons_gold1'] * $multiple);
					}
					$rec += $config['cons_gold1_rec'] * $multiple;
					$month += $config['cons_gold1_month'] * $multiple;
				}
			}
			//当前消费的金币2数量是否大于了票增加设定值
			if( $gold2Ticket >= $config['cons_gold2'] )
			{
				$multiple = 0 ;
				//小说推荐和月票
				if( $module == 'novel' )
				{
					//获得倍数
					if( intval($config['cons_gold2']) > 0 )
					{
						$multiple = floor($gold2Ticket / $config['cons_gold2']);
					}
					//去除倍数后剩余的当前消费金额
					if( $multiple > 0 )
					{
						$gold2Ticket = $gold2Ticket - ( $config['cons_gold2'] * $multiple);
					}
					$rec += $config['cons_gold2_rec'] * $multiple;
					$month += $config['cons_gold2_month'] * $multiple;
				}
			}
			
			//修改消费记录增加值
			$where['consume_id'] = $consumeData['consume_id'];
			$data['consume_gold1_exp'] = $gold1Exp;
			$data['consume_gold2_exp'] = $gold2Exp;
			$data['consume_gold1_ticket'] = $gold1Ticket;
			$data['consume_gold2_ticket'] = $gold2Ticket;
			$data['consume_gold1'] = array('+' , $gold1);
			$data['consume_gold2'] = array('+' , $gold2);
			wmsql::Update( $this->consumeTable , $data , $where);
			
			//消费满足后针对不同的模块执行
			switch ($module)
			{
				case 'novel':
					//推荐票增加
					if( $rec > 0 || $month > 0 )
					{
						$ticketMod = NewModel('user.ticket');
						$ticketData['rec'] = $rec;
						$ticketData['month'] = $month;
						$ticketData['remark'] =  '消费累积赠送赠送！';
						$ticketData['module'] =  'novel';
						$ticketData['cid'] =  $cid;
						$ticketMod->Update( $uid , $ticketData);
					}
					//粉丝经验值增加
					if( $exp > 0 )
					{
						$fansMod = NewModel('user.fans_module');
						$fansMod->Update( 'novel' , $uid , $cid , $exp );
					}
					break;
			}
		}
	}
	
	
	/**
	 * 获得当前模块内容的消费记录
	 * @param 参数1，必须，模块id
	 * @param 参数2，必须，用户id
	 * @param 参数3，必须，内容id
	 */
	function GetConsume( $module , $uid , $cid )
	{
		if( $uid > 0 && $cid > 0 )
		{
			$where['table'] = $this->consumeTable;
			$where['where']['consume_module'] = $module;
			$where['where']['consume_user_id'] = $uid;
			$where['where']['consume_cid'] = $cid;
			$data = wmsql::GetOne($where);
			if( !$data )
			{
				$this->Insert($module, $uid, $cid);
				$data = wmsql::GetOne($where);
			}
			return $data;
		}
	}
}
?>
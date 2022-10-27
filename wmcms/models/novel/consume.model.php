<?php
/**
* 小说消费，附加属性更新模型
*
* @version        $Id: consume.model.php 2017年1月19日 9:20  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class ConsumeModel
{
	
	//构造函数
	public function __construct()
	{
	}
	
	
	/**
	 * 对小说消费后执行的属性变更
	 * @param 参数1，必须，修改的内容
	 */
	function Update($data)
	{
		//用户id
		$uid = $data['uid'];
		//小说id
		$nid = $data['nid'];
		//作者id
		$aid = $data['aid'];
		//是否需要分成
		$copy = $data['copy'];
		//签约等级id
		$sign = $data['sign'];
		//消费来源
		$form = $data['form'];
		
		$rec = GetKey($data,'rec');
		$month = GetKey($data,'month');
		$authorExp = GetKey($data,'author_exp');
		$fansExp = GetKey($data,'fans_exp');
		$userExp = GetKey($data,'user_exp');
		$gold1 = $data['gold1'];
		$gold2 = $data['gold2'];
		
		$logType = $data['log_type'];
		$logRemark = $data['log_remark'];

		//用户推荐票更新
		if($rec > 0 || $month > 0)
		{
			$ticketMod = NewModel('user.ticket');
			$ticketData['rec'] = $rec;
			$ticketData['month'] = $month;
			$ticketData['remark'] =  $data['ticket_remark'];
			$ticketData['module'] =  'novel';
			$ticketData['cid'] =  $nid;
			$ticketMod->Update( $uid , $ticketData);
		}
		//作者经验更新
		if( $authorExp > 0 )
		{
			$expMod = NewModel('author.exp');
			$expMod->Update( 'novel' , $aid , $authorExp );
		}
		//小说粉丝经验更新
		if( $fansExp > 0 )
		{
			$fansMod = NewModel('user.fans_module');
			$fansMod->Update( 'novel' , $uid , $nid , $fansExp );
		}
		//用户经验更新
		if( $userExp > 0 )
		{
			$userExpMod = NewModel('user.user_exp');
			$userExpMod->Update( $uid , $userExp );
		}

		//粉丝消费比例增加经验和推荐票
		$fansConsumeMod = NewModel('user.fans_module_consume');
		$fansConsumeMod->SetConsume( 'novel' , $uid , $nid , $gold1 , $gold2);
		
		//作者累积收入比例 , 经验值变更
		if( $aid > 0 )
		{
			$authorIncomeMod = NewModel('author.module_income');
			$authorIncomeMod->SetIncome( 'novel' , $aid , $nid , $gold1 , $gold2);
		}
		
		//查询小说的福利设置
		$welfareMod = NewModel('novel.welfare');
		$welfareData = $welfareMod->GetByNid($nid);
		$isDevide = false;
		//检查是否允许作者获得分成
		if( $welfareData )
		{
			if( $form == 'sub' && GetKey($welfareData,'welfare_type,sub') == '1' )
			{
				$isDevide = true;
			}
			if( $form == 'reward' && GetKey($welfareData,'welfare_type,reward') == '1' )
			{
				$isDevide = true;
			}
			if( $form == 'prop' && GetKey($welfareData,'welfare_type,prop') == '1' )
			{
				$isDevide = true;
			}
		}
		else
		{
			$isDevide = true;
		}
		
		//验证是否需要分成，如果是签约销售并且签约的id大于0
		if( $isDevide == true && $copy == 1 && $sign > 0 && $aid > 0 )
		{
			$signMod = NewModel('author.sign');
			$signData = $signMod->GetOne($sign);
			$devide = explode(':', $signData['sign_divide']);
			//比例正确，并且用户分成比例大于0
			if( count($devide) == 2 && $devide[1] > 0 )
			{
				//作者分成比例
				$authorDevide= $devide[1];
				$authorGold1 = $authorGold2 = 0;
				if( $gold1 > 0 )
				{
					$authorGold1 = $gold1 * ($authorDevide/10);
				}
				if( $gold2 > 0 )
				{
					$authorGold2 = $gold2 * ($authorDevide/10);
				}


				//写入作者的收入信息
				$logData['module'] = 'novel';
				$logData['type'] = $logType;
				$logData['tuid'] = $uid;
				$logData['cid'] = $nid;
				$logData['aid'] = $aid;
				$logData['remark'] = $logRemark;
				$logData['gold1'] = $authorGold1;
				$logData['gold2'] = $authorGold2;
				//写入作者资金变更
				$authorMod = NewModel('author.author');
				$authorMod->CapitalChange($logData);
			} 
		}
		
		return true;
	}
}
?>
<?php
/**
* 订阅日志模型
*
* @version        $Id: sublog.model.php 2017年1月19日 19:12  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class SubLogModel
{
	//分类表
	public $logTable = '@novel_sublog';
	
	//构造函数
	public function __construct()
	{
	}


	/**
	 * 获得小说的小说记录
	 * @param 参数1，必须，小说id
	 * @param 参数2，选填，区间日期
	 */
	function GetByNid( $nid , $between='')
	{
		$where['table'] = $this->logTable;
		$where['where']['log_nid'] = $nid;
		if( !empty($between) )
		{
			$where['where']['log_time'] = array('between',$between);
		}
		return wmsql::GetAll($where);
	}

	/**
	 * 获得小说的区间数据综合
	 * @param 参数1，必须，小说id
	 * @param 参数2，选填，区间日期
	 * @param 参数3，选填，金币类型默认金币2
	 */
	function GetSumByNid( $nid , $between='' , $field='gold2')
	{
		$where['table'] = $this->logTable;
		$where['field'] = 'log_'.$field;
		$where['where']['log_nid'] = $nid;
		if( !empty($between) )
		{
			$where['where']['log_time'] = array('between',$between);
		}
		return wmsql::GetSum($where);
	}
	
	/**
	 * 插入订阅日志
	 * @param 参数1，必须，条件
	 */
	function Insert( $data )
	{
		$data['log_time'] = time();
		return wmsql::Insert($this->logTable, $data);
	}
	

	/**
	 * 删除一条数据
	 */
	function Delete($wheresql)
	{
		if( !is_array($wheresql) )
		{
			$where['log_id'] = $wheresql;
		}
		return wmsql::Delete($this->logTable , $where);
	}
	
	
	/**
	 * 获得订阅类型
	 * @param 参数1，选填
	 */
	function GetSubType($type = '')
	{
		$arr = array(
			'1'=>'单章订阅',
			'2'=>'全本订阅',
			'3'=>'包月订阅'
		);
		
		if( $type != '' )
		{
			return $arr[$type];
		}
		else
		{
			return $arr;
		}
	}
	
	/**
	 * 检查是否订阅了
	 * @param 参数1，必须，订阅的用户id
	 * @param 参数2，必填，小说的id
	 * @param 参数3，选填，小说章节id
	 * @param 参数4，选填，小说的数据
	 */
	function IsSub( $uid , $nid , $cid = '0' , $data = '')
	{
		//本月起始日期
		$beginTime = strtotime(date("Y-m-d"));
		$where['table'] = $this->logTable;
		$where['where']['novel_id'] = array(
			'string',"(log_type = 1 and log_nid = '{$nid}' and log_cid='{$cid}' and log_uid = '{$uid}') or
			(log_type = 2 and log_nid = '{$nid}' and log_uid = '{$uid}') or
			(log_type = 3 and log_nid = '{$nid}' and log_time>'{$beginTime}' and log_uid = '{$uid}')",
		);
		$count = wmsql::GetCount($where,'log_id');

		if( $count > 0 )
		{
			return true;
		}
		else if ($cid > 0 && $data != '' && is_array($data) )
		{
			//如果没有订阅就查询是否自动订阅了。
			$collMod = NewModel('user.coll');
			$count = $collMod->GetCount('novel' , 'sub' ,$nid , $uid);
			if( $count > 0)
			{
				$subData['nid'] = $nid;
				$subData['cid'] = $cid;
				$subData['uid'] = $uid;
				$subData['st'] = 1;
				$subData['auto'] = 0;
				$subData['copy'] = $data['novel_copyright'];
				$subData['aid'] = $data['author_id'];
				$subData['sid'] = $data['novel_sign_id'];
				$subData['gold1'] = user::GetGold1();
				$subData['gold2'] = user::GetGold2();
				if( $this->Sub($subData) === 200 )
				{
					return true;
				}
			}
			return false;
		}
	}
	
	
	/**
	 * 订阅操作
	 */
	function Sub($data)
	{
		//小说id
		$nid = $data['nid'];
		//章节id
		$cid = $data['cid'];
		//签约id
		$sid = $data['sid'];
		//用户id
		$uid = $data['uid'];
		//作者id
		$aid = $data['aid'];
		//订阅类型类型
		$st = $data['st'];
		//是否签约
		$copy = $data['copy'];
		//是否订阅小说
		$auto = $data['auto'];
		
		//获得购买的金币类型
		$goldType = C('buy_gold_type',null,'novelConfig');
		$gold1 = $gold2 = 0;
		if($goldType == 1)
		{
			$userGold = $data['gold1'];
		}
		else
		{
			$userGold = $data['gold2'];
		}
		//根据作者id查询出用户id，
		$authorMod = NewModel('author.author');
		$authorUid = str::GetKey($authorMod->GetAuthor($aid , 2) , 'user_id');

		//获得出售价格信息
		$sellMod = NewModel('novel.sell');
		$sellData = $sellMod->GetNovelSell($nid);
		
		//查询本书的上架信息
		switch ($st)
		{
			//单章出售
			case '1':
				$price = $sellData['sell_number'];
				$chapterMod = NewModel('novel.chapter');
				$chapterData = $chapterMod->GetOne($cid);
				//计算字数价格
				$price = round($chapterData['chapter_number']/1000 * $sellData['sell_number'] , 2);
				$userLogType = 'novel_number_buy';
				$userLogRemark = '小说单章订阅消耗！';
				$authorLogType = 'novel_number_income';
				$authorLogRemark = '小说单章订阅收入！';
				break;
		
				//全本出售
			case '2':
				$price = $sellData['sell_all'];
				$auto = 0;
				$userLogType = 'novel_all_buy';
				$userLogRemark = '小说全本订阅消耗！';
				$authorLogType = 'novel_all_income';
				$authorLogRemark = '小说全本订阅收入！';
				break;
		
				//包月出售
			case '3':
				$price = $sellData['sell_month'];
				$userLogType = 'novel_month_buy';
				$userLogRemark = '小说包月订阅消耗！';
				$authorLogType = 'novel_month_income';
				$authorLogRemark = '小说包月订阅收入！';
				$auto = 0;
				break;
					
			default:
				exit;
				break;
		}

		//出售价格是否为0
		if( $price == 0 )
		{
			return 201;
		}
		//出售价格是否大于了用户金币
		else if( $price > $userGold )
		{
			return 202;
		}
		if( $goldType == 1 )
		{
			$gold1 = $price;
		}
		else
		{
			$gold2 = $price;
		}

		//插入自动订阅
		if( $auto == 1 && $st == 1)
		{
			$collData['coll_module'] = 'novel';
			$collData['coll_type'] = 'sub';
			$collData['user_id'] = $uid;
			$collData['coll_cid'] = $nid;
			$collMod = NewModel('user.coll');
			$collMod->Insert($collData);
		}
		
		//插入订阅日志
		$subLogData['log_type'] = $st;
		$subLogData['log_nid'] = $nid;
		$subLogData['log_cid'] = $cid;
		$subLogData['log_uid'] = $uid;
		$subLogData['log_gold1'] = $gold1;
		$subLogData['log_gold2'] = $gold2;
		$this->Insert($subLogData);
		
		
		//用户资金变更
		$userMod = NewModel('user.user');
		$log['module'] = 'novel';
		$log['type'] = $userLogType;
		$log['tuid'] = $authorUid;
		$log['cid'] = $nid;
		$log['remark'] = $userLogRemark;
		$userMod->CapitalChange($uid , $log , $gold1 , $gold2 , 2);
		
		//作者小说的资金属性变更
		$consumeData['gold1'] = $gold1;
		$consumeData['gold2'] = $gold2;
		$consumeData['uid'] = $uid;
		$consumeData['nid'] = $nid;
		$consumeData['aid'] = $aid;
		$consumeData['copy'] = $copy;
		$consumeData['sign'] = $sid;
		$consumeData['form'] = 'sub';
		$consumeData['log_type'] = $authorLogType;
		$consumeData['log_remark'] = $authorLogRemark;
		$consumeMod = NewModel('novel.consume');
		$consumeMod->Update($consumeData);
		return 200;
	}
}
?>
<?php
/**
* 打赏记录日志模型
*
* @version        $Id: rewardlog.model.php 2018年1月7日 13:15  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class RewardLogModel
{
	//表
	public $logTable = '@novel_rewardlog';
	
	//构造函数
	public function __construct()
	{
	}


	/**
	 * 获得小说的小说记录离别
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
}
?>
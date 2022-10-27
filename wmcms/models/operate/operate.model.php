<?php
/**
* 操作记录模型
*
* @version        $Id: operate.model.php 2016年5月23日 19:31  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class OperateModel
{
	public $table = '@operate_operate';
	public $scoreTable = '@operate_score';
	//模块表字段数组
	public $moduleTable;
	//操作的模块
	public $module;
	//操作的类型
	public $type;
	//操作的类型条件,为空则用type
	public $operate;
	//操作的内容id
	public $cid;
	public $data;
	//平均分
	public $scoreNumber;
	//分数
	public $score;
	//分数数组
	public $scoreArr;
	

	
	/**
	 * 构造函数，初始化模块表
	 */
	function __construct( $data = null)
	{
		global $tableSer;
		
		$this->module = $data['module'];
		$this->type = GetKey($data,'type');
		$this->cid = GetKey($data,'cid');
		$this->operate = GetKey($data,'operate');
		$this->moduleTable = $tableSer->GetTable($this->module);
	}
	
	
	/**
	 * 检查今日操作的记录条数
	 */
	function CheckOperateCount()
	{
		$where['table'] = $this->table;
		$where['where'] = $this->GetWhere();
		if( is_array($this->operate) )
		{
			$where['where']['operate_type'] = $this->operate;
		}
		
		$count = wmsql::GetCount( $where , 'operate_id' );
		return $count;
	}

	
	/**
	 * 获取条件
	 */
	function GetWhere()
	{
		$where['operate_module'] = $this->module;
		$where['operate_type'] = $this->type;
		$where['operate_cid'] = $this->cid;
		$where['operate_ip'] = GetIp();
		$where['operate_time'] = array( '>' , strtotime(date('Y-m-d')) );
		return $where;
	}

	
	
	/**
	 * 插入操作记录
	 */
	function Insert()
	{
		$data = $this->GetWhere();
		$data['operate_time'] = time();
		$result = wmsql::Insert( '@operate_operate' , $data );
		return $result;
	}
	
	
	/**
	 * 内容表顶踩自增
	 */
	function ContentInc()
	{
		wmsql::Inc( $this->moduleTable['table'] , $this->moduleTable['field'].$this->type , $this->moduleTable['id'].'='.$this->cid);
	}
	

	/**
	 * 获得内容的条数
	 */
	function GetContentCount()
	{
		return GetContentCount($this->moduleTable['table'] , $this->moduleTable['id'] , $this->cid);
	}
	
	
	/**
	 * 内容平均分字段更新
	 */
	function ContentSvg()
	{
		$where[$this->moduleTable['id']] = $this->cid;
		$data[$this->moduleTable['field'].'score'] = $this->scoreNumber;
		wmsql::Update( $this->moduleTable['table'] , $data , $where );
	}
	
	
	
	/**
	 * 更新评分数据 
	 * @param 参数1，必须。分数字段
	 */
	function UpdateScore()
	{
		$scoreField = $this->scoreArr[$this->score];
		if( $scoreField == '' )
		{
			ReturnData('文件：'.__FILE__.'。第'.__LINE__.'行出错!<br/>可能因为没有设置分数数组！');
		}

		//检查需要评分的内容是否存在评分数据
		$where['table'] = $this->scoreTable;
		$where['where']['score_cid'] = $this->cid;
		$where['where']['score_module'] = $this->module;
		$scoreData = wmsql::GetOne($where);
		
		//不存在就插入一条
		if ( !$scoreData )
		{
			$data = $where['where'];
			$data['score_cid'] = $this->cid;
			$data['score_module'] = $this->module;
			$data[$scoreField] = 1;
			$res = wmsql::Insert( $this->scoreTable , $data );
			//平均分数
			$this->scoreNumber = $this->score;
		}
		//否则就修改
		else
		{
			//相应的字段评分
			$upWhere['score_cid'] = $this->cid;
			$upWhere['score_module'] = $this->module;
			$res = wmsql::Inc( $this->scoreTable , $scoreField , $upWhere);
		
			//计算平均分数
			$one = $scoreData['score_one'];
			$two = $scoreData['score_two'];
			$three = $scoreData['score_three'];
			$four = $scoreData['score_four'];
			$five = $scoreData['score_five'];
			$sum = $one + $two + $three + $four + $five;
		
			$this->scoreNumber = round( ( $one*1 + $two*2 + $three*3 + $four*4 + $five*5 ) / $sum , 1 );
		}
		
		return $this->scoreNumber;
	}
}
?>
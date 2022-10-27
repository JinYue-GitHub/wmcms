<?php
/**
* 小说上架模型
*
* @version        $Id: sell.model.php 2017年3月13日 21:17  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class SellModel
{
	public $sellTable = '@novel_sell';
	public $novelTable = '@novel_novel';
	
	
	/**
	 * 构造函数
	 */
	function __construct(){}

	/**
	 * 插入小说上架信息
	 * @param 参数1，必须，条件
	 */
	function Insert( $data )
	{
		$data['sell_time'] = time();
		return wmsql::Insert($this->sellTable, $data);
	}
	
	
	/**
	 * 获得小说上架信息
	 * @param 参数1，必须，小说id
	 */
	function GetLastOne( $nid )
	{
		$where['table'] = $this->sellTable;
		$where['where']['sell_novel_id'] = $nid;
		$where['order'] = 'sell_id desc';
		return wmsql::GetOne($where);
	}
	
	/**
	 * 获得小说出售价格
	 * @param 参数1，必须，小说id
	 */
	function GetNovelSell($nid)
	{
		$price = array();
		$price['sell_number'] = $price['sell_all'] = $price['sell_month'] = '0';
		$sellData = $this->GetLastOne($nid);
		if( $sellData['sell_status'] = 1 )
		{
			$sellType = explode(',', $sellData['sell_type']);
			foreach ($sellType as $v)
			{
				if( $v == 1)
				{
					$price['sell_number'] = $sellData['sell_number'];
				}
				else if( $v == 2)
				{
					$price['sell_all'] = $sellData['sell_all'];
				}
				else if( $v == 3)
				{
					list($surplusDay , $sumDay) = str::GetMonthDay();
					$price['sell_month'] = round(($sellData['sell_month']/$sumDay)*$surplusDay,2);
				}
			}
		}
		return $price;
	}
	

	/**
	 * 修改小说的上架信息
	 * @param 参数1，必须，小说id
	 * @param 参数2，选填，是否上架d
	 */
	function SetNovelSell( $nid , $sell = '1' )
	{
		$data['novel_sell'] = $sell;
		$where['novel_id'] = $nid;
		return wmsql::Update($this->novelTable, $data, $where);
	}

	/**
	 * 删除一条数据
	 */
	function Delete($wheresql)
	{
		if( !is_array($wheresql) )
		{
			$where['sell_id'] = $wheresql;
		}
		else
		{
			$where = $wheresql;
		}
		return wmsql::Delete($this->sellTable , $where);
	}
}
?>
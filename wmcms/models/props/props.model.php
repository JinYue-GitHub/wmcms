<?php
/**
* 道具模块模型
*
* @version        $Id: props.model.php 2017年3月5日 21:21  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class PropsModel
{
	private $typeTable = '@props_type';
	private $propsTable = '@props_props';
	private $sellTable = '@props_sell';
	//出售道具赠送给谁的？
	public $tuid=0;
	//道具的数组
	private $propsData;
	
	/**
	 * 构造函数
	 */
	function __construct(){}

	
	/**
	 * 获得所有道具数据
	 * @param 参数1，必须，查询条件
	 */
	function GetAll($wheresql)
	{
		$where['table'] = $this->propsTable;
		$where['left'][$this->typeTable] = 'type_id=props_type_id';
		$where['where'] = $wheresql;
		return wmsql::GetAll($where);
	}
	
	
	/**
	 * 获得一条道具数据
	 * @param 参数1，必须，查询条件
	 */
	function GetOne($wheresql)
	{
		$where['table'] = $this->propsTable;
		$where['left'][$this->typeTable] = 'type_id=props_type_id';
		$where['where'] = $wheresql;
		return wmsql::GetOne($where);
	}
	
	
	/**
	 * 根据id获得道具数据
	 * @param 参数1，必须，道具id
	 */
	function GetById($id)
	{
		$where['props_id'] = $id;
		return $this->GetOne($where);
	}
	

	/**
	 * 根据id删除道具
	 * @param 参数1，必须，道具id
	 */
	function DeleteById($id)
	{
		$where['props_id'] = $id;
		return wmsql::Delete($this->propsTable , $where);
	}
	
	
	/**
	 * 出售道具
	 * @param 参数1，必须，模块
	 * @param 参数2，必须，道具id
	 * @param 参数3，必须，模块内容ID
	 * @param 参数4，选填，出售的数量
	 * @param 参数5，选填，购买的备注
	 */
	function Sell($module , $pid , $cid , $number = 1,$remark='')
	{
		$user = user::GetInfo();
		$where['type_module'] = $module;
		$where['props_id'] = $pid;
		$this->propsData = $data = $this->GetOne($where);
		//道具不存在
		if( !$data )
		{
			return 201;
		}
		//没有上架
		else if( $data['props_status'] == 0)
		{
			return 202;
		}
		//库存不足
		else if( $data['props_stock'] <= 0)
		{
			return 203;
		}
		//用户金币1数量
		else if( $data['props_gold1']*$number > $user['user_gold1'])
		{
			return 204;
		}
		//用户金币2数量
		else if( $data['props_gold2']*$number > $user['user_gold2'])
		{
			return 205;
		}
		else
		{
			//插入出售记录
			$sellData['sell_module'] = $module;
			$sellData['sell_cid'] = $cid;
			$sellData['sell_user_id'] = $user['user_id'];
			$sellData['sell_props_id'] = $pid;
			$sellData['sell_number'] = $number;
			$sellData['sell_remark'] = $remark;
			$this->SellLog($sellData);
			
			//用户消费记录
			$userMod = NewModel('user.user');
			$log['module'] = $module;
			$log['type'] = 'props_buy';
			$log['tuid'] = $cid;
			$log['cid'] = $data['props_id'];
			$log['remark'] = '赠送道具！';
			$userMod->CapitalChange($user['user_id'] , $log , $data['props_gold1']*$number, $data['props_gold2']*$number , 2);
			
			return $data;
		}
	}
	
	

	/**
	 * 插入出售道具日志，并且修改库存
	 * @param 参数1，必须，销售记录
	 */
	function SellLog($sellData)
	{
		$data = $this->propsData;
		//插入出售记录
		$sellData['sell_gold1'] = $data['props_gold1'] * $sellData['sell_number'];
		$sellData['sell_gold2'] = $data['props_gold2'] * $sellData['sell_number'];
		$sellData['sell_money'] = $data['props_money'] * $sellData['sell_number'];
		$sellData['sell_time'] = time();
		wmsql::Insert($this->sellTable, $sellData);
		
		//减少库存
		wmsql::Dec($this->propsTable, 'props_stock' , array('props_id'=>$data['props_id']) );
	}
}
?>
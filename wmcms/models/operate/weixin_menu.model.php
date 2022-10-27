<?php
/**
* 微信自定义菜单模型
*
* @version        $Id: weixin_menu.model.php 2019年03月09日 13:10  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class Weixin_MenuModel
{
	public $table = '@weixin_menu';
	public $accountTable = '@weixin_account';

	
	/**
	 * 构造函数，初始化模块表
	 */
	function __construct( $data = null)
	{
	}
	
	
	/**
	 * 获得数据条数
	 * @param 参数1，必须，查询条件
	 */
	function GetCount($where)
	{
		$where['table'] = $this->table;
		$where['left'][$this->accountTable] = 'account_id=menu_account_id';
		return wmsql::GetCount($where);
	}
	
	/**
	 * 获取条件
	 * @param 参数1，必须，查询条件
	 */
	function GetList($where)
	{
		//获取列表条件
		$where['table'] = $this->table;
		$where['left'][$this->accountTable] = 'account_id=menu_account_id';
		return wmsql::GetAll($where);
	}
	
	
	/**
	 * 获得一条数据
	 * @param 参数1，必须，查询条件
	 */
	function GetOne($where)
	{
		$wheresql['table'] = $this->table;
		$wheresql['left'][$this->accountTable] = 'account_id=menu_account_id';
		$wheresql['where'] = $where;
		return wmsql::GetOne($wheresql);
	}
	
	/**
	 * 获得指定id的数据
	 * @param 参数1，必须，数据id
	 */
	function GetById($id)
	{
		$where['menu_id'] = $id;
		return $this->GetOne($where);
	}
	

	/**
	 * 插入记录
	 * @param 参数1，必须，插入的数据
	 */
	function Insert($data)
	{
		$data['menu_addtime'] = time();
		$data['menu_updatetime'] = time();
		$data['menu_pushtime'] = 0;
		return wmsql::Insert( $this->table , $data );
	}
	
	/**
	 * 修改数据
	 * @param 参数1，必须，修改的内容
	 * @param 参数2，必须，内容的id
	 */
	function Update($data,$id)
	{
		$wheresql['menu_id'] = $id;
		$data['menu_updatetime'] = time();
		return wmsql::Update($this->table, $data, $wheresql);
	}
	
	/**
	 * 推送目录的操作
	 * @param 参数1，必须，公众号id
	 * @param 参数2，必须，目录id
	 */
	function PushMenu($aid,$mid)
	{
		//取消该公众号所有目录使用状态
		wmsql::Update($this->table, array('menu_status'=>0), array('menu_account_id'=>$aid));
		//设置当前目录为使用状态
		return $this->Update(array('menu_status'=>1,'menu_pushtime'=>time()), $mid);
	}
	
	/**
	 * 根据id删除数据
	 * @param 参数1，必须，公众号id
	 */
	function DelById($id)
	{
		return $this->Del(array('menu_id'=>$id));
	}
	
	/**
	 * 根据条件删除数据
	 * @param 参数1，必须，删除条件
	 */
	function Del($where)
	{
		return wmsql::Delete($this->table,$where);
	}
}
?>
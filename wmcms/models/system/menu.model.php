<?php
/**
* 后台菜单模型
*
* @version        $Id: menu.model.php 2017年8月11日 11:56  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class MenuModel
{
	public $indexTable = '@system_menu_default';
	
	function __construct( $data = '' ){}

	
	/**
	 * 根据获得一条首页数据
	 * @param 参数1，必须，管理员id
	 */
	function DefaultGetOne( $cid )
	{
		$where['table'] = $this->indexTable;
		$where['where']['default_mid'] = $cid;
		$data = wmsql::GetOne($where);
		return $data;
	}
	/**
	 * 插入一条数据
	 * @param 参数1，必须，插入的数据
	 */
	function DefaultInsert( $data )
	{
		return wmsql::Insert($this->indexTable,$data);
	}
	/**
	 * 修改数据
	 * @param 参数1，必须，修改的数据
	 * @param 参数2，必须，管理员id
	 */
	function DefaultSave( $data , $id )
	{
		$where['default_id'] = $id;
		return wmsql::Update($this->indexTable, $data, $where);
	}
}
?>
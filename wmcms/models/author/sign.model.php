<?php
/**
* 作者签约等级模块模型
*
* @version        $Id: sign.model.php 2017年3月12日 13:21  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class SignModel
{
	public $table = '@author_sign';
	
	/**
	 * 构造函数
	 */
	function __construct(){}

	/**
	 * 获得全部签约等级
	 * @param 参数1，必须，模块
	 */
	function GetAll($module)
	{
		$where['table'] = $this->table;
		$where['where']['sign_module'] = 'novel';
		$where['order'] = 'sign_order';
		return $data = wmsql::GetAll($where);
	}
	

	/**
	 * 获得一条签约等级
	 * @param 参数1，必须，签约等级id
	 */
	function GetOne($id)
	{
		$where['table'] = $this->table;
		$where['where']['sign_id'] = $id;
		return wmsql::GetOne($where);
	}
}
?>
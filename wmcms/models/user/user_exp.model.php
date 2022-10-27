<?php
/**
* 用户经验模型
*
* @version        $Id: user_exp.model.php 2017年3月18日 18:48  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class User_ExpModel
{
	public $table = '@user_user';
	
	/**
	 * 构造函数
	 */
	function __construct()
	{
	}
	


	/**
	 * 修改用户的经验值
	 * @param 参数1，必须，用户的id
	 * @param 参数2，必须，变更的经验值
	 */
	function Update($uid , $exp = 0)
	{
		if( $exp > 0 )
		{
			$where['user_id'] = $uid;
			$data['user_exp'] = array('+',$exp);
			return wmsql::Update($this->table, $data, $where);
		}
	}
}
?>
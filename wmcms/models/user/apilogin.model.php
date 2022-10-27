<?php
/**
* 用户API登录模型
*
* @version        $Id: apilogin.model.php 2022年03月27日 10:36  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class ApiLoginModel
{
	public $table = '@user_apilogin';
	
	//构造函数
	function __construct(){}
	
	/**
	 * 根据用户id获得该用户所有绑定的第三方登录
	 * @param 参数1，必须，用户id
	 * @param 参数2，必须，接口类型
	 */
	function GetByUid($uid,$api='')
	{
		$where['table'] = $this->table;
		$where['where']['api_uid'] = $uid;
		if( !empty($api) )
		{
			$where['where']['api_type'] = $api;
		}
		$data = wmsql::GetAll($where);
	    if( $data )
	    {
	        $data = array_column($data,null,'api_type');
	    }
	    return $data;
	}
	
	/**
	 * 根据用户id获得该用户取消接口绑定
	 * @param 参数1，必须，用户id
	 * @param 参数2，必须，接口类型
	 */
	function UnBind($uid,$api)
	{
	    $where['api_uid'] = $uid;
	    $where['api_type'] = $api;
	    return wmsql::Delete($this->table,$where);
	}
}
?>
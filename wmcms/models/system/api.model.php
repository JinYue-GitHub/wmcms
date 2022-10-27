<?php
/**
* api接口模型
*
* @version        $Id: api.model.php 2022年03月25日 14:36  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class ApiModel
{
	public $table = '@api_api';
	
	
	function __construct()
	{
	}
	
	/**
	 * 根据条件获得随机一条数据
	 * @param 参数1，必须，分类di
	 * @param 参数2，选填，是否开启接口
	 */
	function GetByTypeRandOne($tid,$open='')
	{
		$where['table'] = $this->table;
		$where['where']['type_id'] = $tid;
		if( $open != '' )
		{
		    $where['where']['api_open'] = $open;
		}
		$data = wmsql::RandOne($where);
		return $data;
	}

	/**
	 * 根据条件获得所有数据
	 * @param 参数1，必须，分类id
	 * @param 参数2，选填，是否开启接口
	 */
	function GetByType($tid,$open='')
	{
		$where['table'] = $this->table;
		$where['where']['type_id'] = $tid;
		if( $open != '' )
		{
			$where['where']['api_open'] = $open;
		}
		$data = wmsql::GetAll($where);
		return $data;
	}
}
?>
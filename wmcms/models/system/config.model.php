<?php
/**
* 系统配置模型
*
* @version        $Id: config.model.php 2018年4月18日 15:05  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class ConfigModel
{
	private $configTable = '@config_config';
	
	/**
	 * 构造函数
	 */
	function __construct()
	{
	}

	/**
	 * 根据配置名获取内容
	 * @param 参数1，必须，配置名
	 * @param 参数2，选填，模块
	 */
	function GetByName($name,$module='')
	{
	    $where['table'] = $this->configTable;
		$where['where']['config_name'] = $name;
	    if( !empty($module) )
	    {
		    $where['where']['config_module'] = $module;
	    }
		return wmsql::GetOne($where);
	}
	
	/**
	 * 根据模块获取配置
	 * @param 参数1，必须，模块
	 */
	function GetByModule($module)
	{
	    $where['table'] = $this->configTable;
		$where['where']['config_module'] = $module;
		return wmsql::GetAll($where);
	}
	/**
	 * 修改来自表单的
	 * @param 参数1，必须，需要更新数据
	 */
	function UpdateToForm($data)
	{
		foreach ($data as $key=>$val)
		{
			$where = array();
			$data = array();
			if( is_array($val) )
			{
				foreach($val as $k=>$v)
				{
					$this->UpdateByName($key,$k,$v);
				}
			}
			else
			{
				$this->UpdateById($key,$val);
			}
		}
		return true;
	}
	

	/**
	 * 获取自定义字段能够使用的模块
	 */
	function GetFieldModule()
	{
		$arr = GetModuleName();
	
		unset($arr['all']);
		unset($arr['message']);
		unset($arr['author']);
		unset($arr['user']);
		unset($arr['zt']);
		unset($arr['diy']);
		unset($arr['down']);
		unset($arr['replay']);
		unset($arr['search']);
		return $arr;
	}
	
	
	/**
	 * 根据配置标识修改数据
	 * @param 参数1，必须，配置的模块
	 * @param 参数2，必须，配置的标识
	 * @param 参数3，必须，需要修改的值
	 */
	function UpdateByName($module,$name,$val)
	{
		if( $module == 'finance' && ( $name == 'activity_starttime' || $name == 'activity_endtime') )
		{
			$val = strtotime($val);
		}
		$where['config_module'] = $module;
		$where['config_name'] = $name;
		$data['config_value'] = str::Escape($val, 'e');
		return wmsql::Update( $this->configTable, $data , $where);
	}
	
	/**
	 * 根据配置ID修改数据
	 * @param 参数1，必须，配置的ID
	 * @param 参数2，必须，需要修改的值
	 */
	function UpdateById($id,$val)
	{
		$where['config_id'] = $id;
		$data['config_value'] = str::Escape($val, 'e');
		return wmsql::Update( $this->configTable, $data , $where);
	}
}
?>
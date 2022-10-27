<?php
/**
* 插件配置模型
*
* @version        $Id: config.model.php 2018年6月17日 12:18  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class ConfigModel
{
	private $configTable = '@plugin_config';
	
	/**
	 * 构造函数
	 */
	function __construct()
	{
	}

	
	/**
	 * 根据插件id获得配置列表
	 * @param 参数1，必须，插件id
	 */
	function GetList($id)
	{
		$where['table'] = $this->configTable;
		$where['where']['config_plugin_id'] = $id;
		$list = wmsql::GetAll($where);
		return $list;
	}
	
	
	/**
	 * 新增插件配置
	 * @param 参数1，必须，插件id
	 * @param 参数2，必须，参数键
	 * @param 参数3，必须，参数值
	 */
	function Insert($id,$key,$val)
	{
		$data['config_plugin_id'] = $id;
		$data['config_key'] = $key;
		$data['config_val'] = $val;
		return wmsql::Insert($this->configTable, $data);
	}
	
	
	/**
	 * 修改插件配置
	 * @param 参数1，必须，插件id
	 * @param 参数2，必须，参数键
	 * @param 参数3，必须，参数值
	 */
	function Update($id,$key,$val)
	{
		$where['config_plugin_id'] = $id;
		$where['config_key'] = $key;
		$data['config_val'] = $val;
		return wmsql::Update($this->configTable, $data, $where);
	}
	
	
	/**
	 * 根据插件id和键名获得配置的值
	 * @param 参数1，必须，插件id
	 * @param 参数2，必须，键名
	 * @param 参数3，选填，如果有值并且查询不出当前配置就自动插入
	 */
	function GetConfig($id,$key,$val=null)
	{
		$where['table'] = $this->configTable;
		$where['where']['config_plugin_id'] = $id;
		$where['where']['config_key'] = $key;
		$data = wmsql::GetOne($where);
		if( $data )
		{
			return $data['config_val'];
		}
		else if( !empty($val) )
		{
			return $this->Insert($id,$key,$val);
		}
		else
		{
			return '';
		}
	}
	
	/**
	 * 根据插件id的所有配置
	 * @param 参数1，必须，插件id
	 * @param 参数2，必须，键名
	 */
	function GetConfigList($id)
	{
		$where['table'] = $this->configTable;
		$where['where']['config_plugin_id'] = $id;
		$list = wmsql::GetAll($where);
		return $list;
	}
	
	/**
	 * 快速删除指定插件的配置
	 * @param 参数1，必须，插件的id
	 * @param 参数2，必须，插件的键名
	 * @return Ambigous <boolean, string>
	 */
	function Delete($id,$key)
	{
		$where['config_plugin_id'] = $id;
		$where['config_key'] = $key;
		return wmsql::Delete($this->configTable,$where);
	}
}
?>
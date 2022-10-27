<?php
/**
* 插件父类方法
*
* @version        $Id: plugin.class.php 2018年6月17日 18:07  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
class Plugin
{
	static private $configMod;
	//插件的数据
	static private $plugin;
	//插件id
	static private $id;
	//键的前缀
	static private $keyPre;
	
	/**
	 * 设置插件数据
	 * @param 参数1，必须，插件数据
	 */
	static function SetData($data)
	{
		self::$configMod = NewModel('plugin.config');
		self::$plugin = $data;
		self::$id = $data['plugin_id'];
		self::$keyPre = 'plugin_'.$data['plugin_floder'].'_';
	}
	
	//获得插件数据
	static function GetData()
	{
		return self::$plugin;
	}
	
	/**
	 * 快速获得插件的自身表名字
	 * @param 参数1，必须，表名字
	 * @return string
	 */
	static function Table($tableName)
	{
		return wmsql::Table(self::$keyPre).$tableName;
	}
	

	/**
	 * 快速删除插件表
	 * @param 参数1，必须，表名字
	 * @return string
	 */
	static function Drop($tableName)
	{
		return wmsql::Drop('@'.self::$keyPre.$tableName);
	}
	
	/**
	 * 新增插件配置
	 * @param 参数1，必须，键名
	 * @param 参数2，必须，如果有值并且查询不出当前配置就自动插入
	 */
	static function AddConfig($key,$val)
	{
		return self::GetConfig($key,$val);
	}
	
	
	/**
	 * 快速删除插件的配置
	 * @param 参数1，必须，需要删除的键名
	 */
	static function DelConfig($key)
	{
		return self::$configMod->Delete(self::$id,self::$keyPre.$key);
	}
	
	/**
	 * 获得插件指定键的值
	 * @param 参数1，必须，键名
	 * @param 参数2，选填，如果有值并且查询不出当前配置就自动插入
	 */
	static function GetConfig($key,$val=null)
	{
		return self::$configMod->GetConfig(self::$id,self::$keyPre.$key,$val);
	}
	
	//获得插件所有的配置
	static function GetConfigList()
	{
		return self::$configMod->GetConfigList(self::$id);
	}
}
?>
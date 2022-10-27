<?php
/**
* 插件模型
*
* @version        $Id: plugin.model.php 2018年6月7日 19:18  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class PluginModel
{
	private $pluginTable = '@plugin';
	private $configTable = '@plugin_config';
	
	/**
	 * 构造函数
	 */
	function __construct()
	{
	}
	
	
	/**
	 * 根据文件夹名字查询插件
	 * @param 参数1，必须，文件夹名字
	 */
	function GetByFloder($floder)
	{
		$where['plugin_floder'] = $floder;
		return $this->GetOne($where);
	}
	
	/**
	 * 根据插件ID获得数据
	 * @param 参数1，必须，插件id
	 */
	function GetById($id)
	{
		$where['plugin_id'] = $id;
		return $this->GetOne($where);
	}
	
	/**
	 * 查询一条数据
	 * @param 参数1，必须，查询条件
	 */
	function GetOne($where)
	{
		$wheresql['table'] = $this->pluginTable;
		$wheresql['where'] = $where;
		return wmsql::GetOne($wheresql);
	}
	
	
	/**
	 * 获得插件列表
	 * @param 参数1，选填，是否进行条件查询
	 */
	function GetList($where=array())
	{
		$wheresql['table'] = $this->pluginTable;
		$wheresql['where'] = $where;
		return wmsql::GetAll($wheresql);
	}
	
	
	/**
	 * 安装插件
	 * @param 参数1，必须，需要插入的数据
	 */
	function Insert($data)
	{
		$data['plugin_time'] = time();
		return wmsql::Insert($this->pluginTable, $data);
	}
	
	
	/**
	 * 根据id删除数据
	 * @param 参数1，必须，需要删除的ID
	 */
	function DelById($id)
	{
		//删除插件
		$where['plugin_id'] = $id;
		wmsql::Delete($this->pluginTable,$where);
		//删除配置
		$configWhere['config_plugin_id'] = $id;
		wmsql::Delete($this->configTable,$configWhere);
		return true;
	}
	
	
	/**
	 * 修改插件的版本信息
	 * @param 参数1，必须，应用id标识
	 * @param 参数2，必须，应用需要修改的版本
	 */
	function UpdateVersion($ids,$version)
	{
		$data['plugin_version'] = $version;
		$where['plugin_floder'] = $ids;
		return wmsql::Update($this->pluginTable, $data, $where);
	}
	
	
	/**
	 * 注册插件钩子配置
	 * @param 参数1，必须，应用id标识
	 */
	function RegHook($path)
	{
		require_once WMPLUGIN.'/hook.php';
		$mainPluginHook = $pluginHook;
		$pluginHookFile = WMPLUGIN.'/apps/'.$path.'/inc/hook.php';
		if( file_exists($pluginHookFile) )
		{
			require_once $pluginHookFile;
			if( !empty($pluginHook) )
			{
				foreach ($pluginHook as $a=>$v)
				{
					foreach ($v as $m=>$v1)
					{
						foreach ($v1 as $c=>$v2)
						{
							$mainPluginHook[$a][$m][$c][$path] = $v2;
						}
					}
				}
			}
			return file_put_contents(WMPLUGIN.'/hook.php', '<?php $pluginHook = '.var_export($mainPluginHook, true).';?>');
		}
		return true;
	}
	
	/**
	 * 注销插件钩子配置
	 * @param 参数1，必须，应用id标识
	 */
	function ExitHook($path)
	{
		require_once WMPLUGIN.'/hook.php';
		if( !empty($pluginHook) )
		{
			foreach ($pluginHook as $a=>$v)
			{
				foreach ($v as $m=>$v1)
				{
					foreach ($v1 as $c=>$v2)
					{
						if( isset($v2[$path]) )
						{
							unset($pluginHook[$a][$m][$c][$path]);
						}
					}
				}
			}
		}
		return file_put_contents(WMPLUGIN.'/hook.php', '<?php $pluginHook = '.var_export($pluginHook, true).';?>');
	}
}
?>
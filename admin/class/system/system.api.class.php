<?php
/**
 * 网站api生成类文件
 *
 * @version        $Id: system.api.class.php 2017年2月20日 17:24  weimeng
 * @package        WMCMS
 * @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
 * @link           http://www.weimengcms.com
 *
 */
class SystemApi
{
	/**
	 * 生成新的api配置
	 */
	function Update()
	{
		$where['table'] = '@api_api';
		$configArr = wmsql::GetAll($where);
		$config = '';
		foreach ($configArr as $v)
		{
			$v = str::Escape($v , 'e');
			$config .="'{$v['api_name']}' => array('api_type'=>'{$v['type_id']}','api_title'=>'{$v['api_title']}','api_ctitle'=>'{$v['api_ctitle']}','api_open'=>'{$v['api_open']}','api_appid'=>'{$v['api_appid']}','api_apikey'=>'{$v['api_apikey']}','api_secretkey'=>'{$v['api_secretkey']}','api_option'=>'{$v['api_option']}'),";
		}
		file_put_contents( WMCONFIG."api.config.php" , '<?php $C["config"]["api"]=array('.$config.');?>');
	}
}
?>
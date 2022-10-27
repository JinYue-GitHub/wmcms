<?php
/**
 * 应用属性的类文件
 *
 * @version        $Id: app.attr.class.php 2016年5月16日 20:36  weimeng
 * @package        WMCMS
 * @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
 * @app           http://www.weimengcms.com
 *
 */
class AppAttr
{
	public $table = '@app_attr';
	
	
	/**
	 * 获取属性
	 * @param 参数，选填，c费用，p平台，l语言
	 */
	function GetAttr( $type = '' )
	{
		$where['table'] = $this->table;
		if( $type != '' )
		{
			$where['where']['attr_type'] = $type;
		}
		
		return wmsql::GetAll($where);
	}
	
	
	/**
	 * 获取属性
	 * @param 参数，选填，c费用，p平台，l语言
	 */
	function GetType( $type = '' )
	{
		$arr = array(
			'c'=>'资费',
			'p'=>'平台',
			'l'=>'语言',
		);
		
		if( $type != '' )
		{
			return $arr[$type];
		}
		else
		{
			return $arr;
		}
	}
}
?>
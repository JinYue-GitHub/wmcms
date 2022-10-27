<?php
/**
 * 应用的类文件
 *
 * @version        $Id: app.app.class.php 2016年5月16日 21:25  weimeng
 * @package        WMCMS
 * @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
 * @link           http://www.weimengcms.com
 *
 */
class AppApp
{
	public $table = '@app_app';
	
	/**
	 * 获得所有文章属性分类
	 */
	function GetAttr()
	{
		$arr = array(
			'rec'=>'推荐',
		);
		
		return $arr;
	}
	


	/**
	 * 检查应用是否存在
	 */
	function CheckName( $wheresql )
	{
		//应用名字检查
		$where['table'] = $this->table;
		$where['where'] = $wheresql;
		$data = wmsql::GetOne($where);

		if ( $data )
		{
			return $data;
		}
		else
		{
			return false;
		}
	}
}
?>
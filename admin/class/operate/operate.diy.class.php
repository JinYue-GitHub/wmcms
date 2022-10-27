<?php
/**
 * diy页面的类文件
 *
 * @version        $Id: operate.diy.class.php 2016年5月7日 21:56  weimeng
 * @package        WMCMS
 * @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
 * @link           http://www.weimengcms.com
 *
 */
class OperateDiy
{
	public $table = '@diy_diy';
	

	/**
	 * 获取搜索的类型
	 * @param 参数1，选填，获取搜索的类型
	 */
	function GetStatus( $k = '' )
	{
		$arr = array(
			'1'=>'显示',
			'0'=>'隐藏',
		);
	
		if( $k != '' )
		{
			return $arr[$k];
		}
		else
		{
			return $arr;
		}
	}
}
?>
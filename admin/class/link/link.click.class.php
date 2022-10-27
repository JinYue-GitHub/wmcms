<?php
/**
 * 友链分类的类文件
 *
 * @version        $Id: link.type.class.php 2016年5月13日 13:37  weimeng
 * @package        WMCMS
 * @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
 * @link           http://www.weimengcms.com
 *
 */
class LinkClick
{
	/**
	 * 获得所有分类
	 * @param 参数1，必填，点击类型
	 */
	function GetType( $type = '' )
	{
		switch ($type)
		{
			case "out":
				return '点出';
				break;
				
			default:
				return '点入';
				break;
		}
	}
}
?>
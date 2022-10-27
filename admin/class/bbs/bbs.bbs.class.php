<?php
/**
 * 主题列表的类文件
 *
 * @version        $Id: bbs.bbs.class.php 2016年5月18日 14:33  weimeng
 * @package        WMCMS
 * @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
 * @link           http://www.weimengcms.com
 *
 */
class bbsbbs
{
	public $table = '@bbs_bbs';
	
	/**
	 * 获得所有文章属性分类
	 */
	function GetAttr()
	{
		$arr = array(
			'rec'=>'推荐',
			'es'=>'精华',
			'top'=>'置顶',
		);
		
		return $arr;
	}
}
?>
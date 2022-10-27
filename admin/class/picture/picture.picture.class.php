<?php
/**
 * 图集的类文件
 *
 * @version        $Id: picture.picture.class.php 2016年5月15日 13:19  weimeng
 * @package        WMCMS
 * @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
 * @link           http://www.weimengcms.com
 *
 */
class PicturePicture
{
	public $table = '@picture_picture';
	
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
}
?>
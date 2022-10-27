<?php
/**
 * 专题节点 的类文件
 *
 * @version        $Id: operate.zt.node.class.php 2016年5月10日 10:56  weimeng
 * @package        WMCMS
 * @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
 * @link           http://www.weimengcms.com
 *
 */
class OperateZtNode
{
	public $table = '@zt_node';
	

	/**
	 * 获取搜索的类型
	 * @param 参数1，选填，获取搜索的类型
	 */
	function GetType( $k = '' )
	{
		$arr = array(
			'1'=>'图片',
			'2'=>'文本',
			'3'=>'标签',
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
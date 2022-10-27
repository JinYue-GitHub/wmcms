<?php
/**
 * 菜单目录类文件
 *
 * @version        $Id: system.menu.class.php 2016年5月15日 18:24  weimeng
 * @package        WMCMS
 * @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
 * @link           http://www.weimengcms.com
 *
 */
class SystemMenu
{
	public $table = '@system_menu';
	
	/**
	 * 获得所有父级菜单名字
	 * @param 参数1，必须，目录id
	 * @param 参数2，选填，当前已经有了的父级目录标题
	 */
	public function GetParent( $id , $menuName = '')
	{
		$where['table'] = $this->table;
		$where['where']['menu_id'] = $id;
		$data = wmsql::GetOne($where);

		if( $data )
		{
			$menuName = $data['menu_title'].' -> '.$menuName;

			if( $data['menu_pid'] == '0' )
			{
				return $menuName;
			}
			else
			{
				return $this->GetParent( $data['menu_pid'] , $menuName);
			}
		}
		else
		{
			return $menuName;
		}
	}
}
?>
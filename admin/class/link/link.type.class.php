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
class LinkType
{
	public $table = '@link_type';
	//电脑板的分类列表
	function GetHtml( $v , $lv = '')
	{
		$tabSign = '';
		if( $lv >= 3)
		{
			$tabSign = '<span class="tab-sign-s"></span>';
		}
		echo '<dt class="cf">
			<div class="btn-toolbar opt-btn cf">
				<a href="index.php?d=yes&c=link.type.edit&t=edit&id='.$v['type_id'].'" data-toggle="navtab" data-id="link-type-edit" data-title="编辑友链分类"><span class="btn btn-secondary size-MINI radius">编辑</span></a>
				<a class="btn btn-danger radius" onclick="'.GetCFun().'delAjax('.$v['type_id'].')">删除</a>
			</div>
			<div class="fold"><i></i></div>
			<div class="order">'.$v['type_id'].'</div>
			<div class="order">'.$v['type_order'].'</div>
			<div class="name">'.$tabSign.'<span class="tab-sign"></span>'.$v['type_name'].'</div></dt>';
	}
	
	
	/**
	 * 获得所有分类
	 * @param 参数1，选填，sql条件
	 */
	function GetType( $where = array() )
	{
		$wheresql = $where;
		$wheresql['table'] = $this->table;
		$typeArr = wmsql::GetAll($wheresql);
		return $typeArr;
	}
}
?>
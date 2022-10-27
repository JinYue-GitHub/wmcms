<?php
/**
 * 图集分类的类文件
 *
 * @version        $Id: picture.type.class.php 2016年5月15日 9:52  weimeng
 * @package        WMCMS
 * @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
 * @picture           http://www.weimengcms.com
 *
 */
class pictureType
{
	public $table = '@picture_type';
	
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
				<a href="index.php?d=yes&c=picture.type.edit&t=edit&id='.$v['type_id'].'" data-toggle="navtab" data-id="picture-type-edit" data-title="编辑图集分类"><span class="btn btn-secondary size-MINI radius">编辑</span></a>
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
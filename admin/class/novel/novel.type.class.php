<?php
/**
 * 小说分类的类文件
 *
 * @version        $Id: novel.type.class.php 2016年4月25日 15:07  weimeng
 * @package        WMCMS
 * @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
 * @link           http://www.weimengcms.com
 *
 */
class NovelType
{
	public $table = '@novel_type';
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
				<a href="index.php?d=yes&c=novel.type.edit&t=edit&id='.$v['type_id'].'" data-toggle="navtab" data-id="novel-type-edit" data-title="编辑小说分类"><span class="btn btn-secondary size-MINI radius">编辑</span></a>
				<a class="btn btn-danger radius" onclick="'.GetCFun().'delAjax('.$v['type_id'].')">删除</a>
			</div>
			<div class="fold"><i></i></div>
			<div class="order">'.$v['type_id'].'</div>
			<div class="order">'.$v['type_order'].'</div>
			<div class="name">'.$tabSign.'<span class="tab-sign"></span>'.$v['type_name'].'</div></dt>';
	}
	
	
	/**
	 * 获得所有分类
	 * @return Ambigous <boolean, unknown, multitype:>
	 */
	function GetType()
	{
		$wheresql['table'] = $this->table;
		$typeArr = wmsql::GetAll($wheresql);
		
		return $typeArr;
	}
}
?>
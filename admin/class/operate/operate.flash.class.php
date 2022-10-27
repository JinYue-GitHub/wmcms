<?php
/**
 * 幻灯片的类文件
 *
 * @version        $Id: operate.flash.class.php 2016年5月7日 14:00  weimeng
 * @package        WMCMS
 * @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
 * @link           http://www.weimengcms.com
 * @uptime 		   2018年8月21日 20:46
 *
 */
class OperateFlash
{
	public $table = '@flash_flash';
	public $typeTable = '@flash_type';

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
				<a href="index.php?d=yes&c=operate.flash.typeedit&t=type_edit&id='.$v['type_id'].'" data-toggle="navtab" data-id="novel-type-edit" data-title="编辑幻灯片分类"><span class="btn btn-secondary size-MINI radius">编辑</span></a>
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
		$wheresql['table'] = $this->typeTable;
		$typeArr = wmsql::GetAll($wheresql);
	
		return $typeArr;
	}
	
	/**
	 * 获取能设置预设模版的模块
	 * @param 参数1，选填，获取模块的键
	 */
	function GetModule( $k = '' )
	{
		$arr = GetModuleName();
		$arr['index'] = '全站首页';
	
		unset($arr['user']);
		unset($arr['all']);
		unset($arr['message']);
		unset($arr['down']);
		unset($arr['replay']);
	
		if( $k != '' )
		{
			return $arr[$k];
		}
		else
		{
			return $arr;
		}
	}
	

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
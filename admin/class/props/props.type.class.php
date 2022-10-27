<?php
/**
 * 道具分类的类文件
 *
 * @version        $Id: props.type.class.php 2017年3月5日 16:57  weimeng
 * @package        WMCMS
 * @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
 * @link           http://www.weimengcms.com
 *
 */
class PropsType
{
	public $table = '@props_type';
	
	//电脑板的分类列表
	function GetHtml( $v , $lv = '')
	{
		$tabSign = '';
		if( $lv >= 3)
		{
			$tabSign = '<span class="tab-sign-s"></span>';
		}

		$status = $this->GetStatus($v['type_status']);
		if( $v['type_status'] == '1' )
		{
			$status = '<span style="color:green">【'.$status;
		}
		else
		{
			$status = '<span style="color:#CCCCCC">【'.$status;
		}
		echo '<dt class="cf">
			<div class="btn-toolbar opt-btn cf">
				<a href="index.php?d=yes&c=props.type.edit&t=typeedit&id='.$v['type_id'].'" data-toggle="navtab" data-id="article-type-edit" data-title="编辑道具分类"><span class="btn btn-secondary size-MINI radius">编辑</span></a>
				<a class="btn btn-danger radius" onclick="'.GetCFun().'delAjax('.$v['type_id'].')">删除</a>
			</div>
			<div class="fold"><i></i></div>
			<div class="order" style="width:90px">'.GetModuleName($v['type_module']).'</div>
			<div class="order" style="width:65px">'.$v['type_id'].'</div>
			<div class="order" style="width:65px">'.$v['type_order'].'</div>
			<div class="name">'.$tabSign.'<span class="tab-sign"></span>'.$status.'】-- '.$v['type_name'].'</span></div></dt>';
	}

	/**
	 * 获得分类状态
	 * @param 参数1，选填，如果k不为空就返回出指定的值
	 */
	function GetStatus( $k = '')
	{
		$arr = array(
			'0'=>'隐藏',
			'1'=>'显示',
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
	
	
	/**
	 * 获得可以操作的模块
	 */
	function GetModule()
	{
		$moduleArr = array(
			'novel'=>'小说模块',
		);
		
		return $moduleArr;
	}
}
?>
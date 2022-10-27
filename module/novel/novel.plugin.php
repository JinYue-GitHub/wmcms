<?php
/**
* 小说二次开发插件类
*
* @version        $Id: novel.plugin.php 2019年01月24日 22:16  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
class novellabelplugin extends novellabel
{
	function __construct(){}
	
	function PublicNovelBefore($data,$blcode,$level='')
	{
		/*foreach ($data as $k => $v)
		{
			$arr2=array(
				'aa'=>'这是aa',
				'唯一'=>$v['novel_name'].rand(1000,9999).time(),
			);
			tpl::SetBeforeLabel('PublicNovel',$k, $arr2);
		}*/
	}
	
	//前置方法
	function ReadLabelBefore()
	{
	}
	//后置方法
	function ReadLabelAfter()
	{
	}
}
?>
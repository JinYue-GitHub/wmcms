<?php
/**
* 绑定模块设置处理器
*
* @version        $Id: system.module.config.php 2016年9月14日 11:35  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$manager = AdminNewClass('manager');

//编辑绑定模块
if( $type == 'edit' )
{
	$modlueStr = '';
	$moduleName = Get('module');
	$moduleArr = $post['module'];
	
	foreach ($moduleArr as $k=>$v)
	{
		//如果是用户模块就设置成第一位。
		if( $v == 'user' )
		{
			$modlueStr = "'{$v}',".$modlueStr;
		}
		else
		{
			$modlueStr .= "'{$v}'";
			if( !IsLast($moduleArr , $v) )
			{
				$modlueStr .= ',';
			}
		}
	}

	//获得模块公共配置内容
	$content = $manager->GetModuleCommon($moduleName);
	$commonModule = tpl::Tag('BaseModule = array([a])', $content);
	
	$content = tpl::Rep( array( $commonModule[1][0] => $modlueStr ) , $content , 2);
	
	//写入文件内容
	file_put_contents(WMMODULE.$moduleName.'/'.$moduleName.'.common.php', $content);
	Ajax( '恭喜您，绑定模块成功' , 200);
}
?>
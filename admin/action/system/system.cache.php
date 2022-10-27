<?php
/**
* 缓存配置处理器
*
* @version        $Id: system.cache.php 2016年10月22日 10:00  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$manager = AdminNewClass('manager');
$configMod = NewModel('system.config');

//修改配置
if ( $type == 'config' )
{
	//如果请求信息存在
	if( $post )
	{
		$configMod->UpdateToForm($post);
	
		//写入操作记录
		SetOpLog( '修改网站缓存设置' , 'system' , 'update' );
		
		//更新配置文件
		$manager->UpConfig('web');
		
		Ajax();
	}
}
//清除缓存
else if ( $type == 'clear' )
{
	if($post['page'] == 0 && $post['block'] == 0 && $post['sql'] == 0 && $post['log'] == 0)
	{
		Ajax('对不起，至少请选择一种缓存机制!' , 300);
	}
	else
	{
		if( $post['page'] == '1' )
		{
			file::DelDir(WMROOT.$C['config']['web']['cache_path'].'/site/page');
		}
		if( $post['block'] == '1' )
		{
			file::DelDir(WMROOT.$C['config']['web']['cache_path'].'/site/block');
		}
		if( $post['sql'] == '1' )
		{
			file::DelDir(WMROOT.$C['config']['web']['cache_path'].'/sql');
		}
		if( $post['log'] == '1' )
		{
			file::DelDir(WMROOT.$C['config']['web']['cache_path'].'/log');
		}
		file_put_contents(WMCACHE.'/basic.wm','');
		Ajax('缓存清除成功');
	}
}
?>
<?php
/**
* 数据库管理处理器
*
* @version        $Id: data.mysql.php 2016年5月13日 11:21  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//重命名文件或者文件夹操作
if ( $type == 'runsql' )
{
	$sql = stripslashes($post['sql']);
	if( $sql == '' )
	{
		Ajax( '对不起，sql语句不能为空!' , 300);
	}
	else
	{
		if( wmsql::Query($sql , 'rs') === false )
		{
			Ajax( '对不起，请检查SQL是否正确!' , 300);
		}
		else
		{
			Ajax( '恭喜您，SQL成功执行!');
		}
	}
}
//优化表或者修复表
else if ( $type == 'optimize' || $type == 'repair' )
{
	$table = Request('table');
	
	if( $table == '' || $type == '' )
	{
		Ajax( '对不起，操作的表或者操作类型不能为空!' , 300);
	}
	else
	{
		switch ($type)
		{
			//优化表
			case "optimize":
				$info = $table.'表优化成功';
				$sql = 'OPTIMIZE TABLE  `'.$table.'`';
			
			//修复表
			case "repair":
				$info = $table.'表修复成功';
				$sql = 'REPAIR TABLE  `'.$table.'`';
				break;
		}
		
		if( wmsql::Query($sql , 'rs') === false )
		{
			Ajax( '对不起，请检查SQL是否正确!' , 300);
		}
		else
		{
			Ajax( $info );
		}
	}
}
?>
<?php
/**
* 多级联动处理文件
*
* @version        $Id: linkage.php 2016年4月8日 14:36  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$val = Get('val');
//上传预设的模版
if( $type == 'templates' )
{
	$tempSer = AdminNewClass('system.templates');
	//选中的类型
	$dataArr = $tempSer->GetTempType($val);
}

echo '[';
if ( is_array($dataArr) )
{
	foreach ($dataArr as $k=>$v)
	{
		echo '["'.$k.'", "'.$v.'"]';
		if( end($dataArr) != $v)
		{
			echo ',';
		}
	}
}
echo ']';
exit;
?>
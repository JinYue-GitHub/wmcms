<?php
/**
* 开发者工具模型
*
* @version        $Id: dev.model.php 2017年8月2日 20:51  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class DevModel
{
	function __construct( $data = '' ){}

	
	/**
	 * 创建文件
	 * @param 参数1，必须，模块
	 * @param 参数2，必须，文件名字
	 */
	function Create($data)
	{
		$module = $data['module'];
		$fileName = $data['file_name'];
		$name = $data['name'];
		$page = $data['page'];
		//设置文件路径
		if( $module != 'all' )
		{
			$filePath = WMROOT.'module/'.$module.'/'.$fileName.'.php';
		}
		else
		{
			$filePath = WMROOT.$fileName.'.php';
		}
		//检查文件路径
		if(file_exists($filePath))
		{
			return false;
		}
		
		//获得文件模版内容
		if( $module == 'all' )
		{
			$fileContent = file::GetFile(WMTEMPLATE.'system/dev/index.html');
		}
		else
		{
			$fileContent = file::GetFile(WMTEMPLATE.'system/dev/module.html');
		}

		//替换标签
		$arr = array(
			'name'=>$name,
			'fileName'=>$fileName,
			'module'=>$module,
			'page'=>$page,
			'time'=>date('Y年m月d日 H:i'),
		);
		$fileContent = tpl::Rep($arr,$fileContent);
		
		return file::CreateFile($filePath, $fileContent);
	}
}
?>
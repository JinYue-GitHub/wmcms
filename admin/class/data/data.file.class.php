<?php
/**
 * 文件列表的类文件
 *
 * @version        $Id: data.file.class.php 2016年5月11日 22:42  weimeng
 * @package        WMCMS
 * @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
 * @link           http://www.weimengcms.com
 *
 */
class DataFile
{
	public $icoPath = '../files/images/fileico/';

	/**
	 * 根据文件类型获得文件图标
	 * @param 参数1，必须，ico图标的文件夹
	 */
	function GetFileIco( $ext )
	{
		$file = $this->icoPath.$ext.'.png';
		if( file_exists($file) )
		{
			return $file;
		}
		else
		{
			return $this->icoPath.'file.png';
		}
	}
	
	
	/**
	 * 检查文件格式是否可以编辑
	 * @param 参数1,必须，文件格式。
	 */
	function IsEdit( $fileType )
	{
		$arr = array('css','js','html','htm','ini','shtml','xml','php','txt');

		$isEdit = false;
		if( in_array($fileType, $arr) )
		{
			$isEdit = true;
		}
		
		return $isEdit;
	}
}
?>
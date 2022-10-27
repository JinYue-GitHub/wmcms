<?php
/**
 * 筛选条件类文件
 *
 * @version        $Id: system.retrieval.class.php 2017年6月18日 11:39  weimeng
 * @package        WMCMS
 * @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
 * @link           http://www.weimengcms.com
 *
 */
class SystemRetrieval
{
	function GetType($module)
	{
		$reMod = NewModel('system.retrieval');
		$typeData = $reMod->GetType(array('where'=>array('type_module'=>$module)));
		$typeArr = array();
		if( $typeData )
		{
			foreach ($typeData as $k=>$v)
			{
				$typeArr[$v['type_id']] = $v['type_name'];
			}
		}
		return $typeArr;
	}
}
?>
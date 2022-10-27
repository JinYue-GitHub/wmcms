<?php
/**
* 搜索标签处理文件
*
* @version        $Id: search.label.php 2015年9月19日 11:24  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2015年12月28日 17:32 weimeng
*
*/
class searchlabel extends search
{
	function __construct()
	{		
		//数组键：类名，值：方法名
		$CF['search'] = 'GetData';
		
		$repFun['t']['searchlabel'] = 'SearchPublic';
		tpl::Label('{热门搜索:[s]}[a]{/热门搜索}','hot', $CF, $repFun['t']);
	}
	
	
	static function SearchPublic($data,$blcode){
		$code = '';
		$i = 1;
		//循环数据
		foreach ($data as $k => $v)
		{
			//没组数据循环，以字段名为标签名
			foreach ($v as $key => $val)
			{
				$arr1[L.$key]=$v[$key];
			}
			//每次循环重新调取原始标签
			$lcode = $blcode;
				
			//计数器标签和选中标签替换
			$lcode = tpl::I( $lcode , $i );
				
			//显示固定字数标签
			$keyArr = tpl::Exp('{搜索关键字:[d]}' , $v['search_key'] , $lcode);

			//设置自定义中文标签
			$arr2=array(
				'i'=>$i,
				'url'=>'/module/search/search.php?key='.$v['search_key'].'&type='.$v['search_type'].'&module='.$v['search_module'],
				'搜索关键字'=>$v['search_key'],
				'搜索类型'=>GetType($v['search_type']),
				'搜索类型代码'=>$v['search_type'],
				'搜索关键字:'.GetKey($keyArr,'0')=>GetKey($keyArr,'1'),
			);
			//合并两组标签
			$arr = array_merge($arr1,$arr2);
			//替换标签
			$code.=tpl::rep($arr,$lcode);
			$i++;
		}
	
		//返回最后的结果
		return $code;
	}
}
?>
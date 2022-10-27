<?php
/**
* 文章模块配置控制器文件
*
* @version        $Id: system.seo.createrewrite.php 2016年9月19日 10:18  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
//所有模块分类
$moduleArr = GetModuleName('replay.search');
$config = $htaccess = $ini = array();

$where['table'] = '@seo_urls';
$where['where']['urls_url2'] = array('notlike','.php');
$data = wmsql::GetAll($where);
if( $data )
{
	foreach ($data as $key=>$val)
	{
		//动态地址
		$url1 = $val['urls_url1'];
		//静态地址
		$url2 = $val['urls_url2'];
		//匹配出的地址
		$urlArr = tpl::Tag('{[s]}',$url2);
		
		//如果伪静态地址不存在pt就去掉动态地址的tp
		if( !str::in_string('{pt}', $url2) )
		{
			$url1 = tpl::rep(array('pt={pt}&'=>'','?pt={pt}'=>'','&pt={pt}'=>'','{pt}'=>''),$url1,2);
		}
		
		$i = 1;
		if( GetKey($urlArr,'1,0') == '')
		{
			//三种伪静态
			$htaccess['url1'] = $url1;
			$htaccess['url2'] = $url2;
			$config['url1'] = $url1;
			$config['url2'] = $url2;
			$ini['url1'] = $url1;
			$ini['url2'] = $url2;
		}
		else
		{
			foreach ( $urlArr[1] as $k=>$v)
			{
				$url1Par[$v] = '$'.$i;
				$url2Par[$v] = '{s}';
				//config伪静态替换
				$url11Par[$v] = '{R:'.$i.'}';
	
				if( $v=='page' || strstr($v,"id"))
				{
					$url2Par[$v] = '(\d*)';
					//其他伪静态规则
					$url2 = str_replace('{'.$v.'}', '(\d*)' , $url2);
				}
				//分类的拼音
				else if( $v == 'tpinyin')
				{
					$url1 = str_replace('{tid}', $url1Par[$v] , $url1);
					$url2 = str_replace('{'.$v.'}', $url2Par[$v] , $url2);
				}
				//内容的拼音
				else if( strstr($v,"pinyin") )
				{
					$url1 = str_replace('{cid}', $url1Par[$v] , $url1);
					$url1 = str_replace('{did}', $url1Par[$v] , $url1);
					$url1 = str_replace('{nid}', $url1Par[$v] , $url1);
					$url1 = str_replace('{aid}', $url1Par[$v] , $url1);
					$url1 = str_replace('{pid}', $url1Par[$v] , $url1);
					$url2 = str_replace('{'.$v.'}', $url2Par[$v] , $url2);
				}

				//ini伪静态地址
				$url22 = str_replace('.', '\.' , $url2);
				$url22 = str_replace('?', '\?' , $url2);
				$url22 = str_replace('{'.$v.'}', '(\d*)' , $url22);
				//ini的动态地址
				$url21 = str_replace('.', '\.' , $url1);
				$url21 = str_replace('?', '\?' , $url21);
				
				$url2Par['s'] = '([^/]*)';
				//三种伪静态
				$htaccess['url1'] = tpl::Rep($url1Par,$url1);
				$htaccess['url2'] = tpl::Rep($url2Par,$url2);
				$config['url1'] = tpl::Rep($url11Par,$url1);
				$config['url2'] = tpl::Rep($url2Par,$url2);
				$ini['url1'] = tpl::Rep($url1Par,$url21);
				$ini['url2'] = tpl::Rep($url2Par,$url22);
				
				$i++;
			}
		}

		//url1清理
		$clearUrl1['&copy'] = '&#38;copy';
		$ini['url1'] = tpl::Rep($clearUrl1,$ini['url1'],2);
		$config['url1'] = tpl::Rep($clearUrl1,$config['url1'],2);
		$htaccess['url1'] = tpl::Rep($clearUrl1,$htaccess['url1'],2);
		//url2清理
		$clearUrl2['{s}'] = '(.*?)';
		$ini['url2'] = tpl::Rep($clearUrl2,$ini['url2'],2);
		$config['url2'] = tpl::Rep($clearUrl2,$config['url2'],2);
		$htaccess['url2'] = tpl::Rep($clearUrl2,$htaccess['url2'],2);
		
		$pageData[$val['urls_module']][$val['urls_id']]['htaccess'] = $htaccess;
		$pageData[$val['urls_module']][$val['urls_id']]['config'] = $config;
		$pageData[$val['urls_module']][$val['urls_id']]['ini'] = $ini;
		$pageData[$val['urls_module']][$val['urls_id']]['name'] = $val['urls_pagename'];
		$pageData[$val['urls_module']][$val['urls_id']]['page'] = $val['urls_page'];
	}
}
?>
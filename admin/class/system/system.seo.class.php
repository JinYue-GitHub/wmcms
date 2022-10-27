<?php
/**
 * SEO优化类文件
 *
 * @version        $Id: system.seo.class.php 2016年4月7日 10:09  weimeng
 * @package        WMCMS
 * @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
 * @link           http://www.weimengcms.com
 *
 */
class SystemSeo
{
	
	/**
	 * 生成seo优化的缓存文件
	 */
	function UpConfig()
	{
		$htmls = array();
		$keys = $urls = '';
		//先查询关键词信息
		$where['table'] = '@seo_keys';
		$where['field'] = 'keys_page as page,keys_title as title,keys_key as `key`,keys_desc as `desc`';
		$keyArr = wmsql::GetAll($where);
		foreach ($keyArr as $k=>$v) {
			//$keys[$v['page']]=$v;
			$keys .= "'{$v['page']}'=>array('page'=>'{$v['page']}','title'=>'{$v['title']}','key'=>'{$v['key']}','desc'=>'{$v['desc']}'),";
		}
		//$keys = var_export($keys, true);
		$keys = 'array('.$keys.')';

		
		//再查询url
		$where['table'] = '@seo_urls';
		$where['field'] = 'urls_page as page,urls_url1 as url1,urls_url2 as url2,urls_url3 as url3,urls_url4 as url4,urls_url5 as url5,urls_url6 as url6';
		$urlArr = wmsql::GetAll($where);
		foreach ($urlArr as $k=>$v) {
			//$urls[$v['page']]=$v;
			$urls .= "'{$v['page']}'=>array('page'=>'{$v['page']}','url1'=>'{$v['url1']}','url2'=>'{$v['url2']}','url3'=>'{$v['url3']}','url4'=>'{$v['url4']}','url5'=>'{$v['url5']}','url6'=>'{$v['url6']}'),";
		}
		$urls = 'array('.$urls.')';
		//$urls = var_export($urls, true);
		
		//查询静态路径规则
		$where['table'] = '@seo_html';
		$where['field'] = 'html_module,html_type,html_type_id,html_path4';
		$htmlArr = wmsql::GetAll($where);
		if( $htmlArr )
		{
			foreach ($htmlArr as $k=>$v)
			{
				$htmls[$v['html_module']][$v['html_type_id']][$v['html_type']]=array('path4'=>$v['html_path4']);
			}
			$htmls = var_export($htmls, true);
		}
		else
		{
			$htmls = "''";
		}
		
		
		$filename = WMCONFIG."seo.config.php";//定义好要创建的文件名称
		file_put_contents($filename,'<?php $C["config"]["seo"]["keys"]='.$keys.';$C["config"]["seo"]["urls"]='.$urls.';$C["config"]["seo"]["htmls"]='.$htmls.';?>');
		
		return true;
	}
	
	
	
	/**
	 * 查询分类的静态路径
	 * @param 参数1，必须，模板的id
	 */
	function GetHtml( $module , $type , $id )
	{
		$where['field'] = 'html_path4';
		$where['table'] = '@seo_html';
		$where['where']['html_module'] = $module;
		$where['where']['html_type'] = $type;
		$where['where']['html_type_id'] = $id;
		$data = wmsql::GetOne($where);
		
		if( $data )
		{
			$data = $data['html_path4'];
		}
		
		return $data;
	}
	
	
	
	/**
	 * 设置模块分类的html的url格式路径
	 * @param 参数1，必须，模块
	 * @param 参数2，必须，html数组
	 * @param 参数3，必须，分类的id
	 */
	function SetTypeHtml($module , $arr , $tid )
	{
		$table = '@seo_html';
		//如果分类id存在，并且有数据才进来。
		if( str::Number($tid) && is_array($arr) )
		{
			foreach ( $arr as $k=>$v )
			{
				//查询
				$where['table'] = $table;
				$where['where']['html_module'] = $module;
				$where['where']['html_type'] = $k;
				$where['where']['html_type_id'] = $tid;
				$htmlData = wmsql::GetOne($where);

				//存在数据就删除当前的数据
				if( $htmlData && $v == '' )
				{
					$wheresql['html_id'] = $htmlData['html_id'];
					wmsql::Delete($table , $wheresql);
				}
				//存在数据，并且设置的html规则不一样
				else if ( $htmlData && $v != '' && $htmlData['html_path4'] != $v )
				{
					$data['html_path4'] = $v;
					$wheresql['html_id'] = $htmlData['html_id'];
					wmsql::Update($table, $data, $wheresql);
				}
				//不存在数据并且设置的不为空，就插入一条
				else if ( !$htmlData && $v != '' )
				{
					$where['where']['html_path4'] = $v;
					wmsql::Insert($table, $where['where']);
				}
				$where = array();
			}
			return true;
		}
	}
}
?>
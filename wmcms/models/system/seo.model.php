<?php
/**
* SEO模块模型
*
* @version        $Id: seo.model.php 2017年6月6日 20:31  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class SeoModel
{
	public $errpageTable = '@seo_errpage';
	public $spiderTable = '@seo_spider';
	public $urlTable = '@seo_urls';
	public $keyTable = '@seo_keys';
	
	function __construct( $data = '' ){}

	
	/**
	 * 查询url是否存在
	 * @param 参数1，必须，模块
	 * @param 参数2，必须，页面标识
	 */
	function GetUrlByPage($module,$page)
	{
		$where['table'] = $this->urlTable;
		$where['where']['urls_module'] = $module;
		$where['where']['urls_page'] = $page;
		return wmsql::GetOne($where);
	}


	/**
	 * 查询seo信息是否存在
	 * @param 参数1，必须，模块
	 * @param 参数2，必须，页面标识
	 */
	function GetSeoByPage($module,$page)
	{
		$where['table'] = $this->keyTable;
		$where['where']['keys_module'] = $module;
		$where['where']['keys_page'] = $page;
		return wmsql::GetOne($where);
	}
	

	/**
	 * 写入url页面数据
	 * @param 参数1，必须，数据
	 */
	function AddUrl($data)
	{
		return wmsql::Insert($this->urlTable, $data);
	}
	
	
	/**
	 * 写入seo关键词页面数据
	 * @param 参数1，必须，数据
	 */
	function AddKey($data)
	{
		return wmsql::Insert($this->keyTable, $data);
	}
	
	
	
	/**
	 * 写入错误页面数据
	 * @param 参数1，必须，错误页面代码
	 */
	function AddErrPage( $code )
	{
		$data['errpage_code'] = $code;
		$data['errpage_url'] = substr(GetUrl(),0,500);
		$data['errpage_ua'] = substr(isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:'',0,500);
		$data['errpage_time'] = time();
		return wmsql::Insert($this->errpageTable, $data);
	}
	

	/**
	 * 写入蜘蛛爬行记录
	 * @param 参数1，必须，蜘蛛数据
	 */
	function AddSpider( $spiderData )
	{
		if( isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != '' )
		{
			$url = DOMAIN.$_SERVER['REQUEST_URI'];
		}
		else
		{
			$url = GetUrl();
		}
		$data['spider_group'] = $spiderData['group'];
		$data['spider_group_name'] = $spiderData['group_name'];
		$data['spider_title'] = $spiderData['spider'];
		$data['spider_name'] = $spiderData['spider_name'];
		$data['spider_url'] = $url;
		$data['spider_ua'] = isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:'';
		$data['spider_time'] = time();
		return wmsql::Insert($this->spiderTable, $data);
	}
}
?>
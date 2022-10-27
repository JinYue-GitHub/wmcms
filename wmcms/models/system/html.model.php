<?php
/**
* HTML生成模块模型
*
* @version        $Id: html.model.php 2017年5月19日 22:08  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class HtmlModel
{
	private $config;
	private $httpSer;
	private $tableSer;
	private $domain;
	private $module;
	
	function __construct( $data = '' )
	{
		global $tableSer,$C;
		$this->config = $C;
		$this->httpSer = NewClass('http');
		$this->tableSer = $tableSer;
		$this->domain = DOMAIN;
		$this->module = $data['module'];
		$moduleConfig = GetModuleConfig($data['module']);
		$this->autoOpen = $moduleConfig['auto_create_html'];
	}
	
	/**
	 * 生成内容HTML
	 * @param 参数1，必须，所属的模块
	 * @param 参数2，必须，内容id
	 */
	function CreateContentHtml( $cid )
	{
		if( $this->autoOpen == 0 )
		{
			return false;
		}
		else
		{
			$tableSer = $this->tableSer;
			$C = $this->config;
			$module = $this->module;
			$cTable = $tableSer->tableArr[$module]['table'];
			$cidName = $tableSer->tableArr[$module]['id'];
			$timeName = $tableSer->tableArr[$module]['time'];
			$cPYName = GetKey($tableSer->tableArr,$module.',pinyin');
			$tTable = $tableSer->tableArr[$module.'type']['table'];
			$tidName = $tableSer->tableArr[$module.'type']['id'];
			$pidName = $tableSer->tableArr[$module.'type']['pid'];
			$tPYName = GetKey($tableSer->tableArr,$module.'type,pinyin');
			$cPYField = $tPYField = '';
			if( $cPYName != '' )
			{
				$cPYField = ','.$cPYName.' as cpinyin';
			}
			if( $tPYName != '' )
			{
				$tPYField = ','.$tPYName.' as tpinyin';
			}
			
			$wheresql['table'] = $cTable.' as c';
			$wheresql['field'] = 't.'.$tidName.' as tid,'.$cidName.' as cid'.$cPYField.$tPYField;
			$wheresql['left'][$tTable.' as t'] = 't.'.$tidName.' = c.'.$tidName;
			//如果是小说就查询章节内容
			if( $module == 'novel' )
			{
				$wheresql['field'] .= ',chapter_id as rid';
				$wheresql['left']['@novel_chapter as r'] = 'chapter_nid = '.$cidName;
				$wheresql['where']['chapter_id'] = array('>',0);
				$cidName = 'chapter_id';
				$timeName = 'chapter_time';
			}
			//如果是文章模块就只查询显示的
			if( $module == 'article' )
			{
				$wheresql['where']['article_display'] = 1;
			}
			$wheresql['where'][$cidName] = $cid;
			$data = wmsql::GetOne($wheresql);
			
			if( $data )
			{
				//默认html保存路径
				$htmlPath = GetKey($C,'config,seo,htmls,'.$module.','.$data['tid'].',content,path4');
				//小说页面的内容
				if($module == 'novel')
				{
					$htmlPath = GetKey($C,'config,seo,htmls,'.$module.','.$data['tid'].',read,path4');
				}
				
				if( $htmlPath == '' )
				{
					return false;
				}
				else
				{
					$par = array('pid'=>$data['cid'],'nid'=>$data['cid'],'aid'=>$data['cid'],'tid'=>$data['tid'],'cid'=>GetKey($data,'rid'),'tpinyin'=>$data['tpinyin'],'apinyin'=>GetKey($data,'cpinyin'),'npinyin'=>GetKey($data,'cpinyin'));
					$urlType = $module.'_'.$module;
					if($module == 'novel')
					{
						$urlType = 'novel_read';
					}
					
					$htmlPath = tpl::Rep($par , $htmlPath);
					$url = tpl::url( $urlType , $par , 1);
					//如果url没有带上http协议就添加当前的域名
					if( !str::CheckUrl($url) )
					{
						$url = $this->domain.$url;
					}
					$httpSer = NewClass('http');
					$html = $this->httpSer->GetUrl($url);
					file::CreateFile(WMROOT.$htmlPath, $html , 1);
					return true;
				}
			}
			else
			{
				return false;
			}
		}
	}
}
?>
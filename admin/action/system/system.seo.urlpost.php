<?php
/**
* 静态文件生成处理器
*
* @version        $Id: system.seo.urlpost.php 2017年5月9日 21:47  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$sptype = Request('sptype');
$module = Request('module');
$urls = Request('urls');
$contentWhere = Request('contentWhere');
$startid = Request('startid');
$endid = Request('endid');
$starttime = Request('starttime');
$endtime = Request('endtime');

//提交url
if ( $type == 'post' )
{
	if( $sptype=='' )
	{
		Ajax('对不起，请选择搜索引擎类型!',300);
	}
	else if ( $module=='' && $urls == '' )
	{
		Ajax('对不起，自动提交和手动提交必需选择一项!',300);
	}
	else if($contentWhere == ''  && $urls == '' )
	{
		Ajax('对不起，请选择生成条件!',300);
	}
	else if( C('config.api.bdurl.api_open') == 0 )
	{
		Ajax('对不起，url提交接口已经被关闭!',300);
	}
	else
	{
		//如果是直接提交url
		if($urls != '' )
		{
			$urlArr = explode("\n", $urls);
		}
		else
		{
			//获得表字段等信息。
			$cTable = $tableSer->tableArr[$module]['table'];
			$cidName = $tableSer->tableArr[$module]['id'];
			$cPYName = GetKey($tableSer->tableArr,$module.',pinyin');
			$cTimeName = $tableSer->tableArr[$module]['time'];
			$tTable = $tableSer->tableArr[$module.'type']['table'];
			$tidName = $tableSer->tableArr[$module.'type']['id'];
			$tPYName = $tableSer->tableArr[$module.'type']['pinyin'];
			$cPYField = $tPYField = '';
			if( $cPYName != '' )
			{
				$cPYField = ','.$cPYName;
			}
			if( $tPYName != '' )
			{
				$tPYField = ','.$tPYName;
			}

			$wheresql['table'] = $cTable.' as c';
			$wheresql['field'] = 't.'.$tidName.','.$cidName.$cPYField.$tPYField;
			$wheresql['left'][$tTable.' as t'] = 't.'.$tidName.' = c.'.$tidName;
			if( $contentWhere == 'id' )
			{
				$wheresql['where'][$cidName] = array('between',$startid.','.$endid);
			}
			else if( $contentWhere == 'time' )
			{
				$wheresql['where'][$cTimeName] = array('between',$startid.','.$endid);
			}
			else
			{
				$wheresql['order'] = $cTimeName.' desc';
			}
			$wheresql['limit'] = 2000;
			$data = wmsql::GetAll($wheresql);
			if( !$data )
			{
				Ajax('对不起，当前条件下没有内容!',300);
			}
			else
			{
				foreach ($data as $k=>$v)
				{
					$cid = $v[$cidName];
					$cPinYin = GetKey($v,$cPYName);
					$tid = $v[$tidName];
					$tPinYin = GetKey($v,$tPYName);
					$par = array('pid'=>$cid,'nid'=>$cid,'aid'=>$cid,'tid'=>$tid,'tpinyin'=>$tPinYin,'apinyin'=>$cPinYin,'npinyin'=>$cPinYin);
					$urlType = $module.'_'.$module;
					switch ($module)
					{
						case "novel":
							$urlType = 'novel_info';
							break;
						default:
							break;
					}
					$url = tpl::url( $urlType , $par);
					if( !str::in_string('http:',$url) && !str::in_string('https:',$url) )
					{
						$url = TCP_TYPE.'://'.WEB_URL.$url;
					}
					$urlArr[] = $url;
				}
			}
		}

		//提交url
		$seoSer = NewClass('seo');
		$result = $seoSer->UrlPost($sptype , $urlArr);
		Ajax($result['message'],$result['code'],GetKey($result,'data'));
	}
}
?>
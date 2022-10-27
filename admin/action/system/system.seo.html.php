<?php
/**
* 静态文件生成处理器
*
* @version        $Id: system.seo.html.php 2017年2月13日 23:47  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$httpSer = NewClass('http');
$domain = DOMAIN;
$step = Request('step');
$module = Request('module');
$pagetype = Request('pagetype');
$tid = Request('tid');
$tpinyin = Request('tpinyin');
$where = Request('where');
$page = Request('page');
$startid = Request('startid');
$endid = Request('endid');
$starttime = Request('starttime');
$endtime = Request('endtime');
$id = Request('id');
//设置不是为html
tpl::SetIsHtml(0);
		
//检查是否是静态的路径
function pathIsHtml($url)
{
	if( str_replace('.php', '', $url) != $url )
	{
		Ajax('对不起，当前链接没有设置伪静态的保存路径！' , 300);
	}
	return $url;
}

//检查是否开启了html访问
if( $C['config']['route']['ishtml'] != '1' )
{
	Ajax('对不起，网站没有开启HTML静态访问！' , 300);
}
//生成首页
else if ( $type == 'index' )
{
	$id = $C['config']['web']['tpmark'.Request('id')];
	$path = Post('path');
	
	if( $module == '' || $id == '' )
	{
		Ajax('参数错误!',300);
	}
	else
	{
		$urlType = 'index';
		if( $module != 'index')
		{
			$urlType = $module.'_index';
		}
		$url = GetKey($C,'config,seo,urls,'.$urlType.',url1');
		//如果保存路径为空就设置读取伪静态的地址。
		if( $path == '' )
		{
			$path = GetKey($C,'config,seo,urls,'.$urlType.',url2');
		}
		
		if($url == '' || $path == '' )
		{
			Ajax('对不起，当前分类首页不存在！');
		}
		else
		{
			$url = str_replace('{pt}', $id, $url);
			$path = pathIsHtml(tpl::PtRep($path , $id));
			if( $module == 'novel' )
			{
				for($i=0;$i<3;$i++)
				{
					$tpArr = array('','boy','girl');
					$newUrl = str_replace('{tp}', $tpArr[$i], $url);
					$newPath = str_replace('{tp}', $tpArr[$i], $path);
					$html = $httpSer->GetUrl($domain.$newUrl);
					file::CreateFile(WMROOT.$newPath, $html , 1);
				}
				$path = $newPath;
			}else{
				$html = $httpSer->GetUrl($domain.$url);
				file::CreateFile(WMROOT.$path, $html , 1);
			}
			Ajax($path.'生成成功！');
		}
	}
}
//根据模块获取分类
else if ( $type == 'gettype' )
{
	if( $module == '' )
	{
		$data[] = array('type_id'=>0,'type_topid'=>0,'type_pid'=>0,'type_name'=>'对不起，请选择模块');
		Ajax('获取成功' , 200 , $data);
	}
	else
	{
		$wheresql['table'] = '@'.$module.'_type';
		$wheresql['field'] = 'type_id,type_topid,type_pid,type_name';
		$wheresql['order'] = 'type_order';
		if( $module == 'article' )
		{
			$wheresql['where']['type_status'] = 1;
		}
		$data = wmsql::GetAll($wheresql);

		Ajax('获取成功' , 200 , $data);
	}
}

/**
 * 生成html的初始化操作和生成操作
 */
//生成列表html初始化操作
else if( $type == 'list' && $step == 'init')
{
	if( Request('module') == '' )
	{
		Ajax('请选择模块!' , 300);
	}
	$child = Post('child');
	$all = Post('all');
	$tTable = $tableSer->tableArr[$module.'type']['table'];
	$tidName = $tableSer->tableArr[$module.'type']['id'];
	$pidName = $tableSer->tableArr[$module.'type']['pid'];
	$tPYName = $tableSer->tableArr[$module.'type']['pinyin'];
	if( $tPYName != '' )
	{
		$tPYField = ','.$tPYName;
	}
	$cTable = $tableSer->tableArr[$module]['table'];

	$wheresql['table'] = $tTable;
	$wheresql['field'] = 'type_id,type_tempid'.$tPYField;
	if( $tid != '0' && $child == '1')
	{
		$wheresql['where'][$tidName] = array('string',"(FIND_IN_SET({$tid},{$pidName})) or {$tidName}={$tid}");
	}
	else if( $tid != '0')
	{
		$wheresql['where'][$tidName] = $tid;
	}
	if( $module == 'article' )
	{
		$wheresql['where']['type_status'] = 1;
	}

	$data = wmsql::GetAll($wheresql);
	//加入一个全部分类
	if( $all == '1' )
	{
		$data[] = array(
				$tidName =>'0',
				$tPYName =>'all',
				'type_tempid'=>0
		);
	}
	if( $data )
	{
		//总数据量
		$sumData = 0;
		//总数据的页数
		$sumPageCount = 0;
		//每页数量
		$pageCount = 1;
		$count = 1;

		//引入模块分类
		NewModuleClass($module);
		//引入模版服务
		$tempSer = AdminNewClass('system.templates');
		//列表页标签
		switch ($module)
		{
			case 'article':
				$listLabel = '文章列表';
				break;
			case 'novel':
				$listLabel = '小说列表';
				break;
			case 'picture':
				$listLabel = '图集列表';
				break;
		}

		foreach ($data as $k=>$v)
		{
			$tempId = $v['type_tempid'];
			if($tempId > 0)
			{
				$tempFile = $tempSer->GetTemp($tempId , 'temp_temp4');
			}
			else
			{
				$tempFile = WMTEMPLATE.$C['config']['web']['tp'.Request('id')].'/'.$module.'/type.html';
			}
			//打开模版文件
			$tempCode = file::GetFile($tempFile);
			if( $tempCode != '' )
			{
				//获得分类的标签
				if( !empty($listLabel) )
				{
					$labelArr = tpl::Tag('{'.$listLabel.':[s]}[a]{/'.$listLabel.'}', $tempCode);
					//获得条件
					$where = $module::GetWhere(GetKey($labelArr,'1,0'));
					$where['table'] = $cTable.' as c';
					$where['left'][$tTable.' as t'] = 't.'.$tidName.' = c.'.$tidName;
					if(GetKey($labelArr,'1,0')!= '')
					{
						if( $v[$tidName] > 0 )
						{
							$where['where']['type_pid'] = array('and-or',array('rin',$v[$tidName]),array('t.'.$tidName=>$v[$tidName]));
						}
						if( $where['limit'] != '' )
						{
							list($a,$pageCount) = explode(',', $where['limit']);
							unset($where['order']);
							unset($where['limit']);
						}
						$count = wmsql::GetCount($where);
					}
					else
					{
						$count = $pageCount = '1';
					}
				}
				$pageSum = ceil(($count)/$pageCount);
				if( $pageSum == 0 )
				{
					$pageSum = 1;
				}
				else if($page <= $pageSum && $page != 0)
				{
					$pageSum = intval($page);
				}
				$newData[] = array(
						'tid'=>$v[$tidName],
						'tpinyin'=>$v[$tPYName],
						'sumpage'=>$pageSum,
						'nowpage'=>1,
				);
				$sumData++;
				$sumPageCount += $pageSum;
			}
		}
		//生成临时文件保存
		$cacheData['sum_data'] = $sumData;
		$cacheData['sum_page_count'] = $sumPageCount;
		$cacheData['now_data'] = 0;
		$cacheData['now_page_count'] = 0;
		$cacheData['data'] = $newData;
		//记录开始时间
		$cacheData['start_time'] = time();
		file::CreateFile(WMCACHE.'createhtml/'.$module.'_type.txt', serialize($cacheData),1);
		Ajax('初始化成功！' , 200 );
	}
	else
	{
		Ajax('没有需要生成的数据!' , 300);
	}
}
//生成列表操作
else if( $type == 'list' && $step == 'create')
{
	if( Request('module') == '' )
	{
		Ajax('请选择模块!' , 300);
	}
	else
	{
		//获得需要生成的分类信息
		$cacheData = unserialize(file_get_contents(WMCACHE.'createhtml/'.$module.'_type.txt'));
		//url类型
		$urlType = $module.'_type';
		if( empty($cacheData['data']) )
		{
			//删除临时文件
			file::DelFile(WMCACHE.'createhtml/'.$module.'_type.txt');
			
			die('<html><head></head><body>任务完成，本次共生成'.$cacheData['sum_data'].'个分类，'.$cacheData['sum_page_count'].'个页面!<br/>任务开始时间：'.date('Y-m-d H:i:s',$cacheData['start_time']).'<br/>任务结束时间：'.date('Y-m-d H:i:s',time()).'</body></html>');
		}
		else
		{
			//当前循环次数
			$nowForNumber = 0;
			//循环数据
			foreach ($cacheData['data'] as $k=>$v)
			{
				//获取html保存路径
				if( $v['tid'] == 0)
				{
					$htmlPath = current(GetKey($C,'config,seo,htmls,'.$module));
					$htmlPath = GetKey($htmlPath,'list,path4');
				}
				else
				{
					$htmlPath = GetKey($C,'config,seo,htmls,'.$module.','.$v['tid'].',list,path4');
				}
				for( $i = $v['nowpage'] ; $i <= $v['sumpage'] ; $i++)
				{
					//需要替换的参数
					$par = array('tid'=>$v['tid'],'tpinyin'=>$v['tpinyin'],'page'=>$i);
					//替换url和保存路径的参数
					$url = tpl::url( $urlType , $par , 1);
					$newHtmlPath = tpl::Rep($par , $htmlPath);
					//获取html源码保存
					$html = $httpSer->GetUrl($domain.$url);
					file::CreateFile(WMROOT.$newHtmlPath, $html , 1);
					
					$nowForNumber++;
					//当前已经生成了3个页面就跳出循环
					if( $nowForNumber >= 3 )
					{
						//当前的i小于总的次数就修改当前数据的当前页数
						if( $i < $v['sumpage'] )
						{
							$cacheData['data'][$k]['nowpage'] = $i+1;
						}
						//如果在总页数循环次数内就删除当前值
						else if( $i == $v['sumpage'] )
						{
							unset($cacheData['data'][$k]);
						}
						break 2;
					}
					
					//如果小于3次也删除
					if( $i == $v['sumpage'] )
					{
						unset($cacheData['data'][$k]);
					}
				}
			}
		}
		//重新保存临时文件
		$cacheData['now_data'] = $cacheData['sum_data']-count($cacheData['data']);
		if( $cacheData['now_data'] == 0 )
		{
			$cacheData['now_data'] = 1;
		}
		$cacheData['now_page_count'] = $cacheData['now_page_count'] + $nowForNumber;
		file::CreateFile(WMCACHE.'createhtml/'.$module.'_type.txt', serialize($cacheData),1);

		//显示完成进度
		$progress = round($cacheData['now_page_count']/$cacheData['sum_page_count']*100,2);
		//进度大于等于100就清空数据
		if( $progress >= 100 )
		{
			unset($cacheData['data']);
			file::CreateFile(WMCACHE.'createhtml/'.$module.'_type.txt', serialize($cacheData),1);
		}
		die('<html><head><meta http-equiv="refresh" content="1;url="></head><body>生成中....['.$cacheData['now_page_count'].'/'.$cacheData['sum_page_count'].']已完成'.$progress.'%！</body></html>');
	}
}


//生成内容html初始化操作
else if( $type == 'content' && $step == 'init')
{
	$child = Post('child');
	$pageType = Post('pagetype');
	if( Request('module') == '' )
	{
		Ajax('请选择模块!' , 300);
	}
	$cTable = $tableSer->tableArr[$module]['table'];
	$cidName = $tableSer->tableArr[$module]['id'];
	$timeName = $tableSer->tableArr[$module]['time'];
	$cPYName = GetKey($tableSer->tableArr,$module.',pinyin');
	$tTable = $tableSer->tableArr[$module.'type']['table'];
	$tidName = $tableSer->tableArr[$module.'type']['id'];
	$pidName = $tableSer->tableArr[$module.'type']['pid'];
	$tPYName = $tableSer->tableArr[$module.'type']['pinyin'];
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
	if( $pagetype == 'read' )
	{
		$wheresql['field'] .= ',chapter_id as rid';
		$wheresql['left']['@novel_chapter as r'] = 'chapter_nid = '.$cidName;
		$wheresql['where']['chapter_id'] = array('>',0);
		$cidName = 'chapter_id';
		$timeName = 'chapter_time';
	}
	if( $module == 'article' )
	{
		$wheresql['where']['article_display'] = 1;
	}
	
	if( $tid != '0' && $child == '1')
	{
		$wheresql['where'][$cidName] = array('string',"(FIND_IN_SET({$tid},t.{$pidName})) or t.{$tidName}={$tid}");
	}
	else if( $tid != '0')
	{
		$wheresql['where']['t.'.$tidName] = $tid;
	}
	
	//id查询条件
	if( $where == 'id' )
	{
		//如果结束id等于0就指定id生成
		if( $endid == '0' )
		{
			$wheresql['where'][$cidName] = $startid;
		}
		else
		{
			$wheresql['where'][$cidName] = array('between',$startid.','.$endid);
		}
	}
	//时间查询条件
	if( $where == 'time' )
	{
		$starttime = strtotime($starttime);
		$endtime = strtotime($endtime);
		$wheresql['where'][$timeName] = array('between',$starttime.','.$endtime);
	}
	$data = wmsql::GetAll($wheresql);
	if( $data )
	{
		//生成临时文件保存
		$cacheData['page_type'] = $pageType;
		$cacheData['sum_data'] = count($data);
		$cacheData['now_data'] = 0;
		$cacheData['data'] = $data;
		//记录开始时间
		$cacheData['start_time'] = time();
		file::CreateFile(WMCACHE.'createhtml/'.$module.'_content.txt', serialize($cacheData),1);
		Ajax('初始化成功！' , 200 );
	}
	else
	{
		Ajax('没有需要生成的数据!' , 300);
	}
}
//生成内容html操作
else if( $type == 'content' && $step == 'create')
{
	if( Request('module') == '' )
	{
		Ajax('请选择模块!' , 300);
	}
	else
	{
		//获得需要生成的分类信息
		$cacheData = unserialize(file_get_contents(WMCACHE.'createhtml/'.$module.'_content.txt'));
		//url类型
		$urlType = $module.'_'.$module;
		if( empty($cacheData['data']) )
		{
			//删除临时文件
			file::DelFile(WMCACHE.'createhtml/'.$module.'_content.txt');
			die('<html><head></head><body>任务完成，本次共生成'.$cacheData['sum_data'].'条数据!<br/>任务开始时间：'.date('Y-m-d H:i:s',$cacheData['start_time']).'<br/>任务结束时间：'.date('Y-m-d H:i:s',time()).'</body></html>');
		}
		else
		{
			//当前循环次数
			$nowForNumber = 0;
			//循环数据
			foreach ($cacheData['data'] as $k=>$v)
			{
				//获取html保存路径，默认为内容页面
				$htmlPath = GetKey($C,'config,seo,htmls,'.$module.','.$v['tid'].',content,path4');
				//是否是阅读页面
				if($cacheData['page_type'] == 'read')
				{
					$htmlPath = GetKey($C,'config,seo,htmls,'.$module.','.$v['tid'].',read,path4');
				}
				$par = array('pid'=>$v['cid'],'nid'=>$v['cid'],'aid'=>$v['cid'],'tid'=>$v['tid'],'cid'=>GetKey($v,'rid'),'tpinyin'=>$v['tpinyin'],'apinyin'=>GetKey($v,'cpinyin'),'npinyin'=>GetKey($v,'cpinyin'));
				switch ($module)
				{
					case "novel":
						$urlType = 'novel_info';
						if($cacheData['page_type'] == 'read')
						{
							$urlType = 'novel_read';
						}
						break;
					default:
						break;
				}
				//替换url和保存路径的参数
				$newHtmlPath = tpl::Rep($par , $htmlPath);
				$url = tpl::url( $urlType , $par , 1);
				//保存为文件
				$html = $httpSer->GetUrl($domain.$url);
				file::CreateFile(WMROOT.$newHtmlPath, $html , 1);
				
				$nowForNumber++;
				unset($cacheData['data'][$k]);
				//当前已经生成了3个页面就跳出循环
				if( $nowForNumber >= 3 )
				{
					break;
				}
			}
		}
		
		//重新保存临时文件
		$cacheData['now_data'] = $cacheData['sum_data']-count($cacheData['data']);
		if( $cacheData['now_data'] == 0 )
		{
			$cacheData['now_data'] = 1;
		}
		file::CreateFile(WMCACHE.'createhtml/'.$module.'_content.txt', serialize($cacheData),1);

		//显示完成进度
		$progress = round($cacheData['now_data']/$cacheData['sum_data']*100,2);
		//进度大于等于100就清空数据
		if( $progress >= 100 )
		{
			unset($cacheData['data']);
			file::CreateFile(WMCACHE.'createhtml/'.$module.'_content.txt', serialize($cacheData),1);
		}
		die('<html><head><meta http-equiv="refresh" content="1;url="></head><body>生成中....['.$cacheData['now_data'].'/'.$cacheData['sum_data'].']已完成'.$progress.'%！</body></html>');
	}
}


//生成分类首页html初始化操作
else if( $type == 'tindex' && $step == 'init')
{
	if( Request('module') == '' )
	{
		Ajax('请选择模块!' , 300);
	}
	$child = Post('child');
	$tTable = $tableSer->tableArr[$module.'type']['table'];
	$tidName = $tableSer->tableArr[$module.'type']['id'];
	$pidName = $tableSer->tableArr[$module.'type']['pid'];

	$wheresql['table'] = $tTable;
	$wheresql['field'] = 'type_pinyin,type_id,type_tempid';
	if( $tid != '0' && $child == '1')
	{
		$wheresql['where'][$tidName] = array('string',"(FIND_IN_SET({$tid},{$pidName})) or {$tidName}={$tid}");
	}
	else if( $tid != '0')
	{
		$wheresql['where'][$tidName] = $tid;
	}
	$data = wmsql::GetAll($wheresql);
	if( $data )
	{
		foreach ($data as $k=>$v)
		{
			$newData[] = array(
				'tid'=>$v['type_id'],
				'tpinyin'=>$v['type_pinyin'],
			);
		}
		Ajax('初始化成功！' , 200 , $newData , count($data));
	}
	else
	{
		Ajax('没有需要生成的数据!' , 300);
	}
}
//生成分类首页html操作
else if( $type == 'tindex' && $step == 'create')
{
	if( Request('module') == '' || Request('tid') == '')
	{
		Ajax('参数错误!' , 300);
	}
	$htmlPath = GetKey($C,'config,seo,htmls,'.$module.','.$tid.',tindex,path4');
	if( $htmlPath == '' )
	{
		Ajax('当前分类没有设置列表HTML保存路径!');
	}
	else
	{
		$par = array('tid'=>$tid,'tpinyin'=>$tpinyin);
		$htmlPath = tpl::Rep($par , $htmlPath);
		$pageType = $module.'_tindex';
		switch ($module)
		{
			default:
				break;
		}
		$url = tpl::url( $pageType , $par , 1);

		$html = $httpSer->GetUrl($domain.$url);
		file::CreateFile(WMROOT.$htmlPath, $html , 1);
		Ajax('分类ID：'.$tid.'的分类首页生成成功！');
	}
}

//生成目录列表html初始化操作
else if( $type == 'menu' && $step == 'init')
{
	if( Request('module') == '' )
	{
		Ajax('请选择模块!' , 300);
	}
	$child = Post('child');
	$tTable = $tableSer->tableArr[$module.'type']['table'];
	$tidName = $tableSer->tableArr[$module.'type']['id'];
	$pidName = $tableSer->tableArr[$module.'type']['pid'];
	$tPYName = $tableSer->tableArr[$module.'type']['pinyin'];
	if( $tPYName != '' )
	{
		$tPYField = ','.$tPYName;
	}
	$cTable = $tableSer->tableArr[$module]['table'];
	
	$wheresql['table'] = $tTable;
	$wheresql['field'] = 'type_id,type_mtempid'.$tPYField;
	if( $tid != '0' && $child == '1')
	{
		$wheresql['where'][$tidName] = array('string',"(FIND_IN_SET({$tid},{$pidName})) or {$tidName}={$tid}");
	}
	else if( $tid != '0')
	{
		$wheresql['where'][$tidName] = $tid;
	}
	$data = wmsql::GetAll($wheresql);
	if( $data )
	{
		$where = array();
		$menuData = array();
		$newData = array();
		$sumData = 0;
		$sumPageCount = 0;
		$pageCount = '';
		//引入模块分类
		NewModuleClass($module);
		//引入模版服务
		$tempSer = AdminNewClass('system.templates');
		foreach ($data as $k=>$v)
		{
			$tempId = $v['type_mtempid'];
			if($tempId > 0)
			{
				$tempFile = WMROOT.$tempSer->GetTemp($tempId , 'temp_temp4');
			}
			else
			{
				$tempFile = '/'.$module.'/menu.html';
				switch ($module)
				{
					case 'novel':
						$listLabel = '小说章节列表';
						break;
				}
				
				$tempFile = WMTEMPLATE.$C['config']['web']['tp'.Request('id')].$tempFile;
			}
			//打开模版文件
			$tempCode = file::GetFile($tempFile);
			if( $tempCode != '' )
			{
				//获得当前分类的所有小说
				$where['table'] = '@novel_novel as n';
				$where['field'] = 'novel_id,novel_pinyin';
				$where['left']['@novel_type as t'] = 'n.type_id = t.type_id';
				$where['where']['n.type_id'] = $v['type_id'];
				$where['group'] = 'novel_id';
				$novelData = wmsql::GetAll($where);
				if( $novelData )
				{
					//获得分类的标签
					$labelArr = tpl::Tag('{'.$listLabel.':[s]}[a]{/'.$listLabel.'}', $tempCode);
					//获得条件
					$where = $module::GetWhere($labelArr[1][0]);
					if( $where['limit'] != '' )
					{
						list($a,$pageCount) = explode(',', $where['limit']);
						unset($where['order']);
						unset($where['limit']);
					}
					$pageSum=1;
					foreach ($novelData as $k1=>$v1)
					{
						$chapterWhere['table'] = '@novel_chapter';
						$chapterWhere['field'] = 'chapter_id';
						$chapterWhere['where']['chapter_nid'] = $v1['novel_id'];
						$chapterWhere['where']['chapter_status'] = 1;
						if( $pageCount != '' )
						{
							$pageSum = ceil(wmsql::GetCount($chapterWhere)/$pageCount);
						}
						$menuData[] = array('cid'=>$v1['novel_id'],'cpinyin'=>$v1['novel_pinyin'],'sumpage'=>$pageSum,'nowpage'=>1);
						$sumData++;
						$sumPageCount += $pageSum;
					}
					$newData[] = array(
						'tid'=>$v[$tidName],
						'tpinyin'=>$v[$tPYName],
						'menu'=>$menuData,
					);
					$menuData = array();
					$where = array();
				}
			}
		}
		//生成临时文件保存
		$cacheData['sum_data'] = $sumData;
		$cacheData['sum_page_count'] = $sumPageCount;
		$cacheData['now_data'] = 0;
		$cacheData['now_page_count'] = 0;
		$cacheData['data'] = $newData;
		//记录开始时间
		$cacheData['start_time'] = time();
		file::CreateFile(WMCACHE.'createhtml/'.$module.'_menu.txt', serialize($cacheData),1);
		Ajax('初始化成功！' , 200 );
	}
	else
	{
		Ajax('没有需要生成的数据!' , 300);
	}
}
//生成目录列表html操作
else if( $type == 'menu' && $step == 'create')
{
	if( Request('module') == '' )
	{
		Ajax('请选择模块!' , 300);
	}
	else
	{
		//获得需要生成的分类信息
		$cacheData = unserialize(file_get_contents(WMCACHE.'createhtml/'.$module.'_menu.txt'));
		//url类型
		$urlType = $module.'_menu';
		if( empty($cacheData['data']) )
		{
			//删除临时文件
			file::DelFile(WMCACHE.'createhtml/'.$module.'_menu.txt');
			die('<html><head></head><body>任务完成，本次共生成'.$cacheData['sum_data'].'本书籍，'.$cacheData['sum_page_count'].'个页面!<br/>任务开始时间：'.date('Y-m-d H:i:s',$cacheData['start_time']).'<br/>任务结束时间：'.date('Y-m-d H:i:s',time()).'</body></html>');
		}
		else
		{
			//当前循环次数
			$nowForNumber = 0;
			//循环数据
			foreach ($cacheData['data'] as $k=>$v)
			{
				//获取html保存路径
				$htmlPath = GetKey($C,'config,seo,htmls,'.$module.','.$v['tid'].',menu,path4');
				
				//循环生成目录列表
				foreach ($v['menu'] as $k1=>$v1)
				{
					//需要替换的参数
					for( $i = $v1['nowpage'] ; $i <= $v1['sumpage'] ; $i++)
					{
						//需要替换的参数
						$par = array('tid'=>$v['tid'],'tpinyin'=>$v['tpinyin'],'nid'=>$v1['cid'],'npinyin'=>$v1['cpinyin'],'page'=>$i);
						//替换url和保存路径的参数
						$url = tpl::url( $urlType , $par , 1);
						$newHtmlPath = tpl::Rep($par , $htmlPath);
						//获取html源码保存
						$html = $httpSer->GetUrl($domain.$url);
						file::CreateFile(WMROOT.$newHtmlPath, $html , 1);
							
						$nowForNumber++;
						//当前已经生成了3个页面就跳出循环
						if( $nowForNumber >= 3 )
						{
							//当前的i小于总的次数就修改当前数据的当前页数
							if( $i < $v1['sumpage'] )
							{
								$cacheData['data'][$k]['nowpage'] = $i+1;
							}
							//如果在总页数循环次数内就删除当前值
							else if( $i == $v1['sumpage'] )
							{
								unset($cacheData['data'][$k]['menu'][$k1]);
							}
							break 3;
						}
						
						//如果小于3次也删除
						if( $i == $v1['sumpage'] )
						{
							unset($cacheData['data'][$k]['menu'][$k1]);
						}
					}
				}
			}
		}
		//重新保存临时文件
		$cacheData['now_data'] = $cacheData['sum_data']-count($cacheData['data']);
		if( $cacheData['now_data'] == 0 )
		{
			$cacheData['now_data'] = 1;
		}
		$cacheData['now_page_count'] = $cacheData['now_page_count'] + $nowForNumber;
		file::CreateFile(WMCACHE.'createhtml/'.$module.'_menu.txt', serialize($cacheData),1);

		//显示完成进度
		$progress = round($cacheData['now_page_count']/$cacheData['sum_page_count']*100,2);
		//进度大于等于100就清空数据
		if( $progress >= 100 )
		{
			unset($cacheData['data']);
			file::CreateFile(WMCACHE.'createhtml/'.$module.'_menu.txt', serialize($cacheData),1);
		}
		die('<html><head><meta http-equiv="refresh" content="1;url="></head><body>生成中....['.$cacheData['now_page_count'].'/'.$cacheData['sum_page_count'].']已完成'.$progress.'%！</body></html>');
	}
}
?>
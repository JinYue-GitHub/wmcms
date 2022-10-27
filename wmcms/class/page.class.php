<?php
/**
* 分页处理类
*
* @version        $Id: page.class.php 2015年12月22日 10:38  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime         2016年11月13日 15:56 weimeng
*
*/
class page{
	//分页数组
	static public $pageArr = '';
	static public $url;
	static public $dataCount;
	static public $limit;
	static public $page;
	

	/**
	 * 初始化分页类
	 * @param 参数1，必填，分页的url
	 * @param 参数2，必填，总数据量
	 * @param 参数3，必填，每页多少条。格式1为，0,10。格式2为，10
	 * @param 参数4，如果参数3为格式1，那么此参数为空。如果参数3为格式2，那么此参数必填。
	 */
	static public function Start( $url = '' , $dataCount = 0 , $limit = 0 , $page = 1 )
	{
		global $cacheSer;
		if($url == '' )
		{
			$url = 'javascript:alert(\'请设置listurl\');';
		}
		else
		{
			$url = self::Url($url);
		}
		
		self::$dataCount = $dataCount;
		self::$limit = $limit;
		self::$page = $page;
		self::$url = $url;

		if(	$cacheSer->siteOpen && $cacheSer->mechanism == 'block' )
		{
			$cacheName = GetUrlPath('3').'page/'.md5(Encrypt($_SERVER['QUERY_STRING']));
			$cacheContent = $dataCount.'|||'.$limit.'|||'.$page.'|||'.$url;
			$cacheSer->SetSite($cacheName , $cacheContent , $cacheSer->cacheTime);
		}
		//格式化页数
		self::Format(self::$dataCount,self::$limit,self::$page);
		//上下页标签
		self::PageLabel();
		//数字跳页
		self::JumpPage();
		//下拉分页
		self::OptionPage();
		//隐藏第一页或者最后页
		self::DisplayPage();
	}
	
	
	/**
	 * 格式化页数，并且返回页数数组
	 * @param 参数1，选填，数据总行数
	 * @param 参数2，选填，每页多少条
	 * @param 参数3，选填，当前页数
	 */
	static function Format( $dataCount = 0 , $limit = 0 , $page = 0 )
	{
		if ( $dataCount == '' )
		{
			$dataCount = self::$dataCount;
			$limit = self::$limit;
			$page = self::$page;
		}

		$prePage = $nextPage = $sumPage = '';

		//如果limit是用逗号分开
		if ( str_replace(',' , '' , $limit) != $limit)
		{
			$limitArr = explode( ',' , $limit );
			$limit = $limitArr[1];
			self::$page = $page = $limitArr[0] / $limitArr[1] + 1;
			C('page.page_count' , $limit);
		}
		else
		{
			$limit = intval($limit);
		}
		
		//是整数就当前处理
		if($limit == 0 )
		{
			$sumPage = 1;
		}
		else
		{
			if( is_int( $dataCount / $limit ) )
			{
				$sumPage = $dataCount / $limit;
				if( $sumPage == 0 )
				{
					$sumPage = 1;
				}
			}
			else
			{
				$sumPage = ceil( $dataCount / $limit);
			}
		}
		
		if( $page > $sumPage)
		{
			$page = $sumPage;
			$prePage = $page;
			$nextPage = $page;
		}
		else
		{
			$prePage = $page - 1;
			$nextPage = $page + 1;
	
			if( $prePage < 1)
			{
				$prePage = 1;
			}
			
			if( $nextPage > $sumPage )
			{
				$nextPage = $sumPage;
			}
		}
		
		
		$arr = array(
			'page'=>$page,
			'datacount'=>$dataCount,
			'sumpage'=>$sumPage,
			'prepage'=>$prePage,
			'nextpage'=>$nextPage,
			'limit'=>($page-1) * $limit.','.$limit,
			'limitcount'=>($page-1) * $limit,
		);
		
		self::$pageArr = $arr;
		return $arr;
	}
	

	//分页标签替换
	static function PageLabel()
	{
		$page = self::$pageArr;

		//第一页和上一页的配置
		if( 1 >= $page['page'] )
		{
			$index = 'javascript:alert(\'已经是第一页了\');';
			$pre = 'javascript:alert(\'已经是第一页了\');';
		}
		else
		{
			$index = self::GetUrl(1);
			$pre = self::GetUrl($page['prepage']);
		}
		
		//下一页和最后页的配置
		if( $page['page'] >= $page['sumpage'] )
		{
			$next = 'javascript:alert(\'没有更多了\');';
			$last = 'javascript:alert(\'没有更多了\');';
		}
		else
		{
			$next = self::GetUrl($page['nextpage']);
			$last = self::GetUrl($page['sumpage']);
		}

		$arr = array(
			'第一页'=>$index,
			'上一页'=>$pre,
			'下一页'=>$next,
			'最后页'=>$last,
			'总数据'=>$page['datacount'],
			'当前页'=>$page['page'],
			'总页数'=>$page['sumpage'],
		);
		
		tpl::Rep($arr);
	}
	
	
	/**
	 * 获得url
	 * @param 参数1，页数，必填。
	 */
	static function GetUrl( $page )
	{
		return tpl::Rep( array('page'=>$page) , self::$url );
	}
	


	//跳页
	static function JumpPage()
	{
		$arr = tpl::Tag('{跳页:[d]}[a]{/跳页}');
		if( !empty($arr[0][0]) )
		{
			foreach($arr[0] as $key=>$val)
			{
				$pageList = '';
				$page = intval(self::$page);
				$sumPage = intval(self::$pageArr['sumpage']);
				$number = intval($arr[1][$key]);
				$lCode = $arr[2][$key];

				//如果当前页+跳页的页数大于了总页数，那么就让当前页数减少跳页的页数
				if( $page+$number > $sumPage )
				{
					$page = $sumPage-$number;
				}

				//循环输出
				for($i = $page-$number ; $i <= $page+$number ; $i++ ){
					if( $i < 1 )
					{
						$page++;
					}
					else if( $i > $sumPage )
					{
						break;
					}
					else
					{
						$code = $lCode;
						//找出选中页标签并且进行替换
						$tagArr = tpl::Tag('{选中页}[a]{/选中页}' , $code);
						if ( GetKey($tagArr,'0,0') != '' &&  $i == self::$page )
						{
							$code = $tagArr[1][0];
						}
						else if ( GetKey($tagArr,'0,0') != '' )
						{
							$code = tpl::Rep( array('{选中页}[a]{/选中页}'=>'') , $code , 3);
						}
						
						//替换cur标签
						$code = tpl::Cur( $i , self::$page , $code );
						
						$labelArr = array(
							'url'=>self::$url,
							'i'=>$i,
							'page'=>$i,
						);
						$pageList.= tpl::Rep( $labelArr , $code );
					}
				}
				tpl::Rep( array($arr[0][$key]=>$pageList) ,null , 2);
			}
		}
	}
	
	
	//下拉分页
	static function OptionPage()
	{
		$optionList = '';
		$arr = tpl::Tag('{下拉分页}[a]{/下拉分页}');

		if ( array_key_exists('0',$arr[0]) )
		{
			$page = self::$page;
			$sumPage = self::$pageArr['sumpage'];
			for( $i = 1; $i <= $sumPage ; $i++)
			{
				$lCode = $arr[1][0];
				$labelArr = array(
					'url'=>self::$url,
					'i'=>$i,
					'page'=>$i,
				);
				$optionList .= tpl::Rep( $labelArr , $lCode );
			}
			
			tpl::Rep( array($arr[0][0]=>$optionList) ,null , 2);
		}
	}
	
	

	//分页标签替换
	static function DisplayPage()
	{
		$page = self::$pageArr;

		//当为第一页的时候隐藏部分
		if( $page['page'] <= 1 )
		{
			tpl::Rep( array('隐藏最后页'=>'','/隐藏最后页'=>''));
			tpl::Rep( array('{隐藏第一页}[a]{/隐藏第一页}'=>'') , null , 3);
		}
		//当为最后页的时候隐藏部分
		else if ( $page['page'] >= $page['sumpage'])
		{
			tpl::Rep( array('隐藏第一页'=>'','/隐藏第一页'=>''));
			tpl::Rep( array('{隐藏最后页}[a]{/隐藏最后页}'=>'') , null , 3);
		}
		else
		{
			tpl::Rep( array('隐藏第一页'=>'','/隐藏第一页'=>'','隐藏最后页'=>'','/隐藏最后页'=>''));
		}
	
	}
	
	/**
	 * 格式化筛选条件url
	 */
	static function Url($url)
	{
		$retrievalUrl = tpl::url(C('page.pagetype').'_retrieval');
		if(!empty($retrievalUrl) && $retrievalUrl != 'javascript:void(0)')
		{
			$urlArr = UrlFormat();
			$typePinyin = C('page.data.type_pinyin');
			$urlArr['tpinyin'] = empty($typePinyin)?$urlArr['tid']:C('page.data.type_pinyin');
			unset($urlArr['page']);
			$reUrlArr = UrlFormat($retrievalUrl);
			if( !$reUrlArr )
			{
				$parArr = tpl::Tag('{[s]}',$retrievalUrl);
				if( !empty($parArr[1]) )
				{
					foreach ($parArr[1] as $key=>$val)
					{
						if( !isset($urlArr[$val]) )
						{
							$urlArr[$val]=0;
						}
					}
				}
			}
			else
			{
				unset($reUrlArr['page']);
				foreach ($reUrlArr as $key=>$val)
				{
					if( !isset($urlArr[$key]) )
					{
						$urlArr[$key]=0;
					}
				}
			}
			return tpl::url(C('page.pagetype').'_retrieval',$urlArr);
		}
		else
		{
			return $url;
		}
	}
}
?>
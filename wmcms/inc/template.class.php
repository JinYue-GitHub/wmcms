<?php
/**
* wmcms模版类
* 初始化参数说明：tpl(参数1，参数2)
* @param 参数1，选填，默认为空，模版的样式文件夹。
* @param 参数2，选填，默认为空，模版的名字。
* @version        $Id: template.class.php 2015年9月6日 21:06  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime         2016年11月19日 13:21 weimeng
*
**/
if(!defined('WMINC')){ exit("dont alone open!");}

class tpl{
	//公共类
	protected $commonSer;
	//模版的内容
	static public $tempCode;
	static public $tempContent;
	//是否替换pt开关
	protected static $ptRepOpen;
	//关键词信息
	protected static $keys;
	//当前模版类型标识
	protected $mark;
	//url信息
	protected static $urls;
	//模版文件夹名字
	protected static $path;
	//站群模版文件夹
	protected static $sitePath;
	//网站的url规则类型
	public static $urlType;
	//路由文件的后缀
	public static $urlmodeSuffix;
	//网站的是否是html路径
	public static $isHtml;
	public static $htmlArr;
	//模版完整位置
	public static $tempPath = WMTEMPLATE;
	//模版完整文件
	protected $tempFile;
	//缓存对象
	protected static $cacheSer;

	//自定义字段可以使用的模块
	public static $fieldModule = array();
	
	//标签数组的个数
	public static $labelCount;
	public static $labelArr;
	//标签的模块和标签的前置方法
	public static $label;
	public static $labelFun;
	//前置方法是否使用过了
	public static $beForeUse = false;

	//需要替换的中文标签
	public static $cnLabel = array();
	//public循环和普通标签前置的数据
	public static $beforeLabel = array();
	//默认的模板文件
	private static $defaultTemp;

	
	/**
	* 构造函数，初始化的时候调用
	* @param 参数1，数组，页面id和页面的文件名
	* par1：pagetype：页面id
	* par2：seo->tpath：页面的名字
	*/
	function __construct()
	{
		//数据获取
		$pageType = C('page.pagetype');
		$file = C('page.tpath');

		//如果有就进行模版输出
		if( $pageType <> '' && $file <> '' )
		{
			self::$ptRepOpen = C('config.web.pt_rep');
			$this->mark = C('page.mark');
			self::$urls = C('config.seo.urls');
			self::$path = C('ua.path');
			self::$urlType = C('config.route.url_type');
			self::$label = C('page.label');
			self::$labelFun = C('page.label_fun');
			self::$isHtml = C('config.route.ishtml');
			self::$htmlArr = C('config.seo.htmls');
			self::$sitePath = C('ua.site_path');

			//标题，关键词，描述
			$title = isset(self::$keys['title'])?self::$keys['title']:'';
			$key = isset(self::$keys['key'])?self::$keys['key']:'';
			$desc = isset(self::$keys['desc'])?self::$keys['desc']:'';
			
			//重新设置缓存时间
			self::GetCacheSer()->SetCacheTime($pageType);
			
			//如果是模版是传入的字符串
			if( C('page.tempid') == 'string' )
			{
			    self::$tempCode = $file;
			}
			//如果是读取模版内容
			else
			{
    			//对模版数据进行填充
    			if( C('page.tempid') == 'reset' )
    			{
    				$this->tempFile = WMROOT.$file;
    			}
    			else
    			{
    				//模版是否是数组
    				if( is_array($file) )
    				{
    					//文件是否存在
    					if( file_exists( self::$tempPath . self::$path . '/'.$file[0] ) )
    					{
    						$file = $file[0];
    					}
    					else
    					{
    						$file = $file[1];
    					}
    				}
    				$this->tempFile = self::$tempPath . self::$path . '/'.$file;
    				//如果文件不存在就设置为默认模版
        			if( !file_exists($this->tempFile) )
        			{
        			    //如果是默认移动模版，并且系统默认模版存在
        			    if( C('ua.mark') == 'm' && file_exists(self::$tempPath.'wmcms-m/'.$file) )
        			    {
        			        self::$path = 'wmcms-m';
        			    }
        			    //否则全部默认为pc模版
        			    else
        			    {
        			        self::$path = 'wmcms-web';
        			    }
        			    C('ua.path',self::$path);
    				    $this->tempFile = self::$tempPath . self::$path . '/'.$file;
        			}
    			}
    
    			if( !file_exists( $this->tempFile ) )
    			{
    				$tempFile = str_replace(WMROOT, '/', $this->tempFile);
    				self::ErrInfo('<b style="color:red;">对不起,模版文件"'.$tempFile.'"不存在！</b>',null,null,'system','templates.html');
    			}
    			else
    			{
    				$temp = file_get_contents( $this->tempFile );
    				//写入打开后的模版文件
    				self::$tempCode = $temp;
    			} 
			}
			//替换头部公共文件		
			$this->IncTemp('header');
			//替换头部公共文件
			$this->IncTemp('footer');
			//替换标题、关键词、描述的标签
			self::rep(array('title'=>$title,'key'=>$key,'desc'=>$desc));

			
			//创建公共方法
			$this->commonSer = new common();
			//使用设置好的模型类
			$moduleArr = C('module.inc.module');
			if( $moduleArr )
			{
				//开始new模型类
				foreach ( $moduleArr as $k )
				{
					if( file_exists( WMMODULE . $k . '/' . $k . '.class.php') && $k!='replay')
					{
						//new一个模块类实例
						new $k();
					}
				}
			}
			if( $file == 'err.html' )
			{
				return true;
			}
		}
    }
	

	/**
	* 替换标签
	* @param 参数1，数组，必填：需要替换的数组，格式为array(替换前,替换后);
	* @param 参数2，字符串，选填：需要被替换的字符串，如果为空则对系统字符串替换，否则对参数进行替换;
	* @param 参数3，数字，选填：进行替换的方式，1为标签替换，自动填充{}，2为普通替换，3为正则替换;
	**/
	static function Rep($arr,$str='',$rep_type='1')
	{
		$default = '';
		if( !empty($arr) )
		{
			//如果没有参数则对默认数据进行替换			
			if( $str == '' )
			{
				$default = true;
				$str = self::$tempCode;
			}
			
			//对数组进行替换
			if( is_array($arr) )
			{
				foreach( $arr as $k=>$v )
				{
					//标签替换
					if( $rep_type == '1' )
					{
						$str=str_replace('{'.$k.'}',$v,$str);
					}
					//普通字符串替换
					else if( $rep_type == '2' )
					{
						$str=str_replace($k,$v,$str);
					}
					//正则替换
					else if( $rep_type == '3' )
					{
						//$k = str_replace('/','\/',$k);
						$k = self::Z($k);
						$str = preg_replace('/'.$k.'/',$v,$str);
					}
				}
			
				//如果不是默认数据则直接返回
				if( !$default )
				{
					return $str;
				}
				else
				{
					return self::$tempCode = $str;
				}
			}
			else
			{
				return false;
			}
		}
	}
	
	
	
	/**
	 * if语句标签替换
	 * @param 参数1，必须，左边的值
	 * @param 参数2，必须，运算符
	 * @param 参数3，必须，右边的值
	 * @param 参数4，必须，if为真的标签
	 * @param 参数5，必须，if为假的标签
	 * @param 参数6，选填，是否用此代码替换
	 */
	static function IfRep( $lVal , $op , $rVal , $ilabel , $elabel = '' , $code = '' )
	{
		switch ( $op )
		{
			case '>':
				$isTrue = $lVal > $rVal ? true : false;
				break;
				
			case '<':
				$isTrue = $lVal < $rVal ? true : false;
				break;
				
			case '=':
				$isTrue = $lVal == $rVal ? true : false;
				break;
		}
		

		if( $isTrue )
		{
			if ( $code == '' )
			{
				tpl::Rep( array($ilabel=>'','/'.$ilabel=>'') );
				if( $elabel != '' )
				{
					tpl::Rep( array('{'.$elabel.'}[a]{/'.$elabel.'}'=>'') , null ,3);
				}
			}
			else
			{
				$code = tpl::Rep( array($ilabel=>'','/'.$ilabel=>'') , $code );
				if( $elabel != '' )
				{
					$code = tpl::Rep( array('{'.$elabel.'}[a]{/'.$elabel.'}'=>'') , $code ,3);
				}
			}
		}
		else
		{
			if ( $code == '' )
			{
				if( $elabel != '' )
				{
					tpl::Rep( array($elabel=>'','/'.$elabel=>'') );
				}
				tpl::Rep( array('{'.$ilabel.'}[a]{/'.$ilabel.'}'=>'') , null ,3);
			}
			else
			{
				if( $elabel != '' )
				{
					$code = tpl::Rep( array($elabel=>'','/'.$elabel=>'') , $code );
				}
				$code = tpl::Rep( array('{'.$ilabel.'}[a]{/'.$ilabel.'}'=>'') , $code ,3);
			}
		}
		return $code;
	}
	

	/**
	* 引用文件替换
	* @param 参数1，字符串，必填：需要引用的文件名字。
	* @param 参数2，字符串，必填：需要从处理的模版内容。
	**/
	function IncTemp( $name )
	{
		$inctemp = '';
		preg_match_all('/{'.$name.':(.*?)}/', self::$tempCode, $label);
		$count=count($label[0]);

		//是否是开启了站群
		if( self::$sitePath != '' )
		{
			$nowPath = self::$sitePath;
		}
		else
		{
			$nowPath = self::$tempPath . self::$path;
		}
		
		for( $i=1 ; $i <= $count ; $i++ )
		{
			$fileName = $label[1][$i-1];
			
			if( !file_exists( $nowPath. '/' . $fileName ) )
			{
				if ( ERR )
				{
					self::ErrInfo('在模版文件夹【'. self::$path .'】未找到引用文件【'.$fileName.'】',null,null,'system','templates.html');
				}
				else
				{
					if( DEBUG )
					{
						$inctemp = '警告：在模版文件夹【'. self::$path .'】未找到引用文件【'.$fileName.'】';
					}
					else
					{
						$inctemp =  $fileName.'不存在';
					}
				}
			}
			else if ( file_exists( $nowPath . '/' . $fileName ) )
			{
				$inctemp = file_get_contents( $nowPath. '/' . $fileName);
			}
			self::$tempCode = str_replace('{'.$name.':'.$fileName.'}' , $inctemp , self::$tempCode);
		}
	}

	
	/**
	 * 跳转链接
	 * @param 参数1，必须，链接地址或者页面名字
	 * @param 参数2，选填，默认是内部链接，否则为外部链接
	 */
	static public function Jump($pageType , $urlType = 0)
	{
		if( $urlType == '0' )
		{
			$url = self::Url($pageType);
		}
		else
		{
			$url = $pageType;
		}
		die(header('Location:'.$url));
	}
	
	/**
	 * 设置是否是静态路径
	 */
	static public function SetIsHtml($val)
	{
		$val = "{$val}";
		self::$isHtml = $val;
		return $val;
	}
	/**
	 * 获得是否是静态路径
	 */
	static public function GetIsHtml()
	{
		if( self::$isHtml == '' )
		{
			self::$isHtml = C('config.route.ishtml');
		}
		return self::$isHtml;
	}
	
	/**
	 * 获得是否是静态seo数组
	 */
	static public function GetHtmlArr()
	{
		if( self::$htmlArr == '' )
		{
			self::$htmlArr = C('config.seo.htmls');
		}
		return self::$htmlArr;
	}
	
	/**
	* 获得seo.config.php里面的url配置
	* @param 参数1，必须，需要返回的页面名字
	* @param 参数2，选填，是否替换默认数据
	* 返回值，字符串。
	**/
	static public function Url( $pageType , $arr='' , $urlType='')
	{
		//路由模式大于2的地址后缀
		if( self::$urlmodeSuffix == '' )
		{
			self::$urlmodeSuffix = C('config.route.urlmode_suffix');
		}
		if ( self::$urls == '' || self::$urlType == '' || self::$ptRepOpen == '' )
		{
			self::$urls = C('config.seo.urls');
			self::$urlType = C('config.route.url_type');
			self::$ptRepOpen = C('config.web.pt_rep');
		}
		if( $urlType == '' )
		{
			$urlType = self::$urlType;
		}
		$url = '';
		$tid = isset($arr['tid'])?$arr['tid']:'0';


		$module = '';
		if( $pageType != 'index' )
		{
			list($module,$page) = explode('_', $pageType);
		}
		//开启了静态路径并且暂时只对电脑版有效
		if(self::GetIsHtml() == 1 && C('ua.pt_int') == '4')
		{
			if( $pageType == 'index' )
			{
				$url = C('config.seo.urls.'.$pageType.'.url2');
			}
			else
			{
				if( $module == 'article' || $module == 'novel' || $module == 'about' || $module == 'picture')
				{
					if( $page == 'info' || $page == $module )
					{
						$page = 'content';
					}
					else if ($page == 'type' )
					{
						if($tid == '0' )
						{
							$tid = 1;
						}
						$page = 'list';
					}
					$htmlArr = self::GetHtmlArr();
					$url = GetKey($htmlArr,$module.','.$tid.','.$page.',path4');
				}
			}
			if( $url == '' )
			{
				$url = C('config.seo.urls.'.$pageType.'.url'.$urlType);
			}
		}
		else
		{
			$url = C('config.seo.urls.'.$pageType.'.url'.$urlType);
		}
		//如果url还是为空
		if ( $url == '' )
		{
			$url = C('system.par.no_url' , null , 'lang' );
		}

		//判断是否是默认的参数
		if( empty($arr) )
		{
			$arr = array('page'=>'1','tid'=>'0','lid'=>'0','cid'=>'0','ot'=>'0',);
		}
		
		$url = self::Rep( $arr , $url );
		$moduleDomain = C('config.domain.'.$module.'.domain');
		if( $module != '' && $moduleDomain != '' )
		{
			$url = TCP_TYPE.'://'.$moduleDomain.$url;
		}
		
		//替换模版标识
		$url = self::PtRep($url , C('ua.pt'));
		//如果大于3，并且路由后缀不为空
		if( $urlType > 3 && self::$urlmodeSuffix != '' && $url != '/')
		{
			$url = $url.self::$urlmodeSuffix;
		}
		//如果没有开启debug就进行替换不存在的参数
		/*if( DEBUG == false )
		{
			$url = preg_replace('/&[a-zA-Z0-9]+=\{.*?\}/', '', $url );
			$url = preg_replace('/[a-zA-Z0-9]+=\{.*?\}&/', '', $url );
			$url = preg_replace('/\?[a-zA-Z0-9]+=\{.*?\}/', '', $url );
		}
		else
		{
			$url = preg_replace('/\{.*?\}/', '', $url );
		}*/
		return $url;
	}
	
	/**
	 * url的pt标签替换
	 */
	static function PtRep($url , $pt = '')
	{
		$urlType = self::$urlType;
		$ptRepOpen = (int)self::$ptRepOpen;
		if( $ptRepOpen == '' )
		{
			$ptRepOpen = C('config.web.pt_rep');
		}
		if ( $ptRepOpen == 0 && $urlType == '1')
		{
			$url = self::Rep( array('pt={pt}&'=>'','?pt={pt}'=>'','&pt={pt}'=>'','{pt}'=>'') , $url , 2);
		}
		//替换模版标识
		else if( $ptRepOpen == 1 || $urlType != '1')
		{
			$url = self::Rep( array( 'pt'=> $pt) , $url );
		}
		return $url;
	}
	

	/**
	 * 替换模版里面的url标签
	 * @param 参数1，必须，url标签。
	 * @param 参数2，必须，url的类型名。
	 * @param 参数3，选填，
	 * 返回值，字符串。
	 **/
	static public function GetUrl( $label , $urlType , $typeArr = '')
	{
		$parArr = '';
		
		$urlArr = tpl::Tag( '{'.$label.':[s]}' );

		for ($i = 0 ; $i < count($urlArr[0]) ; $i++ )
		{
			list($key,$val) = explode( '=' , $urlArr[1][$i] );
			
			$parArr = $typeArr;
			if ( is_array($parArr) || $parArr != '' )
			{
				$parArr[$key] = $val;
			}

			$code = tpl::url( $urlType , $parArr );
			
			tpl::Rep( array($urlArr[0][$i]=>$code) , null , 2 );
		}
	}
	
	
	
	/**
	 * 获取时间参数
	 * @param 参数1，字符串，必须，需要格式化的时间格式
	 * @param 参数2，数组，必须，需要格式化的时间数据
	 */
	static public function Time( $label , $arr ){
		if( $label == '' )
		{
			return true;
		}
		else
		{
			$timeArr = array(
				'n'=>date("Y",$arr),
				'y'=>date("m",$arr),
				'r'=>date("d",$arr),
				's'=>date("H",$arr),
				'f'=>date("i",$arr),
				'm'=>date("s",$arr),
			);
			return self::Rep($timeArr , $label , 2);
		}
	}

	
	/**
	 * 设置前置的标签数据
	 * @param 参数1，必须，方法名
	 * @param 参数2，必须，键名
	 * @param 参数3，必须，值
	 */
	static function SetBeforeLabel($key,$k='',$val='')
	{
		if( empty($k) && $k!==0 )
		{
			self::$beforeLabel[$key] = $val;
		}
		else
		{
			self::$beforeLabel[$key][$k] = $val;
		}
	}
	/**
	 * 获得前置的标签数据
	 * @return multitype:
	 */
	static function GetBeforeLabel($key,$k='')
	{
		if( empty($k) && $k!==0 && isset(self::$beforeLabel[$key]) )
		{
			return self::$beforeLabel[$key];
		}
		else if( isset(self::$beforeLabel[$key][$k]) )
		{
			return self::$beforeLabel[$key][$k];
		}
		else
		{
			return array();
		}
	}
	

	/**
	 * 设置中文标签
	 * @param 参数1，必须，键名
	 * @param 参数2，必须，值
	 */
	static function SetLabel($key,$val)
	{
		self::$cnLabel[$key] = $val;
	}
	/**
	 * 获得设置的中文标签
	 * @return multitype:
	 */
	static function GetLabel()
	{
		return self::$cnLabel;
	}
	
	
	/**
	 * 设置默认的模板
	 * @param 参数1，必须，模板文件名字
	 */
	static function SetTemp($fileName)
	{
		if( !empty($fileName) )
		{
			self::$defaultTemp = $fileName.'.html';
		}
	}
	
	/**
	 * 获得设置的默认模板文件名
	 * @return string
	 */
	static function GetTemp()
	{
		return self::$defaultTemp;
	}
	
	
	/**
	 * 输出模版内容
	 * @param 参数1，选填，默认自动判断,否则根据传入的值输出头部，如果是return则不输出代码，直接返回模版内容
	 */
	function Display( $mark = '' )
	{
		//替换设置的中文标签(主要用于插件)
		$this->Rep(self::GetLabel());
		//使用公共方法的后置函数。
		$this->commonSer->After();
		
		//输出模版
		if( $mark === 'xml' )
		{
		    header("Content-type: text/xml");
		}
		else if( $this->mark == 'wap' )
		{
			header('Content-Type:text/vnd.wap.wml;charset=utf-8');  
		}
		else
		{
			header('Content-Type:text/html;charset=utf-8');
		}

		//替换模版标识
		if( self::$ptRepOpen == '1' )
		{
			$this->rep(array('pt'=>C('ua.pt')));
		}
		else
		{
			$this->rep(array('pt={pt}&'=>'','?pt={pt}'=>'','&pt={pt}'=>'','{pt}'=>''),'',2);
		}
		//检查网站基本配置
		CheckBasicConfig();
		
		//输出模版
		if ( DEVELOPER )
		{
			$tempCode = self::$tempContent;
			//区分debug信息
			$tempCode = '-----------------以上为DEBUG信息-----------------<br/><br/>'.$tempCode;

			//记录完成时间，输出php执行时间
			$endTime = microtime(true);
			$tempCode.= '耗时'.round( $endTime - C('startTime') , 3).'秒';
			self::$tempContent = $tempCode;
		}

		$cacheSer = $this->GetCacheSer();
		//开启了页面缓存
		if(	$cacheSer->siteOpen && $cacheSer->mechanism == 'page' )
		{
			$cacheSer->SetSite(GetUrlPath('3' , 1) , $this->RepTempCode(self::$tempContent) , $cacheSer->cacheTime);
		}
		//如果开启了区块缓存，存在分页就保存分页数据
		else if($cacheSer->siteOpen && $cacheSer->mechanism == 'block' && class_exists('page') )
		{
			$cacheName = GetUrlPath('3').'page/'.md5(Encrypt($_SERVER['QUERY_STRING']));
			$cacheContent = $cacheSer->GetSite($cacheName);
			if( $cacheContent )
			{
				$pageArr = explode('|||', $cacheContent);
				page::Start( $pageArr[3] , $pageArr[0] , $pageArr[1] , $pageArr[2] );
			}
		}
		//如果是return则不输出代码，直接返回模版内容
		if( $mark == 'return' )
		{
		    return self::$tempContent;
		}
		else
		{
    		exit( self::$tempContent );
    		die();
		}
	}

	
	/**
	 * 占位符标签替换
	 * @param 参数1，必填，需要进行替换的字符串。
	 */
	static public function Z($str)
	{
		$str = self::rep(array('['=>'\[',']'=>'\]','('=>'\(',')'=>'\)','|'=>'\|'),$str,2);
		$str = self::rep(array('\[d\]'=>'(\d*)','\[s\]'=>'(.*?)','\[a\]'=>'([\s\S]*?)'),$str,2);
		$str = self::rep(array('/'=>'\/','[d]'=>'(\d*)','[s]'=>'(.*?)','[a]'=>'([\s\S]*?)'),$str,2);
		return $str;
	}
	

	/**
	 * 获得缓存对象
	 * @return cache
	 */
	public static function GetCacheSer()
	{
		if( self::$cacheSer )
		{
			return self::$cacheSer;
		}
		else
		{
			global $cacheSer;
			self::$cacheSer = $cacheSer;
			return self::$cacheSer;
		}
	}
	
	/**
	 * 替换模版中换行提行符号
	 * @param 参数1，需要替换的变量
	 */
	public static function RepTempCode($code)
	{
		//$code = str_replace("\r\n", '', $code);
		$code = str_replace("\t", '', $code);
		return $code;
	}
	
	
	/**
	 * 模块标签前置函数
	 */
	public static function LabelBefore()
	{
		if( self::$label != '' && self::$labelFun != '' && !self::$beForeUse)
		{
			self::$beForeUse = true;
			//是否运行主方法
			$runMainFun = true;
			//模块类名
			$moduleClassName = self::$label;
			//模块方法名
			$moduleFunName = self::$labelFun;
			//模块插件类名
			$pluginClassName = $moduleClassName.'plugin';
			//模块插件前置类名
			$pluginBeforeFunName = $moduleFunName.'Before';
			//模块插件后置类名
			$pluginAafterFunName = $moduleFunName.'After';
			//如果插件类存在就定义
			if( class_exists($pluginClassName) )
			{
				$pluginClass = new $pluginClassName();
			}
			else
			{
				$pluginClass = array();
			}
			
			//前置方法存在就调用
			if( !empty($pluginClass) && method_exists($pluginClass,$pluginBeforeFunName) )
			{
				$runMainFun = $pluginClass->$pluginBeforeFunName();
			}
			//调用主方法
			if( $runMainFun !== false )
			{
				$moduleClassName::$moduleFunName();
			}
			//后置方法存在就调用
			if( !empty($pluginClass) && method_exists($pluginClass,$pluginAafterFunName) )
			{
				$pluginClass->$pluginAafterFunName();
			}
		}
	}
	
	
	/**
	 * 模版循环类型标签替换处理
	 * @param 参数1，必填，需要进行处理的模版字符串
	 * @param 参数2，选填，获得数据的类型，是分类还是内容。【如果参数3为空，并且此参数为数组，那么就是直接传入数据，不进行数据获取，只进行标签替换】
	 * @param 参数3，选填，获得数据的数组。
	 * @param 参数4，必填，替换标签的数组。
	 */
	public static function Label($str , $type = '' , $CF = '' , $repFun=array())
	{
		//请求数据的类型，标签的级别
		$level = $code = '';
		$cacheSer = self::GetCacheSer();
		
		if( is_array($type) && $CF != '' )
		{
			$level = $type[1];
			$type = $type[0];
		}
		//获取当前标签的名字
		preg_match_all('/{\/(.*?)}/', $str, $labelName );
		C('label.name',$labelName[1][0]);
		
		//替换标签占位符
		$pregStr = self::Z($str);
		//标签提取
		preg_match_all('/'.$pregStr.'/', self::$tempCode, $label);

		//同样的标签个数
		$labelCount = count($label[0]);

		//循环匹配到的标签
		for( $i=1 ; $i <= $labelCount ; $i++ )
		{
			$cacheName = GetUrlPath('3').md5(Encrypt($str.$label[0][$i-1].GetKey($_SERVER, 'QUERY_STRING')));
			//如果开启了区块缓存
			if( $cacheSer->siteOpen && $cacheSer->mechanism == 'block')
			{
				$cacheContent = $cacheSer->GetSite($cacheName);
				if( $cacheContent )
				{
					self::$tempCode = str_replace($label[0][$i-1], $cacheContent, self::$tempCode);
					continue;
				}
			}
			
			//参数获取
			$where = $label[1][$i-1];
			C('page.label_where' , $where);
			$lcode = $label[2][$i-1];
			//获得数据
			if ( $CF == '' )
			{
				$data = $type;
			}
			else if( is_string($CF) && $CF='key' ) 
			{
				$where = self::GetWhere($label[1][$i-1],null,false);
				$page = 0;
				$limit = 0;
				$tags = array();
				if( !empty($where['limit']) )
				{
					list($page,$limit) = explode(',',$where['limit']);
				}
				if( isset($where['where']['key']) )
				{
					$tags = explode(',',$where['where']['key']);
				}
				
				if( $limit > 0 && count($tags) > $limit )
				{
					$tags = array_slice($tags,0,$limit);
				}
				$data = $tags;
			}
			else
			{
				$arrKeys = array_keys( $CF );
				//为了支持php7，把类和方法名字单独定义变量调用
				$className = $arrKeys[0];
				$funcName = $CF[$arrKeys[0]];
				$data = $className::$funcName( $type , $where );
			}

			//获得无数据的标签
			$tagArr = self::Tag('{无数据}[a]{/无数据}' , $lcode);
			if ( empty($tagArr[0][0]) )
			{
				$tagArr[0] = array('','');
				$tagArr[1] = array('','');
			}

			//替换标签数据
			if( !empty($data) )
			{
				//把无数据的标签替换为空
				$lcode = self::Rep( array($tagArr[0][0]=>'') , $lcode , 2 );
				$arrKeys = array_keys( $repFun );
				//$code = $arrKeys[0]::$repFun[$arrKeys[0]]( $data , $lcode );
				//为了支持php7，把类和方法名字单独定义变量调用
				$className = $arrKeys[0];
				$funcName = $repFun[$arrKeys[0]];

				
				//是否存在自定义字段允许的模块数据
				if( empty(self::$fieldModule) )
				{
					self::$fieldModule = NewModel('system.config')->GetFieldModule();;
				}
				//绑定数据前进行自定义字段验证
				$fieldModule = str_replace('label', '', strtolower($className));
				$fieldFuntion = str_replace('public', '', strtolower($funcName));
				//存在允许自定义字段的模块，并且方法为type或者为模块名就进行自定义字段数据查询
				if( array_key_exists($fieldModule, self::$fieldModule) && ($fieldFuntion == 'type' || $fieldModule == $fieldFuntion) )
				{
					$data = GetFieldData($data , $fieldModule , $fieldFuntion );
				}

				//模块插件类名
				$pluginClassName = self::$label.'plugin';
				//模块插件前置类名
				$pluginBeforeFunName = $funcName.'Before';
				//模块插件后置类名
				$pluginAafterFunName = $funcName.'After';
				$pluginClass = array();
				//如果插件类存在就定义
				if( class_exists($pluginClassName) )
				{
					$pluginClass = new $pluginClassName();
				}
				//前置方法存在就调用
				if( !empty($pluginClass) && method_exists($pluginClass,$pluginBeforeFunName) )
				{
					$code = $pluginClass->$pluginBeforeFunName( $data , $lcode , $level );
				}
				//调用主方法
				if( $code !== false )
				{
					$code = $className::$funcName( $data , $lcode , $level );
				}
				//后置方法存在就调用
				if( !empty($pluginClass) && method_exists($pluginClass,$pluginAafterFunName) )
				{
					$pluginClass->$pluginAafterFunName( $data , $lcode , $level );
				}
			}
			else
			{
				$code = self::Rep( array($lcode=>$tagArr[1][0]) , $lcode , 2 );
			}
			//替换模版标签
			self::rep(array($label[0][$i-1] => $code),null,2);
			

			//如果开启了区块缓存
			if( $cacheSer->siteOpen && $cacheSer->mechanism == 'block')
			{
				$code = self::RepTempCode($code);
				$cacheSer->SetSite($cacheName , $code , $cacheSer->cacheTime);
			}
		}
	}
	
	
	/**
	* 可变标签匹配处理
	* @param 参数1，标签配置值，[d]代表数字，[s]代表字符串，[a]代表所有
	* @param 参数2，可选，从给定的字符串中匹配，否则从全模版匹配
	* @param 参数3，可选，是否进行通配符转义
	* 返回值，匹配到的数据，数组格式
	**/
	public static function Tag($str,$lcode = '',$z=true)
	{
		//替换标签占位符
		if( $z == true )
		{
			$str = self::Z($str);
		}
		
		//开始匹配标签
		if ( $lcode == '' )
		{
			$lcode = self::$tempCode;
		}
		preg_match_all('/'.$str.'/', $lcode, $label);

		//把匹配到的数据保存
		self::$labelArr = $label;
		//匹配到的标签条数保存
		self::$labelCount = count($label[0]);
		
		return $label;
	}


	/**
	* 替换计数器
	* @param 参数1，必填，需要进行替换的模版字符串。
	* @param 参数2，必填，当前计数器的值。
	* @param 参数3，选填，默认计数器为i，可以自定义标签。
	**/
	public static function I( $label , $i , $lTtype = 'i' )
	{
		//寻找标签
		preg_match_all('/{'.$lTtype.':(.*?)}([\s\S]*?){\/'.$lTtype.'}/', $label, $iLable);
		//计数器标签共有多少个。
		$icount=count( $iLable[0] );

		for( $k=1 ; $k <= $icount ; $k++ )
		{
			//预设值赋值
			$isTrue = false;
			//分割计数器
			$iArr = explode( ',' , $iLable[1][$k-1] );
			
			foreach( $iArr as $v )
			{
				for($symbolI = 1;$symbolI<=4;$symbolI++)
				{
					$symbol = array('1'=>'>=','2'=>'<=','3'=>'>','4'=>'<');
					if( array_key_exists($symbolI, $symbol) )
					{
						$symbolArr = explode( $symbol[$symbolI] , $v );
						if( count($symbolArr) > 1 )
						{
							if( $symbolI == '1' && $i >= $symbolArr[1] )
							{
								$isTrue = true;
								break;
							}
							else if( $symbolI == '2' && $i <= $symbolArr[1] )
							{
								$isTrue = true;
								break;
							}
							else if( $symbolI == '3' && $i > $symbolArr[1] )
							{
								$isTrue = true;
								break;
							}
							else if( $symbolI == '4' && $i < $symbolArr[1] )
							{
								$isTrue = true;
								break;
							}
						}
					}
				}
				if( $i == $v )
				{
					$isTrue = true;
					break;
				}
			}
			
			if( $isTrue )
			{
				$label = str_replace( '{'.$lTtype.':'.$iLable[1][$k-1].'}'.$iLable[2][$k-1].'{/'.$lTtype.'}' , $iLable[2][$k-1] , $label );
			}
			else
			{
				$label = str_replace( '{'.$lTtype.':'.$iLable[1][$k-1].'}'.$iLable[2][$k-1].'{/'.$lTtype.'}' , '' , $label );
			}
		}
		
		return $label;
	}
	
	
	
	/**
	 * 当前选中标签替换
	 * @param 参数1，必填，当前的id。
	 * @param 参数2，必填，需要对比的id。
	 * @param 参数3，必填，需要替换的模版标签。
	 * @param 参数4，选填，cur的前缀。
	 */
	static function Cur( $tid , $bTid , $lcode , $level='')
	{
		if($tid == $bTid){
			$lcode = str_replace( '{'.$level.'cur}' , '' , $lcode );
			$lcode = str_replace( '{/'.$level.'cur}' , '' , $lcode );
			$lcode = preg_replace( '/{'.$level.'nocur}([\s\S]*?){\/'.$level.'nocur}/' , '' , $lcode);
		}else{
			$lcode = str_replace( '{'.$level.'nocur}' , '' , $lcode);
			$lcode = str_replace( '{/'.$level.'nocur}' , '' , $lcode);
			$lcode = preg_replace( '/{'.$level.'cur}([\s\S]*?){\/'.$level.'cur}/' , '' , $lcode);
		}
		
		return $lcode;
	}
	
	
	/**
	 * 分割符替换
	 * @param 参数1，必填，总得长度。
	 * @param 参数2，必填，当前长度。
	 * @param 参数3，必填，模版字符串。
	 * @param 参数4，选填，分隔符的标签名字。
	 */
	static function Segmentation( $count , $i , $lcode , $label='分隔符')
	{
		$tagArr = tpl::Tag('{'.$label.'}[a]{/'.$label.'}' , $lcode );
		//如果存在数据
		if( !empty($tagArr[0][0]) )
		{
			foreach ($tagArr[0] as $k=>$v)
			{
				//如果当前序号小于总的数据
				if( $i < $count )
				{
					$lcode = str_replace( $tagArr[0][$k] , $tagArr[1][$k] , $lcode );
				}
				else
				{
					$lcode = str_replace( $tagArr[0][$k] , '' , $lcode );
				}
			}
		}
		return $lcode;
	}
	
	
	
	/**
	* 提取指定个数的字符串
	* @param 参数1，标签配置值，[d]代表数字，如果lcode为空则为数字
	* @param 参数2，完整的内容
	* @param 参数3，选填，匹配标签，默认为当前页面的模版。
	* @param 参数4，选填，是否清理内容，默认为true。
	**/
	public static function Exp( $str , $content , $lcode='' , $clear = true)
	{
		if( $lcode != '' && is_int( $str ) )
		{
			$label[1][0] = $str;
		}
		else
		{
			if( $lcode == '' )
			{
				$lcode = self::$tempCode;
			}
			
			//替换标签占位符
			$str = self::rep(array('[d]'=>'(\d*)'),$str,2);
			//开始匹配标签
			preg_match_all('/'.$str.'/', $lcode, $label);
			
			if( empty($label[1][0]) ){
				return false;
			}
		}
		
		//返回处理后的数据
		if( $clear == true )
		{
			$content = str_replace(" ", '', $content);
			$content = str_replace("\r", '', $content);
			$content = str_replace("\n", '', $content);
			$content = str_replace("\t", '', $content);
		}
		$content = mb_substr(strip_tags($content), 0, $label[1][0]+1,'utf-8');
		
		if( $lcode == '' )
		{
			return $content;
		}
		else
		{
			return array($label[1][0] , $content);
		}
	}


	/**
	* 错误提示信息
	* @param 参数1，字符串，提示语句
	* @param 参数2，字符串，跳转的url
	* @param 参数3，数字，自动跳转的时间
	* @param 参数4，字符串，指定模版文件夹
	**/
	static function ErrInfo( $info , $gourl = "" , $time = "3" , $path = '' , $file = '')
	{
		if ( C('page.ajax') || IsAjax() )
		{
			ReturnData( $info , true , C('code')!=''?C('code'):false);
			exit();
		}
		else if(C('page.pagetype')=='err')
		{
			die($info);
			return;
		}
		else
		{
			if ( trim($path) != '')
			{
				C('ua.path' , $path);
				C('page.tpath',$file);
				
				if( WMTEMPLATE != self::$tempPath && $path == 'system')
				{
					self::$tempPath = WMTEMPLATE;
				}
				//自定义模版不存在
				if( !file_exists(self::$tempPath.$path.'/'.$file) )
				{
					$path = '';
				}
			}
			//路径为空就表示为错误提示页面
			if( trim($path) == '' )
			{
				//如果是插件模板路径，就设置path为版本标识
				if( WMTEMPLATE != self::$tempPath )
				{
					C('ua.path',C('ua.mark'));
				}
				//自定义模板的错误文件不存在就调用系统错误提示模板
				if( !file_exists(self::$tempPath.C('ua.path').'/err.html') )
				{
					C('ua.path','system');
					self::$tempPath = WMTEMPLATE;
				}
				C('page.pagetype','err');
				C('page.tpath','err.html');
			}
			//拒绝重置
			C('page.tempid','0');
			//默认提示
			$str = $info;
			$html = '';
			$location = '';
			//如果提示是数组
			if( is_array($info) )
			{
				$str = $info['info'];
				$gourl = isset($info['gourl'])?$info['gourl']:'';
				$html = isset($info['html'])?$info['html']:'';
				$time = isset($info['time'])?$info['time']:$time;
			}

			//设置seo信息
			self::$keys['title'] = self::$keys['key'] = self::$keys['desc'] = strip_tags($str);
			//new模型类
			$err = new tpl();
			if( !empty($gourl) )
			{
				$location='<meta http-equiv="Refresh" content="'.$time.';URL='.$gourl.'">';
			}

			self::rep(array(
				'code'=>C('code'),
				'错误代码'=>C('code'),
				'errinfo'=>$str,
				'错误提示'=>$str,
				'title'=>strip_tags($str),
				'gourl'=>$gourl,
				'html'=>$html,
				'time'=>$time,
				'重定向'=>$location,
			));
			
			$err->display();
			exit();
		}
	}

	/**
	* 获得指定页面的seo信息
	* @param 参数1，$pagetype 页面的名字
	* @param 参数2，$data 自定义seo的数据
	* @param 参数3，$tempid 自定义模版的id
	* @param 参数4，$dtemp 默认的模版文件名
	**/
	public static function GetSeo(){
		$pageSeo = array();
		//读取页面配置信息数组$page里面的page下面的键
		$pagetype = C('page.pagetype');
		$data = C('page.data');
		$tempid = C('page.tempid');
		$dtemp = C('page.dtemp');

		//当自定义数据不为空，并且里面的值也不为空的时候才进行替换
		if( is_array($data) && !empty($data['title']) && !empty($data['key']) && empty($data['desc']) )
		{
			$pageSeo['title'] = $data['title'];
			$pageSeo['key'] = $data['key'];
			$pageSeo['desc'] = $data['desc'];
		}
		else
		{
			$keyArr = C('config.seo.keys');
			$pageSeo['title'] = isset($keyArr[$pagetype]['title'])?$keyArr[$pagetype]['title']:'';
			$pageSeo['key'] = isset($keyArr[$pagetype]['key'])?$keyArr[$pagetype]['key']:'';
			$pageSeo['desc'] = isset($keyArr[$pagetype]['desc'])?$keyArr[$pagetype]['desc']:'';
		}
		
		//设置seo信息到page数组
		self::$keys = $pageSeo;
		C('page.seo',$pageSeo);

		//读取模版id的数据
		if( isset($data[$tempid]) )
		{
			$tempid = $data[$tempid];
		}

		//返回模版的数据或者为重置模版
		if( C('ua.site') == '1' )
		{
			//表示使用自定义的模版路径
			C('page.tempid' , 'reset');
			$tpath = C('ua.site_path').$dtemp;
		}
		else if( trim($tempid) == '' || $tempid == '0' || $tempid == 'reset')
		{
			$tpath = $dtemp;
		}
		else
		{
			$where['table'] = '`@system_templates`';
			$where['where']['temp_id'] = $tempid;
			$row = wmsql::GetOne($where);

			if( empty($row) )
			{
				$tpath = $dtemp;
			}
			else
			{
				//如果使用的不是是当前的模版
				if( $row['temp_address'] != '0' )
				{
					//表示使用自定义的模版路径
					C('page.tempid' , 'reset');
				}
				
				//电脑板模版
				if( C('ua.pt') == C('config.web.tpmark4') && $row['temp_temp4']<>''){
					$tpath = $row['temp_temp4'];
				}
				//触屏板模版
				else if( C('ua.pt') == C('config.web.tpmark3') && $row['temp_temp3']<>'')
				{
					$tpath = $row['temp_temp3'];
				}
				//炫彩板模版
				else if( C('ua.pt') == C('config.web.tpmark2') && $row['temp_temp2']<>'')
				{
					$tpath = $row['temp_temp2'];
				}
				//通用板模版
				else if( C('ua.pt') == C('config.web.tpmark1') && $row['temp_temp1']<>'')
				{
					$tpath = $row['temp_temp1'];
				}
				//否则默认
				else
				{
					$tpath = $dtemp;
				}
			}
		}

		//设置当前页面所使用的模版名字
		C('page.tpath',$tpath);
	}


	/**
	* 对设定的标签进行替换
	* @param 参数1，必须，字符串，模版标签语句
	* @param 参数2，选填，数组，指定替换的标签
	* @param 参数3，选填，布尔值，是否检测字段为中文
	**/
	public static function GetWhere( $str , $arr = '' , $check=true)
	{
		if ( is_array($str) )
		{
			if( array_key_exists('where',$str) )
			{
				return $str;
			}
			else
			{
				$where['where'] = $str;
				return $where;
			}
		}
		else
		{
			//设置默认变量
			$page = $pageCount = $limitSql = $orderSql = $groupSql = '';
			$oldOrderSql = '';
			$whereSql = array();
			//设置标签识别数组
			$limitLabel = array('数量','number','limit');
			$pageLabel = array('页数','page');
			$orderLabel = array('排序','order');
			$groupLabel = array('分组','group');
	
			//以;分割每个条件
			$where = explode(';',$str);

			foreach ($where as $k => $v)
			{
				//以=分割每个条件的键和值存入新的数组。
				if ( $v != '' && strstr($v,'=') )
				{
					$varr = explode('=',$v);
					$key = $varr[0];
					$val = $varr[1];

					//判断是否有page标签
					if ( in_array($key,$pageLabel) )
					{
						$page = intval($val);
						$sql['list'] = true;
					}
					
					//如果当前标签名的数组条件存在
					if ( in_array($key,$limitLabel) )
					{
						//参数赋值，数组转换
						$limitArr = explode(',',$val);
						//如果存在分隔符
						if( strpos($val,',') )
						{
							$limitSql = intval($limitArr[0]).",".intval($limitArr[1]);
						}
						//并且总行数不能为0
						else if( $varr[1] != 0)
						{
							$varr[1] = intval($varr[1]);
							if ( $page > 0 )
							{
								$pageCount = ($page-1) * $varr[1];
							}
							else
							{
								$pageCount = 0;
							}
							$limitSql = $pageCount.",{$varr[1]}";
						}
					}
		
					//数据排序方式
					else if ( in_array($key,$orderLabel) )
					{
						//如果设置了排序字段
						if( $val == '随机' )
						{
							$orderSql = 'rand()';
						}
						else
						{
							//是否已经存在排序条件
							if( $orderSql != '' )
							{
								$oldOrderSql = $orderSql;
							}
								
							if( empty($arr[$val]) )
							{
								if ( str_replace(' ','',$val) == $val && $oldOrderSql != '' )
								{
									$orderSql = '`'.$val.'`';
								}
								else
								{
									$orderSql = $val;
								}
							}
							else
							{
								$orderSql = $arr[$val];
							}
								
							//存在旧的排序条件
							if( $oldOrderSql != '' )
							{
								$orderSql = $oldOrderSql.','.$orderSql;
							}
						}
					}
					

					//数据分组方式
					else if ( in_array($key,$groupLabel) )
					{
						if( !isset($arr[$val]) || $arr[$val] == '' )
						{
							$groupSql = $val;
						}
						else
						{
							$groupSql = $arr[$val];
						}
					}
					
					//否则就设置其他参数
					else if ( !in_array($key,$pageLabel) )
					{
						if( $val != '' )
						{
							if ( $arr ) 
							{
								if( array_key_exists($val , $arr) )
								{
									$val = $arr[$val];
								}
								if( array_key_exists($key , $arr) )
								{
									$key = $arr[$key];
								}
							}
							preg_match('/\[(.*?)->(.*?)\]/', $val, $label);
							
							//如果是高级查询就给高级查询
							if ( array_key_exists( 0 , $label ) )
							{
								if( $label[1] == 'and-or' )
								{
									//列出所有条件
									$parArr = explode('||', $label[2]);
									//第一个条件
									$andOrWhereOne = explode(':', $parArr[0]);
									//第二个条件
									$andOrWhereTwo = explode(':', $parArr[1]);
									$whereSql[$key] = array(
										$label[1],
										array($andOrWhereOne[0],$andOrWhereOne[1]),
										array($andOrWhereTwo[0]=>$andOrWhereTwo[1]),
									);
								}
								else
								{
									$whereSql[$key] = array($label[1],$label[2]);
								}
							}
							//判断标签里面的键是否在设置的数组里面有值
							else if( empty($arr[$key]) )
							{
								if ( str_replace('`','',$key) == $key && str_replace('.','',$key) == $key)
								{
									$k = "`{$k}`";
								}
								$whereSql[$key] = $val;
							}
							else
							{
								if( array_key_exists($val , $arr) )
								{
									$val = $arr[$val];
								}
								$whereSql[$arr[$key]] = $val;
							}
						}
					}
				}
			}
			
			if( $whereSql && $check==true)
			{
				//判断是否有没有替换的中文条件。
				$i=0;
				foreach ($whereSql as $k=>$v)
				{
					if (preg_match("/[\x7f-\xff]/", $k))
					{
						tpl::ErrInfo('对不起，没有“'.$k.'”条件，请检查标签【'.$str.'】!');die();
					}
				}
			}
			
			$sql['where'] = $whereSql;
			$sql['order'] = $orderSql;
			$sql['limit'] = $limitSql;
			$sql['group'] = $groupSql;
			
			return $sql;
		}
	}
}
?>
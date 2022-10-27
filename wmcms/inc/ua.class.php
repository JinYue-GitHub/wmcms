<?php
/**
* 浏览器ua判断类
*
* @version        $Id: ua.php 2015年8月9日 08:57  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
new ua();
class ua{
	//pt
	public static $pt;
	public static $urlStr;
	//自动识别并且跳转
	private static $autoJump;
	//域名
	public static $domain;
	//标本的数字号
	public static $ptInt;
	//模版的文件夹名字
	private static $tpPath;
	//版本域名
	private static $domain1;
	private static $domain2;
	private static $domain3;
	private static $domain4;
	//版本标识
	private static $tpmark1;
	private static $tpmark2;
	private static $tpmark3;
	private static $tpmark4;
	//是否启动代理访问
	private static $proxy;
	//站群模式是否开启
	private static $siteOpen;
	//蜘蛛
	public static $spiderData;
	
	public function __construct()
	{
		$this->SetConfig();
		$this->SetUrlStr();
		$this->SetDomain();
		$this->SetPt();
		
		$this->CheckAdmin();
		if( !WMMANAGER )
		{
			$this->CheckProxy();
			$this->Check301();
			$this->CheckModule();
			$this->CheckInstall();
			$this->CheckSite();
			$this->CheckSpider();
		}
	}

	static function SetConfig()
	{
		self::$autoJump = C('config.web.auto_jump');
		self::$domain1 = C('config.web.domain1');
		self::$domain2 = C('config.web.domain2');
		self::$domain3 = C('config.web.domain3');
		self::$domain4 = C('config.web.domain4');
		self::$tpmark1 = C('config.web.tpmark1');
		self::$tpmark2 = C('config.web.tpmark2');
		self::$tpmark3 = C('config.web.tpmark3');
		self::$tpmark4 = C('config.web.tpmark4');
		self::$proxy = C('config.web.proxy_visit');
		self::$siteOpen = (int)C('config.web.site_open');
	}
	
	//设置地址栏参数
	static function SetUrlStr(){
		$urlStr = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
		self::$urlStr = strtolower($urlStr);
	}
	//获得地址栏url参数
	static function getUrlStr(){
		return self::$urlStr;
	}
	

	//设置域名
	static function SetDomain(){
		self::$domain = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
	}
	//获得域名
	static function getDomain(){
		return self::$domain;
	}

	
	//检查当前模版风格
	static function SetPt(){
		$ptInt = '';
		//是否是ajax请求
		if( Request('ajax') == 'yes' )
		{
			$isAjax = true;
		}
		else
		{
			$isAjax = false;
		}
		C('page.ajax' , $isAjax);
		//默认表示当前域名是设置好版本的域名
		$pt = Get('pt',Cookie('pt'));
		if( $pt != '' && !in_array($pt,array(self::$tpmark1,self::$tpmark2,self::$tpmark3,self::$tpmark4)) )
		{
			$pt = '';
		}

		//如果pt等于空
		if( $pt == '' )
		{
			switch ( self::getDomain() )
			{
				case self::$domain1:
					$pt = self::$tpmark1;
					break;
					
				case self::$domain2:
					$pt = self::$tpmark2;
					break;
					
				case self::$domain3:
					$pt = self::$tpmark3;
					break;
					
				case self::$domain4:
					$pt = self::$tpmark4;
					break;
					
				default:
					if( IsPhone() )
					{
						$pt = self::$tpmark3;
					}
					else
					{
						$pt = self::$tpmark4;
					}
					break;
			}
		}
		//如果自动检测跳转，并且不是设置的模版域名
		else if( $pt != '' && Get('pt') == '' && self::$autoJump == '1' && $isAjax == false)
		{
			if( IsPhone() )
			{
				$pt = self::$tpmark3;
			}
			else
			{
				$pt = self::$tpmark4;
			}
		}

		//当前的模版
		C('ua.pt',$pt);
		//当前的标识
		C('ua.mark',$pt);
		//当前的模版路径
		C('ua.path',self::GetMark($pt));
		//模版类型的数字
		C('ua.pt_int',self::$ptInt);
		//保存pt；		
		if( $pt <> Cookie('pt') && $isAjax == false )
		{
			Cookie('pt', $pt , 'auto');
		}

		//检查域名跳转
		/*if( WMMANAGER == false && C('config.web.domain'.self::$ptInt) != '' && C('config.web.domain'.self::$ptInt) != self::getDomain() )
		{
			//跳转到新的网址
			header('Location:http://'.C('config.web.domain'.self::$ptInt).self::getUrlStr());
		}*/
		self::$pt = $pt;
	}
	
	
	/**
	 * 获得版本标识的模板路径
	 * @param 参数1，选填，pt的值
	 */
	static function GetMark( $pt = '' )
	{
		$tpPath = '';
		if( $pt != '' )
		{
			for($i=1;$i<=4;$i++)
			{
				if( C('config.web.tpmark'.$i) == $pt )
				{
					self::$ptInt = $i;
					$tpPath = C('config.web.tp'.$i);
				}
			}
		}
		if( $tpPath == '' )
		{
			if( IsPhone() )
			{
				self::$ptInt = '3';
				$tpPath = C('config.web.tp3');
			}
			else
			{
				self::$ptInt = '4';
				$tpPath = C('config.web.tp4');
			}
		}
		self::$tpPath = $tpPath;
		return $tpPath;
	}
	
	
	//获得pt
	static function GetPt()
	{
		return self::$pt;
	}
	
	
	//检查是否允许代理访问
	static function CheckProxy()
	{
		$proxyVisit = self::$proxy;
		if( $proxyVisit == '0' && ( isset($_SERVER['HTTP_X_FORWARDED_FOR']) || isset($_SERVER['HTTP_VIA']) || isset($_SERVER['HTTP_PROXY_CONNECTION']) || isset($_SERVER['HTTP_USER_AGENT_VIA']) ))
		{
			// 禁止代理访问
			exit('禁止代理访问');
		}
	}


	//检查后台管理跳转
	static function CheckAdmin()
	{
		if( C('config.web.admin_domain') !='' && C('config.web.admin_path') != '' && 
				C('config.web.admin_domain') == self::getDomain() && WMMANAGER !== true )
		{
			header('Location:'.TCP_TYPE.'://'.C('config.web.admin_domain').'/'.C('config.web.admin_path'));//跳转到后台
		}
	}
	
	
	//检查301跳转
	static function Check301()
	{
		//如果是手机
		if( IsPhone() && self::getDomain() != self::$domain3 && self::$domain3 <> '')
		{
			//发出301头部
			header('HTTP/1.1 301 Moved Permanently');
			$newUrl = self::$domain3;
			//跳转到新的网址
			header('Location:'.TCP_TYPE.'://'.$newUrl.self::getUrlStr());
			exit;
		}
		
		//检查301跳转
		if( C('config.web.bdomain') != '' && C('config.web.ndomain') != ''  && self::getDomain() == C('config.web.bdomain') )
		{
			//发出301头部
			header('HTTP/1.1 301 Moved Permanently');
			$newUrl = C('config.web.ndomain');
			//跳转到新的网址
			header('Location:'.TCP_TYPE.'://'.$newUrl.self::getUrlStr());
			exit;
		}
	}
	
	

	//检查模块的绑定域名
	static function CheckModule()
	{
		if( $_SERVER['REQUEST_URI'] == '/' )
		{
			$mDomain = C('config.domain');
			$ptRepOpen = C('config.web.pt_rep');
			if ( is_array($mDomain) )
			{
				foreach ( $mDomain as $k=>$v )
				{
					if ( $v['domain'] == self::getDomain() )
					{
						$newUrl = C( 'config.seo.urls.'.$k.'_'.$v['index'].'.url'.C('config.route.url_type') );
						
						//替换模版标识
						if( $ptRepOpen == '1' )
						{
							$newUrl=str_ireplace('{pt}',self::$pt,$newUrl);
						}
						else
						{
							$newUrl=str_ireplace('pt={pt}&','',$newUrl);
							$newUrl=str_ireplace('?pt={pt}','',$newUrl);
							$newUrl=str_ireplace('&pt={pt}','',$newUrl);
							$newUrl=str_ireplace('{pt}','',$newUrl);
						}
						
						$newUrl=str_ireplace('{tp}','',$newUrl);
						header('HTTP/1.1 301 Moved Permanently');
						header('Location:'.TCP_TYPE.'://'.$v['domain'].$newUrl);
						exit;
					}
				}
			}
		}
	}
	
	
	//检查当前模版是否已经安装了
	static function CheckInstall()
	{
		if( WMMANAGER == false )
		{
			//安装模版检查
			$isInstall = false;
			$installTemplates = C('config.templates');
			if ( is_array($installTemplates) )
			{
				foreach ( $installTemplates as $k=>$v )
				{
					if( $v['path'] == self::$tpPath )
					{
						$isInstall = true;
						break;
					}
				}
			}
			if( !$isInstall )
			{
				echo C( 'system.templates.noinstall' , null , 'lang' );
				exit();
			}
		}
	}
	

	//检查网站的站群模式
	static function CheckSite()
	{
		$domain = $path = $siteDomain = '';
		//获得根域名。
		if( substr_count(self::$domain,'.') == '1' ){
			$siteDomain = self::$domain;
		}
		else
		{
			$cdArr=explode('.',self::$domain);
			unset($cdArr[0]);
			$siteDomain = implode('.',$cdArr);
		}
		
		//self::$isDomain 这个参数应该记录到session或者cookie，因为如果pt存入了cookie这个值就无法进行判断了。
		//下次更新的时候写入。
		if( self::$domain != WEB_URL && self::$siteOpen > 0)
		{
			require_once WMCONFIG.'site.config.php';
			if( isset($C['config']['site'][self::$domain.'_1']) )
			{
				$siteArr1 = $C['config']['site'][self::$domain.'_1'];
			}
			else
			{
				$siteArr1 = '';
			}
			
			if( isset($C['config']['site'][$siteDomain.'_2']) )
			{
				$siteArr2 = $C['config']['site'][$siteDomain.'_2'];
			}
			else
			{
				$siteArr2 = '';
			}
			
			if( is_array($siteArr1) || is_array($siteArr2) )
			{
				//单域名匹配优先
				if( is_array($siteArr1) )
				{
					$siteArr = $siteArr1;
				}
				//其次泛域名
				else
				{
					$siteArr = $siteArr2;
				}
				
				//如果是独立站点
				if( $siteArr['type'] == '1' )
				{
					//独立站点的代码我还没想好怎么写，先空着吧，反正独立站点功能没有的。
				}
				//设置模版类型
				$pt = 'web';
				if( IsPhone() )
				{
					$pt = 'm';
				}
				//设置数据
				C('ua.site','1');
				C('ua.site_path','templates/site/'.self::$domain.'/templates/'.$pt.'/');
			}
		}
	}
	
	
	//蜘蛛检测，并且记录统计
	static function CheckSpider()
	{
		self::$spiderData = IsSpider();
		if( SPIDER == true && self::$spiderData !== false )
		{
			$seoMod = NewModel('system.seo');
			$seoMod->AddSpider(self::$spiderData);
		}
	}
}
?>
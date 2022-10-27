<?php
/**
* 公共标签配置文件和公共标签类
* 使用方法：在数组$tags_arr里面直接新增一组键值对即可在模版里面使用标签
*
* @version        $Id: public.config.php 2015年9月6日 21:20  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime 		  2015年12月04日 17:15  weimeng
*
*/
class common{
	//第三方登录链接
	private $apiLoginUrl;
	//版本切换的url
	private $ptUrl;
	//当前网站域名
	private $domain;
	
	
	//这里放入可以前置执行的标签
	function __construct()
	{
		$this->domain = DOMAIN;
		//自定义标签
		$this->DiyLabel();
		//替换系统URL的标签
		$this->URL();
		//检查平台
		$this->CheckPlatform();
		//GET参数标签替换
		$this->GET();
	}

	//后置方法
	public function After()
	{
		//公共固定标签替换
		$this->PublicLabel();
		//自定义sql
		$this->DiySql();
		//万能的标签必须提前注册才能使用
		$this->Cur();
		//运算标签
		$this->Arithmetic();
		//格式化标签
		$this->Format();
		//插件功能
		$this->Plugin();
		//判断两个字符串相等的标签
		$this->EQ();
		//评论模块后置执行
		if( class_exists('replay') )
		{
			//如果之前用过就不new
			if( replay::$module == '' )
			{
				new replay();
			}
		}

		
		//设置通用数据调用方法
		$CF['common'] = 'GetData';
		//广告标签
		$repFun['a']['common'] = 'AdLabel';
		tpl::Label( '{广告:[s]}[a]{/广告}', '@ad_ad' , $CF , $repFun['a'] );
		//幻灯片标签
		$repFun['a']['common'] = 'FlashLabel';
		tpl::Label( '{幻灯片:[s]}[a]{/幻灯片}', '@flash_flash' , $CF , $repFun['a'] );
		
		//字符分割标签替换
		$this->WMExplode();
		//php标签替换
		$this->WMPhp();
		//php标签替换
		$this->NativePhp('{php}[a]{/php}');
		//php原生写法替换，目前只对后台管理员生效
		if( WMMANAGER )
		{
			$this->NativePhp('<\?php[a]\?>',false);
		}
		//上传图片
		$this->Screenshot();
		//上传文件
		$this->UploadList();
		
		//page标签替换
		$this->PageData();
		//缓存时间标签
		$this->CacheTime();
		//Json标签
		$this->Json();
		$this->Jsonp();
	}

	/**
	 * 获取第三方链接
	 * @param 参数1，字符串，必填， 第三方链接类型
	 */
	private function GetApiLoginUrl($type)
	{
		$this->apiLoginUrl = $this->domain.'/wmcms/action/index.php?action=user.apilogin&api='.$type;
		return $this->apiLoginUrl;
	}
	
	//获取版本切换url
	private function GetPtUrl( $pt )
	{
		$this->ptUrl = '/wmcms/inc/setua.php?pt=' . $pt;
		return $this->ptUrl;
	}

	private function PublicLabel()
	{
		$time = time();
		$weekArray = array("日","一","二","三","四","五","六");
		//网站基本信息
		$tagsArr = array(
			'网站名'=>C('config.web.webname'),
			'网站地址'=>DOMAIN,
			'邮箱'=>C('config.web.email'),
			'备案号'=>C('config.web.beian'),
			'版本号'=>WMVER,
			'templates'=>'/templates/'.C('ua.path'),
			'统计'=>C('config.web.tongji','','',true),
			'qq'=>C('config.web.qq'),
			'电话'=>C('config.web.phone'),
			'时间戳'=>$time,
			'时间'=>date("Y-m-d H:i:s",$time),
			'年'=>date("Y",$time),
			'月'=>date("m",$time),
			'日'=>date('d',$time),
			'时'=>date('H',$time),
			'分'=>date('i',$time),
			'秒'=>date('s',$time),
			'星期'=>$weekArray[date('w',$time)],
			'logo'=>C('config.web.logo_'.C('ua.pt_int')),
			'简版logo'=>C('config.web.logo_1'),
			'彩版logo'=>C('config.web.logo_2'),
			'触屏logo'=>C('config.web.logo_3'),
			'电脑logo'=>C('config.web.logo_4'),
			'QQ群二维码'=>C('config.web.ewm_qun'),
			'微博二维码'=>C('config.web.ewm_weibo'),
			'支付宝二维码'=>C('config.web.ewm_alipay'),
			'微信二维码'=>C('config.web.ewm_wx'),

			//第三方登陆
			'qq登录'=>$this->GetApiLoginUrl('qqlogin'),
			'百度登录'=>$this->GetApiLoginUrl('bdlogin'),
			'微博登录'=>$this->GetApiLoginUrl('weibologin'),
			'支付宝登录'=>$this->GetApiLoginUrl('alipaylogin'),
			'微信登录'=>$this->GetApiLoginUrl('wxlogin'),
			'微信公众号登录'=>$this->GetApiLoginUrl('wxmplogin'),
	

			'首页'=>tpl::url('index'),
			'搜索提交地址'=>'/module/search/search.php',
			'网站地图:html'=>tpl::url('sitemap_html_index' , '' , 2),
			'网站地图:xml'=>tpl::url('sitemap_xml_index' , '' , 2),
			'网站地图:rss'=>tpl::url('sitemap_rss_index' , '' , 2),

			'作者中心'=>tpl::url('author_index'),

			'协议'=>HTTP_TYPE,
			'当前路径'=>$_SERVER['REQUEST_URI'],
			'当前参数'=>GetKey($_SERVER, 'QUERY_STRING'),
			'当前url'=>HTTP_TYPE.'://'.GetKey($_SERVER, 'HTTP_HOST').$_SERVER['REQUEST_URI'],
			'简洁版当前url'=>HTTP_TYPE.'://'.C('config.domain.domain1').$_SERVER['REQUEST_URI'],
			'炫彩版当前url'=>HTTP_TYPE.'://'.C('config.domain.domain2').$_SERVER['REQUEST_URI'],
			'触屏版当前url'=>HTTP_TYPE.'://'.C('config.domain.domain3').$_SERVER['REQUEST_URI'],
			'电脑版当前url'=>HTTP_TYPE.'://'.C('config.domain.domain4').$_SERVER['REQUEST_URI'],
			'简洁版域名'=>C('config.web.domain1'),
			'炫彩版域名'=>C('config.web.domain2'),
			'触屏版域名'=>C('config.web.domain3'),
			'电脑版域名'=>C('config.web.domain4'),

			'登录'=>tpl::url('user_login'),
			'注册'=>tpl::url('user_reg'),
			'充值'=>tpl::url('user_charge'),
			'找回密码'=>tpl::url('user_getpsw'),
			'用户中心'=>tpl::url('user_home'),
			'退出登录'=>tpl::url('user_exit'),
			'基本资料'=>tpl::url('user_basic'),
			'消息列表'=>tpl::url('user_msglist'),
			'签到中心'=>tpl::url('user_sign'),
			'签到列表'=>tpl::url('user_signlist'),
			'等级详情'=>tpl::url('user_level'),
			
			//版本切换url
			'简洁版'=>$this->GetPtUrl( C('config.web.tpmark1') ),
			'炫彩版'=>$this->GetPtUrl( C('config.web.tpmark2') ),
			'触屏版'=>$this->GetPtUrl( C('config.web.tpmark3') ),
			'电脑版'=>$this->GetPtUrl( C('config.web.tpmark4') ),

			'session_id'=>GetSessionId(),
		);
		
		//如果存在表单锁
		$tokenLock = tpl::Tag( '{表单锁}' );
		if( isset($tokenLock[0][0]) )
		{
			$tagsArr['表单锁'] = FormTokenCreate();
		}
		//如果存在表单锁Token
		$tokenLock = tpl::Tag( '{表单锁Token}' );
		if( isset($tokenLock[0][0]) )
		{
			$tagsArr['表单锁Token'] = FormTokenCreate(true);
		}

		tpl::Rep($tagsArr);
		
		//第三方登录开启
		tpl::IfRep( C('config.api.wxlogin.api_open') , '=' , '1' , '微信登录开启', '微信登录关闭');
		tpl::IfRep( C('config.api.qqlogin.api_open') , '=' , '1' , 'qq登录开启', 'qq登录关闭');
		tpl::IfRep( C('config.api.bdlogin.api_open') , '=' , '1' , '百度登录开启', '百度登录关闭');
		tpl::IfRep( C('config.api.weibologin.api_open') , '=' , '1' , '微博登录开启', '微博登录关闭');
		tpl::IfRep( C('config.api.alipaylogin.api_open') , '=' , '1' , '支付宝登录开启', '支付宝登录关闭');
		tpl::IfRep( C('config.api.wxmplogin.api_open') , '=' , '1' , '微信公众号登录开启', '微信公众号登录关闭');
		tpl::IfRep( C('config.api.wxapp_login.api_open') , '=' , '1' , '微信APP登录开启', '微信APP登录关闭');
		tpl::IfRep( C('config.api.qqapplogin.api_open') , '=' , '1' , '手机QQ登录开启', '手机QQ登录关闭');
	}
	
	/**
	 * 自定义标签
	 */
	function DiyLabel()
	{
		$where['table'] = '@config_label';
		$where['field'] = 'label_name,label_value';
		$data = wmsql::GetAll($where);
		//存在数据就
		if( $data )
		{
			foreach ($data as $k=>$v)
			{
				$arr[$v['label_name']] = $v['label_value'];
			}
			//替换自定义标签
			tpl::Rep($arr);
		}
	}
	
	/**
	 * 系统url标签
	 */
	function URL()
	{
		$tagArr = tpl::Tag('{url:[s]}');
	
		if( !empty($tagArr[0]) )
		{
			foreach ($tagArr[1] as $k=>$v)
			{
				$arr[$tagArr[0][$k]] = Url($v);
			}
			tpl::Rep( $arr , null , 2 );
		}
	}

	
	/**
	 * 执行模版中的PHP标签
	 * 注意：目前不能使用echo，需要把所有结果集合，然后return。
	 **/
	static function WMPhp()
	{
		$phpArr = tpl::Tag('{wmphp}[a]{/wmphp}');
		for ($i = 0 ; $i < count($phpArr[0]) ; $i++ )
		{
			$funName = 'WMCMSPHP'.md5($phpArr[1][$i]);
			$str = 'function '.$funName.'(){$html="";'.$phpArr[1][$i].' return $html;}$html = '.$funName.'();';
			//是否开启了debug
			if( DEBUG )
			{
				eval($str);
			}
			else
			{
				if( @eval($str) === false )
				{
					tpl::ErrInfo('对不起wmphp标签语法错误，如需查看详情请开启debug模式！<br/>错误代码：'.$phpArr[1][$i]);
				}
			}
			tpl::Rep( array($phpArr[0][$i]=>$html) , null , 2 );
		}
	}
	/**
	 * 执行原生PHP标签
	 **/
	static function NativePhp($str='{php}[a]{/php}',$z=true)
	{
		$phpArr = tpl::Tag($str,'',$z);
		for ($nativePhpI = 0 ; $nativePhpI < count($phpArr[0]) ; $nativePhpI++ )
		{
			$phpContent = $phpArr[1][$nativePhpI];
			$cacheTemp = WMCACHE.'temp/eval/'.date('Y-m').'/'.date('d').'-'.md5($phpContent.C('page.pagetype').C('page.path').C('page.tpath')).'.php';
			file::CreateFile($cacheTemp, '<?php '.$phpContent,1);
			
			ob_start();
			$rs = require_once $cacheTemp;
			$html = ob_get_contents();
			ob_end_clean();
			
			//是否开启了debug
			if( !DEBUG && $rs == false )
			{
				tpl::ErrInfo('对不起php标签语法错误，如需查看详情请开启debug模式！<br/>错误代码：'.$phpArr[1][$i]);
			}
			tpl::Rep( array($phpArr[0][$nativePhpI]=>$html) , null , 2 );
		}
	}

	/**
	 * 替换page数组里面的参数
	 **/
	static function PageData()
	{
		$phpArr = tpl::Tag('{页面数据:[s]}');
		for ($i = 0 ; $i < count($phpArr[0]) ; $i++ )
		{
			$data = C('page.'.$phpArr[1][$i]);
			if( is_array($data) )
			{
				print_r($data);
			}
			tpl::Rep( array($phpArr[0][$i]=>$data) , null , 2 );
		}
	}
	

	/**
	 * GET参数替换标签
	 **/
	static function Get()
	{
		$getArr = tpl::Tag('{get:[s]}');
		if( count($getArr[0]) > 0 )
		{
			for ($i = 0 ; $i < count($getArr[0]) ; $i++ )
			{
				$valArr = explode('=',$getArr[1][$i]);
				//get键名
				$getKey = $valArr[0];
				$default = '';
				//设置了默认值
				if( count($valArr) > 1)
				{
					$default = $valArr[1];
				}
				$val = Get($getKey,$default);
				$val = str_replace('{','&#123;',$val);
				$val = str_replace('}','&#125;',$val);
				$arr['get:'.$getArr[1][$i]] = $val;
			}
			tpl::Rep( $arr );
		}
	}

	
	/**
	 * 替换cur标签
	 */
	private function Cur()
	{
		$key = $val = '';
		$curArr = tpl::Tag('{cur:[s]}[a]{/cur}');

		for ($i = 0 ; $i < count($curArr[0]) ; $i++ )
		{
			//查找哪些运算符
			$smpArr = array('!=','>','<','=');
			//循环查询
			foreach ( $smpArr as $k=>$v)
			{
				//是否能够分割出来
				$symbolArr =  explode( $v , $curArr[1][$i] );
				//如果存在运算符
				if( count($symbolArr) > 1 )
				{
					//cur的键和值
					list($key,$val) = $symbolArr;
					$valArr = explode(',' , $val);
					foreach($valArr as $k1=>$v1)
					{
						$code = '';
						$pageKey = C('page.'.$key);
						switch ($v)
						{
							case '!=':
								$parArr = explode(';',$v1);
								$count = count($parArr);
								if($count == 2)
								{
									list($par,$parType) = explode('=',$parArr[1]);
									
								}
								if( ($count==1 || ($count==2 && $parType=='val') ) && $key != $v1[0])
								{
									$code = $curArr[2][$i];
								}
								else if( $count==2 && $parType=='key' && $pageKey != $v1[0] )
								{
									$code = $curArr[2][$i];
								}
								break;
								
							case '>':
								if( ($pageKey != '' && $pageKey > $v1) || intval($key) > $v1)
								{
									$code = $curArr[2][$i];
								}
								break;
								
							case '<':
								if( ($pageKey != '' && $pageKey < $v1) || intval($key) < $v1)
								{
									$code = $curArr[2][$i];
								}
								break;
								
							case '=':
								if( ($pageKey != '' && $pageKey == $v1)  || $key == $v1 )
								{
									$code = $curArr[2][$i];
								}
								break;
						}

						tpl::Rep( array($curArr[0][$i]=>$code) , null , 2 );
					}
				}
			}
		}
	}


	/**
	 * 获取数据，只用于单表查询，并且标签的条件已经设置好了
	 * @param 参数1，表名
	 * @param 参数2，条件
	 */
	static function GetData( $table = '' , $where='' )
	{
		$data = '';
		if ( $table != '' )
		{
			switch ( $table )
			{
				case '@ad_ad':
					$wheresql = self::AdWhere($where);
					break;

				case '@flash_flash':
					$wheresql = self::FlashWhere($where);
					break;
					
				case '@upload':
					$wheresql = tpl::GetWhere($where);
					$wheresql['field'] = 'upload_id,upload_ext,upload_alt,upload_type,upload_img,upload_simg,upload_size,upload_time,upload_height,upload_width';
					break;
					
				default:
					$wheresql = tpl::GetWhere($where);
					break;
			}
			
			if( !isset($wheresql['table']) )
			{
				$wheresql['table'] = $table;
			}
				
			$data = wmsql::GetAll($wheresql);
		}
		return $data;
	}
	
	
	/**
	 * 获得url
	 * @param 参数1，必须，action名字
	 * @param 参数2，必须，处理的类型
	 * @param 参数3，必须，附带的参数
	 */
	static function GetUrl( $action , $data = '' )
	{
		$strPar = '';
		//判断模块是否存在
		if( is_array($data) )
		{
			foreach ($data as $k=>$v)
			{
				$strPar .= '&'.$k.'='.$v;
			}
		}

		return '/wmcms/action/index.php?action='.$action.$strPar;
	}

	/**
	 * 评分标签替换
	 * @param 参数1，必须，类名。
	 * @param 参数2，必须，内容的id。
	 */
	static function ScoreLabel( $module , $cid )
	{
		//评分查询
		$data = self::GetData( '@operate_score' , 'score_cid='.$cid.';score_module='.$module );
	
		if( !$data )
		{
			$one = $two = $three = $four = $five = $avg = $sum = 0;
		}
		else
		{
			$one = $data[0]['score_one'];
			$two = $data[0]['score_two'];
			$three = $data[0]['score_three'];
			$four = $data[0]['score_four'];
			$five = $data[0]['score_five'];
			$sum = $one + $two + $three + $four + $five;
			$avg = round( ($one*1 + $two*2 + $three*3 + $four*4 + $five*5 ) / $sum , 1 );
		}

		//评分模版替换
		$arr = array(
			'评分模版'=>file::GetFile(WMTEMPLATE.'system/score.html'),
			'评分模块'=>$module,
			'评分id'=>$cid,
		);
		tpl::Rep($arr);
		
		//普通标签替换
		$url = '/wmcms/action/index.php?action=score.score&module='.$module.'&cid='.$cid.'&score=';

		$arr = array(
			'一分人数'=>$one,
			'二分人数'=>$two,
			'三分人数'=>$three,
			'四分人数'=>$four,
			'五分人数'=>$five,
			'评分人数'=>$sum,
			'平均分数'=>$avg,
			'平均分整数'=>intval($avg),
			'平均分小数'=>intval($avg)+0.5,

			'一分'=>$url.'1',
			'二分'=>$url.'2',
			'三分'=>$url.'3',
			'四分'=>$url.'4',
			'五分'=>$url.'5',
			'一分:ajax'=>$url.'1&ajax=yes',
			'二分:ajax'=>$url.'2&ajax=yes',
			'三分:ajax'=>$url.'3&ajax=yes',
			'四分:ajax'=>$url.'4&ajax=yes',
			'五分:ajax'=>$url.'5&ajax=yes',
		);
		tpl::Rep($arr);
	}
	
	
	
	/**
	 * 上下一篇内容替换
	 * @param 参数1，必须，where条件，包括表名和字段
	 * @param 参数2，id字段的名字
	 * @param 参数3，内容id
	 * @param 参数4，内容的url链接
	 * @param 参数5，id标签名字，替换参数2
	 * @param 参数6，内容标题的名字
	 */
	static function PreNext( $where , $idName , $cid , $url , $par , $name )
	{
		global $tableSer;
		$moduleArr = explode('_',$idName);
		$table = $tableSer->tableArr[$moduleArr[0]];
		$simgName = isset($table['simg'])?$table['simg']:'';
		
		//上一篇查询
		if( !empty($simgName) )
		{	
			$where['field'] .= ','.$simgName;
		}
		$where['where'][$idName] = array('<',$cid);
		$where['order'] = '`'.$idName.'` desc';
		$data = wmsql::GetOne($where);
		if( !$data )
		{
			$arr['preurl'] = C('system.content.no_preurl' , null , 'lang');
			$arr['上一内容'] = C('system.content.no_predata' , null , 'lang');
			$arr['上一内容缩略图'] = C('system.content.no_predata' , null , 'lang');
		}
		else
		{
			$arr['preurl'] = tpl::Rep( array($par=>$data[$idName] ) , $url );
			$arr['上一内容'] = $data[$name];
			$arr['上一内容缩略图'] = !empty($simgName)?$data[$simgName]:'';
		}
		tpl::IfRep( $data , '=' , true , '有上一内容', '无上一内容');
	
		//下一篇查询
		$where['where'][$idName] = array('>',$cid);
		$where['order'] = '`'.$idName.'` asc';
		$data = wmsql::GetOne($where);

		if( !$data )
		{
			$arr['nexturl'] = C('system.content.no_nexturl' , null , 'lang');
			$arr['下一内容'] = C('system.content.no_nextdata' , null , 'lang');
			$arr['下一内容缩略图'] = C('system.content.no_predata' , null , 'lang');
		}
		else
		{
			$arr['nexturl'] = tpl::Rep( array($par=>$data[$idName] ) , $url );
			$arr['下一内容'] = $data[$name];
			$arr['下一内容缩略图'] = !empty($simgName)?$data[$simgName]:'';
		}
		tpl::IfRep( $data , '=' , true , '有下一内容', '无下一内容');
	
		tpl::Rep($arr);
	}


	/**
	 * 获取上传图片列表
	 */
	static function Screenshot()
	{
		$whereLabel = 'upload_ext=[lin->jpg,jpng,png,gif];';
		tpl::Rep( array('{截图:'=>'{截图:'.$whereLabel) , null , '2' );
		self::UploadList('截图');
	}
	static function UploadList($label='上传文件')
	{
		$CF['common'] = 'GetData';
		$repFun['a']['common'] = 'UploadListLabel';
		tpl::Label( '{'.$label.':[s]}[a]{/'.$label.'}', '@upload' , $CF , $repFun['a'] );
	}
	
	
	/**
	 * 上传文件列表标签替换
	 **/
	static function UploadListLabel( $data , $blcode )
	{
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

			//设置自定义中文标签
			$arr2=array(
				'文件id'=>$v['upload_id'],
				'文件地址'=>$v['upload_img'],
				'文件缩略图'=>$v['upload_simg'],
				'文件描述'=>$v['upload_alt'],
				'文件后缀'=>$v['upload_ext'],
				'文件大小'=>$v['upload_size'],
				'文件宽度'=>$v['upload_width'],
				'文件高度'=>$v['upload_height'],
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
	
	
	/**
	 * 搜索条件转化
	 * @param 参数1，必须，搜索的字段 名字，0为全部，1为标题，2为作者，3为标签
	 * @param 参数2，必须，搜索类型。
	 * @param 参数3，关键词。
	 */
	static function SearchWhere( $fieldArr , $type , $key = '' )
	{
		//先检查搜索类型
		switch ($type)
		{
			case '1':
				$field = $fieldArr[0];
				break;
			case '2':
				$field = $fieldArr[1];
				break;
			case '3':
				$field[0] = $fieldArr[2];
				break;
			default:
				$field = $fieldArr;
				break;
		}

		//再检查关键词
		if ( $key == '' )
		{
			$where = $field;
		}
		else
		{
			//如果是搜索全部类型
			if( is_array($field) )
			{
				$str = '';
				$keys = explode( ' ' , $key );
				foreach ($field as $key=>$val)
				{
					$str .= '(';
					foreach ($keys as $k=>$v)
					{
						//如果包含tags
						if( strstr($val,"_tags") )
						{
							$str .= "`{$val}` like '%{$v}%' or FIND_IN_SET('{$v}',`{$val}`)";
						}
						else
						{
							switch ($val)
							{
								//针对小说设置
								case 'novel_name':
									$str .= "`{$val}` like '%{$v}%' or `novel_wordname` like '%{$v}%'";
									break;
								default:
									$str .= "`{$val}` like '%{$v}%'";
									break;
							}
						}
						if ( !IsLast($keys, $k) )
						{
							$str .= " or ";
						}
					}
					$str .= ')';

					if ( !IsLast($field, $key) )
					{
						$str .= " or ";
					}
				}
				$where[ $field[0] ] = array( 'string' , $str);
			}
			else
			{
				switch ($field)
				{
					//针对小说设置
					case 'novel_name':
						$file['field'] = 'novel_wordname';
						$file['val'] = str::ArrToStr(explode( ' ' , $key ));
						$where[ $field ] = array( 'like' , $file);
						break;
					default:
						$where[ $field ] = array( 'like' , str::ArrToStr(explode( ' ' , $key )) );
						break;
				}
			}
		}

		$wheresql['limit'] = common::SearchLimit();
		$wheresql['where'] = $where;
		$wheresql['list'] = true;
		return $wheresql;
	}

	
	/**
	 * 搜索分页条件转换
	 */
	static function SearchLimit()
	{
		$tagArr = tpl::Tag( '{搜索结果:[s]}[a]{/搜索结果}' );

		if( isset($tagArr[1][0]) && $tagArr[1][0] != '' )
		{
		    $limit = tpl::GetWhere($tagArr[1][0])['limit'];
		}
		//防止number为空
		else
		{
    		$limit = 0;
		}
		return $limit;
	}
	
	
	/**
	 * 广告标签的中文条件
	 * @param 参数1，必须，标签条件
	 */
	static function AdWhere($where)
	{
		//设置需要替换的字段
		$arr = array(
			'id' =>'ad_id',
			'时间' =>'ad_time',
			'最新' =>'ad_time',
			'分类id' =>'ad_type_id',
			'时间倒序' =>'ad_time desc',
			'类型' =>'ad_type',
			'文字' =>'1',
			'图文' =>'2',
			'js' =>'3',
		);
	
		$where = tpl::GetWhere($where,$arr);
		$where['ad_status'] = 1;
		return $where;
	}
	
	/**
	* 替换广告标签
	**/
	static function AdLabel(  $data , $blcode )
	{
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

			//设置自定义中文标签
			$arr2=array(
				'i'=>$i,
				'广告名字'=>$v['ad_name'],
				'广告标题'=>$v['ad_title'],
				'广告链接'=>$v['ad_url'],
				'广告图片'=>$v['ad_img'],
				'广告js'=>str::ToHtml($v['ad_js'],true,false),
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

	
	/**
	 * 幻灯片标签的中文条件
	 * @param 参数1，必须，标签条件
	 */
	static function FlashWhere($where)
	{
		//设置需要替换的字段
		$arr = array(
			'id' =>'flash_id',
			'时间' =>'flash_time',
			'最新' =>'flash_time',
			'分类id' =>'f.type_id',
			'分类拼音' =>'type_pinyin',
			'时间倒序' =>'flash_time desc',
			'排序' =>'flash_order',
			'模块' =>'flash_module',
			'页面' =>'flash_pid',
		);

		$where = tpl::GetWhere($where,$arr);
		$where['table'] = '@flash_flash as f';
		$where['left']['@flash_type as t'] = 't.type_id=f.type_id';
		$where['where']['flash_status'] = 1;
		return $where;
	}
	
	/**
	 * 幻灯片标签替换
	 **/
	static function FlashLabel(  $data , $blcode )
	{
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
	
			//设置自定义中文标签
			$arr2=array(
				'i'=>$i,
				'幻灯片链接'=>$v['flash_url'],
				'幻灯片图片'=>$v['flash_img'],
				'幻灯片标题'=>$v['flash_title'],
				'幻灯片简介'=>$v['flash_info'],
				'幻灯片描述'=>str::ToHtml($v['flash_desc']),
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
	


	/**
	 * 判断是否相等的标签
	 */
	private function EQ()
	{
		$key = $val = '';
		$curArr = tpl::Tag('{eq:[s]:[s]}[a]{/eq}');

		for ($i = 0 ; $i < count($curArr[0]) ; $i++ )
		{
			//多个值判断
			$val = explode(',', $curArr[2][$i]);
			$isEq = false;
			foreach ($val as $k=>$v)
			{
				if( $curArr[1][$i] == $v )
				{
					$isEq = true;
					break;
				}
			}
			
			if( $isEq == true )
			{
				tpl::Rep( array($curArr[0][$i]=>$curArr[3][$i]) , null , 2 );
			}
			else
			{
				tpl::Rep( array($curArr[0][$i]=>'') , null , 2 );
			}
		}
	}
	

	/**
	 * 模版运算标签
	 */
	private function Arithmetic()
	{
		$tagArr = tpl::Tag('{运算:[s]}');

		if( !empty($tagArr[0]) )
		{
			foreach ($tagArr[1] as $k=>$v)
			{
				$artStr = '$val = @('.$v.');';
				if( @eval($artStr) === false)
				{
					
				}
				$arr[$tagArr[0][$k]] = $val;
			}
			tpl::Rep( $arr , null , 2 );
		}
	}
	

	/**
	 * 模版格式化标签
	 * @param 参数1，格式化的操作类型
	 * @param 参数2，需要进行格式化的数据
	 * @param 参数3，格式化的后的格式
	 * @param 参数3示例，{格式化:时间:12525555:n-y-r s:f:m}
	 * @param 参数3示例，{格式化:数字:12525555:1000|2}
	 * @param 参数3示例，{格式化:数字:12525555:,}
	 */
	private function Format()
	{
		$tagArr = tpl::Tag('{格式化:[s]:[s]:[s]}');
		$arr = array();
		if( !empty($tagArr[0]) )
		{
			foreach ($tagArr[1] as $k=>$v)
			{
				switch ($v)
				{
					case '时间':
						$label = tpl::Time($tagArr[3][$k], $tagArr[2][$k]);
						break;
	
					case '数字':
						$formatArr = explode('|',$tagArr[3][$k]);
						//如果第一个不是数字，那么只是没三位增加间隔符
						if( count($formatArr) == 1 )
						{
							$label = number_format($tagArr[2][$k]);
							if( $tagArr[3][$k] != '' && $tagArr[3][$k] != ',' )
							{
								$label = str_replace(',',$tagArr[3][$k],$label);
							}
						}
						//是数字就除以相关参数
						else
						{
							$label = round($tagArr[2][$k]/$formatArr[0],$formatArr[1]);
						}
						break;
	
					default:
						break;
				}
				$arr[$tagArr[0][$k]] = $label;
			}
			tpl::Rep( $arr , null , 2 );
		}
	}

	
	/**
	* 替换自定义sql
	**/
	function DiySql()
	{
		$labelArr = tpl::tag('{wmsql:[s]}[a]{/wmsql}');
		$labelCount = count($labelArr[0]);
		for ( $i = 0 ; $i < $labelCount ; $i++ )
		{
			$str = '';
			$sql = $labelArr[1][$i];
			$lcode = $labelArr[2][$i];

			$reslut = wmsql::Query( $this->DiySqlRep($sql) );

			if ( !$reslut )
			{
				tpl::rep( array('wmsql:'.$sql.'}'.$lcode.'{/wmsql'=>'') );
			}
			else
			{
				foreach ( $reslut as $k=>$v)
				{
					$arr = array();
					$labelCode = $lcode;
					
					preg_match_all('/{'.L.'(.*?)}/', $labelCode, $wlable);
					
					for( $j=0; $j < count( $wlable[0] ) ; $j++ )
					{
						$arr[L.$wlable[1][$j]] = $v[ $wlable[1][$j] ];
					}
					
					$str.= tpl::rep( $arr , $labelCode );
				}
				tpl::rep(array('wmsql:'.$sql.'}'.$lcode.'{/wmsql'=>$str));
			}
		}
	}
	
	/**
	 * 替换sql语句
	 * @param 参数1，必须，sql语句
	 */
	function DiySqlRep( $sql )
	{
		$arr = array(
			'查询'=>'select * from ',
			'用户'=>'@@user_user ',
			'文章'=>'@@article_article ',
			'应用'=>'@@app_app ',
			'小说'=>'@@novel_novel' ,
			'条件'=>'where ',
			'随机'=>'order by rand()',
			'排序'=>'order by ',
			'分组'=>'group by ',
			'数据'=>'limit ',
			'条'=>'',
		);
		
		$sql = tpl::Rep( $arr , $sql , 3);
		return $sql;
	}

	
	
	/**
	 * 替换json/jsonp标签
	 */
	private function Jsonp()
	{
		$this->Json('jsonp');
	}
	private function Json($type='json')
	{
		$jsonArr = tpl::Tag('{'.$type.':[a]}[a]{/'.$type.'}');
		if (count($jsonArr[0]) > 0 )
		{
			$res = $data = $listData = array();
			//code代码
			$code = '200';
			//提示语言
			$msg = C('system.action.success',null,'lang');
			//数据模板
			$dataTemp = $jsonArr[2][0];

			//首先检测是否有列表数据，并且列出。检查是否是列表输出
			$listArr = tpl::Tag("{list:[a]}[a]{/list}",$jsonArr[2][0]);
			//如果列表大于0就设置为当前的列表数据量，
			if( count($listArr[0]) > 0)
			{
				//列表开始序号
				$listStartI = 0;
				for($i=0;$i<=count($listArr[0])-1;$i++)
				{
					//提取key
					$listKName = $listKVal = $lastKVal = '';
					if( !empty($listArr[1][$i]) )
					{
						$keyArr = explode(';',$listArr[1][$i]);
						foreach($keyArr as $k=>$v)
						{
							$keysData = array_keys($data);
							$lastKVal = end($keysData);
							if( $v != '' )
							{
								list($listKName,$listKVal) = explode('=',$v);
								if( $listKVal != $lastKVal )
								{
									$listStartI = $i;
								}
							}
						}
					}
					
					
					//循环一次list数据就移除这个list数据模版。
					$dataTemp = str_replace($listArr[0][$i], '', $dataTemp);
					$dataArr = tpl::Tag('{[s]}={[a]};',$listArr[2][$i]);
					if (count($dataArr) == 3 )
					{
						if( $listKVal != $lastKVal )
						{
							$listData = array();
						}
						//匹配出数据
						foreach ($dataArr[1] as $key=>$val)
						{
							$listData[$i-$listStartI][$dataArr[1][$key]] = $dataArr[2][$key];
						}
					}

					
					if( $listKName == 'key' )
					{
						$data[$listKVal] = $listData;
					}
					else
					{
						$data = $listData;
					}
				}
			}
			

			//检测是否是分组
			$groupArr = tpl::Tag("{group:[a]}[a]{/group}",$jsonArr[2][0]);
			if( count($groupArr[0]) > 0)
			{
				//循环列表数据次数
				for($i=0;$i<=count($groupArr[0])-1;$i++)
				{
					$groupData = null;
					//提取key
					$groupKName = $groupKVal = '';
					if( !empty($groupArr[1][$i]) )
					{
						list($groupKName,$groupKVal) = explode('=',$groupArr[1][$i]);
					}
					else
					{
						$groupKVal = 'group';
					}
						
					//循环一次list数据就移除这个list数据模版。
					$dataTemp = str_replace($groupArr[0][$i], '', $dataTemp);
					$dataArr = tpl::Tag('{[s]}={[a]};',$groupArr[2][$i]);
					if (count($dataArr) == 3 )
					{
						//匹配出数据
						foreach ($dataArr[1] as $key=>$val)
						{
							$groupData[$dataArr[1][$key]] = $dataArr[2][$key];
						}
					}
					$data[$groupKVal] = $groupData;
				}
			}
			
			//匹配普通数据模板
			$dataArr = tpl::Tag('{[s]}={[a]};',$dataTemp);
			if (count($dataArr) == 3 )
			{
				//匹配出数据
				foreach ($dataArr[1] as $key=>$val)
				{
					$data[$dataArr[1][$key]] = $dataArr[2][$key];
				}
			}

			//如果不存在就设置失败的code
			if( empty($data) )
			{
				$code = 500;
			}
			if( $jsonArr[1][0] )
			{
				$parArr = explode(';',$jsonArr[1][0]);
				foreach($parArr as $k=>$v)
				{
					list($key,$val) = explode('=',$v);
					//如果指定了固定参数
					if( $key == 'code'|| $key == 'msg' )
					{
						$$key = $val;
					}
					$res[$key] = serialize($val);
				}
				C('res',$res);
			}
		
			if( $type == 'json' )
			{
				ReturnJson($msg,$code,$data);
			}
			else
			{
				ReturnJsonp($msg,$code,$data);
			}
		}
	}

	
	function Plugin()
	{
		/* //插件钩子检测
		 preg_match_all('/<!--{插件:(.*?):(.*?):(.*?)}-->/', $templates, $lable);
		 $count=count($lable[0]);
		 for($i=1;$i<=$count;$i++)
		 {
		 //获得插件的参数
		 $p_id=$lable[1][$i-1];
		 $p_file=$lable[2][$i-1];
		 $p_func=$lable[3][$i-1];
		
		 //检测插件是否存在
		 if(!is_dir(WMPLUGIN.$p_id))
		 {
		 errinf("对不起，插件ID:".$p_id."不存在！");
		 exit;
		 }
		
		 //检查入口文件是否存在
		 if(!file_exists(WMPLUGIN.$p_id.'/'.$p_file))
		 {
		 errinf("对不起，插件ID:".$p_id."的入口文件".$p_file."不存在！");
		 exit;
		 }
		
		 //如果都存在就引用文件
		 require_once(WMPLUGIN.$p_id.'/'.$p_file);
		
		 //检查类是否存在
		 if(!class_exists('Plugin'))
		 {
		 errinf("对不起，插件ID:".$p_id."的入口文件".$p_file."中的类不存在！");
		 exit;
		 }
		 else
		 {
		 $ClassName='Plugin_'.$p_id;
		 $Plugin=new $ClassName;
		 }
		
		 //检查方法是否存在
		 if(!method_exists($Plugin,$p_func))
		 {
		 errinf("对不起，插件ID:".$p_id."的入口文件".$p_file."中的".$p_func."方法不存在！");
		 exit;
		 }
		 else
		 {
		 //方法存在就调用，然后替换掉
		 $templates=str_replace('<!--{插件:'.$p_id.':'.$p_file.':'.$p_func.'}-->', $Plugin->$p_func, $templates);
		 }
		 } */
	}
	
	
	//替换缓存时间标签
	function CacheTime()
	{
		$tagArr = tpl::Tag('{缓存时间:[s]}');
		$repArr = array();
		for ($i = 0 ; $i < count($tagArr[0]) ; $i++ )
		{
			list($module,$page) = explode('-', $tagArr[1][$i]);
			if( $module == 'index' )
			{
				$cacheTime = C('config.web.cache_index');
			}
			else if( $module == $page || $page == 'read' )
			{
				$cacheTime = C('config.web.cache_module_content');
			}
			else
			{
				$cacheTime = C('config.web.cache_module_'.$page);
			}
			
			$repArr[$tagArr[0][$i]] = $cacheTime;
		}
		//替换数据不为空
		if( !empty($repArr) )
		{
			tpl::Rep( $repArr , null , 2 );
		}
	}
	
	/**
	 * 替换微信js签名标签
	 * @param 参数1，必须，微信平台类对象 $wxPfSer
	 */
	private function WxJSSign($wxPfSer)
	{
		$tagArr = tpl::Tag('{微信JS签名:[s]}');
		if(count($tagArr[0]) > 0 )
		{
			//获取微信jssdk签名包
			$signPack = $wxPfSer->GetJSSDKSignPackage();
			
			for ($i = 0 ; $i < count($tagArr[0]) ; $i++ )
			{
				$repArr['微信JS签名:'.$tagArr[1][$i]] = GetKey($signPack,$tagArr[1][$i]);
			}
			$repArr['微信JS签名:'] = "appId:'{$signPack['appId']}',timestamp:{$signPack['timestamp']},nonceStr:'{$signPack['nonceStr']}',signature:'{$signPack['signature']}',";

			//替换标签
			tpl::Rep( $repArr );
		}
	}
	

	//检查是否关注
	function CheckPlatform()
	{
		$repArr['微信JS签名:appId'] = '';
		$repArr['微信JS签名:timestamp'] = '';
		$repArr['微信JS签名:nonceStr'] = '';
		$repArr['微信JS签名:signature'] = '';
		$repArr['微信JS签名:'] = '';
		//如果是微信浏览器
		if( IsWeiXin() )
		{
			$fansMod = NewModel('operate.weixin_fans');
			$accountMod = NewModel('operate.weixin_account');
			$accountData = $accountMod->GetMain();
			//存在微信公众号数据
			if( $accountData )
			{
				$wxConfig['appid'] = $accountData['account_appid'];
				$wxConfig['secret'] = $accountData['account_secret'];
				//微信公众号网页
				$wxAcSer = NewClass('weixin_account',$wxConfig);
				//微信公众号平台
				$wxPfSer = NewClass('weixin_platform',$wxConfig);
				
				$this->WxJSSign($wxPfSer);
				
				
				//公众号开启强制关注，并且不是未关注页面
				if( $accountData['account_follow'] == 1 && C('page.dtemp') != 'user/unsub_weixin.html' )
				{
					$this->__CheckWxSub($accountData['account_id'],$wxAcSer,$wxPfSer);
				}
			}
		}

		//替换标签
		tpl::Rep( $repArr , null , 2 );
		//是否是微信浏览器标签
		tpl::IfRep( IsWeiXin() , '=' , true , '微信浏览器');
	}
	//检查微信关注方法
	private function __CheckWxSub($aid,$wxAcSer,$wxPfSer)
	{
		$fansMod = NewModel('operate.weixin_fans');
		if( Session('weixin_openid') != 'false' && Session('weixin_openid') != '' )
		{
			$userInfo = $fansMod->GetByOpenid($aid,Session('weixin_openid'));
			if( !$userInfo || $userInfo['fans_subscribe'] != '1' )
			{
				Jump('module;user/unsub_weixin.php');
			}
		}
		else
		{
			//获得用户信息
			$userInfo = $wxPfSer->UserGetInfo($wxAcSer->GetOpenid(2));
			//用户信息获取成功
			if( isset($userInfo['openid']) )
			{
				Session('weixin_openid',$userInfo['openid']);
				//公众号开启了强制关注，但是用户没有关注就跳转
				if( $userInfo['subscribe'] != '1')
				{
					Jump('module;user/unsub_weixin.php');
				}
			}
			else
			{
				tpl::ErrInfo($userInfo['errmsg']);
			}
		}
	}
	
	/**
	 * 字符串分割标签
	 */
	private function WMExplode()
	{
		$expArr = tpl::Tag('{字符分割:[s]}[a]{/字符分割}');
		
		for ($i = 0 ; $i < count($expArr[0]) ; $i++ )
		{
			//为空就直接替换
			if( $expArr[1][$i] == '' )
			{
				tpl::Rep( array($expArr[0][$i]=>'') , null , 2 );
			}
			//否则就循环替换
			else
			{
				//判断条件
				$where = tpl::GetWhere($expArr[1][$i],null,false);
				//判断字段是否存在
				if( !isset($where['where']['分割内容']) || empty($where['where']['分割内容']) )
				{
					tpl::Rep( array($expArr[0][$i]=>'') , null , 2 );
				}
				else
				{
					$expStr = $where['where']['分割内容'];
					$limitArr = explode(',',$where['limit']);
					//多个值判断
					$expList = explode(',', $expStr);
					$code = '';
					$j = 1;
					foreach ($expList as $k=>$v)
					{
						//每次循环重新调取原始标签
						$lcode = $expArr[2][$i];
						//计数器标签和选中标签替换
						$lcode = tpl::I( $lcode , $j );
						
						//设置自定义中文标签
						$arr=array(
							'i'=>$j,
							'字符分割值'=>$v,
						);
						//替换标签
						$code .= tpl::rep($arr,$lcode);

						if( !isset($limitArr[1]) || $j<$limitArr[1] )
						{
							$j++;	
						}
						else
						{
							break;
						}
					}
					
					tpl::Rep( array($expArr[0][$i]=>$code) , null , 2 );
				}
			}
		}
	}
}
?>
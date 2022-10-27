<?php
/**
* 应用标签处理类
*
* @version        $Id: app.label.php 2015年12月18日 16:00  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2015年12月24日 14:10 weimeng
*
* @待更新
* @1.应用标签分开链接
* @2.下载地址链接
* @3.评论数量增加
*/
class applabel extends app
{
	static public $lcode;
	static public $data;

	function __construct()
	{
		tpl::labelBefore();
		
		//公共url替换
		self::PublicUrl();
		//调用自定义标签
		self::PublicLabel();
	}


	//公共url替换
	static function PublicUrl()
	{
		$arr = array(
			'应用首页'=>tpl::url('app_index'),
			'全部应用'=>tpl::url('app_type' ,  array('pid'=>0,'cid'=>0,'lid'=>0,'tid'=>0,'ot'=>0,'page'=>1) ),
		);
		tpl::Rep($arr);
	}
	
	/**
	* 关于信息标签公共标签替换
	**/
	static function PublicLabel()
	{
		//数组键：类名，值：方法名
		$CF['app'] = 'GetData';

		$repFun['t']['applabel'] = 'PublicType';
		tpl::Label('{应用分类:[s]}[a]{/应用分类}','type', $CF, $repFun['t']);
		
		$repFun['a']['applabel'] = 'PublicApp';
		tpl::Label('{应用:[s]}[a]{/应用}','content', $CF, $repFun['a']);

		$repFun['attr']['applabel'] = 'PublicAttr';
		tpl::Label('{应用资料:[s]}[a]{/应用资料}','attr', $CF, $repFun['attr']);
		
		tpl::Rep( array('{应用截图:'=>'{截图:upload_module=app;') , null , '2' );
		tpl::Rep( array('{/应用截图}'=>'{/截图}') , null , '2' );
	}

	
	/**
	 * 公共标签替换
	 * @param 参数1，数组，需要进行操作的数组
	 * @param 参数2，字符串，需要进行替换的字符串，原始标签代码。
	 **/
	static function PublicType($data,$blcode){
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
			$lcode = tpl::Cur( $v['type_id'] , C('page.tid') , $lcode );
			//分割每条数据的字符串
			$lcode = tpl::Segmentation(count($data), $i, $lcode);
			$lcode = tpl::Segmentation(count($data), $i, $lcode , '应用分类分隔符');
			
			//显示固定字数标签
			$nameArr = tpl::Exp('{应用分类名字:[d]}' , $v['type_name'] , $lcode);

			//设置自定义中文标签
			$arr2=array(
				'i'=>$i,
				'url'=>tpl::url('app_type',array('cid'=>0,'lid'=>0,'cid'=>0,'pid'=>0,'ot'=>0,'page'=>1,'tid'=>$v['type_id'],'tpinyin'=>$v['type_pinyin'])),
				'应用分类id'=>$v['type_id'],
				'应用分类名字'=>$v['type_name'],
				'应用分类名字:'.GetKey($nameArr,'0')=>GetKey($nameArr,'1'),
				'应用分类简称'=>$v['type_cname'],
				'应用分类拼音'=>$v['type_pinyin'],
				'应用分类图标'=>$v['type_ico'],
				'应用分类简介'=>$v['type_info'],
				'应用分类排序'=>$v['type_order'],
				'应用分类标题'=>$v['type_title'],
				'应用分类关键词'=>$v['type_key'],
				'应用分类描述'=>$v['type_desc'],
			);
			//合并两组标签
			$arr = RepField($arr2,$arr1, $v,1,'应用分类');
			//替换标签
			$code.=tpl::rep($arr,$lcode);
			$i++;
		}
		
		//返回最后的结果
		return $code;
	}
	
	
	/**
	* 公共标签替换
	 * @param 参数1，数组，需要进行操作的数组
	 * @param 参数2，字符串，需要进行替换的标签
	**/
	static function PublicApp($data,$blcode)
	{
		$code = '';
		$page =  C('page.page');
		$pageCount =  C('page.page_count');
		if ( $page > 0 )
		{
			$i = ($page - 1) * $pageCount + 1;
		}
		else
		{
			$i = 1;
		}
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
			$lcode = tpl::Cur( $v['app_id'] , C('page.aid') , $lcode );
			//分割每条数据的字符串
			$lcode = tpl::Segmentation(count($data), $i, $lcode);
			$lcode = tpl::Segmentation(count($data), $i, $lcode , '应用分隔符');

			//显示固定字数标签
			$nameArr = tpl::Exp('{应用名字:[d]}' , $v['app_name'] , $lcode);
			$infoArr = tpl::Exp('{应用简介:[d]}' , $v['app_info'] , $lcode);
			$contentArr = tpl::Exp('{应用内容:[d]}' , $v['app_content'] , $lcode);
			
			//匹配自定义时间标签
			$time = tpl::Tag('{应用发布时间:[s]}',$lcode);
			
			//设置自定义中文标签
			$arr2=array(
				'i'=>$i,
				'url'=>tpl::url( 'app_app' , array('aid'=>$v['app_id'],'tid'=>$v['type_id'],'apinyin'=>$v['app_pinyin'],'tpinyin'=>$v['type_pinyin'])),
				'turl'=>tpl::url( 'app_type' , array('lid'=>0,'cid'=>0,'pid'=>0,'ot'=>0,'page'=>1,'tid'=>$v['type_id'],'tpinyin'=>$v['type_pinyin']) ),
				'应用id'=>$v['app_id'],
				'应用名字'=>$v['app_name'],
				'应用拼音'=>$v['app_pinyin'],
				'应用内容'=>$v['app_content'],
				'应用图标'=>$v['app_ico'],
				'应用缩略图'=>$v['app_simg'],
				'应用简称'=>$v['app_cname'],
				'应用版本'=>$v['app_ver'],
				'应用大小'=>$v['app_size'],
				'应用标签'=>$v['app_tags'],
				'应用简介'=>$v['app_info'],
				'应用阅读量'=>$v['app_read'],
				'应用评论量'=>$v['app_replay'],
				'应用顶'=>$v['app_ding'],
				'应用踩'=>$v['app_cai'],
				'应用星级'=>$v['app_start'],
				'应用评分'=>$v['app_score'],
				'应用平台版本'=>$v['app_osver'],
				'应用下载量'=>$v['app_downnum'],
				'应用分类id'=>$v['type_id'],
				'应用分类名字'=>$v['type_name'],
				'应用分类简称'=>$v['type_cname'],
				'应用分类拼音'=>$v['type_pinyin'],
				'应用分类简介'=>$v['type_info'],
				'应用汉化代码'=>$v['app_tocn'],
				'应用下载地址1'=>$v['app_down1'],
				'应用下载地址2'=>$v['app_down2'],
				'应用下载地址3'=>$v['app_down3'],

				'应用发布时间'=>date("Y-m-d H:i:s",$v['app_addtime']),
				'up:年'=>date("Y",$v['app_addtime']),
				'up:月'=>date("m",$v['app_addtime']),
				'up:日'=>date("d",$v['app_addtime']),
				'up:时'=>date("H",$v['app_addtime']),
				'up:分'=>date("i",$v['app_addtime']),
				'up:秒'=>date("s",$v['app_addtime']),

				'应用名字:'.GetKey($nameArr,'0')=>GetKey($nameArr,'1'),
				'应用内容:'.GetKey($contentArr,'0')=>GetKey($contentArr,'1'),
				'应用简介:'.GetKey($infoArr,'0')=>GetKey($infoArr,'1'),
				'应用发布时间:'.GetKey($time,'1,0')=>tpl::Time(GetKey($time,'1,0'), $v['app_addtime']),

				'应用汉化'=>parent::GetAttr( 'tocn' , $v['app_tocn'] ),
				'应用语言'=>parent::GetAttr( 'lang' , $v['app_lid_text'] ),
				'应用资费'=>parent::GetAttr( 'cost' , $v['app_cid_text'] ),
				'应用平台'=>parent::GetAttr( 'platform' , $v['app_paid_text'] ),
				'应用开发商'=>parent::GetAttr( 'developers' , $v['au_name'] ),
				'应用运用商'=>parent::GetAttr( 'operators' , $v['pa_name'] ),
			);
			
			//合并两组标签
			$arr = RepField($arr2,$arr1, $v,2,'应用');
			//替换标签
			$code.=tpl::rep($arr,$lcode);
			$i++;
		}
		//返回最后的结果
		return $code;
	}

	
	/**
	 * 应用属性标签替换
	 * @param 参数1，数组，需要进行操作的数组
	 * @param 参数2，字符串，需要进行替换的字符串，原始标签代码。
	 **/
	static function PublicAttr($data,$blcode){
		$code = '';
		$i = 1;
		//循环数据
		foreach ($data as $k => $v)
		{
			//没组数据循环，以字段名为标签名
			foreach ($v as $key => $val)
			{
				$arr1[L.$key] = $v[$key];
			}
			//每次循环重新调取原始标签
			$lcode = $blcode;
				
			//计数器标签和选中标签替换
			$lcode = tpl::I( $lcode , $i );

			//属性id判断
			$lid = C('page.lid');
			$cid = C('page.cid');
			$pid = C('page.pid');
			switch ( $v['attr_type'] )
			{
				case 'c':
					$curId = C('page.cid');
					$cid = $v['attr_id'];
					break;
					
				case 'p':
					$curId = C('page.pid');
					$pid = $v['attr_id'];
					break;

				case 'l':
					$curId = C('page.lid');
					$lid = $v['attr_id'];
					break;
			}
			$lcode = tpl::Cur( $v['attr_id'] , $curId , $lcode );
			
			//设置自定义中文标签
			$arr2=array(
				'i'=>$i,
				'url'=>tpl::url('app_type',array('lid'=>$lid,'cid'=>$cid,'pid'=>$pid,'page'=>1,'ot'=>C('page.ot'),'tid'=>C('page.data.type_id'),'tpinyin'=>C('page.data.type_pinyin'))),
				'应用资料id'=>$v['attr_id'],
				'应用资料名字'=>$v['attr_name'],
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
	* 列表页标签替换
	**/
	static function TypeLabel()
	{
		$data = C('page.data');		
		$arr2 = array(
			'分类id'=>$data['type_id'],
			'分类pid'=>$data['type_pid'],
			'分类名字'=>$data['type_name'],
			'分类简称'=>$data['type_cname'],
			'分类拼音'=>$data['type_pinyin'],
			'分类简介'=>$data['type_info'],
			'分类标题'=>$data['type_title'],
			'分类关键词'=>$data['type_key'],
			'分类描述'=>$data['type_desc'],
		);
		//替换自定义字段
		$arr = RepField($arr2,null,$data);
		tpl::Rep($arr);
		

		self::TypeList();

		$CF['app'] = 'GetData';
		$repFun['a']['applabel'] = 'PublicApp';
		tpl::Label('{应用列表:[s]}[a]{/应用列表}','content', $CF, $repFun['a']);
	}

	
	/**
	 * 列表页条件替换
	 * 给应用列表标签加上各种限制条件。
	 **/
	static function TypeList()
	{
		$reWhere = $pageWhere = $tidWhere = $lidWhere = $cidWhere = $pidWhere = $otWhere = '';
	
		if ( C('page.tid') > 0 )
		{
			//$tidWhere = 'tid='.C('page.tid').';';
			$tidWhere = 'type_pid=[and-or->rin:'.C('page.tid').'||t.type_id:'.C('page.tid').'];';
		}
		if ( C('page.page') > 0 )
		{
			$pageWhere = 'page='.C('page.page').';';
		}
		//筛选条件进行查询
		$reMod = NewModel('system.retrieval');
		$reWhere = $reMod->GetWhere('app',null);
		//排序
		if ( C('page.order') != '' || ($reWhere != '' && str::in_string('排序=',$reWhere)) )
		{
			$orderWhere = '排序='.C('page.order').';';
		}
		else
		{
			$orderWhere = '排序=app_addtime desc;';
		}
		
		/*
		 * 2019-05-18 筛选排序功能托管到公共筛选模块，不再分类列表进行查询
		 * if ( C('page.lid') > 0 )
		{
			$lidWhere = 'app_lid='.C('page.lid').';';
		}
		if ( C('page.cid') > 0 )
		{
			$cidWhere = 'app_cid='.C('page.cid').';';
		}
		if ( C('page.pid') > 0 )
		{
			$pidWhere = 'app_paid='.C('page.pid').';';
		}
		switch ( C('page.ot') )
		{
			case "0":
				$otWhere = 'order=app_id desc;';
				break;
				
			case "1":
				$otWhere = 'app_tocn=1;';
				break;
				
			case "2":
				$otWhere = 'order=app_ding desc;';
				break;
				
			case "3":
				$otWhere = 'order=app_downnum desc;';
				break;
				
			case "4":
				$otWhere = 'app_rec=1;';
				break;
		}*/
		
		$appWhere = $pageWhere.$tidWhere.$lidWhere.$cidWhere.$pidWhere.$otWhere.$orderWhere.$reWhere;
	
		tpl::Rep( array('{应用列表:'=>'{应用列表:'.$appWhere) , null , '2' );
	}
	
	
	/**
	* 内容页标签替换
	**/
	static function AppLabel()
	{
		$v = C('page.data');
		
		//显示固定字数标签
		$nameArr = tpl::Exp('{名字:[d]}' , $v['app_name'] );
		$infoArr = tpl::Exp('{简介:[d]}' , $v['app_info'] );
		$contentArr = tpl::Exp('{内容:[d]}' , $v['app_content'] );
			
		//匹配自定义时间标签
		$time = tpl::Tag('{发布时间:[s]}');
		//顶踩链接
		$DCUrl = '/wmcms/action/index.php?action=dingcai.php?module=app&cid='.$v['app_id'].'&type=';
		
		$arr2 = array(
			'内容链接'=>tpl::url( 'app_app' , array('aid'=>$v['app_id'],'tid'=>$v['type_id'],'apinyin'=>$v['app_pinyin'],'tpinyin'=>$v['type_pinyin'])),
			'分类链接'=>tpl::url( 'app_type' , array('lid'=>0,'cid'=>0,'pid'=>0,'ot'=>0,'page'=>1,'tid'=>$v['type_id'],'tpinyin'=>$v['type_pinyin']) ),
			'评论链接'=>tpl::url( 'app_replay' , array('page'=>1,'aid'=>$v['app_id'],'tid'=>$v['type_id'],'apinyin'=>$v['app_pinyin'],'tpinyin'=>$v['type_pinyin'])),
			'id'=>$v['app_id'],
			'名字'=>$v['app_name'],
			'拼音'=>$v['app_pinyin'],
			'内容'=>$v['app_content'],
			'图标'=>$v['app_ico'],
			'缩略图'=>$v['app_simg'],
			'简称'=>$v['app_cname'],
			'版本'=>$v['app_ver'],
			'大小'=>$v['app_size'],
			'标签'=>$v['app_tags'],
			'简介'=>$v['app_info'],
			'阅读量'=>$v['app_read'],
			'评论量'=>$v['app_replay'],
			'顶'=>$v['app_ding'],
			'顶链接'=>$DCUrl.'ding',
			'踩'=>$v['app_cai'],
			'踩链接'=>$DCUrl.'cai',
			'星级'=>$v['app_start'],
			'评分'=>$v['app_score'],
			'平台版本'=>$v['app_osver'],
			'下载量'=>$v['app_downnum'],
			'分类id'=>$v['type_id'],
			'分类名字'=>$v['type_name'],
			'分类简称'=>$v['type_cname'],
			'分类拼音'=>$v['type_pinyin'],
			'分类简介'=>$v['type_info'],
			'汉化代码'=>$v['app_tocn'],
			'下载地址1'=>$v['app_down1'],
			'下载地址2'=>$v['app_down2'],
			'下载地址3'=>$v['app_down3'],
			

			'发布时间'=>date("Y-m-d H:i:s",$v['app_addtime']),

			'名字:'.GetKey($nameArr,'0')=>GetKey($nameArr,'1'),
			'内容:'.GetKey($contentArr,'0')=>GetKey($contentArr,'1'),
			'简介:'.GetKey($infoArr,'0')=>GetKey($infoArr,'1'),
			'发布时间:'.GetKey($time,'1,0')=>tpl::Time(GetKey($time,'1,0'), $v['app_addtime']),

			'汉化'=>parent::GetAttr( 'tocn' , $v['app_tocn'] ),
			'语言'=>parent::GetAttr( 'lang' , $v['app_lid_text'] ),
			'资费'=>parent::GetAttr( 'cost' , $v['app_cid_text'] ),
			'平台'=>parent::GetAttr( 'platform' , $v['app_paid_text'] ),
			'开发商'=>parent::GetAttr( 'developers' , $v['au_name'] ),
			'运用商'=>parent::GetAttr( 'operators' , $v['pa_name'] ),
		);
		//替换自定义字段
		$arr = RepField($arr2,null,$v,2,'',array('app','content'));
		tpl::Rep($arr);
		
		//评分标签
		common::ScoreLabel( 'app' , C('page.data.app_id') );
		
		//上下篇
		self::AppPreNext();
		
		//同标签应用
		self::AppLike();
	}

	//上下一篇应用替换
	static function AppPreNext()
	{
		$data = C('page.data');
		$where['field'] = 'app_id,app_name';
		$where['table']['@app_app'] = '';
		$where['where']['type_id'] = $data['type_id'];
		
		$url = tpl::Url('app_app' , array('tpinyin'=>$data['type_pinyin'],'tid'=>$data['type_id']));
		
		common::PreNext( $where , 'app_id' , $data['app_id'] , $url , 'aid' , 'app_name' );
	}

	//同标签的应用
	static function AppLike()
	{
		$tags = C('page.data.app_tags');
		
		$whereLabel = 'app_id=[不等于->'.C('page.data.app_id').'];type_id='.C('page.data.type_id').';';
		if( $tags != '' )
		{
			$whereLabel.= 'app_tags=[in->'.$tags.'];';
		}

		tpl::Rep( array('{同标签应用:'=>'{同标签应用:'.$whereLabel) , null , '2' );
		
		$CF['app'] = 'GetData';
		$repFun['a']['applabel'] = 'PublicApp';
		tpl::Label('{同标签应用:[s]}[a]{/同标签应用}','content', $CF, $repFun['a']);
	}
	
	//搜索标签
	static function SearchLabel()
	{
		$arr = array(
			'搜索词'=>C('page.key'),
		);
		tpl::Rep($arr);

		//搜索条件
		$where = common::SearchWhere( array('app_name','firms_name','app_tags') , C('page.type') , C('page.key') );
		
		//获得数据并且替换标签
		$data = app::GetData( 'content' , $where );
		$repFun['a']['applabel'] = 'PublicApp';
		tpl::Label( '{搜索结果:[s]}[a]{/搜索结果}' , $data , null , $repFun['a'] );

		//关键词搜索次数+1
		search::SearchNumber( 'app' );
	}
	

	//评论标签
	static function ReplayLabel()
	{
		$pageWhere = '';
	
		//替换应用标签
		self::Applabel();
	
		//替换页面条件
		if ( C('page.page') > 0 )
		{
			$pageWhere = 'page='.C('page.page').';';
		}
		tpl::Rep( array('{评论:'=>'{评论:'.$pageWhere) , null , '2' );
	}
}
?>
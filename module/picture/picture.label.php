<?php
/**
* 图集标签处理类
*
* @version        $Id: picture.label.php 2015年12月18日 16:00  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2016年1月4日 10:53 weimeng
*
*/
class picturelabel extends picture
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
		tpl::Rep( array( '图集首页'=>tpl::Url('picture_index') ) );
		tpl::Rep( array( '图集排行列表'=>tpl::Url('picture_toplist') ) );
	}
	
	
	/**
	* 关于信息标签公共标签替换
	**/
	static function PublicLabel()
	{
		//数组键：类名，值：方法名
		$CF['picture'] = 'GetData';

		$repFun['t']['picturelabel'] = 'PublicType';
		tpl::Label('{图集分类:[s]}[a]{/图集分类}','type', $CF, $repFun['t']);
		tpl::Label('{二级图集分类:[s]}[a]{/二级图集分类}',array('type','二级'), $CF, $repFun['t']);
		
		$repFun['a']['picturelabel'] = 'PublicPicture';
		tpl::Label('{图集:[s]}[a]{/图集}','content', $CF, $repFun['a']);
		
	}

	
	/**
	 * 公共标签替换
	 * @param 参数1，数组，需要进行操作的数组
	 * @param 参数2，字符串，需要进行替换的字符串，原始标签代码。
	 **/
	static function PublicType($data,$blcode,$level=''){
		$code = '';
		$i = 1;
		//循环数据
		foreach ($data as $k => $v)
		{
			//没组数据循环，以字段名为标签名
			foreach ($v as $key => $val)
			{
				$arr1[$level.L.$key]=$v[$key];
			}
			//每次循环重新调取原始标签
			$lcode = $blcode;
			
			//计数器标签和选中标签替换
			$lcode = tpl::I( $lcode , $i );
			$lcode = tpl::Cur( $v['type_id'] , C('page.tid') , $lcode );
			//分割每条数据的字符串
			$lcode = tpl::Segmentation(count($data), $i, $lcode);
			$lcode = tpl::Segmentation(count($data), $i, $lcode , '图集分类分隔符');
			
			//显示固定字数标签
			$nameArr = tpl::Exp('{'.$level.'图集分类名字:[d]}' , $v['type_name'] , $lcode);
			$urlPar = array('page'=>1,'tid'=>$v['type_id'],'tpinyin'=>$v['type_pinyin']);

			//设置自定义中文标签
			$arr2=array(
				$level.'i'=>$i,
				$level.'url'=>tpl::url('picture_type',$urlPar),
				$level.'iurl'=>tpl::url('picture_tindex',$urlPar),
				$level.'图集分类id'=>$v['type_id'],
				$level.'图集分类名字'=>$v['type_name'],
				$level.'图集分类名字:'.GetKey($nameArr,'0')=>GetKey($nameArr,'1'),
				$level.'图集分类简称'=>$v['type_cname'],
				$level.'图集分类拼音'=>$v['type_pinyin'],
				$level.'图集分类图标'=>$v['type_ico'],
				$level.'图集分类简介'=>$v['type_info'],
				$level.'图集分类排序'=>$v['type_order'],
				$level.'图集分类标题'=>$v['type_title'],
				$level.'图集分类关键词'=>$v['type_key'],
				$level.'图集分类描述'=>$v['type_desc'],
			);
			//合并两组标签
			$arr = RepField($arr2,$arr1, $v,1,'图集分类');
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
	static function PublicPicture($data,$blcode)
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
			$lcode = tpl::Cur( $v['picture_id'] , C('page.aid') , $lcode );
			//分割每条数据的字符串
			$lcode = tpl::Segmentation(count($data), $i, $lcode);
			$lcode = tpl::Segmentation(count($data), $i, $lcode , '图集分隔符');

			//显示固定字数标签
			$nameArr = tpl::Exp('{图集名字:[d]}' , $v['picture_name'] , $lcode);
			$infoArr = tpl::Exp('{图集简介:[d]}' , $v['picture_info'] , $lcode);
			$contentArr = tpl::Exp('{图集内容:[d]}' , $v['picture_content'] , $lcode);
			
			//匹配自定义时间标签
			$time = tpl::Tag('{图集发布时间:[s]}',$lcode);
			
			//自由标签替换
			$lcode = parent::GetAttr( '图集推荐' , $v['picture_rec'] , $lcode );
			
			//设置自定义中文标签
			$arr2=array(
				'i'=>$i,
				'url'=>tpl::url( 'picture_picture' , array('page'=>1,'pid'=>$v['picture_id'],'tid'=>$v['type_id'],'tpinyin'=>$v['type_pinyin'])),
				'purl'=>tpl::url( 'picture_picture' , array('page'=>1,'pid'=>$v['picture_id'],'tid'=>$v['type_id'],'tpinyin'=>$v['type_pinyin'])),
				'turl'=>tpl::url( 'picture_type' , array('page'=>1,'tid'=>$v['type_id'],'tpinyin'=>$v['type_pinyin']) ),
				'图集id'=>$v['picture_id'],
				'图集标题'=>$v['picture_name'],
				'图集短标题'=>$v['picture_cname'],
				'图集内容'=>$v['picture_content'],
				'图集缩略图'=>$v['picture_simg'],
				'图集标签'=>$v['picture_tags'],
				'图集简介'=>$v['picture_info'],
				'图集阅读量'=>$v['picture_read'],
				'图集顶'=>$v['picture_ding'],
				'图集踩'=>$v['picture_cai'],
				'图集评论量'=>$v['picture_replay'],
				'图集评分'=>$v['picture_score'],
				'图集星级'=>$v['picture_start'],
					
				'图集发布时间'=>date("Y-m-d H:i:s",$v['picture_time']),
				'up:年'=>date("Y",$v['picture_time']),
				'up:月'=>date("m",$v['picture_time']),
				'up:日'=>date("d",$v['picture_time']),
				'up:时'=>date("H",$v['picture_time']),
				'up:分'=>date("i",$v['picture_time']),
				'up:秒'=>date("s",$v['picture_time']),

				'图集名字:'.GetKey($nameArr,'0')=>GetKey($nameArr,'1'),
				'图集内容:'.GetKey($contentArr,'0')=>GetKey($contentArr,'1'),
				'图集简介:'.GetKey($infoArr,'0')=>GetKey($infoArr,'1'),
				'图集发布时间:'.GetKey($time,'1,0')=>tpl::Time(GetKey($time,'1,0'), $v['picture_time']),
			);
			
			//合并两组标签
			$arr = RepField($arr2,$arr1, $v,2,'图集');
			//替换标签
			$code.=tpl::rep($arr,$lcode);
			$i++;
		}
		//返回最后的结果
		return $code;
	}
	
	
	static function PublicImage($data,$blcode)
	{
		$j = 1;
		$code = '';
		$page =  C('page.page');
		$pageCount =  C('page.page_count');
		$listUrl = C('page.listurl');
		$limit = C('page.limit');
		if ( $page > 0 )
		{
			$i = ($page - 1) * $pageCount + 1;
		}
		else
		{
			$i = 1;
		}
		//循环数据
		foreach ($data['src'] as $k => $v)
		{
			//每次循环重新调取原始标签
			$lcode = $blcode;
			//分割每条数据的字符串
			$lcode = tpl::Segmentation(count($data['src']), $i, $lcode);
			$lcode = tpl::Segmentation(count($data['src']), $i, $lcode , '图片分隔符');
			
			//设置自定义中文标签
			$arr=array(
				'i'=>$i,
				'url'=>tpl::Rep(array('page'=>$j) , $listUrl ),
				'图片原图'=>$v,
				'图片缩略图'=>SImg($v,true),
				'图片描述'=>$data['alt'][$k],
			);
			
			//替换标签
			$code.=tpl::rep($arr,$lcode);
			$i++;
			$j++;
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
		
		if( $data['type_id'] == '0' )
		{
			$topId = 0;
			$topName = '全部分类';
		}
		else
		{
			if( $data['type_topid'] == '0' )
			{
				$topId = $data['type_id'];
				$topName = $data['type_name'];
			}
			else
			{
				list($topId) = explode(',',$data['type_pid']);
				$typeMod = NewModel('picture.type');
				$typeData = $typeMod->GetById($topId);
				$topName = $typeData['type_name'];
			}
		}
		$arr2 = array(
			'分类id'=>$data['type_id'],
			'分类pid'=>$data['type_pid'],
			'一级分类id'=>$topId,
			'一级分类名字'=>$topName,
			'一级分类链接'=>tpl::url('picture_type',array('page'=>1,'tid'=>$topId,'tpinyin'=>$data['type_pinyin'])),
			'一级分类首页链接'=>tpl::url('picture_tindex',array('tid'=>$topId,'tpinyin'=>$data['type_pinyin'])),
			'分类链接'=>tpl::url('picture_type',array('page'=>1,'tid'=>$data['type_id'],'tpinyin'=>$data['type_pinyin'])),
			'分类首页链接'=>tpl::url('picture_tindex',array('tid'=>$data['type_id'],'tpinyin'=>$data['type_pinyin'])),
			'分类名字'=>$data['type_name'],
			'分类简称'=>$data['type_cname'],
			'分类拼音'=>$data['type_pinyin'],
			'分类描述'=>$data['type_info'],
			'分类标题'=>$data['type_title'],
			'分类关键词'=>$data['type_key'],
			'分类描述'=>$data['type_desc'],
		);
		//替换自定义字段
		$arr = RepField($arr2,null,$data);
		tpl::Rep($arr);
		

		self::TypeList();

		$CF['picture'] = 'GetData';
		$repFun['a']['picturelabel'] = 'PublicPicture';
		tpl::Label('{图集列表:[s]}[a]{/图集列表}','content', $CF, $repFun['a']);
	}


	/**
	 * 列表页条件替换
	 * 给文章列表标签加上各种限制条件。
	 **/
	static function TypeList()
	{
		$pageWhere = $tidWhere = $lidWhere = $cidWhere = $pidWhere = $otWhere = '';
	
		if ( C('page.tid') > 0 )
		{
			//$tidWhere = 'tid='.C('page.tid').';';
			$tidWhere = 'type_pid=[and-or->rin:'.C('page.tid').'||t.type_id:'.C('page.tid').'];';
		}
	
		if ( C('page.page') > 0 )
		{
			$pageWhere = 'page='.C('page.page').';';
		}
		
		$pictureWhere = $pageWhere.$tidWhere;

		tpl::Rep( array('{图集列表:'=>'{图集列表:'.$pictureWhere) , null , '2' );
	}
	
	
	/**
	* 内容页标签替换
	**/
	static function PictureLabel()
	{
		self::TypeLabel();
		
		$v = C('page.data');
		$page = C('page.page');
		//显示固定字数标签
		$nameArr = tpl::Exp('{名字:[d]}' , $v['picture_name'] );
		$infoArr = tpl::Exp('{简介:[d]}' , $v['picture_info'] );
		$contentArr = tpl::Exp('{内容:[d]}' , $v['picture_content'] );
			
		//匹配自定义时间标签
		$time = tpl::Tag('{发布时间:[s]}');
			
		//自由标签替换
		$lcode = parent::GetAttr( '推荐' , $v['picture_rec'] );
			
		//设置自定义中文标签
		$arr2 = array(
			'内容链接'=>tpl::url( 'picture_picture' , array('page'=>$page,'pid'=>$v['picture_id'],'tid'=>$v['type_id'],'tpinyin'=>$v['type_pinyin']) ),
			'分类链接'=>tpl::url( 'picture_type' , array('page'=>1,'tid'=>$v['type_id'],'tpinyin'=>$v['type_pinyin']) ),
			'id'=>$v['picture_id'],
			'标题'=>$v['picture_name'],
			'短标题'=>$v['picture_cname'],
			'内容'=>$v['picture_content'],
			'缩略图'=>$v['picture_simg'],
			'标签'=>$v['picture_tags'],
			'简介'=>$v['picture_info'],
			'阅读量'=>$v['picture_read'],
			'顶'=>$v['picture_ding'],
			'踩'=>$v['picture_cai'],
			'评论量'=>$v['picture_replay'],
			'评分'=>$v['picture_score'],
			'星级'=>$v['picture_start'],
			'分类id'=>$v['type_id'],
			'分类名字'=>$v['type_name'],
			'分类简称'=>$v['type_cname'],
			'分类拼音'=>$v['type_pinyin'],
			'分类简介'=>$v['type_info'],
				
			'发布时间'=>date("Y-m-d H:i:s",$v['picture_time']),
	
			'名字:'.GetKey($nameArr,'0')=>GetKey($nameArr,'1'),
			'内容:'.GetKey($contentArr,'0')=>GetKey($contentArr,'1'),
			'简介:'.GetKey($infoArr,'0')=>GetKey($infoArr,'1'),
			'发布时间:'.GetKey($time,'1,0')=>tpl::Time(GetKey($time,'1,0'), $v['picture_time']),
		);
		//替换自定义字段
		$arr = RepField($arr2,null,$v,2,'',array('picture','content'));
		tpl::Rep($arr);
		
		//图片列表替换
		self::PictureContent();
		
		//评分标签
		common::ScoreLabel( 'picture' , C('page.data.picture_id') );

		//上下篇
		self::PicturePreNext();

		//同标签文章
		self::PictureLike();
	}

	//图片列表替换
	static function PictureContent()
	{
		$page = intval(C('page.page'));
		$count = C('page.data.picture_count');
		$expArr = tpl::Tag('{图片:[s]}[a]{/图片}');
		$repFun['a']['picturelabel'] = 'PublicImage';
		
		if( !empty($expArr[0]) )
		{
			$where = tpl::GetWhere($expArr[1][0],null,false);
			$limitArr = explode(',',$where['limit']);
			if( count($limitArr)<2 || $page == 0 )
			{
				$limit = $count;
			}
			else
			{
				$limit = $limitArr[1];
			}
			C('page.page_count',$limit);
			C('page.limit',$limit);
			page::Start( C('page.listurl') , $count , $limit , $page);
		
			//如果页数大于0就按limit查询
			if( $limit != $count )
			{
				$data = unserialize(C('page.data.picture_imgs'));
				$data['src'] = array_values($data['src']);
				$data['alt'] = array_values($data['alt']);
				$start = ($page-1)*$limit;
				$data['src'] = array_slice ($data['src'] , $start , $limit);
				$data['alt'] = array_slice ($data['alt'] , $start , $limit);
			}
			
			tpl::Label('{图片:[s]}[a]{/图片}',$data,null, $repFun['a']);
		}
		
		tpl::Label('{图片列表:[s]}[a]{/图片列表}',unserialize(C('page.data.picture_imgs')),null, $repFun['a']);
	}
	
	//上下一篇文章替换
	static function PicturePreNext()
	{
		$data = C('page.data');
		$where['field'] = 'picture_id,picture_name';
		$where['table']['@picture_picture'] = '';
		$where['where']['type_id'] = $data['type_id'];
		
		$url = tpl::Url('picture_picture' , array('tpinyin'=>$data['type_pinyin'],'tid'=>$data['type_id'],'page'=>1));

		common::PreNext( $where , 'picture_id' , $data['picture_id'] , $url , 'pid' , 'picture_name' );
	}

	//同标签的文章
	static function PictureLike()
	{
		$tags = C('page.data.picture_tags');
		
		$whereLabel = 'picture_id=[不等于->'.C('page.data.picture_id').'];type_id='.C('page.data.type_id').';';
		if( $tags != '' )
		{
			$whereLabel.= 'picture_tags=[rin->'.$tags.'];';
		}

		tpl::Rep( array('{同标签图集:'=>'{同标签图集:'.$whereLabel) , null , '2' );
		
		$CF['picture'] = 'GetData';
		$repFun['a']['picturelabel'] = 'PublicPicture';
		tpl::Label('{同标签图集:[s]}[a]{/同标签图集}','content', $CF, $repFun['a']);
	}
	
	
	//搜索标签
	static function SearchLabel()
	{
		$arr = array(
			'搜索词'=>C('page.key'),
		);
		tpl::Rep($arr);

		//搜索条件
		$where = common::SearchWhere( array('picture_name','','picture_tags') , C('page.type') , C('page.key') );
		
		//获得数据并且替换标签
		$data = picture::GetData( 'content' , $where );
		$repFun['a']['picturelabel'] = 'PublicPicture';
		tpl::Label( '{搜索结果:[s]}[a]{/搜索结果}' , $data , null , $repFun['a'] );

		//关键词搜索次数+1
		search::SearchNumber( 'picture' );
	}


	//排行列表标签
	static function ToplistLabel()
	{
		//当前分类类型替换
		self::TypeLabel();
		//分类排行
		self::ToplistType();
		
		//排行列表标签替换
		if ( C('page.page') > 0 )
		{
			$pageWhere = 'page='.C('page.page').';';
		}
		tpl::Rep( array('{排行列表:'=>'{排行列表:'.$pageWhere.'排序=picture_read desc;') , null , '2' );
		//处理标签的数据
		$CF['picture'] = 'GetData';
		$repFun['a']['picturelabel'] = 'PublicPicture';
		tpl::Label('{排行列表:[s]}[a]{/排行列表}','content', $CF, $repFun['a']);
	}
	
	//分类排行URL
	static function ToplistType()
	{
		//获取数据
		$arr  = parent::GetData( 'type' , '排序=type_order' );
		
		//获取页面url信息
		$urlType = C('config.route.url_type');
		$typeUrl = C('config.seo.urls.picture_type.url'.$urlType);
		$toplistUrl = C('config.seo.urls.picture_toplist.url'.$urlType);

		//设置临时url信息
		C('config.seo.urls.picture_type.url'.$urlType , $toplistUrl);

		//替换标签
		$repFun['t']['picturelabel'] = 'PublicType';
		tpl::Label('{分类排行:[s]}[a]{/分类排行}',$arr, null , $repFun['t']);

		//还原url信息
		C('config.seo.urls.picture_type.url'.$urlType , $typeUrl);
	}
}
?>
<?php
/**
* 文章标签处理类
*
* @version        $Id: article.label.php 2015年12月18日 16:00  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2015年12月29日 10:04 weimeng
*
*/
class articlelabel extends article
{
	static public $lcode;
	static public $data;
	static public $CF = array('article'=>'GetData');

	function __construct()
	{
		tpl::labelBefore();
		
		//url替换
		self::Url();
		
		//公共url
		self::PublicUrl();
		//调用自定义标签
		self::PublicLabel();
	}
	
	
	/**
	 * url替换
	 */
	static function Url()
	{
		$typeArr = array('tid'=>C('page.tid'),'page'=>C('page.page'),'tpinyin'=>C('page.data.type_pinyin'));

		tpl::GetUrl( 'typeurl' , 'article_type' , $typeArr );
	}
	
	//公共url替换
	static function PublicUrl()
	{
		$arr = array(
			'文章首页'=>tpl::url('article_index'),
			'全部文章'=>tpl::url('article_type'),
		);
		tpl::Rep($arr);
	}
	
	/**
	* 公共标签替换
	**/
	static function PublicLabel()
	{
		//数组键：类名，值：方法名
		$CF['article'] = 'GetData';

		$repFun['t']['articlelabel'] = 'PublicType';
		tpl::Label('{文章分类:[s]}[a]{/文章分类}','type', $CF, $repFun['t']);
		tpl::Label('{二级文章分类:[s]}[a]{/二级文章分类}',array('type','二级'), $CF, $repFun['t']);
		tpl::Label('{三级文章分类:[s]}[a]{/三级文章分类}',array('type','三级'), $CF, $repFun['t']);
		
		$repFun['a']['articlelabel'] = 'PublicArticle';
		tpl::Label('{文章:[s]}[a]{/文章}','content', $CF, $repFun['a']);
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
			$lcode = tpl::Segmentation(count($data), $i, $lcode , '文章分类分隔符');
			
			//显示固定字数标签
			$nameArr = tpl::Exp('{'.$level.'文章分类名字:[d]}' , $v['type_name'] , $lcode);

			//设置自定义中文标签
			$arr2=array(
				$level.'i'=>$i,
				$level.'url'=>tpl::url('article_type',array('page'=>1,'tid'=>$v['type_id'],'tpinyin'=>$v['type_pinyin'])),
				$level.'iurl'=>tpl::url('article_tindex',array('tid'=>$v['type_id'],'tpinyin'=>$v['type_pinyin'])),
				$level.'文章分类id'=>$v['type_id'],
				$level.'文章分类父id'=>$v['type_topid'],
				$level.'文章分类名字'=>$v['type_name'],
				$level.'文章分类名字:'.GetKey($nameArr,'0')=>GetKey($nameArr,'1'),
				$level.'文章分类简称'=>$v['type_cname'],
				$level.'文章分类拼音'=>$v['type_pinyin'],
				$level.'文章分类图标'=>GetKey($v,'type_ico'),
				$level.'文章分类简介'=>$v['type_info'],
				$level.'文章分类排序'=>$v['type_order'],
				$level.'文章分类标题'=>$v['type_title'],
				$level.'文章分类关键词'=>$v['type_key'],
				$level.'文章分类描述'=>$v['type_desc'],
			);
			//合并两组标签
			$arr = RepField($arr2,$arr1, $v,1,'文章分类');
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
	static function PublicArticle($data,$blcode)
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
			$lcode = tpl::Cur( $v['article_id'] , C('page.aid') , $lcode );
			//分割每条数据的字符串
			$lcode = tpl::Segmentation(count($data), $i, $lcode);
			$lcode = tpl::Segmentation(count($data), $i, $lcode , '文章分隔符');

			//显示固定字数标签
			$nameArr = tpl::Exp('{文章标题:[d]}' , $v['article_name'] , $lcode);
			$infoArr = tpl::Exp('{文章简介:[d]}' , $v['article_info'] , $lcode);
			$contentArr = tpl::Exp('{文章内容:[d]}' , $v['article_content'] , $lcode);
			
			//匹配自定义时间标签
			$time = tpl::Tag('{文章发布时间:[s]}',$lcode);
			
			//自由标签替换
			$lcode = parent::GetAttr( '文章推荐' , $v['article_rec'] , $lcode );
			$lcode = parent::GetAttr( '文章头条' , $v['article_head'] , $lcode );
			$lcode = parent::GetAttr( '文章加粗' , $v['article_strong'] , $lcode );
			$style = parent::GetStyle( $v['article_strong'] , $v['article_color']);
			
			//设置自定义中文标签
			$arr2=array(
				'i'=>$i,
				'url'=>parent::GetUrl( $v ),
				'aurl'=>parent::GetUrl( $v ),
				'turl'=>tpl::url( 'article_type' , array('page'=>1,'tid'=>$v['type_id'],'tpinyin'=>$v['type_pinyin']) ),
				'文章id'=>$v['article_id'],
				'文章标题'=>$v['article_name'],
				'文章短标题'=>$v['article_cname'],
				'文章状态'=>parent::GetStatus($v['article_status']),
				'文章内容'=>str::ToHtml($v['article_content']),
				'文章缩略图'=>parent::GetSimg($v['article_simg']),
				'文章标题颜色'=>$v['article_color'],
				'文章来源'=>$v['article_source'],
				'文章作者'=>$v['article_author'],
				'文章编辑'=>$v['article_editor'],
				'文章权重'=>$v['article_weight'],
				'文章标签'=>$v['article_tags'],
				'文章简介'=>$v['article_info'],
				'文章阅读量'=>$v['article_read'],
				'文章顶'=>$v['article_ding'],
				'文章踩'=>$v['article_cai'],
				'文章评论量'=>$v['article_replay'],
				'文章评分'=>$v['article_score'],
				'文章标题样式'=>$style,
				'style'=>$style,
				
				'文章分类id'=>$v['type_id'],
				'文章分类名字'=>$v['type_name'],
				'文章分类简称'=>$v['type_cname'],
				'文章分类拼音'=>$v['type_pinyin'],
				'文章分类图标'=>C('type_ico',null,$v),
				'文章分类简介'=>$v['type_info'],
				
				'文章发布时间'=>date("Y-m-d H:i:s",$v['article_addtime']),
				'up:年'=>date("Y",$v['article_addtime']),
				'up:月'=>date("m",$v['article_addtime']),
				'up:日'=>date("d",$v['article_addtime']),
				'up:时'=>date("H",$v['article_addtime']),
				'up:分'=>date("i",$v['article_addtime']),
				'up:秒'=>date("s",$v['article_addtime']),

				'文章标题:'.GetKey($nameArr,'0')=>GetKey($nameArr,'1'),
				'文章内容:'.GetKey($contentArr,'0')=>GetKey($contentArr,'1'),
				'文章简介:'.GetKey($infoArr,'0')=>GetKey($infoArr,'1'),
				'文章发布时间:'.GetKey($time,'1,0')=>tpl::Time(GetKey($time,'1,0'), $v['article_addtime']),
			);

			//合并两组标签
			$arr = RepField($arr2,$arr1, $v,2,'文章');
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
		$parentData = GetModuleTypeParent('article',$data);
		$topId = $parentData['top_id'];
		$topName = $parentData['top_name'];
		$topPinYin = $parentData['top_pinyin'];
		$arr2 = array(
			'分类id'=>$data['type_id'],
			'分类pid'=>$data['type_pid'],
			'一级分类id'=>$topId,
			'一级分类名字'=>$topName,
			'一级分类链接'=>tpl::url('article_type',array('page'=>1,'tid'=>$topId,'tpinyin'=>$data['type_pinyin'])),
			'分类链接'=>tpl::url('article_type',array('page'=>1,'tid'=>$data['type_id'],'tpinyin'=>$data['type_pinyin'])),
			'父级分类首页'=>tpl::url( 'article_tindex' , array('tid'=>$data['type_topid'],'tpinyin'=>$data['type_pinyin']) ),
			'分类名字'=>$data['type_name'],
			'分类简称'=>$data['type_cname'],
			'分类拼音'=>$data['type_pinyin'],
			'分类简介'=>$data['type_info'],
			'分类标题'=>$data['type_title'],
			'分类关键词'=>$data['type_key'],
			'分类描述'=>$data['type_desc'],
			'分类父id'=>$data['type_topid'],
		);
		//替换自定义字段
		$arr = RepField($arr2,null,$data);
		tpl::Rep($arr);

		self::TypeList();

		$CF['article'] = 'GetData';
		$repFun['a']['articlelabel'] = 'PublicArticle';
		tpl::Label('{文章列表:[s]}[a]{/文章列表}','content', $CF, $repFun['a']);
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
		
		$articleWhere = $pageWhere.$tidWhere;
	
		tpl::Rep( array('{文章列表:'=>'{文章列表:'.$articleWhere) , null , '2' );
	}
	
	
	/**
	* 内容页标签替换
	**/
	static function ArticleLabel()
	{
		self::TypeLabel();
		
		$v = C('page.data');
		
		//显示固定字数标签
		$nameArr = tpl::Exp('{名字:[d]}' , $v['article_name'] );
		$infoArr = tpl::Exp('{简介:[d]}' , $v['article_info'] );
		$contentArr = tpl::Exp('{内容:[d]}' , $v['article_content'] );
			
		//匹配自定义时间标签
		$time = tpl::Tag('{发布时间:[s]}');
			
		//自由标签替换
		$lcode = parent::GetAttr( '推荐' , $v['article_rec'] );
		$lcode = parent::GetAttr( '头条' , $v['article_rec'] );
		$lcode = parent::GetAttr( '加粗' , $v['article_rec'] );
		$style = parent::GetStyle( $v['article_strong'] , $v['article_color']);

		//顶踩链接
		$DCUrl = '/wmcms/action/index.php?module=article&cid='.$v['article_id'].'&action=dingcai.';
		
		//设置自定义中文标签
		$arr2 = array(
			'分类首页'=>tpl::url( 'article_tindex' , array('tid'=>$v['type_id'],'tpinyin'=>$v['type_pinyin']) ),
			'父级分类首页'=>tpl::url( 'article_tindex' , array('tid'=>$v['type_topid'],'tpinyin'=>$v['type_pinyin']) ),
			'内容链接'=>tpl::url( 'article_article' , array('aid'=>$v['article_id'],'tid'=>$v['type_id'],'tpinyin'=>$v['type_pinyin']) ),
			'分类链接'=>tpl::url( 'article_type' , array('page'=>1,'tid'=>$v['type_id'],'tpinyin'=>$v['type_pinyin']) ),
			'作者个人中心链接'=>tpl::url('author_author',array('aid'=>$v['article_author_id'])),
			'id'=>$v['article_id'],
			'标题'=>$v['article_name'],
			'短标题'=>$v['article_cname'],
			'内容'=>$v['article_content'],
			'缩略图'=>parent::GetSimg($v['article_simg']),
			'标题颜色'=>$v['article_color'],
			'来源'=>$v['article_source'],
			'作者'=>$v['article_author'],
			'编辑'=>$v['article_editor'],
			'权重'=>$v['article_weight'],
			'标签'=>$v['article_tags'],
			'简介'=>$v['article_info'],
			'阅读量'=>$v['article_read'],
			'顶'=>$v['article_ding'],
			'顶链接'=>$DCUrl.'ding',
			'踩'=>$v['article_cai'],
			'踩链接'=>$DCUrl.'cai',
			'评论量'=>$v['article_replay'],
			'评分'=>$v['article_score'],
			'标题样式'=>$style,
			'分类id'=>$v['type_id'],
			'分类父id'=>$v['type_topid'],
			'分类名字'=>$v['type_name'],
			'分类简称'=>$v['type_cname'],
			'分类拼音'=>$v['type_pinyin'],
			'分类简介'=>$v['type_info'],
			'发布时间'=>date("Y-m-d H:i:s",$v['article_addtime']),
			'标题:'.GetKey($nameArr,'0')=>GetKey($nameArr,'1'),
			'内容:'.GetKey($contentArr,'0')=>GetKey($contentArr,'1'),
			'简介:'.GetKey($infoArr,'0')=>GetKey($infoArr,'1'),
			'发布时间:'.GetKey($time,'1,0')=>tpl::Time(GetKey($time,'1,0'), $v['article_addtime']),
		);

		//替换自定义字段
		$arr = RepField($arr2,null,$v,2,'',array('article','content'));
		tpl::Rep($arr);
		
		//评分标签
		common::ScoreLabel( 'article' , C('page.data.article_id') );
		
		//上下篇
		self::ArticlePreNext();
		
		//同标签文章
		self::ArticleLike();
	}

	//上下一篇文章替换
	static function ArticlePreNext()
	{
		$data = C('page.data');
		$where['field'] = 'article_id,article_name';
		$where['table']['@article_article'] = '';
		$where['where']['type_id'] = $data['type_id'];
		
		$url = tpl::Url('article_article' , array('tpinyin'=>$data['type_pinyin'],'tid'=>$data['type_id']));
		
		common::PreNext( $where , 'article_id' , $data['article_id'] , $url , 'aid' , 'article_name' );
	}

	//同标签的文章
	static function ArticleLike()
	{
		$tags = C('page.data.article_tags');
		
		$whereLabel = 'article_id=[不等于->'.C('page.data.article_id').'];type_id='.C('page.data.type_id').';';
		if( $tags != '' )
		{
			$whereLabel.= 'article_tags=[in->'.$tags.'];';
		}

		tpl::Rep( array('{同标签文章:'=>'{同标签文章:'.$whereLabel) , null , '2' );
		
		$CF['article'] = 'GetData';
		$repFun['a']['articlelabel'] = 'PublicArticle';
		tpl::Label('{同标签文章:[s]}[a]{/同标签文章}','content', $CF, $repFun['a']);
	}
	
	
	//搜索标签
	static function SearchLabel()
	{
		$arr = array(
			'搜索词'=>C('page.key'),
		);
		tpl::Rep($arr);

		//搜索条件
		$where = common::SearchWhere( array('article_name','article_author','article_tags') , C('page.type') , C('page.key') );
		
		//获得数据并且替换标签
		$data = article::GetData( 'content' , $where );
		$repFun['a']['articlelabel'] = 'PublicArticle';
		tpl::Label( '{搜索结果:[s]}[a]{/搜索结果}' , $data , null , $repFun['a'] );

		//关键词搜索次数+1
		search::SearchNumber( 'article' );
	}


	//评论标签
	static function ReplayLabel()
	{
		$pageWhere = '';
		
		//替换文章标签
		self::ArticleLabel();
		
		//替换页面条件
		if ( C('page.page') > 0 )
		{
			$pageWhere = 'page='.C('page.page').';';
		}
		tpl::Rep( array('{评论:'=>'{评论:'.$pageWhere) , null , '2' );
	}
}
?>
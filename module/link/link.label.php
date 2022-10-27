<?php
/**
* 友链标签处理类
*
* @version        $Id: link.label.php 2015年12月18日 16:00  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2016年1月5日 21:03 weimeng
*
*/
class linklabel extends link
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
			'友链首页'=>tpl::url('link_index'),
			'申请友链'=>tpl::url('link_join' ),
			'友链提交地址'=>'/wmcms/action/index.php?action=link.join',
		);
		tpl::Rep($arr);
	}
	
	/**
	* 标签公共标签替换
	**/
	static function PublicLabel()
	{
		//数组键：类名，值：方法名
		$CF['link'] = 'GetData';

		$repFun['t']['linklabel'] = 'PublicType';
		tpl::Label('{友链分类:[s]}[a]{/友链分类}','type', $CF, $repFun['t']);
		
		$repFun['a']['linklabel'] = 'Publiclink';
		tpl::Label('{友链:[s]}[a]{/友链}','content', $CF, $repFun['a']);
	}

	
	/**
	 * 公共分类标签替换
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
			$lcode = tpl::Segmentation(count($data), $i, $lcode , '友链分类分隔符');

			$lcode = parent::GetAttr( '友链分类推荐' , $v['type_rec'] , $lcode );
			
			//显示固定字数标签
			$nameArr = tpl::Exp('{友链分类名字:[d]}' , $v['type_name'] , $lcode);

			//设置自定义中文标签
			$arr2=array(
				'i'=>$i,
				'turl'=>tpl::url('link_type',array('page'=>1,'tid'=>$v['type_id'],'tpinyin'=>$v['type_pinyin'])),
				'友链分类id'=>$v['type_id'],
				'友链分类名字'=>$v['type_name'],
				'友链分类名字:'.GetKey($nameArr,'0')=>GetKey($nameArr,'1'),
				'友链分类简称'=>$v['type_cname'],
				'友链分类拼音'=>$v['type_pinyin'],
				'友链分类图标'=>C('type_ico',null,$v),
				'友链分类简介'=>$v['type_info'],
				'友链分类排序'=>$v['type_order'],
				'友链分类标题'=>$v['type_title'],
				'友链分类关键词'=>$v['type_key'],
				'友链分类描述'=>$v['type_desc'],
			);
			//合并两组标签
			$arr = RepField($arr2,$arr1, $v,1,'友链分类');
			//替换标签
			$code.=tpl::rep($arr,$lcode);
			$i++;
		}
		
		//返回最后的结果
		return $code;
	}
	
	
	/**
	* 公共内容标签替换
	 * @param 参数1，数组，需要进行操作的数组
	 * @param 参数2，字符串，需要进行替换的标签
	**/
	static function PublicLink($data,$blcode)
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
			$lcode = tpl::Cur( $v['link_id'] , C('page.lid') , $lcode );
			$lcode = parent::GetAttr( '是否推荐' , $v['link_rec'] , $lcode );
			$lcode = parent::GetAttr( '是否固链' , $v['link_fixed'] , $lcode );
			//分割每条数据的字符串
			$lcode = tpl::Segmentation(count($data), $i, $lcode);
			$lcode = tpl::Segmentation(count($data), $i, $lcode , '友链分隔符');

			//显示固定字数标签
			$nameArr = tpl::Exp('{友链名字:[d]}' , $v['link_name'] , $lcode);
			$infoArr = tpl::Exp('{友链简介:[d]}' , $v['link_info'] , $lcode);
			$typeNameArr = tpl::Exp('{友链分类名字:[d]}' , $v['type_name'] , $lcode);
			
			//匹配自定义时间标签
			$time = tpl::Tag('{友链加入时间:[s]}',$lcode);
			
			//友链显示方式为直链
			if( $v['link_show'] == '0' )
			{
				$url = $v['link_url'];
			}
			//如果是直接跳出
			else if ( C('click_type',null,'linkConfig') == '0' )
			{
				$url = tpl::url( 'link_link' , array('t'=>'out','lid'=>$v['link_id'],'tid'=>$v['type_id'],'tpinyin'=>$v['type_pinyin']));
			}
			//进入友链展示页面
			else
			{
				$url = tpl::url( 'link_show' , array('lid'=>$v['link_id'],'tid'=>$v['type_id'],'tpinyin'=>$v['type_pinyin']));
			}
			
			//设置自定义中文标签
			$arr2=array(
				'i'=>$i,
				'url'=>$url,
				'turl'=>tpl::url( 'link_type' , array('page'=>1,'tid'=>$v['type_id'],'tpinyin'=>$v['type_pinyin']) ),
				'友链id'=>$v['link_id'],
				'友链名字'=>$v['link_name'],
				'友链拼音'=>$v['link_pinyin'],
				'友链简称'=>$v['link_cname'],
				'友链简介'=>$v['link_info'],
				'友链图标'=>$v['link_ico'],
				'友链qq'=>$v['link_qq'],
					
				'友链日点出'=>$v['link_outday'],
				'友链周点出'=>$v['link_outweek'],
				'友链月点出'=>$v['link_outmonth'],
				'友链总点出'=>$v['link_outsum'],
				'友链最后点出'=>$v['link_lastouttime'],
				'友链日点入'=>$v['link_inday'],
				'友链周点入'=>$v['link_inweek'],
				'友链月点入'=>$v['link_inmonth'],
				'友链总点入'=>$v['link_insum'],
				'友链最后点入'=>$v['link_lastintime'],

				'友链分类id'=>$v['type_id'],
				'友链分类名字'=>$v['type_name'],
				'友链分类名字:'.GetKey($typeNameArr,'0')=>GetKey($typeNameArr,'1'),
				'友链分类简称'=>$v['type_cname'],
				'友链分类拼音'=>$v['type_pinyin'],
				'友链分类简介'=>$v['type_info'],
					
				'友链顶'=>$v['link_ding'],
				'友链踩'=>$v['link_cai'],

				'友链加入时间'=>date("Y-m-d H:i:s",$v['link_jointime']),
				'up:年'=>date("Y",$v['link_jointime']),
				'up:月'=>date("m",$v['link_jointime']),
				'up:日'=>date("d",$v['link_jointime']),
				'up:时'=>date("H",$v['link_jointime']),
				'up:分'=>date("i",$v['link_jointime']),
				'up:秒'=>date("s",$v['link_jointime']),

				'友链名字:'.GetKey($nameArr,'0')=>GetKey($nameArr,'1'),
				'友链简介:'.GetKey($infoArr,'0')=>GetKey($infoArr,'1'),
				'友链加入时间:'.GetKey($time,'1,0')=>tpl::Time(GetKey($time,'1,0'), $v['link_jointime']),
			);
			
			//合并两组标签
			$arr = RepField($arr2,$arr1, $v,2,'友链');
			//替换标签
			$code.=tpl::rep($arr,$lcode);
			$i++;
		}
		//返回最后的结果
		return $code;
	}

	
	/**
	 * 友链属性标签替换
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
				'url'=>tpl::url('link_type',array('lid'=>$lid,'cid'=>$cid,'pid'=>$pid,'page'=>1,'ot'=>C('page.ot'),'tid'=>C('page.data.type_id'),'tpinyin'=>C('page.data.type_pinyin'))),
				'友链资料id'=>$v['attr_id'],
				'友链资料名字'=>$v['attr_name'],
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
			'分类描述'=>$data['type_info'],
			'分类标题'=>$data['type_title'],
			'分类关键词'=>$data['type_key'],
			'分类描述'=>$data['type_desc'],
		);
		//替换自定义字段
		$arr = RepField($arr2,null,$data);
		tpl::Rep($arr);
		

		self::TypeList();

		$CF['link'] = 'GetData';
		$repFun['a']['linklabel'] = 'Publiclink';
		tpl::Label('{友链列表:[s]}[a]{/友链列表}','content', $CF, $repFun['a']);
	}

	
	/**
	 * 列表页条件替换
	 * 给友链列表标签加上各种限制条件。
	 **/
	static function TypeList()
	{
		$pageWhere = $tidWhere =  '';
	
		if ( C('page.tid') > 0 )
		{
			//$tidWhere = 'tid='.C('page.tid').';';
			$tidWhere = 'type_pid=[and-or->rin:'.C('page.tid').'||t.type_id:'.C('page.tid').'];';
		}
		
		if ( C('page.page') > 0 )
		{
			$pageWhere = 'page='.C('page.page').';';
		}

		$linkWhere = $pageWhere.$tidWhere;
	
		tpl::Rep( array('{友链列表:'=>'{友链列表:'.$linkWhere) , null , '2' );
	}
	
	
	/**
	* 内容页标签替换
	**/
	static function LinkLabel()
	{
		$v = C('page.data');

		$lcode = parent::GetAttr( '推荐' , $v['link_rec'] );
		$lcode = parent::GetAttr( '固链' , $v['link_fixed'] );
		

		//匹配自定义时间标签
		$time = tpl::Tag('{加入时间:[s]}',$lcode);
		//显示固定字数标签
		$nameArr = tpl::Exp('{名字:[d]}' , $v['link_name'] );
		$infoArr = tpl::Exp('{简介:[d]}' , $v['link_info'] );
		$typeNameArr = tpl::Exp('{分类名字:[d]}' , $v['link_name'] );
		
		$arr2 = array(
			'内容链接'=>tpl::url( 'link_show' , array('lid'=>$v['link_id'],'tid'=>$v['type_id'],'tpinyin'=>$v['type_pinyin'])),
			'分类链接'=>tpl::url( 'link_type' , array('page'=>1,'tid'=>$v['type_id'],'tpinyin'=>$v['type_pinyin']) ),
			'立即访问'=>tpl::url( 'link_link' , array('t'=>'out','lid'=>$v['link_id'],'tid'=>$v['type_id'],'tpinyin'=>$v['type_pinyin'])),
			'id'=>$v['link_id'],
			'名字'=>$v['link_name'],
			'拼音'=>$v['link_pinyin'],
			'简称'=>$v['link_cname'],
			'简介'=>$v['link_info'],
			'图标'=>$v['link_ico'],
			'qq'=>$v['link_qq'],

			'分类id'=>$v['type_id'],
			'分类名字'=>$v['type_name'],
			'分类名字:'.GetKey($typeNameArr,'0')=>GetKey($typeNameArr,'1'),
			'分类简称'=>$v['type_cname'],
			'分类拼音'=>$v['type_pinyin'],
			'分类简介'=>$v['type_info'],
			
			'日点出'=>$v['link_outday'],
			'周点出'=>$v['link_outweek'],
			'月点出'=>$v['link_outmonth'],
			'总点出'=>$v['link_outsum'],
			'最后点出'=>$v['link_lastouttime'],
			'日点入'=>$v['link_inday'],
			'周点入'=>$v['link_inweek'],
			'月点入'=>$v['link_inmonth'],
			'总点入'=>$v['link_insum'],
			'最后点入'=>$v['link_lastintime'],

			'顶'=>$v['link_ding'],
			'踩'=>$v['link_cai'],

			'加入时间'=>date("Y-m-d H:i:s",$v['link_jointime']),

			'名字:'.GetKey($nameArr,'0')=>GetKey($nameArr,'1'),
			'简介:'.GetKey($infoArr,'0')=>GetKey($infoArr,'1'),
			'加入时间:'.GetKey($time,'1,0')=>tpl::Time(GetKey($time,'1,0'), $v['link_jointime']),
		);
		//替换自定义字段
		$arr = RepField($arr2,null,$v,2,'',array('link','content'));
		tpl::Rep($arr);
		
		//评分标签
		common::ScoreLabel( 'link' , C('page.data.link_id') );
		
		//上下篇
		self::LinkPreNext();
		
		//同标签友链
		self::LinkLike();
	}


	//上下一篇友链替换
	static function LinkPreNext(){
		$where['field'] = 'link_id,link_name';
		$where['table']['@link_link'] = '';
		$where['where']['type_id'] = C('page.data.link_id');
		
		$url = tpl::Url('link_link' , array('tid'=>C('page.tid')));
		
		common::PreNext( $where , 'link_id' , C('page.data.link_id') , $url , 'aid' , 'link_name' );
	}

	//同标签的友链
	static function LinkLike()
	{
		$tags = C('page.data.link_tags');
		
		$whereLabel = 'link_id=[不等于->'.C('page.data.link_id').'];type_id='.C('page.data.type_id').';';
		if( $tags != '' )
		{
			$whereLabel.= 'link_tags=[in->'.$tags.'];';
		}

		tpl::Rep( array('{同标签友链:'=>'{同标签友链:'.$whereLabel) , null , '2' );
		
		$CF['link'] = 'GetData';
		$repFun['a']['linklabel'] = 'Publiclink';
		tpl::Label('{同标签友链:[s]}[a]{/同标签友链}','content', $CF, $repFun['a']);
	}
	
	
	//搜索标签
	static function SearchLabel()
	{
		$arr = array(
			'搜索词'=>C('page.key'),
		);
		tpl::Rep($arr);

		//搜索条件
		$where = common::SearchWhere( array('link_name','author_name','link_tags') , C('page.type') , C('page.key') );
		
		//获得数据并且替换标签
		$data = link::GetData( 'content' , $where );
		$repFun['a']['linklabel'] = 'Publiclink';
		tpl::Label( '{搜索结果:[s]}[a]{/搜索结果}' , $data , null , $repFun['a'] );

		//关键词搜索次数+1
		search::SearchNumber( 'link' );
	}
	
	//链接跳转页
	static function JumpLabel()
	{
		$data = C('page.data');
		tpl::rep(array(
			'链接'=>$data['url'],
			'url'=>$data['url'],
		));
	}
}
?>
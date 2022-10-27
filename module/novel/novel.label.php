<?php
/**
* 小说标签处理类
*
* @version        $Id: novel.label.php 2015年12月18日 16:00  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2016年1月7日 10:04 weimeng
*
*/
class novellabel extends novel
{
	static public $lcode;
	static public $data;
	static public $CF = array('novel'=>'GetData');

	function __construct()
	{
		tpl::labelBefore();
		
		//公共url
		self::PublicUrl();
		
		//调用自定义标签
		self::PublicLabel();
		
		//分类排行
		self::TopType();
	}
	
	
	//公共url替换
	static function PublicUrl()
	{
		$arr = array(
			'小说首页'=>tpl::url('novel_index', array('tp'=>'')),
			'全部小说'=>tpl::url('novel_type', array('tpinyin'=>'all','tid'=>'0','page'=>'1')),
			'小说男生版'=>tpl::url('novel_index_boy'),
			'小说女生版'=>tpl::url('novel_index_girl'),
			'小说排行列表'=>tpl::url('novel_toplist', array('type'=>'1','tid'=>'0','page'=>'1')),
			'小说排行首页'=>tpl::url('novel_topindex'),
			'小说分类首页'=>tpl::url('novel_tindex', array('tid'=>'0')),
		);
		tpl::Rep($arr);
	}
	
	
	/**
	* 关于信息标签公共标签替换
	**/
	static function PublicLabel()
	{
		//数组键：类名，值：方法名
		$repFun['t']['novellabel'] = 'PublicType';
		tpl::Label('{小说分类:[s]}[a]{/小说分类}','type', self::$CF, $repFun['t']);
		tpl::Label('{二级小说分类:[s]}[a]{/二级小说分类}',array('type','二级'), self::$CF, $repFun['t']);
		tpl::Label('{三级小说分类:[s]}[a]{/三级小说分类}',array('type','三级'), self::$CF, $repFun['t']);

		//小说标签
		$repFun['a']['novellabel'] = 'PublicNovel';
		tpl::Label('{推荐小说:[s]}[a]{/推荐小说}','rec', self::$CF, $repFun['a']);
		tpl::Label('{限时小说:[s]}[a]{/限时小说}','timelimit', self::$CF, $repFun['a']);
		tpl::Label('{小说:[s]}[a]{/小说}','content', self::$CF, $repFun['a']);
		//小说标签
		$repFun['a']['novellabel'] = 'PublicTag';
		tpl::Label('{小说标签:[s]}[a]{/小说标签}',array('explode','小说'),'key', $repFun['a']);
		
		//小说章节
		$repFun['a']['novellabel'] = 'PublicChapter';
		tpl::Label('{小说章节:[s]}[a]{/小说章节}','chapterlist', self::$CF, $repFun['a']);
	}

	
	/**
	 * 公共标签替换
	 * @param 参数1，数组，需要进行操作的数组
	 * @param 参数2，字符串，需要进行替换的字符串，原始标签代码。
	 * @param 参数3，选填，标签的级别。
	 **/
	static function PublicType($data,$blcode,$level='')
	{
		$code = '';
		$i = 1;
		//循环数据
		foreach ($data as $k => $v)
		{
			$arr1 = tpl::GetBeforeLabel('PublicType',$k);
			//没组数据循环，以字段名为标签名
			foreach ($v as $key => $val)
			{
				$arr1[L.$level.$key]=$v[$key];
			}
			//每次循环重新调取原始标签
			$lcode = $blcode;
			
			//计数器标签和选中标签替换
			$lcode = tpl::I( $lcode , $i );
			$lcode = tpl::I( $lcode , $i ,$level.'小说分类i');
			$lcode = tpl::Cur( $v['type_id'] , C('page.tid') , $lcode , $level);
			//分割每条数据的字符串
			$lcode = tpl::Segmentation(count($data), $i, $lcode , '小说分类分隔符');
			$lcode = tpl::Segmentation(count($data), $i, $lcode);
			
			//显示固定字数标签
			$nameArr = tpl::Exp('{小说分类名字:[d]}' , $v['type_name'] , $lcode);
			$urlPar = array('page'=>1,'tid'=>$v['type_id'],'tpinyin'=>$v['type_pinyin']);
			
			//设置自定义中文标签
			$arr2=array(
				$level.'i'=>$i,
				$level.'url'=>tpl::url('novel_type',$urlPar),
				$level.'iurl'=>tpl::url('novel_tindex',$urlPar),
				$level.'surl'=>self::GetRetrieval($v['type_id'],$v['type_pinyin']),
				$level.'小说分类id'=>$v['type_id'],
				$level.'小说分类父级id'=>$v['type_topid'],
				$level.'小说分类名字'=>C('type_name',null,$v),
				$level.'小说分类名字:'.GetKey($nameArr,'0')=>GetKey($nameArr,'1'),
				$level.'小说分类简称'=>$v['type_cname'],
				$level.'小说分类拼音'=>$v['type_pinyin'],
				$level.'小说分类图标'=>C('type_ico',null,$v),
				$level.'小说分类简介'=>C('type_info',null,$v),
				$level.'小说分类排序'=>C('type_order',null,$v),
				$level.'小说分类标题'=>C('type_title',null,$v),
				$level.'小说分类关键词'=>C('type_key',null,$v),
				$level.'小说分类描述'=>C('type_desc',null,$v),
			);
			//合并两组标签
			$arr = RepField($arr2,$arr1, $v,1,'小说分类');
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
	static function PublicNovel($data,$blcode)
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
			$arr1 = tpl::GetBeforeLabel('PublicNovel',$k);
			
			//没组数据循环，以字段名为标签名
			foreach ($v as $key => $val)
			{
				$arr1[L.$key]=$v[$key];
			}
			//每次循环重新调取原始标签
			$lcode = $blcode;
			
			//计数器标签和选中标签替换
			$lcode = tpl::I( $lcode , $i );
			$lcode = tpl::I( $lcode , $i ,'j');
			$lcode = tpl::Cur( $v['novel_id'] , C('page.aid') , $lcode );
			//分割每条数据的字符串
			$lcode = tpl::Segmentation(count($data), $i, $lcode , '小说分隔符');
			$lcode = tpl::Segmentation(count($data), $i, $lcode);
			
			$newChpater = parent::GetChaper($v['novel_newcname']);
			$startChpater = parent::GetChaper($v['novel_startcname']);
			$lastChpater = parent::GetChaper(isset($v['chapter_name'])?$v['chapter_name']:'');
			
			//显示固定字数标签
			$nameArr = tpl::Exp('{小说名字:[d]}' , $v['novel_name'] , $lcode);
			$newChapterNameArr = tpl::Exp('{小说最新章节名字:[d]}' , $newChpater , $lcode);
			$startChapterNameArr = tpl::Exp('{小说第一章节名字:[d]}' , $startChpater , $lcode);
			$infoArr = tpl::Exp('{小说简介:[d]}' , str::ClearHtml($v['novel_info']) , $lcode);

			//vip作品检测
			$lcode = tpl::IfRep( $v['novel_sell'] , '=' , 1 , 'vip小说' , '免费小说' , $lcode);
			//签约小说
			$lcode = tpl::IfRep( $v['novel_copyright'] , '>' , 0 , '已签约小说' , '未签约小说' , $lcode);
			
			//匹配自定义时间标签
			$time = tpl::Tag('{小说更新时间:[s]}',$lcode);
			$creatTime = tpl::Tag('{小说创建时间:[s]}',$lcode);
			$readTime = tpl::Tag('{小说首次阅读时间:[s]}',$lcode);
			$lastTime = tpl::Tag('{小说上次阅读时间:[s]}',$lcode);
			$limitStartTime = tpl::Tag('{小说限时免费开始时间:[s]}',$lcode);
			$limitEndTime = tpl::Tag('{小说限时免费结束时间:[s]}',$lcode);

			$url = tpl::url( 'novel_info' , array('nid'=>$v['novel_id'],'tid'=>$v['type_id'],'npinyin'=>$v['novel_pinyin'] ,'tpinyin'=>$v['type_pinyin']) );
			
			$v['novel_uptime'] = intval($v['novel_uptime']);
			$v['novel_createtime'] = intval($v['novel_createtime']);
			//设置自定义中文标签
			$arr2=array(
				'i'=>$i,
				'j'=>$i,
				'url'=>$url,
				'nurl'=>$url,
				'turl'=>tpl::url( 'novel_type' , array('page'=>1,'tid'=>$v['type_id'],'tpinyin'=>$v['type_pinyin']) ),
				'iurl'=>tpl::url( 'novel_tindex' , array('tid'=>$v['type_id'],'tpinyin'=>$v['type_pinyin']) ),
				'murl'=>tpl::url( 'novel_menu' , array('page'=>1,'tid'=>$v['type_id'],'nid'=>$v['novel_id'],'tpinyin'=>$v['type_pinyin'],'npinyin'=>$v['novel_pinyin'])),
				'rurl'=>parent::GetReadUrl($v,'1'),
				'aurl'=>tpl::url('author_author',array('aid'=>$v['author_id'])),
				'lurl'=>parent::GetReadUrl($v,'3'),
				'小说最新章节url'=>parent::GetReadUrl($v),
				'小说第一章节url'=>parent::GetReadUrl($v , '1'),
				'小说上次阅读url'=>parent::GetReadUrl($v , '3'),
				'小说id'=>$v['novel_id'],
				'小说名字'=>$v['novel_name'],
				'小说拼音'=>$v['novel_pinyin'],
				'小说推荐标题'=>parent::GetRT( $v ),
				'小说推荐封面'=>parent::GetRC( $v ),
				'小说电脑推荐封面'=>parent::GetRC( $v , 4),
				'小说触屏推荐封面'=>parent::GetRC( $v , 3),
				'小说状态'=>parent::GetStatus($v['novel_status']),
				'小说进程'=>parent::GetProcess($v['novel_process']),
				'小说进程id'=>$v['novel_process'],
				'小说类型'=>parent::GetType($v['novel_type']),
				'小说类型id'=>$v['novel_type'],
				'小说作者'=>$v['novel_author'],
				'小说作者昵称'=>GetKey($v, 'author_nickname'),
				'小说作者简介'=>GetKey($v, 'author_info'),
				'小说作者公告'=>GetKey($v, 'author_notice'),
				'小说作者用户昵称'=>GetKey($v, 'user_nickname'),
				'小说作者生日'=>GetKey($v, 'user_birthday'),
				'小说作者签名'=>GetKey($v, 'user_sign'),
				'小说作者头像'=>GetKey($v, 'user_head'),
				'小说封面'=>$v['novel_cover'],
				'小说点评'=>$v['novel_comment'],
				'小说简介'=>$v['novel_info'],
				'小说标签'=>$v['novel_tags'],
				'小说字数'=>$v['novel_wordnumber'],
				'小说最新章节id'=>$v['novel_newcid'],
				'小说最新章节名字'=>$newChpater,
				'小说第一章节id'=>$v['novel_startcid'],
				'小说第一章节名字'=>$startChpater,
				'小说上次阅读章节id'=>isset($v['chapter_id'])?$v['chapter_id']:0,
				'小说上次阅读章节名字'=>$lastChpater,
				'小说顶'=>$v['novel_ding'],
				'小说踩'=>$v['novel_cai'],
				'小说评论量'=>$v['novel_replay'],
				'小说评分'=>$v['novel_score'],
				'小说日点击'=>$v['novel_todayclick'],
				'小说周点击'=>$v['novel_weekclick'],
				'小说月点击'=>$v['novel_monthclick'],
				'小说年点击'=>$v['novel_yearclick'],
				'小说总点击'=>$v['novel_allclick'],
				'小说日收藏'=>$v['novel_todaycoll'],
				'小说周收藏'=>$v['novel_weekcoll'],
				'小说月收藏'=>$v['novel_monthcoll'],
				'小说年收藏'=>$v['novel_yearcoll'],
				'小说总收藏'=>$v['novel_allcoll'],
				'小说日推荐'=>$v['novel_todayrec'],
				'小说周推荐'=>$v['novel_weekrec'],
				'小说月推荐'=>$v['novel_monthrec'],
				'小说年推荐'=>$v['novel_yearrec'],
				'小说总推荐'=>$v['novel_allrec'],
				'小说更新时间戳'=>$v['novel_uptime'],
				'小说创建时间戳'=>$v['novel_createtime'],
				'小说上次阅读时间戳'=>GetKey($v,'read_time'),
				'小说限时免费开始时间戳'=>GetKey($v,'timelimit_starttime'),
				'小说限时免费结束时间戳'=>GetKey($v,'timelimit_endtime'),

				'小说更新时间'=>date("Y-m-d H:i:s",$v['novel_uptime']),
				'小说创建时间'=>date("Y-m-d H:i:s",$v['novel_createtime']),
				'小说首次阅读时间'=>date("Y-m-d H:i:s",intval(GetKey($v,'read_time'))),
				'小说上次阅读时间'=>date("Y-m-d H:i:s",intval(GetKey($v,'read_lasttime'))),
				'小说限时免费开始时间'=>date("Y-m-d H:i:s",intval(GetKey($v,'timelimit_starttime'))),
				'小说限时免费结束时间'=>date("Y-m-d H:i:s",intval(GetKey($v,'timelimit_endtime'))),
				'小说分类id'=>$v['type_id'],
				'小说分类名字'=>$v['type_name'],
				'小说分类简称'=>$v['type_cname'],
				'小说分类拼音'=>$v['type_pinyin'],
					
				'小说名字:'.GetKey($nameArr,'0')=>GetKey($nameArr,'1'),
				'小说简介:'.GetKey($infoArr,'0')=>GetKey($infoArr,'1'),
				'小说最新章节名字:'.GetKey($newChapterNameArr,'0')=>GetKey($newChapterNameArr,'1'),
				'小说第一章节名字:'.GetKey($startChapterNameArr,'0')=>GetKey($startChapterNameArr,'1'),
				'小说更新时间:'.GetKey($time,'1,0')=>tpl::Time(GetKey($time,'1,0'), $v['novel_uptime']),
				'小说创建时间:'.GetKey($creatTime,'1,0')=>tpl::Time(GetKey($creatTime,'1,0'), $v['novel_uptime']),

				'小说首次阅读时间:'.GetKey($readTime,'1,0')=>tpl::Time(GetKey($readTime,'1,0'), intval(GetKey($v,'read_time'))),
				'小说上次阅读时间:'.GetKey($lastTime,'1,0')=>tpl::Time(GetKey($lastTime,'1,0'), intval(GetKey($v,'read_lasttime'))),
				'小说限时免费开始时间:'.GetKey($limitStartTime,'1,0')=>tpl::Time(GetKey($limitStartTime,'1,0'), intval(GetKey($v,'timelimit_starttime'))),
				'小说限时免费结束时间:'.GetKey($limitEndTime,'1,0')=>tpl::Time(GetKey($limitEndTime,'1,0'), intval(GetKey($v,'timelimit_endtime'))),
				'删除收藏'=>common::GetUrl('user.delcoll' , array('cid'=>isset($v['coll_id'])?$v['coll_id']:0) ),
			);
			//合并两组标签
			$arr = RepField($arr2,$arr1, $v,2,'小说');
			//替换标签
			$lcode = tpl::rep($arr,$lcode);

			$code .= $lcode;
			$i++;
		}
		//返回最后的结果
		return $code;
	}
	
	

	/**
	 * 小说标签组替换
	 * @param 参数1，数组，需要进行操作的数组
	 * @param 参数2，字符串，需要进行替换的字符串，原始标签代码。
	 * @param 参数3，选填，标签的级别。
	 */
	static function PublicTag($data,$blcode,$level='')
	{
		$code = '';
		$i = 1;
		//循环数据
		foreach ($data as $k => $v)
		{
			//每次循环重新调取原始标签
			$lcode = $blcode;
			
			//计数器标签和选中标签替换
			$lcode = tpl::I( $lcode , $i );
			$lcode = tpl::I( $lcode , $i ,$level.'标签标题i');
			//分割每条数据的字符串
			$lcode = tpl::Segmentation(count($data), $i, $lcode , $level.'标签隔符');
			$lcode = tpl::Segmentation(count($data), $i, $lcode);
			
			//设置自定义中文标签
			$arr=array(
				$level.'i'=>$i,
				$level.'url'=>tpl::url('novel_search',array('module'=>'novel','key'=>$v,'type'=>3,'page'=>1)),
				$level.'surl'=>tpl::url('novel_search',array('module'=>'novel','key'=>$v,'type'=>3,'page'=>1)),
				$level.'标签标题'=>$v,
			);
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
	static function PublicChapter($data,$blcode)
	{
		$code = '';
		$page =  C('page.page');
		$cid = C('page.cid');
		$tid = C('page.data.type_id');
		$nid = C('page.data.novel_id');
		$pageCount =  C('page.page_count');
		$tpinyin =C('page.data.type_pinyin');
		$npinyin = C('page.data.novel_pinyin');
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
			$arr1 = tpl::GetBeforeLabel('PublicChapter',$k);
			//没组数据循环，以字段名为标签名
			foreach ($v as $key => $val)
			{
				$arr1[L.$key]=$v[$key];
			}
			//每次循环重新调取原始标签
			$lcode = $blcode;

			//默认参数
			if( isset($v['type_id']) )
			{
				$tid = $v['type_id'];
			}
			if( isset($v['type_pinyin']) )
			{
				$tpinyin = $v['type_pinyin'];
			}
			if( isset($v['novel_pinyin']) )
			{
				$npinyin = $v['novel_pinyin'];
			}
			//文章内容为文件存储或者为数据存储
			if( $v['chapter_istxt'] == '1' && !empty($v['chapter_path']) && C('page.pagetype') == 'novel_info' )
			{
				$v['content'] = file::GetFile( WMROOT.$v['chapter_path'] );
			}
			
			//计数器标签和选中标签替换
			$lcode = tpl::I( $lcode , $i ,'小说章节序号');
			$lcode = tpl::I( $lcode , $i );
			$lcode = tpl::Cur( $v['chapter_id'] , $cid , $lcode );
			//分割每条数据的字符串
			$lcode = tpl::Segmentation(count($data), $i-(($page-1)*$pageCount), $lcode , '小说章节分隔符');
			$lcode = tpl::Segmentation(count($data), $i-(($page-1)*$pageCount), $lcode);
			
			$titleArr = tpl::Exp('{小说章节名字:[d]}' , $v['chapter_name'] , $lcode);
			$contentArr = tpl::Exp('{小说章节内容:[d]}' , GetKey($v,'content') , $lcode);
			//匹配自定义时间标签
			$creatTime = tpl::Tag('{小说章节创建时间:[s]}',$lcode);
			//vip章节检测
			$lcode = tpl::IfRep( $v['chapter_ispay'] , '=' , 1 , '收费章节' , '免费章节' , $lcode);
			
			//url
			$infoUrl = tpl::url('novel_info',array('nid'=>GetKey($v,'chapter_nid'),'tid'=>$tid,'tpinyin'=>GetKey($v,'type_pinyin'),'npinyin'=>GetKey($v,'novel_pinyin')));
			$url = tpl::url( 'novel_read' , array('cid'=>$v['chapter_id'],'nid'=>$v['chapter_nid'],'tid'=>$tid,'tpinyin'=>$tpinyin,'npinyin'=>$npinyin) );
			//设置自定义中文标签
			$arr2=array(
				'小说章节序号'=>$i,
				'i'=>$i,
				'j'=>$i,
				'nurl'=>$infoUrl,
				'url'=>$url,
				'小说章节id'=>$v['chapter_id'],
				'小说章节名字'=>$v['chapter_name'],
				'小说章节内容'=>GetKey($v,'content'),
				'小说章节创建时间'=>$v['chapter_time']==''?'':date("Y-m-d H:i:s",$v['chapter_time']),
				'小说名字'=>GetKey($v,'novel_name'),
				'小说作者'=>GetKey($v,'novel_author'),
				'小说分类名字'=>GetKey($v,'type_name'),

				'小说章节名字:'.GetKey($titleArr,'0')=>GetKey($titleArr,'1'),
				'小说章节内容:'.GetKey($contentArr,'0')=>GetKey($contentArr,'1'),
				'小说章节创建时间:'.GetKey($creatTime,'1,0')=>tpl::Time(GetKey($creatTime,'1,0'), $v['chapter_time']),
			);
			
			//合并两组标签
			$arr = RepField($arr2,$arr1, $v);
			//替换标签
			$code.=tpl::rep($arr,$lcode);
			$i++;
		}
		//返回最后的结果
		return $code;
	}
	
	
	//分卷替换
	static function PublicVolume($data,$blcode)
	{
		$code = '';
		$i = 1;
		//循环数据
		foreach ($data as $k => $v)
		{
			$arr1 = tpl::GetBeforeLabel('PublicVolume',$k);
			//没组数据循环，以字段名为标签名
			foreach ($v as $key => $val)
			{
				$arr1[L.$key]=$v[$key];
			}
			//每次循环重新调取原始标签
			$lcode = $blcode;
			
			//计数器标签和选中标签替换
			$lcode = tpl::I( $lcode , $i );
			
			$lcode = tpl::Segmentation(count($data), $i, $lcode , '小说分卷分隔符');
			$lcode = tpl::Segmentation(count($data), $i, $lcode);

			$descArr = tpl::Exp('{小说分卷描述:[d]}' , $v['volume_desc'] , $lcode);
			
			//设置自定义中文标签
			$arr2=array(
				'i'=>$i,
				'小说分卷id'=>$v['volume_id'],
				'小说分卷名字'=>$v['volume_name'],
				'小说分卷描述'=>$v['volume_desc'],
				'小说分卷创建时间'=>date("Y-m-d H:i:s",$v['volume_time']),
					
				'小说分卷描述:'.GetKey($descArr,'0')=>GetKey($descArr,'1'),
			);
	
			//合并两组标签
			$arr = ArrMerge($v , $arr1,$arr2);
			//替换标签
			$code.=tpl::rep($arr,$lcode);
			$i++;
		}
		//返回最后的结果
		return $code;
	}
	
	
	//类型排行替换
	static function PublicTop($data , $blcode)
	{
		$code = '';
		//分类id
		$tid = str::Int( C('page.tid') , null , 0);
		//排行的url
		$url = tpl::Url('novel_toplist' , array('page'=>1,'tid'=>$tid));
		//分割数组条件
		$where = explode(',', C('page.label_where'));
		for( $i = 1; $i <= 22 ; $i++ )
		{
			if( in_array('全部' , $where) || in_array($i , $where) )
			{
				$repArr = array(
					'url'=> tpl::Rep( array('type'=>$i) , $url),
					'小说类型排行id'=> $i,
					'小说类型排行名字'=> $data['type_'.$i],
				);
			
				$lcode = tpl::Cur( $i , C('page.type') , $blcode );
				$code.=tpl::Rep( $repArr , $lcode);
			}
		}
		return $code;
	}

	static function PublicChapterLine($data , $blcode)
	{
		$code = '';
		$i = 1;
		//循环数据
		foreach ($data as $k => $v)
		{
			//设置自定义中文标签
			$repArr = array(
				'i'=>$i,
				'章节单行内容'=>$v,
				'章节单行文本内容'=>preg_replace('/　　(.*?)/', '$1', $v),
			);
			
			//替换标签
			$lcode = tpl::Cur( $i , C('page.type') , $blcode );
			$code.=tpl::Rep( $repArr , $lcode);
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
		$parentData = GetModuleTypeParent('novel',$data);
		$topId = $parentData['top_id'];
		$topName = $parentData['top_name'];
		$topPinYin = $parentData['top_pinyin'];	

		$arr1 = tpl::GetBeforeLabel('TypeLabel');
		$arr2 = array(
			'分类id'=>$data['type_id'],
			'分类pid'=>$data['type_pid'],
			'一级分类id'=>$topId,
			'一级分类名字'=>$topName,
			'一级分类链接'=>tpl::url('novel_type',array('page'=>1,'tid'=>$topId,'tpinyin'=>$data['type_pinyin'])),
			'分类链接'=>tpl::url('novel_type',array('page'=>1,'tid'=>$data['type_id'],'tpinyin'=>$data['type_pinyin'])),
			'分类名字'=>$data['type_name'],
			'分类简称'=>$data['type_cname'],
			'分类拼音'=>$data['type_pinyin'],
			'分类简介'=>$data['type_info'],
			'分类标题'=>$data['type_title'],
			'分类关键词'=>$data['type_key'],
			'分类描述'=>$data['type_desc'],
		);
		//替换自定义字段
		$arr = RepField($arr2,$arr1,$data);
		tpl::Rep($arr);
		
		self::TypeList();

		$repFun['a']['novellabel'] = 'PublicNovel';
		tpl::Label('{小说列表:[s]}[a]{/小说列表}','content', self::$CF, $repFun['a']);
	}

	
	/**
	 * 列表页条件替换
	 * 给小说列表标签加上各种限制条件。
	 **/
	static function TypeList()
	{
		$reWhere = $pageWhere = $tidWhere = $orderWhere = '';
		//分类id大于0
		if ( C('page.tid') > 0 )
		{
			//$tidWhere = 'tid='.C('page.tid').';';
			//查询包含下级的内容
			$tidWhere = 'type_pid=[and-or->rin:'.C('page.tid').'||t.type_id:'.C('page.tid').'];';
		}
		//页数
		if ( C('page.page') > 0 )
		{
			$pageWhere = 'page='.C('page.page').';';
		}
		//筛选条件进行查询
		$reMod = NewModel('system.retrieval');
		$reWhere = $reMod->GetWhere('novel',null);
		//排序
		if ( C('page.order') != '' || ($reWhere != '' && str::in_string('排序=',$reWhere)) )
		{
			$orderWhere = '排序='.C('page.order').';';
		}
		else
		{
			$orderWhere = '排序=novel_uptime desc;';
		}
		
		$novelWhere = $pageWhere.$tidWhere.$orderWhere.$reWhere;
		tpl::Rep( array('{小说列表:'=>'{小说列表:'.$novelWhere) , null , '2' );
	}
	
	
	/**
	* 内容页标签替换
	**/
	static function NovelLabel()
	{
		$v = C('page.data');
		
		//小说签约等级
		$signName = '';
		if( $v['novel_copyright'] == 1 )
		{
			$signMod = NewModel('author.sign');
			$signData = $signMod->GetOne($v['novel_sign_id']);
			$signName = $signData['sign_name'];
		}
		else if( $v['novel_copyright'] == 2)
		{
			$signName = parent::GetCopyRigth(2);
		}
		//作者等级
		$authorMod = NewModel('author.level');
		$authorLevel = str::GetKey($authorMod->GetAuthorLevel('novel',$v['author_id']),'level_name');
		$authorData = array();
		//作者信息
		if( $v['author_id'] > 0 )
		{
			$authorMod = NewModel('author.author');
			$authorData = $authorMod->GetAuthor($v['author_id'],2);
			if( $authorData )
			{
			    $v['novel_author'] = $authorData['author_nickname'];
			}
		}
		if( empty($authorData) )
		{
			//公告
			$authorConfig = GetModuleConfig('author');
			$authorData['author_notice'] = $authorConfig['author_default_notice'];
			//头像
			$userConfig = C('','','userConfig');
			$authorData['user_head'] =  $userConfig['default_head'];
		}

		//显示固定字数标签
		$nameArr = tpl::Exp('{名字:[d]}' , $v['novel_name'] );
		$infoArr = tpl::Exp('{简介:[d]}' , str::ClearHtml($v['novel_info']) );
			
		//匹配自定义时间标签
		$time = tpl::Tag('{更新时间:[s]}');
		$createTime = tpl::Tag('{创建时间:[s]}');

		//顶踩链接
		$urlData['module'] = 'novel';
		$urlData['cid'] = $v['novel_id'];

		//是否连载
		tpl::IfRep( $v['novel_process'] , '=' , 0 , '连载中');
		//是否完结
		tpl::IfRep( $v['novel_process'] , '=' , 1 , '已完结');
		//是否断更
		tpl::IfRep( $v['novel_process'] , '=' , 2 , '已断更');
		//是否签约
		tpl::IfRep( $v['novel_copyright'] , '>' , 0 , '已签约','未签约');
		//是否上架
		tpl::IfRep( $v['novel_sell'] , '=' , 0 , '已上架','未上架');


		$arr1 = tpl::GetBeforeLabel('NovelLabel');
		//设置自定义中文标签
		$arr2 = array(
			'加入收藏'=>common::GetUrl('coll.coll' , $urlData ),
			'加入书架'=>common::GetUrl('coll.shelf' , $urlData ),
			'订阅'=>common::GetUrl('coll.dingyue' , $urlData ),
			
			'投推荐票'=>common::GetUrl('novel.ticket' , array('number'=>1,'type'=>'rec','nid'=>$v['novel_id']) ),
			'投月票'=>common::GetUrl('novel.ticket' , array('number'=>1,'type'=>'month','nid'=>$v['novel_id']) ),
			'打赏提交地址'=>common::GetUrl('buy.reward' , array('nid'=>$v['novel_id']) ),
			'报错'=>'/module/message/add.php?content='.urlencode('小说《'.$v['novel_name'].'》,ID:'.$v['novel_id'].'出现错误!'),
			'txt下载'=>tpl::url('down_down' , array('module'=>'novel','cid'=>$v['novel_id'],'fid'=>0) ),

			'目录链接'=>tpl::url( 'novel_menu' , array('page'=>1,'tid'=>$v['type_id'],'nid'=>$v['novel_id'],'tpinyin'=>$v['type_pinyin'],'npinyin'=>$v['novel_pinyin'])),
			'内容链接'=>tpl::url( 'novel_info' , array('nid'=>$v['novel_id'],'tid'=>$v['type_id'],'tpinyin'=>$v['type_pinyin'],'npinyin'=>$v['novel_pinyin']) ),
			'分类链接'=>tpl::url( 'novel_type' , array('page'=>1,'tid'=>$v['type_id'],'tpinyin'=>$v['type_pinyin']) ),
			'最新章节'=>tpl::url( 'novel_read' , array('cid'=>$v['novel_newcid'],'nid'=>$v['novel_id'],'tid'=>$v['type_id'],'tpinyin'=>$v['type_pinyin'],'npinyin'=>$v['novel_pinyin']) ),
			'作者详情'=>tpl::url( 'author_author' , array('aid'=>$v['author_id']) ),
			
			'id'=>$v['novel_id'],
			'作者id'=>$v['author_id'],
			'名字'=>$v['novel_name'],
			'拼音'=>$v['novel_pinyin'],
			'推荐标题'=>parent::GetRT( $v ),
			'进程'=>parent::GetProcess($v['novel_process']),
			'进程id'=>$v['novel_process'],
			'类型'=>parent::GetType($v['novel_type']),
			'类型id'=>$v['novel_type'],
			'是否签约'=>parent::GetCopyRigth($v['novel_copyright']),
			'签约'=>$v['novel_copyright'],
			'签约等级'=>$signName,
			'是否上架'=>parent::GetSell($v['novel_sell']),
			'上架'=>$v['novel_sell'],
			'作者'=>$v['novel_author'],
			'作者等级'=>$authorLevel,
			'作者公告'=>$authorData['author_notice'],
			'作者头像'=>$authorData['user_head'],
			'封面'=>$v['novel_cover'],
			'简介'=>str::ToHtml($v['novel_info']),
			'点评'=>$v['novel_comment'],
			'标签'=>$v['novel_tags'],
			'字数'=>$v['novel_wordnumber'],
			'最新章节id'=>$v['novel_newcid'],
			'最新章节名字'=>$v['novel_newcname'],
			'第一章节id'=>$v['novel_startcid'],
			'第一章节名字'=>$v['novel_startcname'],
			'章节数量'=>parent::GetChapterCount($v['novel_id']),
			'顶'=>$v['novel_ding'],
			'顶链接'=>common::GetUrl('dingcai.ding' , $urlData),
			'踩'=>$v['novel_cai'],
			'踩链接'=>common::GetUrl('dingcai.cai' , $urlData),
			'评论量'=>$v['novel_replay'],
			'评分'=>$v['novel_score'],
			'日点击'=>$v['novel_todayclick'],
			'周点击'=>$v['novel_weekclick'],
			'月点击'=>$v['novel_monthclick'],
			'年点击'=>$v['novel_yearclick'],
			'总点击'=>$v['novel_allclick'],
			'日收藏'=>$v['novel_todaycoll'],
			'周收藏'=>$v['novel_weekcoll'],
			'月收藏'=>$v['novel_monthcoll'],
			'年收藏'=>$v['novel_yearcoll'],
			'总收藏'=>$v['novel_allcoll'],
			'日推荐'=>$v['novel_todayrec'],
			'周推荐'=>$v['novel_weekrec'],
			'月推荐'=>$v['novel_monthrec'],
			'年推荐'=>$v['novel_yearrec'],
			'总推荐'=>$v['novel_allrec'],
				
			'更新时间'=>date("Y-m-d H:i:s",$v['novel_uptime']),
			'创建时间'=>date("Y-m-d H:i:s",$v['novel_createtime']),
			'分类id'=>$v['type_id'],
			'分类名字'=>$v['type_name'],
			'分类简称'=>$v['type_cname'],
			'分类拼音'=>$v['type_pinyin'],

			'名字:'.GetKey($nameArr,'0')=>GetKey($nameArr,'1'),
			'简介:'.GetKey($infoArr,'0')=>GetKey($infoArr,'1'),
			'更新时间:'.GetKey($time,'1,0')=>tpl::Time(GetKey($time,'1,0'), $v['novel_uptime']),
			'创建时间:'.GetKey($createTime,'1,0')=>tpl::Time(GetKey($createTime,'1,0'), $v['novel_uptime']),
		);
		//替换自定义字段
		$arr = RepField($arr2,$arr1,$v,2,'',array('novel','content'));
		tpl::Rep($arr);

		//小说标签替换
		$repFun['a']['novellabel'] = 'PublicTag';
		tpl::Label('{标签:[s]}[a]{/标签}','explode','key', $repFun['a']);
		
		//开始阅读标签
		self::NovelSatrt();
		
		//最新章节列表
		self::NovelChapter();
		
		//评分标签
		common::ScoreLabel( 'novel' , C('page.data.novel_id') );
		
		//上下篇
		self::NovelPreNext();
		
		//同标签小说
		self::NovelLike();
		
		//同分类小说
		self::NovelType();
		
		//同作者小说
		self::NovelAuthor();
		
		//存在用户类才进行如下操作
		if(class_exists('userlabel'))
		{
    		//阅读记录开启记录
    		$v['novel_last_cid'] = $v['novel_startcid'];
    		$v['novel_last_name'] = $v['novel_startcname'];
    		if( C('read_open',null,'novelConfig') == '1' && user::GetUid() > 0)
    		{
    			$readMod = NewModel('user.read');
    			$readLast = $readMod->GetByNid('novel',$v['novel_id'],user::GetUid());
    			if( $readLast )
    			{
    		        $v['novel_last_cid'] = $readLast['read_cid'];
    		        $v['novel_last_name'] = $readLast['read_title'];
    			}
    		}
		    tpl::IfRep( empty($readLast) , '=' , false , '已阅读', '未阅读');
		    
			//道具单功能模块
			IncModule('props',array('module'=>'novel','cid'=>$v['novel_id']));
			//粉丝单功能模块
			IncModule('fans',array('module'=>'novel','cid'=>$v['novel_id']));
			//票类单功能模块
			IncModule('ticket',array('module'=>'novel','cid'=>$v['novel_id']));

			//是否收藏、书架、推荐
			userlabel::PublicColl('novel' , $v['novel_id']);
			//获取用户推荐票额月票数据
			$ticketMod = NewModel('user.ticket');
			$ticketData = $ticketMod->GetTicket(user::GetUid() , 'novel');
			//替换用户月票和推荐片数量的标签
			$arr = array(
				'月票'=>$ticketData['ticket_month'],
				'推荐票'=>$ticketData['ticket_rec'],
			    '上次阅读'=>tpl::url( 'novel_read' , array('cid'=>$v['novel_last_cid'],'nid'=>$v['novel_id'],'tid'=>$v['type_id'],'tpinyin'=>$v['type_pinyin'],'npinyin'=>$v['novel_pinyin']) ),
				'上次阅读章节名字'=>$v['novel_last_name'],
				'上次阅读章节id'=>$v['novel_last_cid'],
			);
			tpl::Rep($arr);
		}
		
	}

	
	//第一章
	static function NovelSatrt()
	{
		$where['table'] = '@novel_chapter';
		$where['where']['chapter_nid'] = C('page.data.novel_id');
		$where['where']['chapter_status'] = '1';
		$where['order'] = 'chapter_order,chapter_id';
		$data = wmsql::GetOne($where);
		
		$url = "javascript:alert('".C('novel.par.novel_chapter',null,'lang')."')";
		if( $data )
		{	
			$url = tpl::url( 'novel_read' , array('cid'=>$data['chapter_id'],'nid'=>$data['chapter_nid'],'tid'=>C('page.data.type_id'),'npinyin'=>C('page.data.novel_pinyin'),'tpinyin'=>C('page.data.type_pinyin') ) );
		}
		tpl::Rep(array('立即阅读'=>$url));
	}
	
	
	//最新章节列表
	static function NovelChapter()
	{

		$nid = C('page.data.novel_id');
		
		$whereLabel = 'volume_nid=[or->0,'.$nid.'];';
		tpl::Rep( array('{小说分卷列表:'=>'{小说分卷列表:'.$whereLabel) , null , '2' );
		$repFun['a']['novellabel'] = 'PublicVolume';
		tpl::Label('{小说分卷列表:[s]}[a]{/小说分卷列表}','volume', self::$CF, $repFun['a']);
		

		$whereLabel = 'chapter_status=1;page='.C('page.page').';chapter_nid='.$nid.';';
		tpl::Rep( array('{小说章节列表:'=>'{小说章节列表:'.$whereLabel) , null , '2' );
		$repFun['a']['novellabel'] = 'PublicChapter';
		tpl::Label('{小说章节列表:[s]}[a]{/小说章节列表}','chapter', self::$CF, $repFun['a']);
	}

	//上下一篇小说替换
	static function NovelPreNext()
	{
		$data = C('page.data');
		$where['field'] = 'novel_id,novel_name';
		$where['table']['@novel_novel'] = '';
		$where['where']['type_id'] = $data['type_id'];
		
		$url = tpl::Url('novel_novel' , array('tpinyin'=>$data['type_pinyin'],'tid'=>$data['type_id']));
		
		common::PreNext( $where , 'novel_id' , $data['novel_id'] , $url , 'nid' , 'novel_name' );
	}

	//同标签的小说
	static function NovelLike()
	{
		$tags = C('page.data.novel_tags');
		
		$whereLabel = 'novel_id=[不等于->'.C('page.data.novel_id').'];type_id='.C('page.data.type_id').';';
		if( $tags != '' )
		{
			$whereLabel.= 'novel_tags=[rin->'.$tags.'];';
		}

		tpl::Rep( array('{同标签小说:'=>'{同标签小说:'.$whereLabel) , null , '2' );
		
		$repFun['a']['novellabel'] = 'PublicNovel';
		tpl::Label('{同标签小说:[s]}[a]{/同标签小说}','content', self::$CF, $repFun['a']);
	}

	//同分类的小说
	static function NovelType()
	{
		$whereLabel = 'novel_id=[不等于->'.C('page.data.novel_id').'];type_id='.C('page.data.type_id').';';
		tpl::Rep( array('{同分类小说:'=>'{同分类小说:'.$whereLabel) , null , '2' );
		
		$repFun['a']['novellabel'] = 'PublicNovel';
		tpl::Label('{同分类小说:[s]}[a]{/同分类小说}','content', self::$CF, $repFun['a']);
	}
	
	//同作者的小说
	static function NovelAuthor()
	{
		$author = C('page.data.novel_author');
		$whereLabel = 'novel_id=[不等于->'.C('page.data.novel_id').'];novel_author='.$author.';';
		tpl::Rep( array('{同作者小说:'=>'{同作者小说:'.$whereLabel) , null , '2' );
	
		$repFun['a']['novellabel'] = 'PublicNovel';
		tpl::Label('{同作者小说:[s]}[a]{/同作者小说}','content', self::$CF, $repFun['a']);
	}
	
	
	//目录列表页标签
	static function MenuLabel()
	{
		//书籍介绍页面的标签
		self::NovelLabel();
	}
	
	
	//章节阅读标签
	static function ReadLabel()
	{
		$data = C('page.data');
		$cid = C('page.cid');
		$nid = C('page.nid');
		$content = str_replace(' ','&nbsp;',$data['content']);
		//出售价格
		$price['sell_number'] = $price['sell_all'] = $price['sell_month'] = '0';
		//小说是否允许出售并且章节为付费章节
		if( $data['chapter_ispay'] == 1 && $data['novel_sell'] == 1)
		{
			//查询出售价格
			$sellMod = NewModel('novel.sell');
			$price = $sellMod->GetNovelSell($data['novel_id']);
			//计算字数价格
			$price['sell_number'] = round($data['chapter_number']/1000 * $price['sell_number'] , 2);
		}
		if( $data['is_sub'] == 0 )
		{
			$content = '对不起，您还没订阅本章！';
		}
		//获得作者的话
		$saidMod = NewModel('novel.said');
		$saidData = $saidMod->GetByNid($nid,$cid);
		$data['said_content'] = GetKey($saidData,'said_content');
		$data['said_uptime'] = intval(GetKey($saidData,'said_uptime'));
		//是否有作者的话
		tpl::IfRep( empty($data['said_content'])?0:1 , '=' , 1 , '作者的话存在','作者的话不存在');
		//是否单章出售
		tpl::IfRep( $price['sell_number'] , '>' , 0 , '单章出售');
		//是否包月出售
		tpl::IfRep( $price['sell_month'] , '>' , 0 , '包月出售');
		//是否全本出售
		tpl::IfRep( $price['sell_all'] , '>' , 0 , '全本出售');

		//匹配自定义时间标签
		$creatTime = tpl::Tag('{章节创建时间:[s]}');
		//匹配自定义作者的话时间标签
		$saidUpatetime = tpl::Tag('{作者的话更新时间:[s]}');
		$arr = array(
			'报错'=>'/module/message/add.php?content='.urlencode('小说《'.C('page.data.novel_name').'》,ID:'.C('page.data.novel_id').'。章节id：'.$cid.'出现错误!'),
			'催更'=>'/module/message/add.php?content='.urlencode('小说《'.C('page.data.novel_name').'》,ID:'.C('page.data.novel_id').'。更新太慢!'),
			'章节名字'=>C('page.data.chapter_name'),
			'分卷名字'=>C('page.data.voulme_name'),
			'章节内容'=>$content,
			'章节id'=>$cid,
			'章节字数'=>$data['chapter_number'],
			'章节分卷id'=>$data['chapter_vid'],
			'本章价格'=>$price['sell_number'],
			'vip章节'=>$data['chapter_isvip'],
			'全本价格'=>$price['sell_all'],
			'包月价格'=>$price['sell_month'],
			'章节url'=>tpl::url( 'novel_read' , array('cid'=>$cid,'nid'=>$nid,'tid'=>$data['type_id'],'npinyin'=>$data['novel_pinyin'],'tpinyin'=>$data['type_pinyin'] ) ),
			'章节创建时间戳'=>$data['chapter_time'],
			'作者的话'=>str::ToHtml($data['said_content']),
			'章节创建时间'=>$data['chapter_time']==''?'':date("Y-m-d H:i:s",$data['chapter_time']),
			'章节创建时间:'.GetKey($creatTime,'1,0')=>tpl::Time(GetKey($creatTime,'1,0'), $data['chapter_time']),
			'作者的话更新时间'=>date("Y-m-d H:i:s",$data['said_uptime']),
			'作者的话更新时间:'.GetKey($saidUpatetime,'1,0')=>tpl::Time(GetKey($saidUpatetime,'1,0'), $data['said_uptime']),
		);
		tpl::Rep($arr);

		//是否订阅
		tpl::IfRep( $data['is_sub'] , '=' , 1 , '已订阅','未订阅');
		//阅读记录开启记录
		if( C('read_open',null,'novelConfig') == '1' && Call('user','GetUid') > 0)
		{
			$readMod = NewModel('user.read');
			$readMod->SetReadLog('novel',$nid,$cid,$data['chapter_name']);
		}
		
		//章节内容格式化
		$contentArr = explode('<br/>',$data['content']);
		$contentLabelArr = tpl::Tag('{章节内容格式化:[s]}');
		if( !empty($contentLabelArr) )
		{
			$repArr = array();
			foreach($contentLabelArr[0] as $k=>$v)
			{
				$contentWhere = tpl::GetWhere($contentLabelArr[1][$k],null,false)['where'];
				$content = $contentWhere['开始标签'].implode($contentWhere['结束标签'].$contentWhere['开始标签'],$contentArr).$contentWhere['结束标签'];
				$repArr = array(
						'章节内容:'.$contentLabelArr[1][$k]=>$content,
				);
			}
			tpl::Rep($repArr);
		}
		//类型排行
		$repFun['t']['novellabel'] = 'PublicChapterLine';
		tpl::Label('{章节单行:[s]}[a]{/章节单行}',$contentArr, null , $repFun['t']);
		
		self::NovelLabel();
		self::ReadPreNext();
	}
	
	//上下一章节替换
	static function ReadPreNext(){
		$cid = C('page.cid');
		$name = C('page.data.novel_name');
		$nid = C('page.data.novel_id');
		$tid = C('page.data.type_id');
		$npinyin = C('page.data.novel_pinyin');
		$tpinyin = C('page.data.type_pinyin');
		$order = C('page.data.chapter_order');
		
		//设置基本条件
		$where['field'] = 'chapter_id,chapter_order,chapter_name';
		$where['table'] = '@novel_chapter';
		$where['where']['chapter_nid'] = $nid;
		$where['where']['chapter_status'] = 1;
		
		$readUrl = tpl::url( 'novel_read' , array('nid'=>$nid,'tid'=>$tid,'npinyin'=>$npinyin,'tpinyin'=>$tpinyin ) );
		$menuUrl = tpl::url( 'novel_menu' , array('page'=>1,'nid'=>$nid,'tid'=>$tid,'npinyin'=>$npinyin,'tpinyin'=>$tpinyin ) );

		$arr['上一章'] = $menuUrl;
		$arr['上一章id'] = 0;
		$arr['上一章节名字'] = $name.'最新章节目录';
		$arr['下一章'] = $menuUrl;
		$arr['下一章id'] = 0;
		$arr['下一章节名字'] = $name.'最新章节目录';
			
		//上一篇查询
		$where['where']['chapter_order'] = array('<',$order);
		$dataList = wmsql::GetAll($where);
		if( $dataList )
		{
			$dataList = ArrSort($dataList , 'chapter_order' , 'desc');
			$data = $dataList[0];
			$arr['上一章'] = tpl::Rep( array('cid'=>$data['chapter_id'] ) , $readUrl );
			$arr['上一章id'] = $data['chapter_id'];
			$arr['上一章节名字'] = $data['chapter_name'];
		}
		//下一篇查询
		$where['where']['chapter_order'] = array('>',$order);
		$dataList = wmsql::GetAll($where);
		if( $dataList )
		{
			$dataList = ArrSort($dataList , 'chapter_order' , 'asc');
			$data = $dataList[0];
			$arr['下一章'] = tpl::Rep( array('cid'=>$data['chapter_id'] ) , $readUrl );
			$arr['下一章id'] = $data['chapter_id'];
			$arr['下一章节名字'] = $data['chapter_name'];
		}
		tpl::Rep($arr);
	}
	
	
	//搜索标签
	static function SearchLabel()
	{
		$arr = array(
			'搜索词'=>C('page.key'),
		);
		tpl::Rep($arr);

		//搜索条件
		$where = common::SearchWhere( array('novel_name','novel_author','novel_tags') , C('page.type') , C('page.key') );
		$where['where']['novel_status'] = 1;
		
		//获得数据并且替换标签
		$data = novel::GetData( 'content' , $where );
		$repFun['a']['novellabel'] = 'PublicNovel';
		tpl::Label( '{搜索结果:[s]}[a]{/搜索结果}' , $data , null , $repFun['a'] );

		//关键词搜索次数+1
		search::SearchNumber( 'novel' );
	}


	//评论标签
	static function ReplayLabel()
	{
		$pageWhere = '';
		
		//替换小说标签
		self::novellabel();
		
		//替换页面条件
		if ( C('page.page') > 0 )
		{
			$pageWhere = 'page='.C('page.page').';';
		}
		//查找回复人条件
		if ( C('page.rid') > 0 )
		{
			$pageWhere = 'replay_rid='.C('page.rid').';';
		}
		//查找祖父条件
		if ( C('page.pid') > 0 )
		{
			$pageWhere = 'replay_pid='.C('page.pid').';';
		}
		tpl::Rep( array('{评论:'=>'{评论:'.$pageWhere) , null , '2' );
	}
	
	
	
	//分类排行URL替换
	static function TopType()
	{
		//获取数据
		$arr  = parent::GetData( 'type' , '排序=type_order' );
		$type = str::Int( C('page.type') , null , 0);
		
		//获取页面url信息
		$urlType = C('config.route.url_type');
		$typeUrl = tpl::Url('novel_type');
		$toplistUrl = tpl::Url('novel_toplist' , array('type'=>$type));
		
		//设置临时url信息
		C('config.seo.urls.novel_type.url'.$urlType , $toplistUrl);
		//替换标签
		$repFun['t']['novellabel'] = 'PublicType';
		tpl::Label('{小说分类排行:[s]}[a]{/小说分类排行}','type', self::$CF, $repFun['t']);
		//还原url信息
		C('config.seo.urls.novel_type.url'.$urlType , $typeUrl);
	
		//类型排行
		$repFun['t']['novellabel'] = 'PublicTop';
		tpl::Label('{小说类型排行:[s]}[a]{/小说类型排行}',C('novel.par',null,'lang'), null , $repFun['t']);
	}
	

	//排行列表标签
	static function ToplistLabel()
	{
		//替换标签
		$type = C('page.type');
		$tid = C('page.tid');
		$typeName = '';
		
		//如果分类id大于0
		if( $tid > 0 )
		{
			$typeName = str::GetOne(novel::GetData('type' , 'type_id='.$tid) , 'type_name');
		}
		$arr = array(
			"类型排行"=>$typeName.C('novel.par.type_'.$type,null,'lang'),
			"类型排行id"=>$type,
		);
		tpl::Rep( $arr );

		switch ( $type )
		{
			case "1":
				C('page.order' , 'novel_createtime desc');
				break;
			case "2":
				C('page.order' , 'novel_allclick desc');
				break;
			case "3":
				C('page.order' , 'novel_yearclick desc');
				break;
			case "4":
				C('page.order' , 'novel_monthclick desc');
				break;
			case "5":
				C('page.order' , 'novel_weekclick desc');
				break;
			case "6":
				C('page.order' , 'novel_todayclick desc');
				break;
			case "7":
				C('page.order' , 'novel_allcoll desc');
				break;
			case "8":
				C('page.order' , 'novel_yearcoll desc');
				break;
			case "9":
				C('page.order' , 'novel_monthcoll desc');
				break;
			case "10":
				C('page.order' , 'novel_weekcoll desc');
				break;
			case "11":
				C('page.order' , 'novel_todaycoll desc');
				break;
			case "12":
				C('page.order' , 'novel_allrec desc');
				break;
			case "13":
				C('page.order' , 'novel_yearrec desc');
				break;
			case "14":
				C('page.order' , 'novel_monthrec desc');
				break;
			case "15":
				C('page.order' , 'novel_weekrec desc');
				break;
			case "16":
				C('page.order' , 'novel_todayrec desc');
				break;
			case "17":
				C('page.order' , 'novel_replay desc');
				break;
			case "18":
				C('page.order' , 'novel_ding desc');
				break;
			case "19":
				C('page.order' , 'novel_cai desc');
				break;
			case "20":
				C('page.order' , 'novel_score desc');
				break;
			case "21":
				C('page.order' , 'novel_wordnumber desc');
				break;
			case "22":
				C('page.order' , 'novel_chapter desc');
				break;
		}

		self::TypeLabel();
		
		$repFun['a']['novellabel'] = 'PublicNovel';
		tpl::Label('{小说列表:[s]}[a]{/小说列表}','content', self::$CF, $repFun['a']);
	}
}
?>
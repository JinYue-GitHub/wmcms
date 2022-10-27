<?php
/**
* 作者标签处理类
*
* @version        $Id: author.label.php 2015年12月18日 16:00  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2022年04月30日 11:00 weimeng
* 
*/
class authorlabel extends author
{
	static public $lcode;
	static public $data;
	static public $CF = array('author'=>'GetData');

	function __construct()
	{
		tpl::labelBefore();
		
		//公共url
		self::PublicUrl();

		//调用自定义标签
		self::PublicLabel();
	}
	
	
	//公共url替换
	static function PublicUrl()
	{
		$arr = array(
			'作家首页'=>tpl::url('author_index'),
			'作家注册协议'=>tpl::url('author_agreement'),
			'作家基本资料'=>tpl::url('author_basic'),
			'创建小说'=>tpl::url('author_novel_noveledit',array('nid'=>0)),
			'小说作品列表'=>tpl::url('author_novel_novellist'),
			'小说收入列表'=>tpl::url('author_novel_incomelist',array('page'=>1,'type'=>'all')),
			'文章投稿'=>tpl::url('author_article_draftedit',array('did'=>0)),
			'文章投稿列表'=>tpl::url('author_article_articlelist'),
			'小说数据统计'=>tpl::url('author_novel_statistics'),
		);
		
		//收入列表的url
		$incomeUrl = tpl::Tag('{小说收入列表:[a]}');
		if( isset($incomeUrl[1][1]) )
		{
			foreach ($incomeUrl[1] as $k=>$v)
			{
				$arr['小说收入列表:'.$v] = tpl::url('author_novel_incomelist',array('page'=>1,'type'=>$v));
			}
		}
		
		tpl::Rep($arr);
	}
	
	
	//标签公共标签替换
	static function PublicLabel()
	{
		$author = parent::GetAuthor();
		$arr = array(
			'作家笔名'=>author::GetNickName(),
			'作家id'=>author::GetUid(),
			'作家简介'=>author::GetInfo(),
			'作家公告'=>author::GetNotice(),
			'作家加入时间'=>author::GetTime(),
			'作家头像'=>user::GetHead(),
		);
		tpl::Rep($arr);
		
		//作者列表
		$repFun['a']['authorlabel'] = 'PublicAuthor';
		tpl::Label('{作者:[s]}[a]{/作者}','author', self::$CF, $repFun['a']);
		//作者文章列表
		$repFun['ar']['articlelabel'] = 'PublicArticle';
		tpl::Label('{作者文章:[s]}[a]{/作者文章}','author_article', self::$CF, $repFun['ar']);
		//作者小说列表
		$repFun['n']['novellabel'] = 'PublicNovel';
		tpl::Label('{作者小说:[s]}[a]{/作者小说}','author_novel', self::$CF, $repFun['n']);
		
		//开关投稿标签
		tpl::IfRep( C('author_article_open',null,'authorConfig') , '=' , '1' , '文章投稿开启' , '文章投稿关闭');
	}

	
	/**
	 * 草稿列表公共标签
	 * @param 参数1，数组，需要进行操作的数组
	 * @param 参数2，字符串，需要进行替换的字符串，原始标签代码。
	 **/
	static function PublicDraft($data,$blcode)
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
			
			//显示固定字数标签
			$titleArr = tpl::Exp('{草稿标题:[d]}' , $v['draft_title'] , $lcode);
			$contentArr = tpl::Exp('{草稿内容:[d]}' , $v['draft_content'] , $lcode);
			$timeArr = tpl::Tag('{草稿创建时间:[s]}',$lcode);
			
			//设置自定义中文标签
			$arr2=array(
				'i'=>$i,
				'url'=>tpl::url(C('page.url'),array('nid'=>$v['draft_cid'],'did'=>$v['draft_id'])),
				'草稿id'=>$v['draft_id'],
				'草稿标题'=>$v['draft_title'],
				'草稿内容'=>$v['draft_content'],
				'草稿字数'=>$v['draft_number'],
				'草稿创建时间'=>date("Y-m-d H:i:s",$v['draft_createtime']),

				'草稿标题:'.GetKey($titleArr,'0')=>GetKey($titleArr,'1'),
				'草稿内容:'.GetKey($contentArr,'0')=>GetKey($contentArr,'1'),
				'草稿创建时间:'.GetKey($timeArr,'1,0')=>tpl::Time(GetKey($timeArr,'1,0'), $v['draft_createtime']),
					
				'删除草稿'=>common::GetUrl('author.draftdel' , array('did'=>GetKey($v,'draft_id')) ),
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
	

	/**
	 * 收入标签
	 * @param 参数1，参数
	 * @param 参数2，标签模版
	 */
	static function PublicIncomeLabel($data,$blcode)
	{
		$code = '';
		$i = 1;
		//循环数据
		$logMod = NewModel('user.finance_log');
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

			//如果是销售报表模式二
			if( C('author_income_type',null,'authorConfig')=='2' )
			{
				$arr2=array(
						'i'=>$i,
						'收入id'=>$v['report_id'],
						'收入来源'=>$logMod->GetTypeName($v['report_module'],$v['report_type']),
						'收入金币2'=>$v['report_gold2'],
						'收入状态'=>parent::GetIncomeStatusType($v['report_settlement'],2),
						'收入备注'=>$v['report_remark'],
						'收入时间'=>date("Y-m-d H:i:s",$v['report_time']),
				);
			}
			//模式一直接到账
			else
			{
				$arr2=array(
						'i'=>$i,
						'收入id'=>$v['log_id'],
						'收入来源'=>$logMod->GetTypeName($v['log_module'],$v['log_type']),
						'收入金币2'=>$v['log_gold2'],
						'收入状态'=>parent::GetIncomeStatusType(1,1),
						'收入备注'=>$v['log_remark'],
						'收入时间'=>date("Y-m-d H:i:s",$v['log_time']),
				);
			}
			//合并两组标签
			$arr = ArrMerge($v , $arr1,$arr2);
			//替换标签
			$code.=tpl::rep($arr,$lcode);
			$i++;
		}
	
		//返回最后的结果
		return $code;
	}
	
	/**
	 * 作者列表公共标签
	 * @param 参数1，数组，需要进行操作的数组
	 * @param 参数2，字符串，需要进行替换的字符串，原始标签代码。
	 **/
	static function PublicAuthor($data,$blcode)
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
					'url'=>tpl::url('author_author',array('aid'=>$v['author_id'])),
					'作者用户中心'=>tpl::Url('user_fhome',array('uid'=>$v['user_id'])),
					'作者id'=>$v['author_id'],
					'作者用户id'=>$v['user_id'],
					'作者昵称'=>$v['author_nickname'],
					'作者简介'=>$v['author_info'],
					'作者公告'=>$v['author_notice'],
					'作者用户昵称'=>$v['user_nickname'],
					'作者头像'=>$v['user_head'],
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
	
	
	
	/**
	 * 草稿内容标签
	 */
	static function DraftPublicLabel()
	{
		$data = C('page.data');
		$arr = array(
			'标题'=>GetKey($data,'draft_title'),
			'内容'=>GetKey($data,'draft_content'),
			'字数'=>GetKey($data,'draft_number'),
			'创建时间'=>GetKey($data,'draft_createtime')==''?'':date("Y-m-d H:i:s",GetKey($data,'draft_createtime')),
			'删除草稿'=>common::GetUrl('author.deldraft' , array('did'=>GetKey($data,'draft_id')) ),
		);
		tpl::Rep($arr);
	}

	
	/**
	 * 作者信息公共标签
	 * @param 参数1，必须，作者ID
	 */
    static function AuthorPublicLabel($aid)
    {
        //签约数据
		$novelModel = NewModel('novel.novel');
		$novelCount = $novelModel->GetCount(array('novel_status'=>1,'author_id'=>$aid));
		$novelNumber = $novelModel->GetWordNumber($aid);
		$signNovelCount = NewModel('novel.sign')->GetAuthorSign($aid);
		//经验
		$levelModel = NewModel('author.level');
		$novelLevel = $levelModel->GetAuthorLevel('novel' , $aid);
		
		$arr = array(
			'所有小说数量'=>$novelCount,
			'签约小说数量'=>$signNovelCount,
			'所有小说字数'=>$novelNumber,
			'小说经验值'=>$novelLevel['exp_number'],
			'小说等级名'=>$novelLevel['level_name'],
			'小说等级开始经验'=>$novelLevel['level_start'],
			'小说等级结束经验'=>$novelLevel['level_end'],
			'小说等级下一等级'=>$novelLevel['next']['level_name'],
			'小说等级升级经验'=>$novelLevel['next']['exp_number'],
		);
		tpl::Rep($arr);
    }


	/**
	 * 作者自己的首页
	 */
	static function IndexLabel()
	{
	    $data = author::GetAuthor();
	    
		self::AuthorPublicLabel($data['author_id']);
		userlabel::FinanceLabel();
		userlabel::AttributeLabel();
	}
	

	/**
	 * 注册协议
	 */
	static function ApplyLabel()
	{
		$arr = array('表单提交地址'=>common::GetUrl('author.applyauthor'),);
		tpl::Rep($arr);
	}
	
	
	/**
	 * 注册协议
	 */
	static function AgreementLabel()
	{
		$arr = array(
			'协议内容'=>file::GetFile('lang/'.C('config.web.lang').'/agreement.txt',true),
		);
		tpl::Rep( $arr);
	}
	
	/**
	 * 修改基本资料
	 */
	static function BasicLabel()
	{
		$arr = array('表单提交地址'=>common::GetUrl('author.upbasic'),);
		tpl::Rep($arr);
		
		userlabel::FinanceLabel();
	}
	
	
	/**
	 * 小说作品管理的公共url
	 */
	static function NovelPublic()
	{
		//不检查小说审核状态
		C('page.novel_check_status',0);
		
		$id = str::Int(C('page.id') , null , 0);
		$arr = array(
			'编辑小说'=>tpl::url('author_novel_noveledit',array('nid'=>$id)),
			'小说草稿箱列表'=>tpl::url('author_novel_draftlist',array('nid'=>$id,'page'=>1)),
			'小说新建草稿'=>tpl::url('author_novel_draftedit',array('nid'=>$id,'did'=>0)),
			'小说分卷列表'=>tpl::url('author_novel_volumelist',array('nid'=>$id,'page'=>1)),
			'小说新建分卷'=>tpl::url('author_novel_volumeedit',array('nid'=>$id,'vid'=>0)),
			'小说章节列表'=>tpl::url('author_novel_chapterlist',array('nid'=>$id,'page'=>1)),
			'小说新建章节'=>tpl::url('author_novel_chapteredit',array('nid'=>$id,'cid'=>0)),
		);
		tpl::Rep($arr);
	}
	
	/**
	 * 公共小说标签
	 */
	static function NovelPublicNovel()
	{
		$v = C('page.data');
		$id = C('page.id');
		if( !$v )
		{
			$v['novel_cover'] =  C('cover',null,'novelConfig');
			$v['novel_name'] = $v['novel_info'] = '';
			$v['novel_process'] = $v['novel_type'] = $v['novel_wordnumber'] = $v['novel_ding'] = 0;
			$v['novel_cai'] = $v['novel_replay'] = $v['novel_score'] = $v['novel_allclick'] = 0;
			$v['novel_allcoll'] = $v['novel_allrec'] = 0;
		}
		
		//设置自定义中文标签
		$arr = array(
			'id'=>$id,
			'名字'=>$v['novel_name'],
			'封面'=>$v['novel_cover'],
			'简介'=>$v['novel_info'],
			'标签'=>GetKey($v,'novel_tags'),
			'分类名字'=>GetKey($v,'type_name'),
			'process'=>str::Int($v['novel_process'] , null , 0),
			'分类'=>str::Int($v['novel_type'] , null , 0),
			'type'=>str::Int($v['novel_type'] , null , 0),
			'字数'=>str::Int($v['novel_wordnumber'] , null , 0),
			'顶'=>str::Int($v['novel_ding'] , null , 0),
			'踩'=>str::Int($v['novel_cai'] , null , 0),
			'评论量'=>str::Int($v['novel_replay'] , null , 0),
			'评分'=>str::Int($v['novel_score'] , null , 0),
			'总点击'=>str::Int($v['novel_allclick'] , null , 0),
			'总收藏'=>str::Int($v['novel_allcoll'] , null , 0),
			'总推荐'=>str::Int($v['novel_allrec'] , null , 0),
		);

		tpl::rep($arr);
	}
	
	
	/**
	 * 公共小说章节类型标签
	 */
	static function NovelPublicChapterTypeLabel()
	{
		$novelData = C('page.data');
		$arr[0] = array('id'=>0,'title'=>C('novel.par.chapter_type_0',null,'lang'));
		//如果已经签约才能进行销售
		if( $novelData['novel_sell'] == 1 )
		{
			$arr[1] = $arr[0];
			$arr[0] = array('id'=>1,'title'=>C('novel.par.chapter_type_1',null,'lang'));
		}
		$repFun = array('authorlabel'=>'NovelPublicChapterType');
		
		tpl::Label('{小说章节类型列表:[s]}[a]{/小说章节类型列表}',$arr, null , $repFun);
	}
	/**
	 * 公共小说章节类型
	 * @param 参数1，参数
	 * @param 参数2，标签模版
	 */
	static function NovelPublicChapterType($data,$blcode)
	{
		$code = '';
		$i = 1;
		//循环数据
		foreach ($data as $k => $v)
		{
			//每次循环重新调取原始标签
			$lcode = $blcode;
				
			//设置自定义中文标签
			$arr=array(
				'i'=>$i,
				'小说章节类型id'=>$v['id'],
				'小说章节类型名字'=>$v['title'],
			);
			//替换标签
			$code.=tpl::rep($arr,$lcode);
			$i++;
		}
		
		//返回最后的结果
		return $code;
	}
	
	/**
	 * 公共小说进程标签
	 */
	static function NovelPublicNovelProcessLabel()
	{
		$arr[] = array('id'=>1,'title'=>C('novel.par.novel_process_1',null,'lang'));
		$arr[] = array('id'=>2,'title'=>C('novel.par.novel_process_2',null,'lang'));
		$arr[] = array('id'=>3,'title'=>C('novel.par.novel_process_3',null,'lang'));
		$repFun = array('authorlabel'=>'NovelPublicNovelProcess');
		
		tpl::Label('{小说进程列表:[s]}[a]{/小说进程列表}',$arr, null , $repFun);
	}
	/**
	 * 公共小说进程
	 * @param 参数1，参数
	 * @param 参数2，标签模版
	 */
	static function NovelPublicNovelProcess($data,$blcode)
	{
		$code = '';
		$i = 1;
		//循环数据
		foreach ($data as $k => $v)
		{
			//每次循环重新调取原始标签
			$lcode = $blcode;
				
			//设置自定义中文标签
			$arr=array(
				'i'=>$i,
				'小说进程id'=>$v['id'],
				'小说进程名字'=>$v['title'],
			);
			//替换标签
			$code.=tpl::rep($arr,$lcode);
			$i++;
		}
		
		//返回最后的结果
		return $code;
	}

	/**
	 * 公共小说授权级别标签
	 */
	static function NovelPublicNovelAuthorizeLabel()
	{
		$arr[] = array('id'=>1,'title'=>C('novel.par.novel_type_1',null,'lang'));
		$arr[] = array('id'=>2,'title'=>C('novel.par.novel_type_2',null,'lang'));
		$repFun = array('authorlabel'=>'NovelPublicNovelAuthorize');
	
		tpl::Label('{小说类型列表:[s]}[a]{/小说类型列表}',$arr, null , $repFun);
	}
	/**
	 * 公共小说授权级别
	 * @param 参数1，参数
	 * @param 参数2，标签模版
	 */
	static function NovelPublicNovelAuthorize($data,$blcode)
	{
		$code = '';
		$i = 1;
		//循环数据
		foreach ($data as $k => $v)
		{
			//每次循环重新调取原始标签
			$lcode = $blcode;
	
			//设置自定义中文标签
			$arr=array(
				'i'=>$i,
				'小说类型id'=>$v['id'],
				'小说类型名字'=>$v['title'],
			);
			//替换标签
			$code.=tpl::rep($arr,$lcode);
			$i++;
		}
	
		//返回最后的结果
		return $code;
	}

	/**
	 * 公共小说分类标签
	 */
	static function NovelPublicNovelTypeLabel()
	{
		$data = C('page.data');
		if( $data && $data['type_pid'] != '0' )
		{
			$pid = '0,'.$data['type_pid'];
			$tid = $data['type_pid'].','.$data['type_id'];

			$pidArr = explode(',', $pid);
			$tidArr = explode(',', $tid);
			foreach ($pidArr as $k=>$v)
			{
				$arr[] = array('topid'=>$v,'typeid'=>$tidArr[$k]);
			}
		}
		else
		{
			$pid = isset($data['type_id'])?$data['type_id']:0;
			$arr[] = array('topid'=>0,'typeid'=>$pid);
		}
		
		$repFun = array('authorlabel'=>'NovelPublicNovelType');
		tpl::Label('{小说分类列表:[s]}[a]{/小说分类列表}',$arr, null , $repFun);
	}
	/**
	 * 公共小说分类
	 * @param 参数1，参数
	 * @param 参数2，标签模版
	 */
	static function NovelPublicNovelType($data,$blcode)
	{
		$code = '';
		$i = 1;
		//循环数据
		foreach ($data as $k => $v)
		{
			//每次循环重新调取原始标签
			$lcode = $blcode;
	
			//设置自定义中文标签
			$arr=array(
				'i'=>$i,
				'topid'=>$v['topid'],
				'typeid'=>$v['typeid'],
			);
			//替换标签
			$code.=tpl::rep($arr,$lcode);
			$i++;
		}
	
		//返回最后的结果
		return $code;
	}
	
	
	/**
	 * 小说作品列表
	 */
	static function NovelNovelListLabel()
	{
		self::NovelPublic();
		//不检查小说状态
		C('page.novel_check_status',0);
		//设置回调标签
		C('page.callback_label',array('authorlabel','NovelNovelListCallBackLabel'));
		
		//只显示自己的小说
		$where = 'au.author_id='.author::GetUid().';';
		if ( C('page.page') > 0 )
		{
			$where .= 'page='.C('page.page').';';
		}
		tpl::Rep( array('{小说列表:'=>'{小说列表:'.$where) , null , '2' );

		$repFun['a']['novellabel'] = 'PublicNovel';
		tpl::Label('{小说列表:[s]}[a]{/小说列表}','content', novellabel::$CF, $repFun['a']);

		//清空设置回调标签
		C('page.callback_label','delete');
	}
	/**
	 * 小说作品列表页的回调标签
	 * @param 参数1，必须，数据
	 */
	static function NovelNovelListCallBackLabel($v)
	{
		$arr = array(
			'deurl'=>tpl::url('author_novel_draftedit',array('nid'=>$v['novel_id'],'did'=>0)),
			'dlurl'=>tpl::url('author_novel_draftlist',array('nid'=>$v['novel_id'],'page'=>1)),
			'neurl'=>tpl::url('author_novel_noveledit',array('nid'=>$v['novel_id'])),
			'curl'=>tpl::url('author_novel_chapterlist',array('nid'=>$v['novel_id'],'page'=>1)),
		);
		return $arr;
	}
	
	
	/**
	 * 小说创建/编辑
	 */
	static function NovelNovelEditLabel()
	{
		$arr = array(
			'封面审核状态'=>1,
		);
		
		//封面审核状态
		$coverApply = C('page.data.cover_apply');
		if( !empty($coverApply) )
		{
			$option = C('page.data.cover_apply.apply_option');
			$arr = array(
				'封面'=>$option['file'],
				'封面审核状态'=>0,
			);
		}
		tpl::rep($arr);
		
		self::NovelPublic();
		self::NovelPublicNovel();
		self::NovelPublicNovelProcessLabel();
		self::NovelPublicNovelAuthorizeLabel();
		self::NovelPublicNovelTypeLabel();

		$arr['表单提交地址'] = common::GetUrl('author.novel_noveledit');
		tpl::rep($arr);
	}
	

	/**
	 * 小说草稿列表
	 */
	static function NovelDraftListLabel()
	{
		self::NovelPublic();
		self::NovelPublicNovel();
		self::DraftPublicLabel();
		self::NovelPublicChapterTypeLabel();
		
		$v = C('page.data');
		$id = C('page.id');
		//草稿的url地址
		C('page.url','author_novel_draftedit');
		$pageWhere = '';

		if ( C('page.page') > 0 )
		{
			$pageWhere .= 'page='.C('page.page').';';
		}
		tpl::Rep( array('{草稿列表:'=>'{草稿列表:draft_author_id='.author::GetUid().';draft_module=novel;draft_cid='.$id.';'.$pageWhere) , null , '2' );
		$repFun['a']['authorlabel'] = 'PublicDraft';
		tpl::Label('{草稿列表:[s]}[a]{/草稿列表}','draft', self::$CF, $repFun['a']);

		
		$whereLabel = 'volume_nid=[lin->0,'.$id.'];排序=volume_order,volume_id asc;';
		tpl::Rep( array('{小说分卷列表:'=>'{小说分卷列表:'.$whereLabel) , null , '2' );
		$repFun['n']['novellabel'] = 'PublicVolume';
		tpl::Label('{小说分卷列表:[s]}[a]{/小说分卷列表}','volume', novellabel::$CF, $repFun['n']);
		
		$arr = array(
			'隐藏表单'=>'<input type="hidden" value="" id="did" name="did"><input type="hidden" value="novel" id="module" name="module"><input type="hidden" value="'.$id.'" id="contentid" name="contentid">',
		);
		tpl::rep($arr);
		
	}
	

	/**
	 * 小说草稿编辑
	 */
	static function NovelDraftEditLabel()
	{
		self::NovelPublicNovel();
		self::NovelPublic();
		self::DraftPublicLabel();
		self::NovelPublicChapterTypeLabel();
		
		$data = C('page.data');
		$option = unserialize(GetKey($data,'draft_option'));
		if( !isset($option['pay']) )
		{
			$option['pay'] = $data['novel_sell'];
		}
		if( !isset($option['vid']) )
		{
			$option['vid'] = 1;
		}
		$id = C('page.id');
		$did = C('page.did');
		
		$whereLabel = 'volume_nid=[lin->0,'.$id.'];排序=volume_order desc,volume_id desc;';
		tpl::Rep( array('{小说分卷列表:'=>'{小说分卷列表:'.$whereLabel) , null , '2' );
		$repFun['novellabel'] = 'PublicVolume';
		tpl::Label('{小说分卷列表:[s]}[a]{/小说分卷列表}','volume', novellabel::$CF, $repFun);

		$arr = array(
			'表单提交地址'=>common::GetUrl('author.draftedit'),
			'did'=>$did,
			'分卷id'=>$option['vid'],
			'是否上架'=>$option['pay'],
			'隐藏表单'=>'<input type="hidden" value="'.$did.'" id="did" name="did"><input type="hidden" value="novel" id="module" name="module"><input type="hidden" value="'.$id.'" id="contentid" name="contentid">',
		);
		tpl::rep($arr);
	}
	


	/**
	 * 小说分卷列表
	 */
	static function NovelVolumeListLabel()
	{
		self::NovelPublic();
		self::NovelPublicNovel();
	
		$v = C('page.data');
		$id = C('page.id');

		//设置回调标签
		C('page.callback_label',array('authorlabel','NovelVolumeListCallBackLabel'));
		
		$whereLabel = 'page='.C('page.page').';volume_nid='.$id.';排序=volume_order desc,volume_id asc;';
		tpl::Rep( array('{小说分卷列表:'=>'{小说分卷列表:'.$whereLabel) , null , '2' );
		$repFun['novellabel'] = 'PublicVolume';
		tpl::Label('{小说分卷列表:[s]}[a]{/小说分卷列表}','volume', novellabel::$CF, $repFun);

		$arr = array(
			'表单提交地址'=>common::GetUrl('author.novel_volumeedit'),
			'隐藏表单'=>'<input type="hidden" value="" id="vid" name="vid"><input type="hidden" value="'.$id.'" id="nid" name="nid">',
		);
		tpl::rep($arr);
		
		//清空回调标签
		C('page.callback_label','delete');
	}
	/**
	 * 小说分卷列表页的回调标签
	 * @param 参数1，必须，数据
	 */
	static function NovelVolumeListCallBackLabel($v)
	{
		$arr = array(
			'vurl'=>tpl::url('author_novel_volumeedit',array('nid'=>C('page.id'),'vid'=>$v['volume_id'])),
			'删除小说分卷'=>common::GetUrl('author.novel_volumedel' , array('nid'=>C('page.id'),'vid'=>$v['volume_id']) ),
		);
		return $arr;
	}
	
	/**
	 * 小说分卷编辑
	 */
	static function NovelVolumeEditLabel()
	{
		self::NovelPublicNovel();
		self::NovelPublic();
	
		$data = C('page.data');
		$id = C('page.id');
		$vid = C('page.vid');
		if( $vid == 0)
		{
			$data['volume_order'] = 0;
		}
		
		$arr = array(
			'表单提交地址'=>common::GetUrl('author.novel_volumeedit'),
			'删除分卷'=>common::GetUrl('author.novel_volumedel' , array('nid'=>$id,'vid'=>$vid) ),
			'分卷名字'=>GetKey($data,'volume_name'),
			'分卷顺序'=>GetKey($data,'volume_order'),
			'分卷简介'=>GetKey($data,'volume_desc'),
			'vid'=>$vid,
			'隐藏表单'=>'<input type="hidden" value="'.$vid.'" id="vid" name="vid"><input type="hidden" value="'.$id.'" id="nid" name="nid">',
		);
		tpl::rep($arr);
	}
	
	/**
	 * 小说章节列表
	 */
	static function NovelChapterListLabel()
	{
		self::NovelPublic();
		self::NovelPublicNovel();
		self::NovelPublicChapterTypeLabel();
		
		$v = C('page.data');
		$id = C('page.id');

		//设置回调标签
		C('page.callback_label',array('authorlabel','NovelVolumeListCallBackLabel'));
		$whereLabel = 'page='.C('page.page').';volume_nid=[lin->0,'.$id.'];;排序=volume_order desc,volume_id asc;';
		tpl::Rep( array('{小说分卷列表:'=>'{小说分卷列表:'.$whereLabel) , null , '2' );
		$repFun['v']['novellabel'] = 'PublicVolume';
		tpl::Label('{小说分卷列表:[s]}[a]{/小说分卷列表}','volume', novellabel::$CF, $repFun['v']);

		//清空回调标签
		C('page.callback_label','delete');
		$whereLabel = 'page='.C('page.page').';chapter_nid='.$id.';排序=chapter_order;';
		tpl::Rep( array('{小说章节列表:'=>'{小说章节列表:'.$whereLabel) , null , '2' );
		$repFun['c']['novellabel'] = 'PublicChapter';
		tpl::Label('{小说章节列表:[s]}[a]{/小说章节列表}','chapter', novellabel::$CF, $repFun['c']);
	}


	/**
	 * 小说收入列表
	 */
	static function NovelIncomeListLabel()
	{
		$type = C('page.type');
		$page = C('page.page');
		//小说列表标签
		self::NovelNovelListLabel();
		
		//设置回调标签
		C('page.callback_label',array('authorlabel','NovelInconmeListCallBackLabel'));
		
		//结算模式二
		if( C('author_income_type',null,'authorConfig')=='2')
		{
		    $dataClass = 'novel_finance_report';
	        $moduleField = 'report_module';
	        $userField = 'report_user_id';
	        $typeField = 'report_type';
		}
		//实时到账模式一
		else
		{
	        $dataClass = 'novel_finance_log';
	        $moduleField = 'log_module';
	        $userField = 'log_user_id';
	        $typeField = 'log_type';
		}
		
		//设置where条件
		$where = $moduleField.'=novel;'.$userField.'='.user::GetUid().';';
		if ( $page > 0 )
		{
			$where .= 'page='.$page.';';
		}
		$logMod = NewModel('user.finance_log');
		if( $type == 'all' )
		{
			$where .= $typeField.'=[lin->'.$logMod->GetLogType('novel').'];';
		}
		else
		{
			$where .= $typeField.'=[lin->'.$logMod->GetLogType('novel' , $type , 1).'];';
		}
		tpl::Rep( array('{收入列表:'=>'{收入列表:'.$where) , null , '2' );
	
		$repFun['a']['authorlabel'] = 'PublicIncomeLabel';
		tpl::Label('{收入列表:[s]}[a]{/收入列表}',$dataClass, authorlabel::$CF, $repFun['a']);

		//清空回调标签
		C('page.callback_label','delete');
	}
	
	/**
	 * 小说收入回调标签
	 * @param 参数1，内容数组
	 */
	static function NovelInconmeListCallBackLabel($data)
	{
		$arr = array(
			'收入作品'=>$data['novel_name'],
		);
		return $arr;
	}


	/**
	 * 文章投稿管理的公共url
	 */
	static function ArticlePublic()
	{
		//不检查文章审核状态
		C('page.novel_check_status',0);
	
		$arr = array(
			'文章草稿箱列表'=>tpl::url('author_article_draftlist',array('page'=>1)),
		);
		tpl::Rep($arr);
	}

	/**
	 * 公共文章分类标签
	 */
	static function ArticlePublicArticleTypeLabel($tid)
	{
		$typeMod = NewModel('article.type');
		$data = $typeMod->GetById( $tid );
		if( $data['type_pid'] != '0' )
		{
			$pid = '0,'.$data['type_pid'];
			$tid = $data['type_pid'].','.$data['type_id'];
	
			$pidArr = explode(',', $pid);
			$tidArr = explode(',', $tid);
			foreach ($pidArr as $k=>$v)
			{
				$arr[] = array('topid'=>$v,'typeid'=>$tidArr[$k]);
			}
		}
		else
		{
			$arr[] = array('topid'=>0,'typeid'=>0);
		}
	
		$repFun = array('authorlabel'=>'ArticlePublicArticleType');
		tpl::Label('{文章分类列表:[s]}[a]{/文章分类列表}',$arr, null , $repFun);
	}
	
	/**
	 * 公共小说分类
	 * @param 参数1，参数
	 * @param 参数2，标签模版
	 */
	static function ArticlePublicArticleType($data,$blcode)
	{
		$code = '';
		$i = 1;
		//循环数据
		foreach ($data as $k => $v)
		{
			//每次循环重新调取原始标签
			$lcode = $blcode;
	
			//设置自定义中文标签
			$arr=array(
				'i'=>$i,
				'topid'=>$v['topid'],
				'typeid'=>$v['typeid'],
			);
			//替换标签
			$code.=tpl::rep($arr,$lcode);
			$i++;
		}
	
		//返回最后的结果
		return $code;
	}
	

	/**
	 * 文章投稿列表
	 */
	static function ArticleArticleListLabel()
	{
		self::ArticlePublic();
		//不检查小说状态
		C('page.article_check_status',0);
		//设置回调标签
		C('page.callback_label',array('authorlabel','ArticleArticleListCallBackLabel'));
	
		//只显示自己的小说
		$where = 'article_author_id='.author::GetUid().';';
		if ( C('page.page') > 0 )
		{
			$where .= 'page='.C('page.page').';';
		}
		tpl::Rep( array('{文章列表:'=>'{文章列表:'.$where) , null , '2' );
	
		$repFun['a']['articlelabel'] = 'PublicArticle';
		tpl::Label('{文章列表:[s]}[a]{/文章列表}','content', articlelabel::$CF, $repFun['a']);
	
		//清空设置回调标签
		C('page.callback_label','delete');
	}
	/**
	 * 文章投稿列表页的回调标签
	 * @param 参数1，必须，数据
	 */
	static function ArticleArticleListCallBackLabel($v)
	{
		$arr = array(
			'eurl'=>tpl::url('author_article_articleedit',array('id'=>$v['article_id'])),
		);
		return $arr;
	}
	
	/**
	 * 文章草稿列表
	 */
	static function ArticleDraftListLabel()
	{
		$v = C('page.data');
		//草稿的url地址
		C('page.url','author_article_draftedit');
		$where = '';
		if ( C('page.page') > 0 )
		{
			$where .= 'page='.C('page.page').';';
		}
		tpl::Rep( array('{草稿列表:'=>'{草稿列表:draft_author_id='.author::GetUid().';draft_module=article;'.$where) , null , '2' );
		$repFun['a']['authorlabel'] = 'PublicDraft';
		tpl::Label('{草稿列表:[s]}[a]{/草稿列表}','draft', self::$CF, $repFun['a']);
	}
	
	/**
	 * 文章投稿草稿编辑
	 */
	static function ArticleDraftEditLabel()
	{
		self::ArticlePublic();
		self::DraftPublicLabel();

		$v = C('page.data');
		$did = C('page.did');
		$option = unserialize(GetKey($v,'draft_option'));
		if( $did == 0 )
		{
			$sourceMod = NewModel('article.source');
			$option['source'] = $sourceMod->GetSource();
		}
		else
		{
			self::ArticlePublicArticleTypeLabel($option['tid']);
		}
		
		
		$arr = array(
			'表单提交地址'=>common::GetUrl('author.draftedit'),
			'did'=>$did,
			'tid'=>C('tid',null,$option),
			'短标题'=>C('cname',null,$option),
			'缩略图'=>article::GetSimg(C('simg',null,$option)),
			'来源'=>C('source',null,$option),
			'简介'=>C('info',null,$option),
			'标签'=>C('tags',null,$option),
			'隐藏表单'=>'<input type="hidden" value="'.$did.'" id="did" name="did"><input type="hidden" value="article" id="module" name="module"><input type="hidden" value="0" name="contentid">',
		);
		tpl::rep($arr);
	}
	


	/**
	 * 文章投稿草稿编辑
	 */
	static function ArticleArticleEditLabel()
	{
		$id = C('page.id');
		$data = C('page.data');
		
		self::ArticlePublic();
		self::ArticlePublicArticleTypeLabel($data['type_id']);

		$arr = array(
			'表单提交地址'=>common::GetUrl('author.edit'),
			'id'=>$id,
			'tid'=>$data['type_id'],
			'标题'=>$data['article_name'],
			'内容'=>$data['article_content'],
			'短标题'=>$data['article_cname'],
			'缩略图'=>$data['article_simg'],
			'来源'=>$data['article_source'],
			'简介'=>$data['article_info'],
			'标签'=>$data['article_tags'],
			'隐藏表单'=>'<input type="hidden" value="'.$id.'" id="id" name="id">',
		);
		tpl::rep($arr);

		self::DraftPublicLabel();
	}

	
	/**
	 * 作者首页标签替换
	 **/
	static function AuthorLabel()
	{
		$data = C('page.data');
		$arr = array(
			'id'=>$data['author_id'],
			'uid'=>$data['user_id'],
			'笔名'=>$data['author_nickname'],
			'简介'=>$data['author_info'],
			'公告'=>$data['author_notice'],
			'时间'=>$data['author_time'],
			'昵称'=>$data['user_nickname'],
			'头像'=>$data['user_head'],
		);
		tpl::Rep($arr);
		self::AuthorPublicLabel($data['author_id']);
	}
	
	/**
	 * 数据统计页 
	 **/
	static function NovelStatisticsLabel()
	{
	    self::NovelNovelListLabel();
	}
}
?>
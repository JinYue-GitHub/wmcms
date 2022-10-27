<?php
/**
* 论坛标签处理类
*
* @version        $Id: bbs.label.php 2015年12月18日 16:00  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2016年1月25日 11:51 weimeng
*
*/
class bbslabel extends bbs
{
	static public $lcode;
	static public $data;
	static public $CF = array('bbs'=>'GetData');

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
		$tid = str::Int( C('page.data.type_id') , null ,  0);
		$bid = str::Int( C('page.data.bbs_id') , null ,  0);

		$arr = array(
			'论坛首页'=>tpl::url('bbs_index'),
			'论坛版块'=>tpl::url('bbs_type'),
			'论坛提交地址'=>'/wmcms/action/index.php?action=bbs.post',
			'发表帖子'=>tpl::url('bbs_post' , array('tid'=>$tid,'bid'=>0)),
		);
		tpl::Rep($arr);
	}
	
	
	/**
	* 关于信息标签公共标签替换
	**/
	static function PublicLabel()
	{
		$arr = array(
			'发帖验证码id'=>'code_bbs_post',
			'发帖验证码类型'=>C('config.web.code_bbs_post_type'),
			'回帖验证码id'=>'code_bbs_replay',
			'回帖验证码类型'=>C('config.web.code_bbs_replay_type'),
		);
		if( str::in_string('{发帖验证码}',tpl::$tempCode) )
		{
			$arr['发帖验证码'] = FormCodeCreate('code_bbs_post');
		}
		if( str::in_string('{回帖验证码}',tpl::$tempCode) )
		{
			$arr['回帖验证码'] = FormCodeCreate('code_bbs_replay');
		}
		tpl::Rep($arr);
		
		//注册验证码、登录验证码、修改密码验证码、找回密码验证码
		tpl::IfRep( C('config.web.code_bbs_post') , '=' , 1 , '发帖验证码开启' , '发帖验证码关闭');
		tpl::IfRep( C('config.web.code_bbs_replay') , '=' , 1 , '回帖验证码开启' , '回帖验证码关闭');
		
		//数组键：类名，值：方法名
		$repFun['a']['bbslabel'] = 'PublicType';
		tpl::Label('{版块列表:[s]}[a]{/版块列表}','type', self::$CF, $repFun['a']);

		$repFun['t']['bbslabel'] = 'PublicType';
		tpl::Label('{版块分类:[s]}[a]{/版块分类}','type', self::$CF , $repFun['t']);
		
		$repFun['a']['bbslabel'] = 'PublicBbs';
		tpl::Label('{帖子:[s]}[a]{/帖子}','content', self::$CF , $repFun['a']);
	}

	
	/**
	 * 公共标签替换
	 * @param 参数1，数组，需要进行操作的数组
	 * @param 参数2，字符串，需要进行替换的字符串，原始标签代码。
	 **/
	static function PublicType($data,$blcode){
		$code = $EL = '';
		$i = 1;
		//判断当前标签的名字是
		if ( C('label.name') == '版块列表' )
		{
			$EL = '版块列表=>';
		}
		//循环数据
		foreach ($data as $k => $v)
		{
			//没组数据循环，以字段名为标签名
			foreach ($v as $key => $val)
			{
				$arr1[$EL.L.$key]=$v[$key];
			}
			//每次循环重新调取原始标签
			$lcode = $blcode;
			
			//计数器标签和选中标签替换
			$lcode = tpl::I( $lcode , $i );
			$lcode = tpl::Cur( $v['type_id'] , C('page.tid') , $lcode );
			//分割每条数据的字符串
			$lcode = tpl::Segmentation(count($data), $i, $lcode);
			$lcode = tpl::Segmentation(count($data), $i, $lcode , '版块分类分隔符');
			
			//显示固定字数标签
			$nameArr = tpl::Exp('{'.$EL.'版块分类名字:[d]}' , $v['type_name'] , $lcode);

			//设置自定义中文标签
			$arr2=array(
				$EL.'i'=>$i,
				$EL.'url'=>tpl::url('bbs_list',array('page'=>1,'tid'=>$v['type_id'],'tpinyin'=>$v['type_pinyin'])),
				$EL.'turl'=>tpl::url('bbs_type',array('tid'=>$v['type_id'],'tpinyin'=>$v['type_pinyin'])),
				$EL.'lurl'=>tpl::url('bbs_list',array('page'=>1,'tid'=>$v['type_id'],'tpinyin'=>$v['type_pinyin'])),
				$EL.'版块分类id'=>$v['type_id'],
				$EL.'版块分类名字'=>$v['type_name'],
				$EL.'版块分类名字:'.GetKey($nameArr,'0')=>GetKey($nameArr,'1'),
				$EL.'版块分类简称'=>$v['type_cname'],
				$EL.'版块分类拼音'=>$v['type_pinyin'],
				$EL.'版块分类简介'=>$v['type_info'],
				$EL.'版块分类排序'=>$v['type_order'],
				$EL.'版块分类标题'=>$v['type_title'],
				$EL.'版块分类关键词'=>$v['type_key'],
				$EL.'版块分类描述'=>$v['type_info'],
				$EL.'版块分类图标'=>$v['type_ico'],
				$EL.'版块分类最后发帖'=>$v['type_last_post'],
				$EL.'版块分类最后回帖'=>$v['type_last_replay'],
				$EL.'版块分类总发帖'=>$v['type_sum_post'],
				$EL.'版块分类总回帖'=>$v['type_sum_replay'],
				$EL.'版块分类总浏览'=>$v['type_sum_read'],
				$EL.'版块分类日发帖'=>$v['type_today_post'],
				$EL.'版块分类日回帖'=>$v['type_today_replay'],
				$EL.'版块分类日浏览'=>$v['type_today_read'],
			);
			//合并两组标签
			$arr = RepField($arr2,$arr1, $v,1,'版块分类');
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
	static function PublicBbs($data,$blcode)
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
			$lcode = tpl::Cur( $v['bbs_id'] , C('page.bid') , $lcode );
			//分割每条数据的字符串
			$lcode = tpl::Segmentation(count($data), $i, $lcode);
			$lcode = tpl::Segmentation(count($data), $i, $lcode , '帖子分隔符');

			$lcode = tpl::IfRep( $v['bbs_status'] , '=' , 0 , '等待审核', '已经审核' , $lcode );
			
			//显示固定字数标签
			$contentArr = tpl::Exp('{帖子内容:[d]}' , $v['bbs_content'] , $lcode);
			
			//匹配自定义时间标签
			$time = tpl::Tag('{帖子发布时间:[s]}',$lcode);
			$reTime = tpl::Tag('{帖子最后回帖时间:[s]}',$lcode);
			
			//自由标签替换
			$lcode = parent::GetAttr( '推荐' , $v['bbs_rec'] , $lcode );
			$lcode = parent::GetAttr( '精华' , $v['bbs_es'] , $lcode );
			$lcode = parent::GetAttr( '置顶状态' , $v['bbs_top'] , $lcode );
			$lcode = parent::GetAttr( '帖子状态' , $v['bbs_status'] , $lcode );
			$lcode = parent::GetAttr( '全站置顶' , $v['bbs_top'] , $lcode , 1 );
			$lcode = parent::GetAttr( '分类置顶' , $v['bbs_top'] , $lcode , 2 );
			$lcode = parent::GetAttr( '当前置顶' , $v['bbs_top'] , $lcode , 3 );
			$lcode = parent::GetAttr( '有图' , $v['bbs_simg'] , $lcode );
			
			//设置自定义中文标签
			$arr2=array(
				'i'=>$i,
				'url'=>tpl::url( 'bbs_bbs' , array('page'=>1,'bid'=>$v['bbs_id'],'tid'=>$v['type_id'],'tpinyin'=>$v['type_pinyin']) ),
				'burl'=>tpl::url( 'bbs_bbs' , array('page'=>1,'bid'=>$v['bbs_id'],'tid'=>$v['type_id'],'tpinyin'=>$v['type_pinyin']) ),
				'turl'=>tpl::url( 'bbs_type' , array('tid'=>$v['type_id'],'tpinyin'=>$v['type_pinyin']) ),
				'lurl'=>tpl::url('bbs_list',array('page'=>1,'tid'=>$v['type_id'],'tpinyin'=>$v['type_pinyin'])),
				'帖子id'=>$v['bbs_id'],
				'帖子标题'=>$v['bbs_title'],
				'帖子内容'=>$v['bbs_content'],
				'帖子缩略图'=>$v['bbs_simg'],
				'帖子作者id'=>$v['user_id'],
				'帖子作者昵称'=>$v['user_nickname'],
				'帖子作者签名'=>$v['user_sign'],
				'帖子作者头像'=>$v['user_head'],
				'帖子作者性别'=>user::GetSex($v['user_sex']),
				'帖子作者性别'=>$v['user_sex'],
				'帖子标签'=>$v['bbs_tags'],
				'帖子最后回帖用户昵称'=>$v['bbs_replay_nickname'],
				'帖子最后回帖用户id'=>$v['bbs_replay_uid'],
					
				'帖子回帖量'=>$v['bbs_replay'],
				'帖子浏览量'=>$v['bbs_read'],
				'帖子顶'=>$v['bbs_ding'],
				'帖子踩'=>$v['bbs_cai'],
				'帖子评分'=>$v['bbs_score'],
					
				'帖子发布时间'=>date("Y-m-d H:i:s",$v['bbs_time']),
				'帖子最后回帖时间'=>date("Y-m-d H:i:s",$v['bbs_replay_time']),
				'up:年'=>date("Y",$v['bbs_time']),
				'up:月'=>date("m",$v['bbs_time']),
				'up:日'=>date("d",$v['bbs_time']),
				'up:时'=>date("H",$v['bbs_time']),
				'up:分'=>date("i",$v['bbs_time']),
				'up:秒'=>date("s",$v['bbs_time']),

				'帖子版块id'=>$v['type_id'],
				'帖子版块名字'=>$v['type_name'],
				'帖子版块简称'=>$v['type_cname'],
				'帖子版块拼音'=>$v['type_pinyin'],
				'帖子版块简介'=>$v['type_info'],
				'帖子版块排序'=>$v['type_order'],
				'帖子版块标题'=>$v['type_title'],
				'帖子版块关键词'=>$v['type_key'],
				'帖子版块描述'=>$v['type_info'],
				'帖子版块图标'=>$v['type_ico'],
				'帖子版块最后发帖'=>$v['type_last_post'],
				'帖子版块最后回帖'=>$v['type_last_replay'],
				'帖子版块总发帖'=>$v['type_sum_post'],
				'帖子版块总回帖'=>$v['type_sum_replay'],
				'帖子版块总浏览'=>$v['type_sum_read'],
				'帖子版块日发帖'=>$v['type_today_post'],
				'帖子版块日回帖'=>$v['type_today_replay'],
				'帖子版块日浏览'=>$v['type_today_read'],
					
				'帖子内容:'.GetKey($contentArr,'0')=>GetKey($contentArr,'1'),
				'帖子发布时间:'.GetKey($time,'1,0')=>tpl::Time(GetKey($time,'1,0'), $v['bbs_time']),
				'帖子最后回帖时间:'.GetKey($reTime,'1,0')=>tpl::Time(GetKey($reTime,'1,0'), $v['bbs_replay_time']),
			);
			
			//合并两组标签
			$arr = RepField($arr2,$arr1, $v,2,'帖子');
			//替换标签
			$code.=tpl::rep($arr,$lcode);
			$i++;
		}
		//返回最后的结果
		return $code;
	}
	
	
	/**
	 * 版块分类的标签替换
	 */
	static function PublicTypeArr()
	{
		$data = C('page.data');
		if( $data['type_id'] == '0' )
		{
			$typeMod = NewModel('bbs.type');
			$list = $typeMod->GetList();
			if( $list )
			{
				//总数据和日数据
				$sumPost = $sumReplay = $sumRead = $todayPost = $todayReplay = $todayRead = 0;
				//最后发帖回帖
				$lastPost = $lastReplay = 0;
				foreach ($list as $k=>$v)
				{
					$sumPost += $v['type_sum_post'];
					$sumReplay += $v['type_sum_replay'];
					$sumRead += $v['type_sum_read'];
					$todayPost += $v['type_today_post'];
					$todayReplay += $v['type_today_replay'];
					$todayRead += $v['type_today_read'];
					if( $v['type_last_post'] > $lastPost)
					{
						$lastPost = $v['type_last_post'];
					}
					if( $v['type_last_replay'] > $lastReplay)
					{
						$lastReplay = $v['type_last_replay'];
					}
				}
				$data['type_sum_post'] = $sumPost;
				$data['type_sum_replay'] = $sumReplay;
				$data['type_sum_read'] = $sumRead;
				$data['type_today_post'] = $todayPost;
				$data['type_today_replay'] = $todayReplay;
				$data['type_today_read'] = $todayRead;
				$data['type_last_post'] = $lastPost;
				$data['type_last_replay'] = $lastReplay;
			}
		}
		
		$arr2 = array(
			'版块链接'=>tpl::url('bbs_type',array('tid'=>GetKey($data,'type_id'),'tpinyin'=>GetKey($data,'type_pinyin'))),
			'版块id'=>$data['type_id'],
			'版块名字'=>GetKey($data,'type_name'),
			'版块简称'=>GetKey($data,'type_cname'),
			'版块拼音'=>GetKey($data,'type_pinyin'),
			'版块简介'=>GetKey($data,'type_info'),
			'版块排序'=>GetKey($data,'type_order'),
			'版块标题'=>GetKey($data,'type_title'),
			'版块关键词'=>GetKey($data,'type_key'),
			'版块描述'=>GetKey($data,'type_info'),
			'版块图标'=>GetKey($data,'type_ico'),
			'版块最后发帖'=>GetKey($data,'type_last_post'),
			'版块最后回帖'=>GetKey($data,'type_last_replay'),
			'版块总发帖'=>GetKey($data,'type_sum_post'),
			'版块总回帖'=>GetKey($data,'type_sum_replay'),
			'版块总浏览'=>GetKey($data,'type_sum_read'),
			'版块日发帖'=>GetKey($data,'type_today_post'),
			'版块日回帖'=>GetKey($data,'type_today_replay'),
			'版块日浏览'=>GetKey($data,'type_today_read'),
		);
		//替换自定义字段
		$arr = RepField($arr2,null,$data);
		tpl::Rep($arr);
	}
	
	



	/**
	 * 公共版主标签替换
	 * @param 参数1，数组，需要进行操作的数组
	 * @param 参数2，字符串，需要进行替换的标签
	 **/
	static function PublicModerator($data,$blcode)
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
				'版主id'=>$v['user_id'],
				'版主资料'=>tpl::url( 'user_fhome' , array('uid'=>$v['user_id']) ),
				'版主昵称'=>$v['user_nickname'],
				'版主头像'=>$v['user_head'],
				'版主签名'=>$v['user_sign'],
				'版主性别'=>user::GetSex($v['user_sex']),
				'版主性别码'=>$v['user_sex'],
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
	* 版块分类页标签替换
	**/
	static function TypeLabel()
	{
		self::PublicTypeArr();
	}
	
	
	/**
	 * 列表页条件替换
	 * 给文章列表标签加上各种限制条件。
	 **/
	static function ListLabel()
	{
		self::PublicTypeArr();
		
		$pageWhere = $tidWhere = '';
		//获得当前页面的一些条件
		if ( C('page.tid') > 0 )
		{
			//$tidWhere = 'tid='.C('page.tid').';';
			$tidWhere = 'type_pid=[and-or->rin:'.C('page.tid').'||t.type_id:'.C('page.tid').'];';
		}
		if ( C('page.page') > 0 )
		{
			$pageWhere = 'page='.C('page.page').';';
		}
	
		
		//置顶主题标签,大于第一页就不显示置顶的帖子
		if ( C('page.page') > 1 )
		{
			tpl::Rep(array('{置顶帖子:[a]{/置顶帖子}'=>''),null,3);
		}
		else
		{
			//第一页就显示置顶的帖子
			$repFun['a']['bbslabel'] = 'PublicBbs';
			tpl::Label('{置顶帖子:[s]}[a]{/置顶帖子}','topcontent', self::$CF , $repFun['a']);
		}
		

		//帖子列表标签
		$bbsWhere = $pageWhere.$tidWhere;
		tpl::Rep( array('{帖子列表:'=>'{帖子列表:'.$bbsWhere) , null , '2' );

		$repFun['a']['bbslabel'] = 'PublicBbs';
		tpl::Label('{帖子列表:[s]}[a]{/帖子列表}','content', self::$CF , $repFun['a']);
		
		
		//版主列表查询
		$moderator = parent::GetData('moderator','type_id=[lin->'.C('page.data.type_id').','.C('page.data.type_pid').']');
		//有版主数据
		if ( is_array($moderator) )
		{
			//把数组换成字符串条件
			$moderatorWhere = str::ArrToStr($moderator,',',null,null,'user_id');
			tpl::Rep( array('{版主列表:'=>'{版主列表:user_id=[lin->'.$moderatorWhere.'];') , null , '2' );
		}
		//没有版主
		else
		{
			tpl::Rep( array('{版主列表:'=>'{版主列表:user_id=-999;') , null , '2' );
		}
		//查询版主替换标签
		$repFun['a']['bbslabel'] = 'PublicModerator';
		tpl::Label('{版主列表:[s]}[a]{/版主列表}','user', array('user'=>'GetData') , $repFun['a']);
	}
	
	
	/**
	* 帖子页标签替换
	**/
	static function BbsLabel()
	{
		$v = C('page.data');
		if ( $v )
		{
			//大于第一页就不显示帖子信息
			if ( C('page.page') > 1 )
			{
				tpl::Rep(array('{帖子信息}[a]{/帖子信息}'=>''),null,3);
			}
			else
			{
				tpl::Rep( array('帖子信息'=>'','/帖子信息'=>''));
			}
			//权限检测
			$isLogin = $v['bbs_islogin']==0||$v['bbs_islogin']==1&&user::GetUid()>0;
			$isSelf = $v['bbs_isself']==0||$v['bbs_isself']==1&&user::GetUid()==$v['user_id'];
			if( !$isLogin || !$isSelf )
			{
				$v['bbs_content'] = '浏览权限不足';
			}
		
			//自由标签替换
			tpl::IfRep( $v['bbs_status'] , '=' , 0 , '待审核', '已审核' );
			parent::GetAttr( '荐' , $v['bbs_rec'] );
			parent::GetAttr( '精' , $v['bbs_es'] );
			parent::GetAttr( '置顶' , $v['bbs_top'] );
			parent::GetAttr( '状态' , $v['bbs_status'] );
			parent::GetAttr( '全站' , $v['bbs_top'] ,null, 1 );
			parent::GetAttr( '分类' , $v['bbs_top'] ,null, 2 );
			parent::GetAttr( '当前' , $v['bbs_top'] ,null, 3 );
			
			//版块分类标签
			self::PublicTypeArr();
	
			//显示固定字数标签
			$contentArr = tpl::Exp('{内容:[d]}' , $v['bbs_content'] );
			$time = tpl::Tag('{发布时间:[s]}');
			$reTime = tpl::Tag('{最后回帖时间:[s]}');
				
			//设置自定义中文标签
			$arr=array(
				'帖子列表'=>tpl::url('bbs_list',array('page'=>1,'tid'=>$v['type_id'],'tpinyin'=>$v['type_pinyin'])),
				'帖子内容'=>tpl::url('bbs_bbs',array('page'=>1,'bid'=>$v['bbs_id'],'tid'=>$v['type_id'],'tpinyin'=>$v['type_pinyin'])),
				'作者空间'=>tpl::url('user_fhome',array('uid'=>$v['user_id'])),
				'id'=>$v['bbs_id'],
				'标题'=>$v['bbs_title'],
				'版块'=>$v['type_name'],
				'内容'=>$v['bbs_content'],
				'缩略图'=>$v['bbs_simg'],
				'作者id'=>$v['user_id'],
				'作者昵称'=>$v['user_nickname'],
				'作者签名'=>$v['user_sign'],
				'作者头像'=>$v['user_head'],
				'作者性别'=>user::GetSex($v['user_sex']),
				'作者性别码'=>$v['user_sex'],
				'标签'=>$v['bbs_tags'],
				'最后回帖用户昵称'=>$v['bbs_replay_nickname'],
				'最后回帖用户id'=>$v['bbs_replay_uid'],
				'回帖量'=>$v['bbs_replay'],
				'浏览量'=>$v['bbs_read'],
				'顶'=>$v['bbs_ding'],
				'踩'=>$v['bbs_cai'],
				'评分'=>$v['bbs_score'],
				'发布时间'=>date("Y-m-d H:i:s",$v['bbs_time']),
				'最后回帖时间'=>date("Y-m-d H:i:s",$v['bbs_replay_time']),
				'内容:'.GetKey($contentArr,'0')=>GetKey($contentArr,'1'),
				'发布时间:'.GetKey($time,'1,0')=>tpl::Time(GetKey($time,'1,0'), $v['bbs_time']),
				'最后回帖时间:'.GetKey($reTime,'1,0')=>tpl::Time(GetKey($reTime,'1,0'), $v['bbs_replay_time']),
				'管理操作模版'=>file::GetFile(WMTEMPLATE.'system/bbs_manager.html'),
			);
			tpl::Rep($arr);
	
			//管理权限判断
			if( C('page.edit') > 0 )
			{
				$url = '/wmcms/action/index.php?action=bbs.attr&key={type}&bid='.$v['bbs_id'].'&val={val}';
				//审核操作
				if( $v['bbs_status'] == '0' )
				{
					$managerStatusUrl = strtr($url,array('{type}'=>'status','{val}'=>'1'));
					$managerStatusTitle='通过审核';
				}else{
					$managerStatusUrl = strtr($url,array('{type}'=>'status','{val}'=>'0'));
					$managerStatusTitle='取消审核';
				}
				
				//推荐操作
				if( $v['bbs_rec'] == '0' )
				{
					$managerRecUrl = strtr($url,array('{type}'=>'rec','{val}'=>'1'));
					$managerRecTitle='设为推荐';
				}else{
					$managerRecUrl = strtr($url,array('{type}'=>'rec','{val}'=>'0'));
					$managerRecTitle='取消推荐';
				}
				
				//精华操作
				if( $v['bbs_es'] == '0' )
				{
					$managerEsUrl = strtr($url,array('{type}'=>'es','{val}'=>'1'));
					$managerEsTitle='设为精华';
				}else{
					$managerEsUrl = strtr($url,array('{type}'=>'es','{val}'=>'0'));
					$managerEsTitle='取消精华';
				}
				
				//置顶操作
				$managerTop1Url = strtr($url,array('{type}'=>'top','{val}'=>'1'));
				$managerTop2Url = strtr($url,array('{type}'=>'top','{val}'=>'2'));
				$managerTop3Url = strtr($url,array('{type}'=>'top','{val}'=>'3'));
				$managerTop1Title='全站置顶';
				$managerTop2Title='分类置顶';
				$managerTop3Title='当前置顶';
				switch ($v['bbs_top'])
				{
					case '1':
						$managerTop1Url = strtr($url,array('{type}'=>'top','{val}'=>'0'));
						$managerTop1Title='取消全站';
						break;
					case '2':
						$managerTop2Url = strtr($url,array('{type}'=>'top','{val}'=>'0'));
						$managerTop2Title='取消分类';
						break;
					case '3':
						$managerTop3Url = strtr($url,array('{type}'=>'top','{val}'=>'0'));
						$managerTop3Title='取消当前';
						break;
				}
	
				//替换版主操作标签
				//如果是作者自己就显示他的权限
				if( C('page.edit') == 1 )
				{
					tpl::Rep( array('{版主操作}[a]{/版主操作}'=>'') , null , 3);
				}
				//如果是版主管理
				else
				{
					tpl::Rep( array('版主操作'=>'','/版主操作'=>''));
				}
				
				//替换操作标签
				$arr = array(
					'帖子管理'=>'',
					'/帖子管理'=>'',
					'修改帖子'=>tpl::Url('bbs_post' , array('tpinyin'=>$v['type_pinyin'],'tid'=>$v['type_id'],'bid'=>$v['bbs_id'])),
					'删除帖子'=>'/wmcms/action/index.php?action=bbs.del&bid='.$v['bbs_id'],
					'审核帖子'=>$managerStatusUrl,
					'审核帖子标题'=>$managerStatusTitle,
					'推荐帖子'=>$managerRecUrl,
					'推荐帖子标题'=>$managerRecTitle,
					'设为精华'=>$managerEsUrl,
					'设为精华标题'=>$managerEsTitle,
					'全局置顶'=>$managerTop1Url,
					'全局置顶标题'=>$managerTop1Title,
					'分类置顶'=>$managerTop2Url,
					'分类置顶标题'=>$managerTop2Title,
					'当前置顶'=>$managerTop3Url,
					'当前置顶标题'=>$managerTop3Title,
				);
				tpl::Rep( $arr );
			}
			else
			{
				tpl::Rep( array('{帖子管理}[a]{/帖子管理}'=>'') , null , 3);
			}
			
		
			//回帖列表，替换页面条件
			$pageWhere = '';
			if ( C('page.page') > 0 )
			{
				$pageWhere = 'page='.C('page.page').';';
			}
			tpl::Rep( array('{评论:'=>'{评论:'.$pageWhere) , null , '2' );
			
			//评分标签
			//common::ScoreLabel( 'bbs' , C('page.data.bbs_id') );
			
			//上下篇
			self::BbsPreNext();
			
			//同标签帖子
			self::BbsLike();


			//需要登录并且uid大于0就是已经登录状态
			if( $isLogin == false )
			{
				tpl::IfRep( $isLogin , '=' , false , '需登录');
				tpl::IfRep( $isSelf , '=' , true , '仅自己');
			}
			//仅自己
			if( $isSelf == false )
			{
				tpl::IfRep( $isLogin , '=' , false , '需登录');
				tpl::IfRep( $isSelf , '=' , false , '仅自己');
			}
			//符合浏览权限
			tpl::IfRep( $isLogin&&$isSelf , '=' , true , '有浏览权限' , '无浏览权限');
		}

		 /**
		 //内容UBB替换
		 $isrepfile='1';//默认替换文件标签
		 $content=tohtml($row['content']);
		 //判断是否需要登录、回复才能浏览。
		 if(($row['islogin']=='1' && $user['id']=='' && $row['uid']<>$user['id']) || ($row['isreplay']=='1' && $user['id']=='' && $row['uid']<>$user['id'])){
			 $content='您需要<a href="{登录}">登录</a>/<a href="{注册}">注册</a>后才能浏览内容！';
			 $isrepfile='0';//不读取
		 }else if($row['isreplay']=='1' && $row['uid']<>$user['id']){
			 $rrs=$db->query("SELECT id FROM `wm_replay_content` where `type`='bbs' and `nid`='".$cid."' and `uid`='".$user['id']."'");
			 if($rrs->rowCount() < 1){
				 $content='您需要回复后才能浏览内容！';
				 $isrepfile='0';//不读取
			 }
		 }else if($row['ispay']=='1' && $row['uid']<>$user['id']){
			 $payinfoarr=paycontent_info('bbs',$cid);//查询用户是否购买了本内容
			 if($payinfoarr['statu']=='0'){
				 $content=$payinfoarr['content'];
				 $isrepfile='0';//不读取
			 }
		 }
		 //替换文件的状态为1才进行
		 if($isrepfile=='1'){
		 //读取内容里面的附件
		 preg_match_all('/\[file:(.*?)\](\d*)\[\/file\]/', $content, $lable);
		 $count=count($lable[0]);	//该标签共有多少个
		 //输出里面的id
		 for($i=1;$i<=$count;$i++){
		 $fids.=$lable[2][$i-1].',';
		 $alt[$lable[2][$i-1]]=$lable[1][$i-1];
		 }
		 $fids.='0';
		 $files=$db->query("SELECT * FROM `wm_upload` where id in (".$fids.")");
		 if($files->rowCount() > 0){
		 while($rf = $files->fetch()){
		 if($rf['simg']==''){
		 $simg=$rf['img'];
		 }else{
		 $simg=$rf['simg'];
		 }
		 if($rf['ext']=='png' || $rf['ext']=='gif' || $rf['ext']=='jpg'){
		 $content=str_replace('[file:'.$alt[$rf['id']].']'.$rf['id'].'[/file]','<img src="'.$simg.'" alt="'.$alt[$rf['id']].'"/>',$content);
		 }else{
		 $content=str_replace('[file:'.$alt[$rf['id']].']'.$rf['id'].'[/file]','<a href="/module/down/down.php?t=bbs&amp;fid='.$rf['id'].'" alt="'.$alt[$rf['id']].'"/>'.$alt[$rf['id']].'</a>',$content);
		 }
		 }
		 }
		 }

		 }*/
	}

	//上下一篇文章替换
	static function bbsPreNext(){
		$where['field'] = 'bbs_id,bbs_title';
		$where['table']['@bbs_bbs'] = '';
		$where['where']['type_id'] = C('page.data.type_id');
		
		$url = tpl::Url('bbs_bbs' , array('tid'=>C('page.tid'),'page'=>1));
		
		common::PreNext( $where , 'bbs_id' , C('page.data.bbs_id') , $url , 'bid' , 'bbs_title' );
	}

	//同标签的文章
	static function bbsLike()
	{
		$tags = C('page.data.bbs_tags');
		
		$whereLabel = 'bbs_id=[不等于->'.C('page.data.bbs_id').'];type_id='.C('page.data.type_id').';';
		if( $tags != '' )
		{
			$whereLabel.= 'bbs_tags=[in->'.$tags.'];';
		}

		tpl::Rep( array('{同标签帖子:'=>'{同标签帖子:'.$whereLabel) , null , '2' );
		
		$CF['bbs'] = 'GetData';
		$repFun['a']['bbslabel'] = 'PublicBbs';
		tpl::Label('{同标签帖子:[s]}[a]{/同标签帖子}','content', $CF, $repFun['a']);
	}
	
	
	//搜索标签
	static function SearchLabel()
	{
		$arr = array(
			'搜索词'=>C('page.key'),
		);
		tpl::Rep($arr);

		//搜索条件
		$where = common::SearchWhere( array('bbs_title','user_nickname','bbs_tags') , C('page.type') , C('page.key') );
		
		//获得数据并且替换标签
		$data = bbs::GetData( 'content' , $where );
		$repFun['a']['bbslabel'] = 'Publicbbs';
		tpl::Label( '{搜索结果:[s]}[a]{/搜索结果}' , $data , null , $repFun['a'] );

		//关键词搜索次数+1
		search::SearchNumber( 'bbs' );
	}


	//评论标签
	static function ReplayLabel()
	{
		$pageWhere = '';
		
		//替换文章标签
		self::bbsLabel();
		
		//替换页面条件
		if ( C('page.page') > 0 )
		{
			$pageWhere = 'page='.C('page.page').';';
		}
		tpl::Rep( array('{评论:'=>'{评论:'.$pageWhere) , null , '2' );
	}


	//发帖页面标签
	static function PostLabel()
	{
		$tid = C('page.tid');
		$bid = C('page.bid');
		
		//设置默认参数
		$gold1check = $gold2check = $price = $title = $content = $isReplay = $isLogin = $isSelf = $isPay = $tags = $typeName = '';

		//如果有帖子id就直接查询帖子信息
		if ( $bid > 0 )
		{
			//检查帖子信息是否存在
			$data = bbs::GetData( 'bbs' , 'bbs_id='.$bid , C('system.content.no',null,'lang') );

			//不是管理员，并且不允许作者自己修改
			if ( C('page.isModer') == false && ( $data['user_id'] == user::GetUid() && C('author_up' ,null,'bbsConfig') == 0 ))
			{
				tpl::ErrInfo( C('bbs.bbs_noup',null,'lang') );
			}
			
			//赋值给默认参数
			$bid = $data['bbs_id'];
			$tid = $data['type_id'];
			$title = $data['bbs_title'];
			$content = $data['bbs_content'];
			$tags = $data['bbs_tags'];
			$typeName = $data['type_name'];
			
			//是否需要评论，登录，付费，仅自己查看
			$isReplay = bbs::PostCheck($data['bbs_isreplay']);
			$isLogin = bbs::PostCheck($data['bbs_islogin']);
			$isPay = bbs::PostCheck($data['bbs_ispay']);
			$isSelf = bbs::PostCheck($data['bbs_isself']);
			
			//金币1和金币2默认选中
			switch ( 1 )
			{
				case '1':
					$gold1check = 'checked';
				case '2':
					$gold2check = 'checked';
			}
		}
		//如果分类id大于0就只查询板块分类信息
		else if ( $tid > 0 )
		{
			//检查分类信息是否存在
			$data = str::GetOne( bbs::GetData( 'type' , 'tid='.$tid , C('system.type.no',null,'lang') ) );
			$tid = $data['type_id'];
			$typeName = $data['type_name'];
			$bid = 0;
		}
		else
		{
			$tid = 0;
			$bid = 0;
		}

		$arr = array(
			'版块名字'=>$typeName,
			'帖子列表'=>tpl::url('bbs_list',array('page'=>1,'tid'=>$tid)),
			'标题'=>$title,
			'内容'=>$content,
			'标签'=>$tags,
			'版块id'=>$tid,
			'帖子id'=>$bid,
			'登录选中'=>$isLogin,
			'回帖选中'=>$isReplay,
			'自己查看选中'=>$isSelf,
			'付费选中'=>$isPay,
			'金币1选中'=>$gold1check,
			'金币2选中'=>$gold2check,
			'帖子价格'=>$price,
			'表单提交地址'=>common::GetUrl('bbs.post'),
			'隐藏表单'=>'<input type="hidden" name="tid" value="'.$tid.'"><input type="hidden" name="bid" value="'.$bid.'">',
			'上传附件表单'=>file::GetFile(WMTEMPLATE.'system/bbs_post.html'),
			'上传附件js'=>'<script type="text/javascript" src="/files/js/jquery-1.4.2.min.js"></script><script type="text/javascript" src="/files/js/ajaxfileupload.js"></script><script type="text/javascript" src="/files/js/bbs_post.js"></script>',
		);
		tpl::Rep( $arr );
		
		tpl::Rep( array('{未使用附件:'=>'{未使用附件:upload_module=bbs;user_id='.user::GetUid().';upload_type=[rin->bbspost,replay];upload_cid=0;') , null , '2' );
		common::UploadList('未使用附件');
	}
}
?>
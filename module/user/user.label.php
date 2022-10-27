<?php
/**
* 用户标签处理类
*
* @version        $Id: user.label.php 2015年12月18日 16:00  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2015年1月12日 10:21 weimeng
* 
*/
class userlabel extends user
{
	static public $lcode;
	static public $data;
	static public $CF = array('user'=>'GetData');

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
			'登录提交地址'=>common::GetUrl('user.login'),
			'注册提交地址'=>common::GetUrl('user.reg'),
			'找回密码提交地址'=>common::GetUrl('user.getpsw'),
			'重置密码提交地址'=>common::GetUrl('user.repsw'),
			'账号属性'=>tpl::url('user_attribute'),
			'头像设置'=>tpl::url('user_head'),
			'修改密码'=>tpl::url('user_uppsw'),
			'账号绑定'=>tpl::url('user_bind'),
			'我的书架'=>tpl::url('user_coll' , array('page'=>'1','module'=>'novel','type'=>'shelf')),
			'我的收藏'=>tpl::url('user_coll' , array('page'=>'1','module'=>'novel','type'=>'coll')),
			'我的订阅'=>tpl::url('user_coll' , array('page'=>'1','module'=>'novel','type'=>'sub')),
			'阅读记录'=>tpl::url('user_read' , array('page'=>'1','module'=>'novel')),
			'提现申请'=>tpl::url('user_cash_apply'),
			'提现记录'=>tpl::url('user_cash_list' , array('page'=>'1')),
			'充值成功'=>tpl::url('user_charge_success'),
			'访客列表'=>tpl::Url('user_vistlist',array('page'=>1)),
		);
		tpl::Rep($arr);
	}
	
	
	//标签公共标签替换
	static function PublicLabel()
	{
		$info = parent::GetInfo();
		$lv = parent::GetLV();
		$msg = parent::GetMsg();
		$signIn = parent::GetSignIn();
		$userConfig = C('',null,'userConfig');

		$arr = array(
			'用户头像'=>user::GetHead(),
			'用户id'=>user::GetUid(),
			'用户名'=>user::GetName(),
			'用户出生日期'=>user::GetBirthday(),
			'用户年龄'=>user::GetAge(),
			'用户性别码'=>GetKey($info,'user_sex',1),
			'用户性别'=>user::GetSex(GetKey($info,'user_sex',1)),
			'用户昵称'=>user::GetNickname(),
			'用户邮箱'=>user::GetEmail(),
			'用户经验'=>user::GetExp(),
			'用户签名'=>user::GetSign(),
			'用户人气'=>user::GetBrowse(),
			'用户主题量'=>user::GetTopic(),
			'用户回帖量'=>user::GetReTopic(),
			'用户评论量'=>user::GetReplay(),
				
			'用户qq'=>user::GetQq(),
			'用户手机'=>user::GetTel(),
			'用户余额'=>user::GetMoney(),
			'用户冻结金额'=>user::GetMoneyFreeze(),

			'用户金币1'=>user::GetGold1(),
			'用户金币2'=>user::GetGold2(),
			'用户金币1数量'=>intval(user::GetGold1()),
			'用户金币2数量'=>intval(user::GetGold2()),
			'用户登陆时间'=>user::GetLoginTime(),
			'用户注册时间'=>user::GetRegTime(),
			'用户等级'=>$lv['name'],
			'等级数字'=>$lv['number'],
			'开始经验'=>$lv['start'],
			'结束经验'=>$lv['end'],
			'升级经验'=>$lv['up_exp'],
			'下级经验'=>$lv['next_exp'],
			'下一等级'=>$lv['next_name'],
			'书架总容量'=>$lv['level_shelf'],
			'收藏总容量'=>$lv['level_coll'],
			'经验名字'=>$userConfig['exp_name'],
			'余额名字'=>$userConfig['money_name'],
			'金币1名字'=>$userConfig['gold1_name'],
			'金币2名字'=>$userConfig['gold2_name'],
			'金币1单位'=>$userConfig['gold1_unit'],
			'金币2单位'=>$userConfig['gold2_unit'],
			'推荐票名字'=>$userConfig['ticket_rec'],
			'月票名字'=>$userConfig['ticket_month'],
			'登录赠送金币1'=>$userConfig['login_gold1'],
			'登录赠送金币2'=>$userConfig['login_gold2'],
			'登录赠送经验'=>$userConfig['login_exp'],
			'消息条数'=>$msg['sum_msg'],
			'未读消息'=>$msg['new_msg'],
			'已读消息'=>$msg['read_msg'],
				
			'今日签到时间'=>$signIn['today'],
			'今日签到排名'=>$signIn['rank'],
			'上次签到时间'=>$signIn['pre'],
			'上次签到排名'=>$signIn['prerank'],
			'连续签到天数'=>$signIn['con'],
			'总共签到天数'=>$signIn['sum'],
			'签到'=>'/wmcms/action/index.php?action=user.sign',
				
			'注册验证码id'=>'code_user_reg',
			'注册验证码类型'=>C('config.web.code_user_reg_type'),
			'注册验证码状态'=>C('config.web.code_user_reg'),
			'登录验证码id'=>'code_user_login',
			'登录验证码类型'=>C('config.web.code_user_login_type'),
			'登录验证码状态'=>C('config.web.code_user_login'),
			'修改密码验证码id'=>'code_user_uppsw',
			'修改密码验证码类型'=>C('config.web.code_user_uppsw_type'),
			'找回密码验证码id'=>'code_user_getpsw',
			'找回密码验证码类型'=>C('config.web.code_user_getpsw_type'),
		);
		if( str::in_string('{注册验证码}') )
		{
			$arr['注册验证码'] = FormCodeCreate('code_user_reg');
		}
		if( str::in_string('{登录验证码}') )
		{
			$arr['登录验证码'] = FormCodeCreate('code_user_login');
		}
		if( str::in_string('{修改密码验证码}') )
		{
			$arr['修改密码验证码'] = FormCodeCreate('code_user_uppsw');
		}
		if( str::in_string('{找回密码验证码}') )
		{
			$arr['找回密码验证码'] = FormCodeCreate('code_user_getpsw');
		}
		if( str::in_string('{作者状态}') )
		{
			$author = author::GetAuthor();
			$arr['作者状态'] = $author?$author['author_status']:'';
		}
			
		tpl::Rep($arr);
		
		
		//是否有新消息
		tpl::IfRep( GetKey($msg,'new_msg',0) , '>' , 0 , '有新消息' , '无新消息');
		//消息列表
		$pageWhere = 'msg_tuid='.user::GetUid().';';
		tpl::Rep( array('{用户消息列表:'=>'{用户消息列表:'.$pageWhere) , null , '2' );
		$repFun['a']['userlabel'] = 'PublicMsg';
		tpl::Label('{用户消息列表:[s]}[a]{/用户消息列表}','msg', self::$CF, $repFun['a']);
		
		//登录标签
		tpl::IfRep( parent::GetUid() , '>' , 0 , '已登录' , '未登录');
		//是否签到
		tpl::IfRep( $signIn['sign'] , '=' , 1 , '已签到' , '未签到');
		

		//注册验证码、登录验证码、修改密码验证码、找回密码验证码
		tpl::IfRep( C('config.web.code_user_reg') , '=' , 1 , '注册验证码开启' , '注册验证码关闭');
		tpl::IfRep( C('config.web.code_user_login') , '=' , 1 , '登录验证码开启' , '登录验证码关闭');
		tpl::IfRep( C('config.web.code_user_uppsw') , '=' , 1 , '修改密码验证码开启' , '修改密码验证码关闭');
		tpl::IfRep( C('config.web.code_user_getpsw') , '=' , 1 , '找回密码验证码开启' , '找回密码验证码关闭');
		
		self::PublicReadLabel();
	}


	/**
	 * 浏览记录标签替换
	 **/
	static function PublicReadLabel()
	{
		tpl::Rep( array('{小说浏览记录:'=>'{记录列表:read_module=novel;','{/小说浏览记录}'=>'{/记录列表}') , null , '2' );
		//用户id大于0就查询浏览记录
		if( parent::GetUid() > 0 )
		{
			self::ReadLabel(false);
		}
	}
	
	/**
	 * 用户预设头像列表
	 * @param 参数1，数组，需要进行操作的数组
	 * @param 参数2，字符串，需要进行替换的字符串，原始标签代码。
	 **/
	static function PublicHead($data,$blcode)
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
				'id'=>$v['head_id'],
				'src'=>$v['head_src'],
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
	 * 公共消息标签
	 * @param 参数1，数组，需要进行操作的数组
	 * @param 参数2，字符串，需要进行替换的字符串，原始标签代码。
	 **/
	static function PublicMsg($data,$blcode)
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
	
			$nickname = $v['user_nickname'];
			$name = $v['user_name'];
			$head = $v['user_head'];
			//如果是系统消息就设置系统消息的名字和头像
			if( $v['msg_fuid'] == '0')
			{
				$nickname = C('user.msg_nickname',null,'lang');
				$name = C('user.msg_name',null,'lang');
				$head = C('msg_head',null,'userConfig');
			}
			$lcode = tpl::IfRep( $v['msg_fuid'] , '=' , 0 , '系统用户' , '普通用户' , $lcode);
			$lcode = tpl::IfRep( $v['msg_status'] , '=' , 0 , '未读' , '已读' , $lcode);

			//显示固定字数标签
			$contentArr = tpl::Exp('{消息内容:[d]}' , $v['msg_content'] , $lcode);
			
			//设置自定义中文标签
			$arr2=array(
				'i'=>$i,
				'url'=>tpl::Url('user_msg',array('mid'=>$v['msg_id'])),
				'发信用户昵称'=>$nickname,
				'发信用户名'=>$name,
				'发信用户头像'=>$head,
				'消息内容'=>str::ToHtml($v['msg_content']),
				'消息内容:'.GetKey($contentArr,'0')=>GetKey($contentArr,'1'),
				'发信时间'=>date('Y-m-d H:i:s', $v['msg_time']),
				'删除消息'=>'/wmcms/action/index.php?action=user.delmsg&mid='.$v['msg_id'],
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
	 * 公共签到标签
	 * @param 参数1，数组，需要进行操作的数组
	 * @param 参数2，字符串，需要进行替换的字符串，原始标签代码。
	 **/
	static function PublicSign($data,$blcode)
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

			$signTime = tpl::Tag('{签到时间:[s]}',$lcode);

			//设置自定义中文标签
			$arr2=array(
				'i'=>$i,
				'url'=>tpl::Url('user_fhome',array('uid'=>$v['user_id'])),
				'签到用户昵称'=>$v['user_nickname'],
				'用户签到时间'=>date("Y-m-d H:i:s",$v['sign_time']),
				'签到用户头像'=>$v['user_head'],
				'用户连续签到'=>$v['sign_con'],
				'用户总共签到'=>$v['sign_sum'],
				'用户签到时间:'.GetKey($signTime,'1,0')=>tpl::Time(GetKey($signTime,'1,0'), $v['sign_time']),
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
	 * 访问标签
	 * @param 参数1，数组，需要进行操作的数组
	 * @param 参数2，字符串，需要进行替换的字符串，原始标签代码。
	 **/
	static function PublicVist($data,$blcode)
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
	
			$time = tpl::Tag('{访问时间:[s]}',$lcode);

			//设置自定义中文标签
			$arr2=array(
				'i'=>$i,
				'url'=>tpl::Url('user_fhome',array('uid'=>$v['user_id'])),
				'访客昵称'=>$v['user_nickname'],
				'访客头像'=>$v['user_head'],
				'访问时间'=>date("Y-m-d H:i:s",$v['vist_time']),
				'访问时间:'.GetKey($time,'1,0')=>tpl::Time(GetKey($time,'1,0'), $v['vist_time']),
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
	 * 替换是否收藏的标签
	 * @param 参数1，模块
	 * @param 参数2，内容id
	 */
	static function PublicColl($module , $cid)
	{
		$type = array('coll'=>'收藏','shelf'=>'书架','sub'=>'订阅');

		//new一个模型
		$uid = user::GetUid();
		$collMod = NewModel('user.coll');
		
		foreach ($type as $k=>$v)
		{
			$data['coll_type'] = $k;
			$count = $collMod->GetCount($module , $k , $cid , $uid);
			$labelType = tpl::Tag( '{是否'.$v.'}');
			if( GetKey($labelType,'0,0') != '' )
			{
				//替换是否收藏
				$arr = array(
					'是否'.$v=>$count,
				);
				tpl::Rep($arr);
			}
			//替换if标签
			tpl::IfRep($count, '=', 1, '已'.$v, '未'.$v);
		}
	}
		
	
	/**
	 * 用户中心页
	 */
	static function HomeLabel()
	{
		$arr = array(
			'{访客列表:'=>'{访客列表:vist_uid='.user::GetUid().';',
		);
		tpl::Rep( $arr , null , '2' );
		
		$repFun['a']['userlabel'] = 'PublicVist';
		tpl::Label('{访客列表:[s]}[a]{/访客列表}','vist', self::$CF, $repFun['a']);
		
		//可以使用用户资料页面的标签
		self::AttributeLabel();
	}
	
	
	/**
	 * 好友中心公共标签
	 */
	static function PublicFHome()
	{
		$data = C('page.data');
		$lv = parent::GetLV( $data['user_id'] , $data['user_exp']);
		$uid = $data['user_id'];
		
		$arr = array(
			'好友书架'=>tpl::url('user_fcoll' , array('uid'=>$uid,'page'=>'1','module'=>'novel','type'=>'shelf')),
			'好友收藏'=>tpl::url('user_fcoll' , array('uid'=>$uid,'page'=>'1','module'=>'novel','type'=>'coll')),
			'好友订阅'=>tpl::url('user_fcoll' , array('uid'=>$uid,'page'=>'1','module'=>'novel','type'=>'dingyue')),
			'好友主页'=>tpl::Url('user_fhome',array('uid'=>$uid,'page'=>1)),
			'好友头像'=>$data['user_head'],
			'好友id'=>$data['user_id'],
			'好友名'=>$data['user_name'],
			'好友年龄'=>user::GetAge($data['user_birthday']),
			'好友性别码'=>$data['user_sex'],
			'好友性别'=>user::GetSex($data['user_sex']),
			'好友昵称'=>$data['user_nickname'],
			'好友邮箱'=>$data['user_email'],
			'好友经验'=>$data['user_exp'],
			'好友签名'=>$data['user_sign'],
			'好友人气'=>$data['user_browse'],
			'好友主题量'=>$data['user_topic'],
			'好友回帖量'=>$data['user_retopic'],
			'好友评论量'=>$data['user_replay'],
			'好友金币1'=>$data['user_gold1'],
			'好友金币2'=>$data['user_gold2'],
			'好友金币1数量'=>intval($data['user_gold1']),
			'好友金币2数量'=>intval($data['user_gold1']),
			'好友登陆时间'=>date('Y-m-d H:i:s',$data['user_logintime']),
			'好友注册时间'=>date('Y-m-d H:i:s',$data['user_regtime']),
			'好友等级'=>$lv['name'],
			'好友等级数字'=>$lv['number'],
			'好友开始经验'=>$lv['start'],
			'好友结束经验'=>$lv['end'],
			'好友升级经验'=>$lv['up_exp'],
			'好友下级经验'=>$lv['next_exp'],
			'好友下一等级'=>$lv['next_name'],
			'好友访客列表'=>tpl::Url('user_fvistlist',array('uid'=>$uid,'page'=>1)),
		);
		tpl::Rep($arr);
	}
	
	/**
	 * 好友资料页
	 */
	static function FHomeLabel()
	{
	    //支持好友收藏
		self::FCollLabel();
		
		$arr = array(
			'{好友访客列表:'=>'{好友访客列表:vist_uid='.C('page.uid').';',
		);
		tpl::Rep( $arr , null , '2' );
		
		$repFun['a']['userlabel'] = 'PublicVist';
		tpl::Label('{好友访客列表:[s]}[a]{/好友访客列表}','vist', self::$CF, $repFun['a']);
	}


	/**
	 * 访客列表
	 */
	static function FVistListLabel()
	{
		$pageWhere = '';
		
		if ( C('page.page') > 0 )
		{
			$pageWhere = 'page='.C('page.page').';vist_uid='.C('page.uid').';';
		}
		$arr = array(
			'{访客列表:'=>'{访客列表:'.$pageWhere,
			'{好友首页}'=>tpl::Url('user_fhome',array('uid'=>C('page.uid'))),
		);
		tpl::Rep( $arr , null , '2' );
		
		$repFun['a']['userlabel'] = 'PublicVist';
		tpl::Label('{访客列表:[s]}[a]{/访客列表}','vist', self::$CF, $repFun['a']);
	}
	

	/**
	 * 用户修改头像
	 **/
	static function HeadLabel()
	{
		$arr = array(
			'上传头像表单'=>file::GetFile(WMTEMPLATE.'system/user_head.html'),
			'上传头像js'=>'<script type="text/javascript" src="/files/js/jquery-1.4.2.min.js"></script><script type="text/javascript" src="/files/js/ajaxfileupload.js"></script><script type="text/javascript" src="/files/js/user_head.js"></script>',
		);
		tpl::Rep($arr);
		self::PublicLabel();
		
		$repFun['a']['userlabel'] = 'PublicHead';
		tpl::Label('{预设头像:[s]}[a]{/预设头像}','head', self::$CF, $repFun['a']);
	}
	
	
	/**
	 * 消息标签
	 */
	static function MsgLabel()
	{
		$data = C('page.data');

		$nickname = $data['user_nickname'];
		$name = $data['user_name'];
		$head = $data['user_head'];
		
		//如果是系统消息就设置系统消息的名字和头像
		if( $data['msg_fuid'] == '0')
		{
			$nickname = C('user.msg_nickname',null,'lang');
			$name = C('user.msg_name',null,'lang');
			$head = C('msg_head',null,'userConfig');
		}
		
		$arr = array(
			'发信用户昵称'=>$nickname,
			'发信用户名'=>$name,
			'发信用户头像'=>$head,
			'消息内容'=>str::ToHtml($data['msg_content']),
			'发信时间'=>date('Y-m-d H:i:s', $data['msg_time']),
			'删除消息'=>'/wmcms/action/index.php?action=user.delmsg&mid='.$data['msg_id'],
		);
		tpl::Rep($arr);
		
		tpl::IfRep( $data['msg_fuid'] , '=' , 0 , '系统用户' , '普通用户');
	}
	
	
	/**
	 * 消息列表页
	 **/
	static function MsgListLabel()
	{
		$pageWhere = 'msg_tuid='.user::GetUid().';';
		
		if ( C('page.page') > 0 )
		{
			$pageWhere .= 'page='.C('page.page').';';
		}
		
		tpl::Rep( array('{消息列表:'=>'{消息列表:'.$pageWhere) , null , '2' );
		
		$repFun['a']['userlabel'] = 'PublicMsg';
		tpl::Label('{消息列表:[s]}[a]{/消息列表}','msg', self::$CF, $repFun['a']);
	}
	
	
	/**
	 * 签到首页
	 */
	static function SignLabel()
	{
		$repFun['a']['userlabel'] = 'PublicSign';
		$todayTime = 'sign_time=[大于->'.strtotime(date('Y-m-d')).'];';

		//今天的签到时间
		tpl::Rep( array('{签到列表:'=>'{签到列表:'.$todayTime ), null , '2' );
		
		tpl::Label('{签到列表:[s]}[a]{/签到列表}','sign', self::$CF, $repFun['a']);
	}
	/**
	 * 签到列表页
	 **/
	static function SignListLabel()
	{
		$pageWhere = '';
		$todayTime = 'sign_time=[大于->'.strtotime(date('Y-m-d')).'];';
	
		if ( C('page.page') > 0 )
		{
			$pageWhere = 'page='.C('page.page').';';
		}
	
		tpl::Rep( array('{签到列表:'=>'{签到列表:'.$pageWhere.$todayTime) , null , '2' );
	
		$repFun['a']['userlabel'] = 'PublicSign';
		tpl::Label('{签到列表:[s]}[a]{/签到列表}','sign', self::$CF, $repFun['a']);
	}
	

	/**
	 * 账号属性页面
	 */
	static function AttributeLabel()
	{
		$lv = parent::GetLV();
		$collCount = parent::GetColl('novel','coll');
		$shelfCount = parent::GetColl('novel','shelf');
		$ticketMod = NewModel('user.ticket');
		$ticketData = $ticketMod->GetTicket(user::GetUid() , 'novel');

		$arr = array(
			'收藏已用容量'=>$collCount,
			'书架已用容量'=>$shelfCount,
			'收藏剩余容量'=>$lv['level_coll']-$collCount,
			'书架剩余容量'=>$lv['level_shelf']-$shelfCount,
			'月票'=>$ticketData['ticket_month'],
			'推荐票'=>$ticketData['ticket_rec'],
		);
		tpl::Rep($arr);
	}



	/**
	 * 收藏等信息页面
	 */
	static function CollLabel()
	{
		//判断收藏类型
		switch ( C('page.module') )
		{
			//如果是小说
			case 'novel':
				$repFun['a']['novellabel'] = 'PublicNovel';
				break;
		}
		
		//收藏类型
		$pageWhere = 'user_id='.user::GetUid().';排序=coll_id desc;';
		$type = C('page.type');
		if ( C('page.page') > 0 )
		{
			$pageWhere .= 'page='.C('page.page').';';
		}

		switch ( $type )
		{
			case "shelf":
				$typeName = '书架';
				tpl::Rep( array('{书架列表:'=>'{书架列表:'.$pageWhere) , null , '2' );
				tpl::Label('{书架列表:[s]}[a]{/书架列表}','coll', self::$CF, $repFun['a']);
				break;
				
			case "sub":
				$typeName = '订阅';
				tpl::Rep( array('{订阅列表:'=>'{订阅列表:'.$pageWhere) , null , '2' );
				tpl::Label('{订阅列表:[s]}[a]{/订阅列表}','coll', self::$CF, $repFun['a']);
				break;
				
			case "rec":
				$typeName = '推荐';
				tpl::Rep( array('{推荐列表:'=>'{推荐列表:'.$pageWhere) , null , '2' );
				tpl::Label('{推荐列表:[s]}[a]{/推荐列表}','coll', self::$CF, $repFun['a']);
				break;
				
			default:
				$typeName = '收藏';
				tpl::Rep( array('{收藏列表:'=>'{收藏列表:'.$pageWhere) , null , '2' );
				tpl::Label('{收藏列表:[s]}[a]{/收藏列表}','coll', self::$CF, $repFun['a']);
				break;
		}
		
		$arr = array(
			'收藏类型标识'=>$type,
			'收藏类型文字'=>$typeName,
		);
		tpl::Rep($arr);
	}
	
	
	/**
	 * 阅读记录页面
	 * @param 参数1，模块，选填
	 */
	static function ReadLabel($module='')
	{
		$pageWhere = '';
		//判断类型，false就不进行设置
		if( $module === false )
		{
		    $module = '';
		}
		//如果为空就获取模块
		else if( $module == '' )
		{
		    $module = 'read_module='.C('page.module');
		}
		//不为空则设置为传入
		else
		{
		    $module = 'read_module='.$module;
		}
		//如果是有分页
		if( C('page.page') )
		{
			$pageWhere = ';page='.C('page.page').';';
		}
		
		tpl::Rep( array('{记录列表:'=>'{记录列表:read_uid='.parent::GetUid().$pageWhere.';'.$module.';') , null , '2' );
		
		$data = array();
		$listLabel = tpl::Tag('{记录列表:[s]}');
		if( !empty($listLabel[0]) )
		{
			$where = parent::GetWhere($listLabel[1][0]);
			$wheresql = $where;
			if( isset($wheresql['list']) )
			{
				$wheresql['table'] = '@user_read';
				page::Start( C('page.listurl') , wmsql::GetCount($wheresql) , $wheresql['limit'] );
			}
			$readMod = NewModel('user.read');
			$data = $readMod->GetList($where);
			//设置回调函数
			C('page.callback_label',array('userlabel','ReadCallBackLabel'));
    		switch ( $where['where']['read_module'] )
    		{
    			//文章
    			case 'article':
    				$repFun['a']['articlelabel'] = 'PublicArticle';
    				break;
    			//如果是小说
    			case 'novel':
    				$repFun['a']['novellabel'] = 'PublicChapter';
    				break;
    		}
			tpl::Label('{记录列表:[s]}[a]{/记录列表}',$data, null, $repFun['a']);
			//清空回调
			C('page.callback_label','delete');
		}
	}
	/**
	 * 阅读记录标签回调方法
	 * @param 参数1，数组，需要进行操作的数组
	 **/
	static function ReadCallBackLabel($v)
	{
		$arr=array(
			'小说封面'=>GetKey($v,'novel_cover'),
			'阅读标题'=>$v['read_title'],
			'首次阅读时间'=>date("Y-m-d H:i:s",$v['read_time']),
			'最后阅读时间'=>date("Y-m-d H:i:s",$v['read_lasttime']),
			'首次阅读时间:'.GetKey($v['read_time'],'1,0')=>tpl::Time(GetKey($v['read_time'],'1,0'), $v['read_time']),
			'最后阅读时间:'.GetKey($v['read_lasttime'],'1,0')=>tpl::Time(GetKey($v['read_lasttime'],'1,0'), $v['read_lasttime']),
		);
		return $arr;
	}
	
	
	/**
	 * 好友收藏等信息页面
	 */
	static function FCollLabel()
	{
		//调用主页公共标签
		self::PublicFHome();
		
		//判断收藏类型
		switch ( C('page.module') )
		{
			//如果是小说
			case 'novel':
			default:
				$repFun['a']['novellabel'] = 'PublicNovel';
				break;
		}
	
		//收藏类型
		$pageWhere = 'user_id='.C('page.data.user_id').';排序=coll_id desc;';
		$type = C('page.type');
		if ( C('page.page') > 0 )
		{
			$pageWhere .= 'page='.C('page.page').';';
		}
	
		switch ( $type )
		{
			case "shelf":
				$typeName = '书架';
				tpl::Rep( array('{好友书架列表:'=>'{好友书架列表:'.$pageWhere) , null , '2' );
				tpl::Label('{好友书架列表:[s]}[a]{/好友书架列表}','coll', self::$CF, $repFun['a']);
				break;
	
			case "dingyue":
				$typeName = '订阅';
				tpl::Rep( array('{好友订阅列表:'=>'{好友订阅列表:'.$pageWhere) , null , '2' );
				tpl::Label('{好友订阅列表:[s]}[a]{/好友订阅列表}','coll', self::$CF, $repFun['a']);
				break;
	
			case "rec":
				$typeName = '推荐';
				tpl::Rep( array('{好友推荐列表:'=>'{好友推荐列表:'.$pageWhere) , null , '2' );
				tpl::Label('{好友推荐列表:[s]}[a]{/好友推荐列表}','coll', self::$CF, $repFun['a']);
				break;
	
			default:
				$typeName = '收藏';
				tpl::Rep( array('{好友收藏列表:'=>'{好友收藏列表:'.$pageWhere) , null , '2' );
				tpl::Label('{好友收藏列表:[s]}[a]{/好友收藏列表}','coll', self::$CF, $repFun['a']);
				break;
		}
	
		$arr = array(
			'好友收藏类型标识'=>$type,
			'好友收藏类型文字'=>$typeName,
		);
		tpl::Rep($arr);
	}
	
	/**
	 * 登录界面
	 */
	static function LoginLabel()
	{
		$arr = array('表单提交地址'=>common::GetUrl('user.login'),);
		tpl::Rep($arr);
	}
	/**
	 * 普通注册界面
	 */
	static function RegLabel()
	{
		$arr = array(
			'表单提交地址'=>common::GetUrl('user.reg'),
		);
		tpl::Rep($arr);
	}
	/**
	 * 接口登录注册界面
	 */
	static function ApiLoginLabel()
	{
		$userInfo = C('page.user_info');
		$apiUser = Session('api_login_userinfo');
		$arr = array(
			'绑定提交地址'=>common::GetUrl('user.login'),
			'注册提交地址'=>common::GetUrl('user.reg'),
			'第三方昵称'=>$userInfo['nickname'],
			'第三方类型'=>$userInfo['type'],
			'用户加密字符'=>$apiUser,
			'注册隐藏表单'=>'<input type="hidden" name="apiuser" value="'.$apiUser.'"/>',
			'登录隐藏表单'=>'<input type="hidden" name="apiuser" value="'.$apiUser.'"/>',
		);
		tpl::Rep($arr);
	}
	/**
	 * 找回密码界面
	 */
	static function GetPswLabel()
	{
		$arr = array('表单提交地址'=>common::GetUrl('user.getpsw'),);
		tpl::Rep($arr);
	}
	/**
	 * 重置密码页面
	 */
	static function RepswLabel()
	{
		$arr = array(
			'表单提交地址'=>common::GetUrl('user.repsw'),
			'重置密码锁'=>C('page.key'),
			'key'=>C('page.key'),
			'隐藏表单'=>'<input type="hidden" name="key" value="'.C('page.key').'" />',
		);
		tpl::Rep($arr);
	}
	/**
	 *  账号绑定页面
	 */
	static function BindLabel()
	{
	    //获得当前用户绑定的第三方登录
	    $apiloginMod = NewModel('user.apilogin');
	    $list = $apiloginMod->GetByUid(user::GetUid());
	    //邮箱手机号是否绑定
		$emailTrue = parent::GetEmailTrue();
		$telTrue = parent::GetTelTrue();
		$arr = array(
			'邮箱验证状态'=>$emailTrue,
			'手机验证状态'=>$telTrue,
			'绑定提交地址'=>Common::GetUrl('user.bind'),
			//qq登录/取消绑定
			'qq登录'=>Common::GetUrl('user.apilogin&api=qqlogin&bind=1'),
			'qq登录取消绑定'=>Common::GetUrl('user.unbind&api=qqlogin'),
			//百度登录/取消绑定
			'百度登录'=>Common::GetUrl('user.apilogin&api=bdlogin&bind=1'),
			'百度登录取消绑定'=>Common::GetUrl('user.unbind&api=bdlogin'),
			//微博登录/取消绑定
			'微博登录'=>Common::GetUrl('user.apilogin&api=weibologin&bind=1'),
			'微博登录取消绑定'=>Common::GetUrl('user.unbind&api=weibologin'),
			//支付宝登录/取消绑定
			'支付宝登录'=>Common::GetUrl('user.apilogin&api=alipaylogin&bind=1'),
			'支付宝登录取消绑定'=>Common::GetUrl('user.unbind&api=alipaylogin'),
			//微信登录/取消绑定
			'微信登录'=>Common::GetUrl('user.apilogin&api=wxlogin&bind=1'),
			'微信登录取消绑定'=>Common::GetUrl('user.unbind&api=wxlogin'),
			//微信公众号登录/取消绑定
			'微信公众号登录'=>Common::GetUrl('user.apilogin&api=wxmplogin&bind=1'),
			'微信公众号登录取消绑定'=>Common::GetUrl('user.unbind&api=wxmplogin'),
		);
		tpl::Rep($arr);
		//是否已经验证邮箱
		tpl::IfRep( $emailTrue , '=' , 1 , '邮箱已验证' , '邮箱未验证');
		//是否已经验证手机号
		tpl::IfRep($telTrue , '=' , 1 , '手机已验证' , '手机未验证');
		//第三方登录绑定状态
		tpl::IfRep(isset($list['qqlogin']) , '=' , true , 'qq登录已绑定' , 'qq登录未绑定');
		tpl::IfRep(isset($list['bdlogin']) , '=' , true , '百度登录已绑定' , '百度登录未绑定');
		tpl::IfRep(isset($list['weibologin']) , '=' , true , '微博登录已绑定' , '微博登录未绑定');
		tpl::IfRep(isset($list['alipaylogin']) , '=' , true , '支付宝登录已绑定' , '支付宝登录未绑定');
		tpl::IfRep(isset($list['wxlogin']) , '=' , true , '微信登录已绑定' , '微信登录未绑定');
		tpl::IfRep(isset($list['wxmplogin']) , '=' , true , '微信公众号登录已绑定' , '微信公众号登录未绑定');
	}
	/**
	 *  修改资料页面
	 */
	static function BasicLabel()
	{
		$arr = array(
			'表单提交地址'=>common::GetUrl('user.upbasic'),
			'资料表单提交地址'=>common::GetUrl('user.upbasic'),
		);
		tpl::Rep($arr);

		self::FinanceLabel();
		//是否已经验证
		tpl::IfRep( parent::GetEmailTrue() , '=' , 1 , '邮箱已验证' , '邮箱未验证');
	}
	
	/**
	 * 修改财务信息
	 */
	static function FinanceLabel()
	{
		$finance = parent::GetFinance();
		$arr = array(
			'用户真实姓名'=>C('finance_realname',null,$finance),
			'用户身份证'=>C('finance_cardid',null,$finance),
			'用户联系地址'=>C('finance_address',null,$finance),
			'用户邮编'=>C('finance_zipcode',null,$finance),
			'用户开户行'=>C('finance_bank',null,$finance),
			'用户开户行地址'=>C('finance_bankaddress',null,$finance),
			'用户银行卡号'=>C('finance_bankcard',null,$finance),
			'用户持卡人'=>C('finance_bankmaster',null,$finance),
			'用户支付宝'=>C('finance_alipay',null,$finance),
			'表单提交地址'=>common::GetUrl('user.upfinance'),
			'财务表单提交地址'=>common::GetUrl('user.upfinance'),
		);
		tpl::Rep($arr);
	}
	
	/**
	 *  修改密码页面
	 */
	static function UpPswLabel()
	{
		$arr = array(
			'表单提交地址'=>common::GetUrl('user.uppsw'),
		);
		tpl::Rep($arr);
	}
	
	/**
	 * 充值页
	 */
	static function ChargeLabel()
	{
		//引入财务模块
		IncModule('finance');
	}
	/**
	 * 扫描二维码页面
	 */
	static function ChargeCodeLabel()
	{
		$order = C('page.order');
		$code = Encrypt(C('page.code'),'D');

		$arr = array(
			'订单号'=>$order['charge_sn'],
			'二维码'=>QRcode::GetBase64($code,false,'',6,3),
			'支付方式'=>C('config.api.'.$order['charge_type'].'.api_ctitle'),
			'支付标识'=>$order['charge_type'],
			'支付金额'=>$order['charge_money'],
		);
		tpl::Rep($arr);
	}
	/**
	 * 充值完成
	 */
	static function ChargSuccessLabel()
	{
		$order = C('page.order');
		$arr = array(
			'订单号'=>$order['charge_sn'],
			'支付方式'=>C('config.api.'.$order['charge_type'].'.api_ctitle'),
			'支付标识'=>$order['charge_type'],
			'支付金额'=>$order['charge_money'],
		);
		tpl::Rep($arr);
	}
	

	/**
	 * 提现申请
	 */
	static function CashApplyLabel()
	{
		$financeConfig = GetModuleConfig('finance' , true);
		if( $financeConfig['gold2_to_money_open'] == 1 )
		{
			$cashMoney = $financeConfig['gold2_to_money']*user::GetGold2()+user::GetMoney();
		}
		else
		{
			$cashMoney = user::GetMoney();
		}
		
		$arr = array(
			'提现手续费'=>$financeConfig['cash_cost'],
			'兑换余额比例'=>$financeConfig['gold2_to_money'],
			'最低提现金额'=>$financeConfig['cash_lowest'],
			'可提现金额'=>$cashMoney,
			'是否开启提现'=>$financeConfig['cash_open'],
			'梦宝兑换余额数量'=>$financeConfig['gold2_to_money']*user::GetGold2(),
			'是否开启梦宝兑换余额'=>$financeConfig['gold2_to_money_open'],
		);
		tpl::Rep($arr);

		//是否开启了提现
		tpl::IfRep( $financeConfig['cash_open'] , '=' , 1 , '开启提现' , '关闭提现');
		//是否开启梦宝兑换余额
		tpl::IfRep( $financeConfig['gold2_to_money_open'] , '=' , 1 , '开启梦宝兑换余额' , '关闭梦宝兑换余额');
	}

	/**
	 * 提现申请记录
	 */
	static function CashListLabel()
	{
		$lang = IncModule('finance');
		//替换普通抱歉
		$arr = array(
			'提现手续费'=>C('cash_cost',null,'financeConfig'),
		);
		tpl::Rep($arr);
		
		//替换分页数据
		$page = C('page.page');
		$pageWhere = 'cash_user_id='.user::GetUid().';';
		if ( $page > 0 )
		{
			$pageWhere .= 'page='.$page.';';
		}
		tpl::Rep( array('{提现列表:'=>'{提现列表:排序=cash_id desc;'.$pageWhere) , null , '2' );
		
		//数组键：类名，值：方法名
		$repFun['t']['financelabel'] = 'PublicCash';
		tpl::Label('{提现列表:[s]}[a]{/提现列表}','cash_list', array('finance'=>'GetData'), $repFun['t']);
	}
}
?>
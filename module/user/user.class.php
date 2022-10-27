<?php
/**
* 用户系统类文件
*
* @version        $Id: user.class.php 2015年12月18日 16:00  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2016年12月25日 9:14 weimeng
*
*/
//前置验证
beforVer(array('user'=>'CheckLogin'));
class user
{
	//用户表
	static public $userTabel = '@user_user';
	//用户财务信息表
	static public $financeTabel = '@user_finance';
	//用户数组
	static public $user;
	//用户等级
	static protected $lv;
	//消息
	static protected $msg;
	
	function __construct()
	{
		new userlabel();
	}
	
	

	/**
	 * 根据所得到的条件查询数据
	 * @param 参数1，字符串，type为列表页数据获取，content为内容页数据获取
	 * @param 参数2，传递的sql条件
	 * @param 参数3，选填，没有数据的提示字符串
	 **/
	static function GetData( $type , $where='' , $errInfo='' )
	{
		$wheresql = self::GetWhere($where);
	
		//type为列表页数据获取
		switch ($type)
		{
			//列表页获取
			case 'user':
				$wheresql['table'] = self::$userTabel;
				break;
				
			//头像列表获取
			case 'head':
				$wheresql['table'] = '@user_head';
				break;
				
			//消息内容获取
			case 'msg':
				$wheresql['field'] = 'msg_id,msg_fuid,msg_tuid,msg_status,msg_content,msg_time,user_id,user_name,user_nickname,user_head';
				$wheresql['table'] = '@user_msg';
				$wheresql['left']['@user_user'] = 'user_id = msg_fuid';
				break;

			//签到
			case 'sign':
				$wheresql['field'] = 'sign_id,sign_time,sign_con,sign_sum,b.user_id,user_name,user_nickname,user_head';
				$wheresql['table'] = '@user_sign as a';
				$wheresql['left']['@user_user as b'] = 'a.user_id = b.user_id';
				break;

			//访客列表
			case 'vist':
				$wheresql['field'] = 'vist_id,vist_time,user_id,user_name,user_nickname,user_head';
				$wheresql['table'] = '@user_vist';
				$wheresql['left']['@user_user'] = 'vist_fuid = user_id';
				$wheresql['group'] = 'vist_fuid';
				break;

			//收藏等信息
			case 'coll':
				$module = C('page.module');
				$wheresql['table'] = '@user_coll';
				$wheresql['where']['coll_module'] = $module;
				$wheresql['where']['coll_type'] = C('page.type');
				switch ( $module )
				{
				    case 'novel':
        				if( C('page.type') == 'shelf' )
        				{
        					$wheresql['left']['@user_read'] = 'read_nid=coll_cid and user_id=read_uid';
        					$wheresql['left']['@novel_chapter'] = 'read_cid=chapter_id';
        				}
					default:
						$wheresql['left']['@'.$module.'_'.$module.' as n'] = 'n.'.$module.'_id=coll_cid';
						$wheresql['left']['@'.$module.'_type as t'] = 't.type_id=n.type_id';
						break;
				}
				break;
				
			default:
				tpl::ErrInfo( C('system.module.getdata_no' , null , 'lang' ) );
				break;
		}
		
		//分页处理
		if( GetKey($wheresql,'list') )
		{
			page::Start( C('page.listurl') , wmsql::GetCount($wheresql) , $wheresql['limit'] );
		}
		$data = wmsql::GetAll($wheresql);

		if( !$data && $errInfo != '' )
		{
			tpl::ErrInfo($errInfo);
		}
		return $data;
	}
	
	
	/**
	* 获得字符串中的条件sql
	* 返回值字符串
	* @param 参数1：需要查询的字符串。
	**/
	static function GetWhere($where)
	{
		//设置需要替换的字段
		$arr = array(
			'头像排序' =>'head_order',
			'按用户分组' =>'msg_fuid',
			'签到时间' =>'sign_time',
			'发信时间' =>'msg_time desc',
		);

		return tpl::GetWhere($where,$arr);
	}
	
	
	/**
	 * 检查用户登录
	 */
	static function CheckLogin()
	{
		//先拿TOKEN，如果没有就从cookie里面获取
		$token = trim(urldecode(GetHeader('TOKEN')));
		if( empty($token) )
		{
			$token =  trim(Cookie('user_account'));
		}
		if ( $token != '' )
		{
			//解码cookie字符串
			$arr = str::A( $token , 'D');
			//分析账号密码
			if ( GetKey($arr,'0') != '' && GetKey($arr,'1') != '' )
			{
				$where['table'] = self::$userTabel;
				$where['where'] = array('user_name'=>$arr[0]);
				$data = wmsql::GetOne($where);
				$userConfig = C('',null,'userConfig');
				
				//账号信息存在，并且密码相等
				if ( $data && $data['user_psw'] == $arr[1])
				{
					self::$user = $data;
					//审核中
					if( $data['user_status'] == '0' )
					{
						tpl::ErrInfo(C('user.account_status_0' , null , 'lang' ) );
					}
					//全站封禁
					else if( $data['user_display'] == '0' )
					{
						tpl::ErrInfo( C('user.account_display_0' , null , 'lang' ) );
					}
					//限时封禁
					else if( $data['user_display'] == '2' )
					{
						//正在封禁的时间段内
						if ( $data['user_displaytime'] > time() )
						{
							$info = tpl::Rep( array('时间'=>date('Y-m-d H:i:s',$data['user_displaytime'])) , C('user.account_display_2',null,'lang') );
							tpl::ErrInfo( $info );
						}
						//封禁的时间小于当前时间就解禁
						else
						{
							wmsql::Update(self::$userTabel, 'user_display=1', 'user_id='.$data['user_id']);
						}
					}
					
					//是今日首次登录就进行奖励
					if ( $data['user_logintime'] < strtotime('today') )
					{
						//修改登录时间和赠送的道具
						$userMod = NewModel('user.user');
						$rewardData['gold1'] = $userConfig['login_gold1'];
						$rewardData['gold2'] = $userConfig['login_gold2'];
						$rewardData['exp'] = $userConfig['login_exp'];
						$userMod->EveryDayLogin( $data['user_id'] , $rewardData );
						
						//更新推荐票
						$ticketMod = NewModel('user.ticket');
						$lvData = self::GetLV();
						$ticketData['rec'] = $lvData['level_rec'] + $userConfig['login_rec'];
						$ticketData['month'] = $lvData['level_month'] + $userConfig['login_month'];
						$ticketData['remark'] =  C('user.ticket_login_remark',null,'lang');
						$ticketMod->Update( $data['user_id'] , $ticketData,1,$userConfig);
					}
				}
				//账号存在，但是密码不正确，并且开启了登录日志
				else if ( $data && $userConfig['login_log'] == '1')
				{
					$ua = NewClass('client');
					$uaArr = $ua->Get_Useragent();
					$log['user_id'] = $data['user_id'];
					$log['login_time'] = time();
					$log['login_type'] = '1';
					$log['login_status'] = '2';
					$log['login_ip'] = GetIp();
					$log['login_ua'] = $_SERVER['HTTP_USER_AGENT'];
					$log['login_browser'] = $uaArr['1'];
					$log['login_remark'] = C('user.psw_err',null,'lang');
					wmsql::Insert('@user_login', $log);
					
					//删除cookie
					Cookie( 'user_account' , 'delete' );
				}
				//不存在就删除cook数据
				else
				{
					Cookie( 'user_account' , 'delete' );
				}
			}
		}
	}
	
	
	/**
	 * 检查用户名是否存在
	 * @param 参数1，必须，邮箱帐号
	 * @param 参数2，选填，错误提示信息
	 */
	static function CheckName( $name , $info = '')
	{
		//查询账号是否被注册
		$where['table'] = self::$userTabel;
		$where['where']['user_name'] = $name;
		$count = wmsql::GetCount( $where , 'user_id');
		
		str::RT( $count, 0 , $info );
		return true;
	}
	
	
	/**
	 * 检查邮箱是否存在
	 * @param 参数1，必须，邮箱帐号
	 * @param 参数2，选填，错误提示信息
	 */
	static function CheckEmail( $email , $info = '' )
	{
		//查询账号是否被注册
		$where['table'] = self::$userTabel;
		$where['where']['user_email'] = $email;
		$where['where']['user_emailtrue'] = 1;
		$count = wmsql::GetCount( $where , 'user_id');
		if( $info != '' )
		{
		    str::RT( $count, 0 , $info );
		}
		return $count;
	}
	
	/**
	 * 检查手机号是否存在
	 * @param 参数1，必须，邮箱帐号
	 * @param 参数2，选填，错误提示信息
	 */
	static function CheckTel( $tel , $info = '' )
	{
		//查询账号是否被注册
		$where['table'] = self::$userTabel;
		$where['where']['user_tel'] = $tel;
		$where['where']['user_teltrue'] = 1;
		$count = wmsql::GetCount( $where , 'user_id');
		if( $info != '' )
		{
		    str::RT( $count, 0 , $info );
		}
		return $count;
	}

	/**
	 * 用户基本信息
	 */
	static function GetInfo()
	{
		return self::$user;
	}
	/**
	 * 用户昵称
	 */
	static function GetNickname()
	{
		return GetKey(self::$user,'user_nickname');
	}
	/**
	 * 用户头像
	 */
	static function GetHead()
	{
		$head = GetKey(self::$user,'user_head');
		if( $head == '' )
		{
			$head = C('default_head' , null , 'userConfig');
		}
		return $head;
	}
	/**
	 * 用户邮箱
	 */
	static function GetEmail()
	{
		return GetKey(self::$user,'user_email');
	}
	/**
	 * 用户邮箱是否验证
	 */
	static function GetEmailTrue()
	{
		return GetKey(self::$user,'user_emailtrue');
	}
	/**
	 * 获取用户id
	 */
	static function GetUid()
	{
		$uid = GetKey(self::$user,'user_id',0);
		return str::Int($uid);
	}
	/**
	 * 用户账号
	 */
	static function GetName()
	{
		return GetKey(self::$user,'user_name');
	}
	/**
	 * 用户金币1
	 */
	static function GetGold1()
	{
		return floor(GetKey(self::$user,'user_gold1',0));
	}
	/**
	 * 用户金币2
	 */
	static function GetGold2(){
		return floor(GetKey(self::$user,'user_gold2',0));
	}

	/**
	 * 是否已经充值了
	 */
	static function GetIsCharge(){
		return GetKey(self::$user,'user_ischarge',0);
	}
	/**
	 * 用户性别
	 * @param 参数1，选填，性别代码
	 */
	static function GetSex( $sex = '' )
	{
		if ( $sex == '1' )
		{
			$sex = C('user.user_sex_1',null,'lang');
		}
		else
		{
			$sex = C('user.user_sex_2',null,'lang');
		}
		return $sex;
	}
	/**
	 * 用户出生日期
	 */
	static function GetBirthday()
	{
		return GetKey(self::$user,'user_birthday',0);
	}
	/**
	 * 用户年龄
	 */
	static function GetAge($birthday = '')
	{
		if( $birthday == '' )
		{
			$birthday = GetKey(self::$user,'user_birthday',0);
		}
		$age = date('Y', time()) - date('Y', strtotime($birthday)) - 1;
		if ( date('m', time()) == date('m', strtotime($birthday)) )
		{
			if ( date('d', time()) > date('d', strtotime($birthday)) )
			{
				$age++;
			}
		}
		else if ( date('m', time()) > date('m', strtotime($birthday)) )
		{
			$age++;
		}
		return $age;
	}
	/**
	 * 用户经验
	 */
	static function GetExp()
	{
		return GetKey(self::$user,'user_exp',0);
	}
	/**
	 * 用户签名
	 */
	static function GetSign()
	{
		return GetKey(self::$user,'user_sign');
	}
	/**
	 * 用户空间浏览量
	 */
	static function GetBrowse()
	{
		return GetKey(self::$user,'user_browse',0);
	}
	/**
	 * 用户主题量
	 */
	static function GetTopic()
	{
		return GetKey(self::$user,'user_retopic',0);
	}
	/**
	 * 用户回帖量
	 */
	static function GetReTopic()
	{
		return GetKey(self::$user,'user_retopic',0);
	}
	/**
	 * 用户评论量
	 */
	static function GetReplay()
	{
		return GetKey(self::$user,'user_replay',0);
	}
	/**
	 * 用户qq号
	 */
	static function GetQq()
	{
		return GetKey(self::$user,'user_qq');
	}
	/**
	 * 用户手机号
	 */
	static function GetTel()
	{
		return GetKey(self::$user,'user_tel');
	}
	/**
	 * 用户手机号是否验证
	 */
	static function GetTelTrue()
	{
		return GetKey(self::$user,'user_teltrue');
	}
	/**
	 * 用户余额
	 */
	static function GetMoney()
	{
		return GetKey(self::$user,'user_money',0);
	}
	/**
	 * 用户冻结的金额
	 */
	static function GetMoneyFreeze()
	{
		return GetKey(self::$user,'user_money_freeze',0);
	}
	/**
	 * 用户上次登录时间
	 */
	static function GetLoginTime()
	{
		return date( 'Y-m-d H:i:s' , GetKey(self::$user,'user_logintime',0) );
	}
	/**
	 * 用户注册时间
	 */
	static function GetRegTime()
	{
		return date( 'Y-m-d H:i:s' , GetKey(self::$user,'user_regtime',0) );
	}
	/**
	 * 获得等级信息
	 */
	static function GetLV( $uid = '' , $exp = '' )
	{
		$lv['start'] = $lv['end'] = $lv['name'] = $lv['up_exp'] = $lv['number'] = $lv['next_exp'] = $lv['level_shelf'] = $lv['level_coll'] = 0;
		$lv['next_name'] = C('user.lv_max',null,'lang');
		$lv['is_max'] = 1;
		if ( $uid == '' && $exp == '' )
		{
			$uid = self::GetUid();
			$exp = self::GetExp();
			if( !empty(self::$lv) )
			{
				$lv = self::$lv;
			}
		}
		
		//是否已经有等级了
		if ( (self::GetUid() > 0 && ( !is_array($lv) || $lv == '' )) || ( $uid != '' && $exp != '' ) )
		{
			//当前等级信息
			$where['table'] = '@user_level';

			$where['where']['level_start'] = array('<=' , $exp);
			$where['order'] = 'level_end desc';
			$where['limit'] = '1';
			$data = wmsql::GetOne($where);
			
			$lv = $data;
			$lv['start'] = $data['level_start'];
			$lv['end'] = $data['level_end'];
			$lv['name'] = $data['level_name'];
			$lv['up_exp'] = $data['level_end'] - $exp;
			$lv['number'] = $data['level_order'];
			$lv['next_exp'] = $data['level_end'];
			//下一级名
			$lv['next_name'] = C('user.lv_max',null,'lang');
			//是否满级
			$lv['is_max'] = 1;
		
			
			//下级信息
			$where['where'] = array('level_order'=>array('>',$data['level_order']));
			$where['order'] = 'level_end asc';
			$data = wmsql::GetOne($where);
			//是否存在下一级经验等级
			if ( $data )
			{
				$lv['is_max'] = 0;
				$lv['next_name'] = $data['level_name'];
			}

			
			self::$lv = $lv;
		}
		
		return $lv;
	}
	
	
	/**
	 * 获得最新、所有、已读的消息条数
	 */
	static function GetMsg()
	{
	    $msg['sum_msg'] = $msg['new_msg'] = $msg['read_msg'] = 0;
		if ( self::GetUid() > 0 && ( !is_array(self::$msg) || self::$msg == '' ) )
		{
			//所有消息
			$where['table'] = '@user_msg';
			$where['where'] = array('msg_tuid'=>self::GetUid());
			$count = wmsql::GetCount($where);
			$msg['sum_msg'] = $count;
			
			//新消息
			$where['where']['msg_status'] = 0;
			$count = wmsql::GetCount($where);
			$msg['new_msg'] = $count;
			
			//已读消息
			$msg['read_msg'] = $msg['sum_msg'] - $msg['new_msg'];
		}
		self::$msg = $msg;
		return self::$msg;
	}



	/**
	 * 获得签到信息
	 */
	static function GetSignIn()
	{
	    $arr['today'] = $arr['rank'] = $arr['pre'] = $arr['prerank'] = $arr['con'] = $arr['sum'] = $arr['sign'] = 0;
		//uid大于0并且开启签到
		if ( self::GetUid() > 0 && C('sign_open',null,'userConfig') == '1' )
		{
			//查询签到信息
			$where['table'] = '@user_sign';
			$where['where'] = array('user_id'=>self::GetUid());
			$where['order'] = 'sign_id desc';
			$data = wmsql::GetOne($where);

			//如果没有签到记录就插入一条
			if ( !$data )
			{
				WMSql::Insert($where['table'], array('user_id'=>self::GetUid()));
				//今天签到和本次签到时间
				$arr['today'] = C('user.sign_today',null,'lang');
				$arr['pre'] = C('user.sign_pre',null,'lang');
				//连续签到//总签到
				$arr['con'] = '0';
				$arr['sum'] = '0';
				//今天是否签到了
				$arr['sign'] = '0';
				//上次和本次签到排名
				$arr['prerank'] = C('user.sign_today',null,'lang');
				$arr['rank'] = C('user.sign_today',null,'lang');
			}
			else
			{
				$arr['con'] = $data['sign_con'];
				$arr['sum'] = $data['sign_sum'];
				$arr['prerank'] = $data['sign_prerank'];
				$arr['rank'] = $data['sign_rank'];
				//上次签到时间
				if ( $data['sign_pretime'] == '0' )
				{
					$arr['pre'] = C('user.sign_pre',null,'lang');
					$arr['prerank'] = C('user.sign_pre',null,'lang');
				}
				else
				{
					$arr['pre'] = date("Y-m-d H:i:s",$data['sign_pretime']);
				}
				//签到时间
				if ( $data['sign_time'] == '0' || date("Y-m-d",$data['sign_time']) != date("Y-m-d",time()))
				{
					//如果签到的时间不是今天，那么上次的签到时间就是当前数据
					if( date("Y-m-d",$data['sign_time']) != date("Y-m-d",time()) )
					{
						$arr['pre'] = date("Y-m-d H:i:s",$data['sign_time']);
						$arr['prerank'] = $data['sign_rank'];
					}
					$arr['today'] = C('user.sign_today',null,'lang');
					$arr['rank'] = C('user.sign_today',null,'lang');
				}
				else
				{
					$arr['today'] = date("Y-m-d H:i:s",$data['sign_time']);
				}
				
				//今日未签到
				if ( strtotime('today') > $data['sign_time'] )
				{
					$arr['sign'] = '0';
				}
				else
				{
					$arr['sign'] = '1';
				}
			}
		}
		return $arr;
	}
	
	
	//获得收藏等属性
	static function GetColl( $module , $type )
	{
		//uid大于0并且开启签到
		if ( self::GetUid() > 0 )
		{
			$where['table'] = '@user_coll';
			$where['where']['coll_module'] = $module;
			$where['where']['coll_type'] = $type;
			//如果是推荐
			if ( $type == 'rec' )
			{
				$wheresql['where']['coll_time'] = array('+',strtotime(date("Y-m-1")));
			}
			$where['where']['user_id'] = self::GetUid();
			
			return wmsql::GetCount($where);
		}
	}

	
	/**
	 * 获得用户的财务信息
	 */
	static function GetFinance()
	{
		$where['table'] = self::$financeTabel;
		$where['where']['finance_user_id'] = self::$user['user_id'];
		return wmsql::GetOne($where);
	}
}
?>
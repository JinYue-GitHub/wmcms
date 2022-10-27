<?php
/**
* 用户模型
*
* @version        $Id: user.model.php 2016年5月27日 11:48  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class UserModel
{
	public $table = '@user_user';
	public $apiTable = '@user_apilogin';
	//用户头像
	public $head;
	//邮箱验证状态
	public $emailTrue;
	//用户密码
	public $psw;
	//用户密码盐
	public $salt;
	//用户数据
	public $data;
	//用户注册类型
	private $userType = array();
	
	//构造函数
	function __construct(){}
	
	function GetUserType($type='')
	{
		if( empty($this->userType) )
		{
			$userTypeArr = C('config.api');
			foreach ($userTypeArr as $key=>$val)
			{
				if($val['api_type'] == '2' || $val['api_type'] == '8')
				{
					$userType[$key] = $val['api_title'];
				}
			}
			$userType['default'] = '普通注册';
			$this->userType = $userType;
		}
		if( $type != '' )
		{
			return $this->userType[$type];
		}
		else
		{
			return $this->userType;
		}
	}

	/**
	 * 注册用户账号
	 * @param 参数1，必须
	 * @param name，必须，账号
	 * @param psw，必须，用户密码
	 * @param email，选填，用户的邮箱
	 * @param type，选填，注册类型
	 * @param sex，选填，用户性别
	 */
	function Reg($data)
	{
		$userConfig = GetModuleConfig('user');
		$lang = GetModuleLang('user');
		//插入数据
		$name = $data['name'];
		$nickName = str::IsEmpty(GetKey($data,'nickname'),'',$name);
		$salt = str::GetSalt();
		$psw = str::E($data['psw'],$salt);
		$email = GetKey($data,'email');
		$emailtrue = GetKey($data,'emailtrue');
		$tel = GetKey($data,'tel');
		$teltrue = GetKey($data,'teltrue');
		//是否是api登录
		$api = str::Int(GetKey($data,'api'),'','0');
		//注册类型
		$type = str::IsEmpty(GetKey($data,'type'),'','default');
		$sex = str::CheckElse(GetKey($data,'sex'), '2','2','1');
		//是默认头像还是随机头像
		$head = $userConfig['default_head'];
		if( $userConfig['user_head'] == '1')
		{
			$headMod = NewModel('user.head');
			$headData = $headMod->RandOne();
			if ( $headData )
			{
				$head = $headData['head_src'];
			}
		}
	
		//用户信息
		$addData['user_type'] = $type;
		$addData['user_name'] = $name;
		$addData['user_nickname'] = $nickName;
		$addData['user_psw'] = $psw;
		$addData['user_salt'] = $salt;
		$addData['user_status'] = $userConfig['reg_status'];
		$addData['user_email'] = $email;
		$addData['user_emailtrue'] = $emailtrue=='1'?1:0;
		$addData['user_tel'] = $tel;
		$addData['user_teltrue'] = $teltrue=='1'?1:0;
		$addData['user_head'] = $head;
		$addData['user_sign'] = $userConfig['reg_sign'];
		$addData['user_sex'] = $sex;
		$addData['user_regip'] = GetIp();
		$addData['user_gold1'] = $userConfig['reg_gold1'];
		$addData['user_gold2'] = $userConfig['reg_gold2'];
		$addData['user_exp'] = $userConfig['reg_exp'];
		$addData['user_regtime'] = time();
		$result = $this->Add($addData);
		//如果插入成功
		if( $result )
		{
			//插入财务信息
			$financeData['finance_user_id'] = $result;
			$financeMod = NewModel('user.finance');
			$financeMod->InsertFinance($financeData);
			//插入推荐票信息
			$ticketMod = NewModel('user.ticket');
			$ticketMod->RegInsert($result , $userConfig['reg_rec'] , $userConfig['reg_month'] , 'all' , $lang['user']['ticket_reg_remark']);
			
			//如果是接口登录就绑定账号
			if( $api == 1 )
			{
				$this->InsertApiLogin($result , $data['api_user']);
			}
			return $result;
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * 新增数据
	 */
	function Add($data=array())
	{
		if( empty($data) )
		{
			$data = $this->data;
		}
		return wmsql::Insert($this->table, $data);
	}
	
	
	/**
	 * 用户登录
	 * @param 参数1，选填，登录的奖励数组
	 */
	function EveryDayLogin( $userId , $rewardData = '' )
	{
		$log['module'] = 'user';
		$log['type'] = 'login';
		$log['gold1'] = $rewardData['gold1'];
		$log['gold2'] = $rewardData['gold2'];
		$log['remark'] = '每日登录赠送！';

		//资金变更
		$this->CapitalChange( $userId , $log , $rewardData['gold1'] , $rewardData['gold2']);
		
		//修改登录时间
		$data['user_logintime'] = time();
		return $this->UpdateExp($userId, $rewardData['exp'] , $data);
	}
	
	
	/**
	 * 更新用户经验值
	 * @param 参数1，必须，用户id
	 * @param 参数2，必须，变更的经验值
	 * @param 参数3，选填，其他的条件
	 */
	function UpdateExp($uid , $exp , $data = '')
	{
		if( $exp != '0' && !empty($data) )
		{
			$lvArr = user::GetLV();
			if( $exp > 0 && $lvArr['is_max'] != '1')
			{
				$data['user_exp'] = array( '+' , $exp );
			}
		}
		if( is_array($data) )
		{
			wmsql::Update($this->table, $data, 'user_id='.$uid);
		}
		return true;
	}
	
	/**
	 * 更新用户奖励
	 * @param 参数1，必须，奖励金币1
	 * @param 参数2，必须，奖励金币2
	 * @param 参数3，必须，奖励经验
	 * @param 参数4，选填，奖励备注
	 */
	function RewardUpdate( $uid , $rewardData , $log , $type = '1')
	{
		if( !isset($rewardData['gold1']) )
		{
			$rewardData['gold1'] = '0';
		}
		if( !isset($rewardData['gold2']) )
		{
			$rewardData['gold2'] = '0';
		}
		if( !isset($rewardData['exp']) )
		{
			$rewardData['exp'] = '0';
		}
		
		$log['gold1'] = $rewardData['gold1'];
		$log['gold2'] = $rewardData['gold2'];
		
		//资金变更
		$this->CapitalChange( $uid , $log , $rewardData['gold1'] , $rewardData['gold2'] , $type );
		
		return $this->UpdateExp($uid, $rewardData['exp']);
	}
	
	
	/**
	 * 用户资金变更记录
	 * @param 参数1，必须，用户id
	 * @param 参数2，必须，资金变更日志
	 * @param 参数3，必须，变更的金币1
	 * @param 参数4，必须，变更的金币2
	 * @param 参数5，选填，变更类型，是加还是减
	 */
	function CapitalChange( $uid , $log , $gold1='0' , $gold2='0' , $type = '1' )
	{
		if( ($gold1 == '0' && $gold2 == '0') || !$log)
		{
			return true;
		}
		else
		{
			$userData = $this->GetOne($uid);
			$operator = '+';
			//交易前/交易后的金币1和金币2
			$logData['log_gold1_before'] = $userData['user_gold1'];
			$logData['log_gold1_after'] = $userData['user_gold1']+$gold1;
			$logData['log_gold2_before'] = $userData['user_gold2'];
			$logData['log_gold2_after'] = $userData['user_gold2']+$gold2;
			if( $type == '2' )
			{
				$operator = '-';
				$logData['log_gold1_before'] = $userData['user_gold1'];
				$logData['log_gold1_after'] = $userData['user_gold1']-$gold1;
				$logData['log_gold2_before'] = $userData['user_gold2'];
				$logData['log_gold2_after'] = $userData['user_gold2']-$gold2;
			}
	
			//变更用户资金
			$data['user_gold1'] = array( $operator , $gold1 );
			$data['user_gold2'] = array( $operator , $gold2 );
			wmsql::Update( $this->table , $data , 'user_id='.$uid );
			
			//插入用户资金变更记录
			$logData['log_status'] = $type;
			$logData['log_module'] = $log['module'];
			$logData['log_type'] = $log['type'];
			$logData['log_user_id'] = $uid;
			if( !isset($log['tuid']) )
			{
				$log['tuid'] = '0';
			}
			if( !isset($log['cid']) )
			{
				$log['cid'] = '0';
			}
			$logData['log_tuser_id'] = $log['tuid'];
			$logData['log_cid'] = $log['cid'];
			$logData['log_gold1'] = $gold1;
			$logData['log_gold2'] = $gold2;
			$logData['log_remark'] = $log['remark'];
			$financeMod = NewModel('user.finance_log');
			return $financeMod->Insert($logData);
		}
	}
	
	
	/**
	 * 检查数据条数
	 * @param 参数1，必须，条件
	 */
	function GetCount( $where )
	{
		$where['where']= $where;
		$where['table'] = $this->table;
		$count = wmsql::GetCount( $where , 'user_id');
		return $count;
	}


	/**
	 * 检查数据条数
	 * @param 参数1，必须，条件，数组或者id
	 */
	function GetOne( $wheresql )
	{
		if( is_array($wheresql) )
		{
			$where['where']= $wheresql;
		}
		else
		{
			$where['where']['user_id'] = $wheresql;
		}
		$where['table'] = $this->table;
		$data = wmsql::GetOne( $where );
		return $data;
	}
	/**
	 * 根据用户账号查询用户数据
	 * @param 参数1，必须，用户账号
	 */
	function GetByName($name)
	{
		$where['user_name'] = $name;
		return $this->GetOne($where);
	}
	/**
	 * 根据API查询用户
	 * @param 参数1，必须，登录类型
	 * @param 参数2，必须，openid
	 * @param 参数3，选填，unionid
	 */
	function GetUserByApi($loginType , $openid ,$unionid='')
	{
		$where['table'] = $this->table;
		$where['left'][$this->apiTable] = 'api_uid=user_id';
		$where['where']['api_type'] = $loginType;
		//联合ID为空就查询openid
		if( empty($unionid) )
		{
			$where['where']['api_openid'] = $openid;
		}
		else
		{
			$where['where']['api_unionid'] = $unionid;	
		}
		return wmsql::GetOne($where);
	}
	
	
	/**
	 * 保存用户属性
	 * @param 参数1，必须，修改的数据
	 * @param 参数2，选填，用户id
	 */
	function Save( $data , $uid = '')
	{
		if( $uid == '' )
		{
			$uid = user::GetUid();
		}
		return wmsql::Update($this->table, $data , 'user_id='.$uid);
	}
	/**
	 * 保存用户头像
	 */
	function SaveHead($uid)
	{
		$data['user_head'] = $this->head;
		return $this->Save( $data ,$uid);
	}
	/**
	 * 修改认证状态
	 */
	function SaveAuthTrue($uid,$type,$val)
	{
	    if( $type == 'email' )
	    {
	        return $this->SaveEmailTrue($uid,$val);
	    }
	    else
	    {
	        return $this->SaveTelTrue($uid,$val);
	    }
	    return false;
	}
	/**
	 * 修改用户邮箱验证状态
	 */
	function SaveEmailTrue($uid,$email)
	{
		$data['user_email'] = $email;
		$data['user_emailtrue'] = 1;
		return $this->Save( $data ,$uid);
	}
	/**
	 * 修改用户手机号验证状态
	 */
	function SaveTelTrue($uid,$tel)
	{
		$data['user_tel'] = $tel;
		$data['user_teltrue'] = 1;
		return $this->Save( $data ,$uid);
	}
	/**
	 * 修改用户密码
	 */
	function SavePsw($uid)
	{
		$data['user_psw'] = $this->psw;
		$data['user_salt'] = $this->salt;
		return $this->Save( $data ,$uid);
	}
	/**
	 * 修改用户账号状态
	 */
	function SaveDisplay($uid)
	{
		$data['user_display'] = 1;
		return $this->Save( $data ,$uid);
	}
	/**
	 * 修改用户是否首充
	 */
	function SaveCharge($uid)
	{
		$data['user_ischarge'] = 1;
		return $this->Save( $data , $uid);
	}
	
	
	/**
	 * 获得用户的api登录信息
	 * @param 参数1，必须，登录类型
	 * @param 参数2，必须，openid
	 * @param 参数3，选填，用户id
	 * @param 参数4，选填，unionid
	 */
	function GetApiLogin($loginType , $openid , $uid = '',$unionid='')
	{
		$where['table'] = $this->apiTable;
		$where['where']['api_type'] = $loginType;
		if( empty($unionid) )
		{
			$where['where']['api_openid'] = $openid;
		}
		else
		{
			$where['where']['api_unionid'] = $unionid;
		}
		if( $uid != '' )
		{
			$where['where']['api_uid'] = $uid;
		}
		return wmsql::GetOne($where);
	}
	
	/**
	 * 删除指定用户的所有api登录信息
	 * @param 参数1，必须，用户id
	 */
	function DelApiLogin($uid)
	{
		return wmsql::Delete($this->apiTable , array('api_uid'=>$uid));
	}
	
	/**
	 * 插入用户的接口登录绑定信息
	 * @param 参数1，必须，用户id
	 * @param 参数2，必须，第三方登录数据
	 */
	function InsertApiLogin($uid , $apiData)
	{
	    $apiLoginOpenid = $apiData['openid'];
		$apiLoginType = $apiData['api'];
		$apiLoginUnionid = isset($apiData['unionid'])?$apiData['unionid']:'';
		if( $apiLoginOpenid == '' || $apiLoginType == '' )
		{
		    return false;
		}
		else
		{
    		if( $this->GetApiLogin($apiLoginType, $apiLoginOpenid , $uid) )
    		{
    			return false;
    		}
    		else
    		{
    			$data['api_uid'] = $uid;
    			$data['api_type'] = $apiLoginType;
    			$data['api_openid'] = $apiLoginOpenid;
    			$data['api_unionid'] = $apiLoginUnionid;
    			return WMSql::Insert($this->apiTable, $data);
    		}
		}
	}

	
	/**
	 * 更新用户余额
	 * @param 参数1，必须，用户id
	 * @param 参数2，必须，变更金额
	 * @param 参数3，选填，操作类型，1为收入，2为支出，为空则写入固定值
	 */
	function UpdateMoney($uid , $money, $operator='')
	{
		//1为收入
		if( $operator == '1' )
		{
			$data['user_money'] = array( '+' , $money );
		}
		//2为支出
		else if( $operator == '2' )
		{
			$data['user_money'] = array( '-' , $money );
		}
		//为空则写入固定值
		else
		{
			$data['user_money'] = $money;
		}
		return wmsql::Update($this->table, $data, 'user_id='.$uid);
	}


	/**
	 * 获得用户重置密码的临时key
	 * @param 参数1，必须，用户数据
	 */
	function GetPswKey($data)
	{
		$key = str::E(json_encode($data).str::GetSalt());
		$content = time().'||'.str::Encrypt($data['user_name']);
		$file = WMCACHE.'log/run/'.date('Y-m').'/'.date('m-d').'_uppsw_'.$key.'.txt';
		file::CreateFile($file,$content,1);
		return $key;
	}
	/**
	 * 检查重置密码的临时key是否正确
	 * @param 参数1，必须，临时KEY
	 */
	function CheckGetPswKey($key)
	{
		$file = WMCACHE.'log/run/'.date('Y-m').'/'.date('m-d').'_uppsw_'.$key.'.txt';
		$content = explode('||',file::GetFile($file));
		if( count($content)==2 && time()-intval($content[0]) < 300 )
		{
			return str::Encrypt($content[1],'D');
		}
		return false;
	}
	/**
	 * 删除重置密码的临时key
	 * @param 参数1，必须，临时KEY
	 */
	function DelGetPswKey($key)
	{
		$file = WMCACHE.'log/run/'.date('Y-m').'/'.date('m-d').'_uppsw_'.$key.'.txt';
		return file::DelFile($file);
	}
}
?>
<?php
//参数相关
$lang['user']= array(
	'api_no'=>'对不起，api文件不存在！',
	'api_close'=>'对不起，当前登录接口已经关闭！',
	'api_openid_err'=>'对不起，OPENID获取失败，重新登录！',
	'api_data_err'=>'对不起，接口返回的用户信息为空！',
	'api_bind_err'=>'对不起，绑定账号失败！',
	'api_bind_exist'=>'对不起，已被其他账号绑定！',
	'no_sub'=>'对不起，请先关注公众号！',
		
	'exit'=>'您已经成功退出登录，3秒后跳转到首页！',
	'islogin'=>'对不起,您已经登录了！',
	'no_login'=>array('info'=>'对不起,请先登录！','gourl'=>'{登录}','html'=>'<a href="{登录}">前往登录</a>'),
	'login_close'=>'对不起,网站已经关闭登录！',
	'reg_close'=>'对不起,网站已经关闭了新用户注册！',
	'login_success'=>array('info'=>'恭喜您登录成功！','gourl'=>'{用户中心}','html'=>'<a href="{用户中心}">用户中心</a>'),
	'psw_err'=>'密码错误',
		
	'user_sex_1'=>'男',
	'user_sex_2'=>'女',

	'lv_no'=>'无等级',
	'lv_max'=>'满级',
	'no'=>'对不起，该用户不存在！',
		
	'mid_err'=>'对不起,消息id必须为数字！',
	'msg_no'=>'对不起,暂时没有此id的消息！',
	'msg_name'=>'系统',
	'msg_nickname'=>'系统',
	
	'sign_close'=>'对不起，签到系统已经关闭!',
	'sign_today'=>'未签到',
	'sign_pre'=>'未签到',

	'fuid_err'=>'对不起,好友id必须为数字！',
		
	'coll_module'=>'对不起,收藏模块错误！',
	'coll_type'=>'对不起,操作类型错误！',
	
	'read_module'=>'对不起,阅读记录模块错误！',
		
	'no_key'=>'对不起，请先验证账号信息！',
	'key_exp'=>'对不起，验证超时请在五分钟内验证码！',
	'account_status_0'=>'对不起，您的账号正在审核中！',
	'account_display_0'=>'对不起，您的账号已被全站封禁！',
	'account_display_2'=>'对不起，您的账号已经被封禁到{时间}！',
	'openid_err'=>'对不起,OPENID获取失败，请重新登录！',
	'apilogin_err'=>'您的接口登录已经更新，请重新登录！',

	'gold1_no'=>'对不起，您的{金币1名字}数量不足！',
	'gold2_no'=>'对不起，您的{金币2名字}数量不足！',

	'ticket_reg_remark'=>'注册账号赠送！',
	'ticket_login_remark'=>'每日登录赠送！',
	'card_charge_remark'=>'卡号充值收入！',
		
	'no_code'=>'对不起，二维码不能为空！',
	'no_sn'=>'对不起，订单号不能为空！',
	'no_order'=>'对不起，订单不存在！',
);
return $lang;
?>
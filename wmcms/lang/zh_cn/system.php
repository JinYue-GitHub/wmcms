<?php
//模版相关
$lang['system']['domain'] = array(
	'admin_access'=>'对不起，请通过后台绑定域名访问！',
);

//模版相关
$lang['system']['templates'] = array(
	'noinstall'=>'对不起，您没有安装过此模版，请在后台》模版管理》选择模版安装并应用！',
);
$lang['system']['file'] = array(
	'no'=>'对不起，文件不存在！',
);

//操作相关
$lang['system']['operate'] = array(
	'success'=>'恭喜您，操作成功！',
	'fail'=>'对不起，操作失败！',
	'autojump'=>'自动跳转中,请稍候...',
	'limit'=>'请求频繁，60秒后再试',
);


//分类相关
$lang['system']['type'] = array(
	'no'=>'对不起，该分类不存在或者已经删除！',
);


//变量相关
$lang['system']['par'] = array(
	'err'=>'对不起，参数不正确！',
	'ip_err'=>'对不起，ip地址不正确！',
	'no_url'=>'javascript:void(0)',
	'qq_err'=>'对不起，qq号码格式错误！',
	'name_err'=>'对不起，真实姓名格式错误！',
	'cardid_err'=>'对不起，身份证号码格式错误！',
	'phone_err'=>'对不起，手机码格式错误！',
	'address_err'=>'对不起，联系地址不能为空！',
	'module_err'=>'对不起，模块错误！',
		
	'token_err'=>'对不起，表单TOKEN错误，请刷新后重试！',
	'token_time'=>'对不起，表单提交超时，请返回刷新后重新提交！',
	'code_err'=>'对不起，验证码错误！',
	'code_no'=>'对不起，验证码不能为空！',
	
	'smtp_no'=>'对不起，请添加邮件服务配置！',
);


//内容相关
$lang['system']['content'] = array(
	'no'=>'对不起，该内容不存在或者已经删除！',
	'no_predata'=>'没有了',
	'no_preurl'=>'javascript:void(0)',
	'no_nextdata'=>'没有了',
	'no_nexturl'=>'javascript:void(0)',
	'no_url'=>'javascript:void(0)',
);

//模块相关
$lang['system']['module'] = array(
	'module_no'=>'对不起，当前模块不存在！',
	'getdata_no'=>'对不起，当前模块的GetData方法中数据类型不存在！',
);

//方法相关
$lang['system']['action'] = array(
	'success'=>'请求成功！',
	'fail'=>'请求失败！',
	'no_action'=>'对不起，方法参数缺少处理文件！',
	'no_file'=>'对不起，方法文件不存在！',
);

//财务相关
$lang['system']['finance'] = array(
	'pay_fail'=>'支付失败',
	'pay_fail_sign'=>'支付签名校验失败',
	'pay_success'=>'支付成功',
	'order_subject'=>'在线充值！',
	'order_body'=>'在线充值{money}元',
	'order_remark'=>'充值{money}元奖励！',
	'order_first_remark'=>'首冲奖励！',
	'order_reward_remark'=>'一次性充值{money}元奖励！',
);

//消息相关
$lang['system']['msg'] = array(
	'at_article'=>'用户 “{用户昵称}” 在文章 《<a href="{url}">{内容标题}</a>》的评论中@了你！',
	'at_novel'=>'用户 “{用户昵称}” 在小说 《<a href="{url}">{内容标题}</a>》的评论中@了你！',
	'at_bbs'=>'用户 “{用户昵称}” 在帖子 《<a href="{url}">{内容标题}</a>》的评论中@了你！',
	'at_replay_article'=>'用户 “{用户昵称}” 评论了您的原创文章 《<a href="{url}">{内容标题}</a>》！',
	'at_replay_novel'=>'用户 “{用户昵称}” 评论了您的原创小说 《<a href="{url}">{内容标题}</a>》！',
	'at_replay_bbs'=>'用户 “{用户昵称}” 回复了您的帖子 《<a href="{url}">{内容标题}</a>》',
	
	//短信消息
	'sms_api_no'=>'没有开启任何短信API接口！',
	'sms_temp_no'=>'短信模版"{type}"不存在！',
	'email_temp_no'=>'邮件模版"{type}"不存在！',
	'msg_temp_no'=>'消息模版"{type}"不存在！',
	'email_temp_status'=>'邮件模版"{type}"未启用！',
	'warning_close'=>'预警通知已关闭！',
);

//插件相关
$lang['system']['plugin'] = array(
	'm_no'=>'对不起，插件名不能为空！',
	'c_no'=>'对不起，插件控制器不能为空！',
	'a_no'=>'对不起，插件方法不能为空！',
	'file_err'=>'插件文件{文件路径}不存在！',
	'no_install'=>'插件没有安装！',
	'class_err'=>'插件控制器类名必须为WMCMSPlugin！',
	'action_no'=>'插件方法名{方法名}不存在！',
);

//自定义标签
$lang['system']['label'] = array(
	'diy'=>'自定义字段',
);

//自定义标签
$lang['system']['user'] = array(
	'no_login'=>'对不起，请先登录',
);
?>
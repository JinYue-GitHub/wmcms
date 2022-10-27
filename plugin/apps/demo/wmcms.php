<?php
/**
* 插件应用
*
* @version        $Id: wmcms.php 2018年6月5日 20:55  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
class WMCMSPlugin extends Plugin
{
	private $table = '@plugin_demo_apply';
	function __construct()
	{
	}
	
	//pc首页方法
	public function Action_index()
	{
		tpl::SetLabel('基本',C('plugin.demo.base'));
		tpl::SetLabel('结束',C('plugin.demo.end'));
		tpl::SetLabel('方法',PluginDemoTest());
	}

	//移动方法
	public function Action_index_m()
	{
	}
	
	
	//报名方法
	public function Action_apply()
	{
		$siteOpen = plugin::GetConfig('site_open');
		$name = Post('name');
		$phone = Post('phone');
		if( $name == '' || $phone == '' )
		{
			ReturnJson('对不起，姓名和手机号不能为空！');
		}
		else if( !PluginDemoCheckMobile($phone) || !str::IsTel($phone) )
		{
			ReturnJson('对不起，手机号格式错误！');
		}
		else if( $siteOpen != 1 )
		{
			ReturnJson('对不起，申请已经关闭！');
		}
		else
		{
			$where['table'] = $this->table;
			$where['where']['message_phone'] = $phone;
			$data = wmsql::GetOne($where);
			if( $data )
			{
				ReturnJson('对不起，该手机号已经提交了！');
			}
			else
			{
				$data['message_name'] = $name;
				$data['message_phone'] = $phone;
				$data['message_time'] = time();
				wmsql::Insert($this->table, $data);
				ReturnJson('报名成功！');
			}
		}
	}
}
?>
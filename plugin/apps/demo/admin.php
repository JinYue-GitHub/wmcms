<?php
/**
* 插件后台管理
*
* @version        $Id: admin.php 2018年6月16日  09:21  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
class WMCMSPluginAdmin extends Plugin
{
	private $table = '@plugin_demo_apply';
	function __construct()
	{
		
	}
	
	//基本参数设置
	public function Action_config()
	{
		$siteOpen = plugin::GetConfig('site_open');
		tpl::SetLabel('申请开关', $siteOpen);
	}

	//报名列表
	public function Action_apply_list()
	{
		//搜索关键字
		$key = Request('key');

		//查询数据的总条数
		$where['table'] = $this->table;
		$count = wmsql::GetCount($where,'message_id');

		//查询当前分页的数据
		$where['table'] = $this->table;
		$where['limit'] = wmsql::GetLimit(C('page.pageSize'),C('page.pageCurrent'));
		$where['order'] = 'message_id desc';
		if( $key != '' )
		{
			$where['where']['message_phone'] = array('like',$key);
		}
		//合并排序
		$where = array_merge($where , GetListWhere($where));
		$list = wmsql::GetAll($where);

		C('page.pageCount',$count);
		C('plugin.demo.apply',$list);
	}
	
	//查看报名详情
	public function Action_apply_detail()
	{
		$aid = Request('aid');
		$where['table'] = $this->table;
		$where['where']['message_id'] = $aid;
		$data = wmsql::GetOne($where);
		
		C('plugin.data',$data);
	}

	//删除报名
	public function Action_apply_del()
	{
		$where['message_id'] = GetDelId('aid');
		//删除标题
		wmsql::Delete($this->table, $where);
		Ajax('删除成功！');
	}
}
?>
<?php
use Qiniu\json_decode;
/**
* 域名处理器
*
* @version        $Id: system.api.php 2016年3月28日 11:12  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$manager = AdminNewClass('manager');

//如果请求信息存在
if( $post )
{
	//前置判断是否绑定了后台域名，并且为开启状态。如果是就进行验证，是否能够访问。
	if( $post['admin_domain'] != '' && $C['config']['web']['admin_path'] != '' && GetKey($post,'395') != '' )
	{
		$httpSer = NewClass('http');
		$httpSer->timeout = 4;
		$code = $httpSer->GetUrl($post['admin_domain'].'/'.$post['admin_path']);
		if( empty($code) || !$code )
		{
			Ajax('对不起，请确保后台域名能够正常访问，否则将无法访问后台！',300);
		}
	}
	else
	{
		//$post['395'] = 0;
	}

	//模版域名设置修改
	foreach ($post as $k=>$v)
	{
		if( $k != 'domain' )
		{
			$where = array();
			$data = array();
			
			//如果是id设置
			if(str::Int($k)=='395' || $k == 'admin_domain_access')
			{
				$where['config_id'] = 395;
			}
			//普通
			else
			{
				$where['config_name'] = $k;	
			}
			$data['config_value'] = $v;
			$data = str::Escape($data , 'e');

			wmsql::Update('@config_config', $data, $where);
		}
	}

	//写入操作记录
	SetOpLog( '修改域名配置' , 'system' , 'update' );
	
	//更新配置文件
	$manager->UpConfig('web');

	
	//模块域名绑定
	foreach ($post['domain'] as $k=>$v)
	{
		$where = array();
		$data = array();
		$where['domain_id'] = $k;
		$data['domain_domain'] = $v;
		wmsql::Update('@system_domain', $data, $where);
	}
	
	//更新域名配置文件
	$domainWhere['table'] = '@system_domain';
	$domainArr = wmsql::GetAll($domainWhere);
	$config = '';
	foreach ($domainArr as $v)
	{
		$v = str::Escape($v , 'e');
		$config .="'{$v['domain_name']}' => array('domain'=>'{$v['domain_domain']}','index'=>'{$v['domain_index']}'),";
	}
	file_put_contents( WMCONFIG."domain.config.php" , '<?php $C["config"]["domain"]=array('.$config.');?>');
	
	Ajax();
}
?>
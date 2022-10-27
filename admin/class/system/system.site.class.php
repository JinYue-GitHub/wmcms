<?php
/**
 * 站外站点类文件
 *
 * @version        $Id: system.site.class.php 2017年6月11日 22:39  weimeng
 * @package        WMCMS
 * @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
 * @link           http://www.weimengcms.com
 *
 */
class SystemSite
{
	private $table = '@site_site';
	public $status = array(0=>'禁用',1=>'正常');
	public $domainType = array(1=>'单域名',2=>'泛解析');
	public $type = array(1=>'数据独立',2=>'数据同步');
	
	

	/**
	 * 生成新的站群配置
	 */
	function UpConfig()
	{
		$config = '';
		$where['table'] = $this->table;
		$configArr = wmsql::GetAll($where);
		$config = '';
		if( $configArr )
		{
			foreach ($configArr as $v)
			{
				$v = str::Escape($v , 'e');
				$config .="'".GetDomain($v['site_domain'],false)."_{$v['site_domain_type']}' => array('id'=>'{$v['site_id']}','type'=>'{$v['site_type']}','template'=>'{$v['site_template']}'),";
			}
		}
		file_put_contents( WMCONFIG."site.config.php" , '<?php $C["config"]["site"]=array('.$config.');?>');
	}
	
	
	/**
	 * 获得后台登录的json信息
	 * @param 参数1，必须，后台的数据
	 */
	function GetJson($data)
	{
		$httpSer = NewClass('http');
		$domain = $data['product_domain'].'/';
		$path = $data['product_admin'].'/';
		$url = $domain.$path.'index.php?isAjax=1&a=yes&c=login&name='.$data['product_name'].'&psw='.$data['product_psw'];
		$data = $httpSer->GetUrl($url);
		return json_decode($data,1);
	}
	
	/**
	 * 返回状态的信息
	 * @param 参数1，必须，状态id
	 */
	function GetStatus($val)
	{
		return $this->status[$val];
	}
	/**
	 * 返回域名类型
	 * @param 参数1，必须
	 */
	function GetDomainType($val)
	{
		return $this->domainType[$val];
	}
	/**
	 * 返回站点类型
	 * @param 参数1，必须
	 */
	function GetType($val)
	{
		return $this->type[$val];
	}
}
?>
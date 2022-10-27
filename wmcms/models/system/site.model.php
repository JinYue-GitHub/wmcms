<?php
/**
* 站群模块模型
*
* @version        $Id: site.model.php 2017年6月11日 14:56  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class SiteModel
{
	public $productTable = '@site_product';
	public $siteTable = '@site_site';
	
	function __construct( $data = '' ){}


	/**
	 * 根据获得一条数据
	 * @param 参数1，必须，数据id或者查询条件
	 */
	function ProGetOne( $wheresql )
	{
		$where['table'] = $this->productTable;
		if( !is_array($wheresql) )
		{
			$where['where']['product_id'] = $wheresql;
		}
		else
		{
			$where['where'] = $wheresql;
		}
		return wmsql::GetOne($where);
	}
	/**
	 * 插入数据
	 * @param 参数1，必须，需要插入的数据
	 */
	function ProInsert($data)
	{
		$data['product_time'] = time();
		return wmsql::Insert($this->productTable, $data);
	}
	/**
	 * 根据获得全部相关数据
	 * @param 参数1，必须，查询条件
	 */
	function ProGetCount($where)
	{
		$where['table'] = $this->productTable;
		return wmsql::GetCount($where , 'product_id');
	}
	/**
	 * 根据获得全部相关数据
	 * @param 参数1，选填，查询条件
	 */
	function ProGetAll($wheresql='')
	{
		if( is_array($wheresql) )
		{
			$where = $wheresql;
		}
		$where['table'] = $this->productTable;
		$where['order'] = 'product_order';
		return wmsql::GetAll($where);
	}
	/**
	 * 修改站内站点
	 * @param 参数1，必须，查询条件
	 */
	function ProUpdate($data,$where)
	{
		return wmsql::Update($this->productTable, $data, $where);
	}
	/**
	 * 删除产品站点
	 * @param 参数1，必须，查询条件
	 */
	function ProDel($where='')
	{
		return wmsql::Delete($this->productTable,$where);
	}
	
	

	/**
	 * 根据获得一条数据
	 * @param 参数1，必须，数据id或者查询条件
	 */
	function SiteGetOne( $wheresql )
	{
		$where['table'] = $this->siteTable;
		if( !is_array($wheresql) )
		{
			$where['where']['site_id'] = $wheresql;
		}
		else
		{
			$where['where'] = $wheresql;
		}
		return wmsql::GetOne($where);
	}
	/**
	 * 插入数据
	 * @param 参数1，必须，需要插入的数据
	 */
	function SiteInsert($data)
	{
		$data['site_time'] = time();
		return wmsql::Insert($this->siteTable, $data);
	}
	/**
	 * 根据获得全部相关数据
	 * @param 参数1，必须，查询条件
	 */
	function SiteGetCount($where)
	{
		$where['table'] = $this->siteTable;
		return wmsql::GetCount($where , 'site_id');
	}
	/**
	 * 根据获得全部相关数据
	 * @param 参数1，必须，查询条件
	 */
	function SiteGetAll($wheresql='')
	{
		if( is_array($wheresql) )
		{
			$where = $wheresql;
		}
		$where['table'] = $this->siteTable;
		$where['order'] = 'site_order';
		return wmsql::GetAll($where);
	}
	/**
	 * 修改站内站点
	 * @param 参数1，必须，查询条件
	 */
	function SiteUpdate($data,$where)
	{
		return wmsql::Update($this->siteTable, $data, $where);
	}
	/**
	 * 删除站内站点
	 * @param 参数1，必须，查询条件
	 */
	function SiteDel($where='')
	{
		return wmsql::Delete($this->siteTable,$where);
	}
}
?>
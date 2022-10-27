<?php
/**
* 小说票类功能模块类文件
*
* @version        $Id: ticket.class.php 2022年03月20日 10:10  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
class ticket
{
	static $ticketTable = '@user_ticket_log';
	static protected $module = '';
	static protected $cid = '';
	
	//构造方法
	function __construct($data=array())
	{
		if( $data )
		{
			self::$module = $data['module'];
			self::$cid = $data['cid'];
		}
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
			//推荐票排行获取
			case 'ticket':
				$wheresql['table'] = self::$ticketTable;
				$wheresql['field'] = 'SUM(log_month+log_rec) as log_all,SUM(log_month) as log_month,SUM(log_rec) as log_rec,user_nickname,user_head';
				$wheresql['left'][user::$userTabel] = 'log_user_id=user_id';
				$wheresql['where']['log_module'] = self::$module;
				$wheresql['where']['log_cid'] = self::$cid;
				$wheresql['where']['log_status'] = 2;
				$wheresql['group'] = 'log_user_id';
				$wheresql['order'] = 'log_all desc';
				if( isset($wheresql['where']['order']) && $wheresql['where']['order']=='月票' )
				{
					$wheresql['order'] = 'log_month desc';
				}
				if( isset($wheresql['where']['order']) && $wheresql['where']['order']=='推荐票' )
				{
					$wheresql['order'] = 'log_rec desc';
				}
				unset($wheresql['where']['order']);
				break;

			//推荐排名数据获取
			case 'ticket_rank':
				//关闭检查子查询
				wmsql::$checkSql = false;
				//不处理条件绑定
				wmsql::$wherePrepare = false;
				//排名字段查询
				$rankField = 'log_all';
				if( isset($wheresql['where']['order']) && $wheresql['where']['order']=='月票' )
				{
					$rankField = 'log_month';
				}
				if( isset($wheresql['where']['order']) && $wheresql['where']['order']=='推荐票' )
				{
					$rankField = 'log_rec';
				}
				//获取查询条件
				unset($wheresql['where']['order']);
				$wheresql['where']['log_status'] = 2;
				$wheresql['where']['log_module'] = self::$module;
				$where = wmsql::CheckWhere($wheresql['where']);
        		$limit = wmsql::Checklimit($wheresql['limit']);
        		$table = wmsql::CheckTable(self::$ticketTable);
				//所有排名数据临时表
				$tableA = "SELECT SUM(log_month+log_rec) AS log_all,SUM(log_month) AS log_month,SUM(log_rec) AS log_rec,log_cid 
							FROM {$table} {$where} GROUP BY log_cid ORDER BY {$rankField}  DESC {$limit}";
				//当前小说排名数据
				$tableB = "SELECT SUM(log_month+log_rec) AS log_all FROM {$table} {$where} AND log_cid=".self::$cid;
				$data = wmsql::Query("SELECT {$rankField} as rankField,log_cid FROM ({$tableA}) AS a WHERE log_all>=({$tableB})");
				//清空当前表设置的参数
				wmsql::Clear();
				if( !isset(str::ArrRestKey($data, 'log_cid')[self::$cid]) )
				{
					$data = array();
				}
				return $data;
				break;
				
			default:
				tpl::ErrInfo( C('system.module.getdata_no' , null , 'lang' ) );
				break;
		}

		$data = wmsql::GetAll($wheresql);
		//如果数组为空并且错误提示不为空则输出错误提示。
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
			'模块' =>'log_module',
			'小说' =>'novel',
			'贡献类型' =>'order',
			'贡献时间' =>'log_time',
			'排名时间' =>'log_time',
			'排名类型' =>'order',
		);
		$where = tpl::GetWhere($where,$arr);
		$where['where'] = NewClass('time')->GetWhere($where['where']);
		return $where;
	}
}
?>
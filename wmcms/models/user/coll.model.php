<?php
/**
* 用户收藏等操作模型
*
* @version        $Id: coll.model.php 2016年5月28日 21:48  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class CollModel
{
	public $table = '@user_coll';
	//当前模块
	public $module;
	//模块表字段数组
	public $moduleTable;
	//用户id
	public $userId;
	//收藏id
	public $collId;
	//收藏数据
	public $data;
	
	
	
	/**
	 * 构造函数
	 */
	function __construct()
	{
		global $tableSer;
		$this->moduleTable = $tableSer->GetTable($this->module);
	}
	
	
	/**
	 * 插入收藏订阅等操作
	 * @param 参数1，必须，需要插入的数据
	 */
	function Insert($data)
	{
		$where['table'] = $this->table;
		$where['where'] = $data;
		$count = wmsql::GetCount($where);
		if( $count == 0 )
		{
			$data['coll_time'] = time();
			return wmsql::Insert($this->table, $data);
		}
		else
		{
			return false;
		}
	}
	
	
	/**
	 * 获得查询的条件
	 */
	function GetWhere()
	{
		$where['coll_id'] = $this->collId;
		$where['user_id'] = user::GetUid();
		return $where;
	}
	
	
	/**
	 * 查询一条数据
	 * @param 参数1，选填，查询条件条件
	 */
	function GetOne($wheresql='')
	{
		$where['table'] = $this->table;
		if( $wheresql == '' )
		{
			$where['where'] = $this->GetWhere();
		}
		else
		{
			$where['where'] = $wheresql;
		}
		$this->data = $data = wmsql::GetOne($where);
		return $data;
	}
	

	/**
	 * 获得当前收藏的内容
	 * @param 参数1，必须，模块
	 * @param 参数2，必须，收藏类型
	 * @param 参数3，必须，内容id
	 */
	function GetContent($module , $type , $cid)
	{
		$where['table'] = $this->moduleTable[$module]['table'];
		$where['where'][$this->moduleTable[$module]['id']] = $cid;
		$where['left'][$this->table] = 'coll_cid='.$this->moduleTable[$module]['id']." and coll_module='{$module}' and coll_type='{$type}' and {$this->table}.user_id=".user::GetUid();
		$this->data = wmsql::GetOne( $where );
		return $this->data;
	}
	
	/**
	 * 查询数据行数
	 * @param 参数1，必须，模块
	 * @param 参数2，必须，收藏类型
	 * @param 参数3，必须，内容id
	 * @param 参数4，必须，用户id
	 */
	function GetCount($module , $type , $cid , $uid = '0')
	{
		$where['table'] = $this->table;
		$where['where']['coll_module'] = $module;
		$where['where']['coll_type'] = $type;
		$where['where']['user_id'] = $uid;
		$where['where']['coll_cid'] = $cid;
		$count = wmsql::GetCount($where , 'coll_id');
		return $count;
	}
	
	
	/**
	 * 删除一条数据
	 */
	function DelOne()
	{
		$collData  = $this->data;
		if( is_array($collData) )
		{
			$table = $this->moduleTable[$collData['coll_module']]['table'];
			$where[$this->moduleTable[$collData['coll_module']]['id']] = $collData['coll_cid'];
			//如果是小说，并且是收藏就修改字段信息
			if ( $collData['coll_module'] == 'novel' && ( $collData['coll_type'] == 'coll') )
			{
				$novelMod = NewModel('novel.novel');
				wmsql::Dec( $table , 'novel_allcoll' , $where );
			}
		}
		return wmsql::Delete($this->table , $this->GetWhere());
	}
	



	/**
	 * 更新内容表的字段数据
	 * @param 参数1，必须，模块
	 * @param 参数2，必须，收藏类型
	 * @param 参数3，必须，内容id
	 * @param 参数4，选填，变更数量
	 */
	function UpdateContent($module , $type , $cid , $number=1)
	{
	    if( empty($this->data) )
	    {
	        $this->GetContent($module,$type,$cid);
	    }
		//如果是小说，并且是收藏就修改字段信息
		if ( $module == 'novel' && ( $type == 'coll') )
		{
			$novelMod = NewModel('novel.novel');
			$updateData = $novelMod->GetIncArr( $this->data['novel_'.$type.'time'] , $type );
		}
		else
		{
		    return false;
		}
		//修改内容数据
		return wmsql::Update($this->moduleTable[$module]['table'], $updateData, $this->moduleTable[$module]['id'].'='.$cid);
	}
}
?>
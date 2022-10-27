<?php
/**
* 微信自动回复模型
*
* @version        $Id: weixin_autoreply.model.php 2019年03月09日 14:51  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class Weixin_AutoreplyModel
{
	public $table = '@weixin_autoreply';
	public $accountTable = '@weixin_account';
	public $match = array(1=>'完全匹配',2=>'全文匹配',3=>'开始匹配',4=>'结尾匹配');
	public $type = array('text'=>'文字回复','image'=>'图片回复','tag'=>'标签内容回复');
	public $default = array(0=>'正常',1=>'关注回复',2=>'默认回复');

	
	/**
	 * 构造函数，初始化模块表
	 */
	function __construct( $data = null)
	{
	}
	
	
	/**
	 * 获得数据条数
	 * @param 参数1，必须，查询条件
	 */
	function GetCount($where)
	{
		$where['table'] = $this->table;
		$where['left'][$this->accountTable] = 'account_id=autoreply_account_id';
		return wmsql::GetCount($where);
	}
	
	/**
	 * 获取条件
	 * @param 参数1，必须，查询条件
	 */
	function GetList($where)
	{
		//获取列表条件
		$where['table'] = $this->table;
		$where['left'][$this->accountTable] = 'account_id=autoreply_account_id';
		return wmsql::GetAll($where);
	}
	
	
	/**
	 * 获得一条数据
	 * @param 参数1，必须，查询条件
	 */
	function GetOne($where)
	{
		$wheresql['table'] = $this->table;
		$wheresql['left'][$this->accountTable] = 'account_id=autoreply_account_id';
		$wheresql['where'] = $where;
		return wmsql::GetOne($wheresql);
	}
	
	/**
	 * 获得指定id的数据
	 * @param 参数1，必须，数据id
	 */
	function GetById($id)
	{
		$where['autoreply_id'] = $id;
		return $this->GetOne($where);
	}
	
	/**
	 * 根据公众号id获取设置的默认回复数据
	 * @param 参数1，必须，公众号id
	 * @return Ambigous <boolean, mixed, multitype:>
	 */
	function GetDefaultByAid($aid)
	{
		$where['table'] = $this->table;
		$where['where']['autoreply_account_id'] = $aid;
		$where['where']['autoreply_default'] = array('>',0);
		return wmsql::GetAll($where);
	}
	
	/**
	 * 检测公众号的默认回复和关注回复是否已经设置了
	 * @param 参数1，必须，查询条件
	 * @param 参数2，选填，是否是查询已经存在的
	 */
	function CheckExists($where,$id=0)
	{
		if( $id > 0 )
		{
			$where['autoreply_id'] = array('!=',$id);
		}
		$data = $this->GetOne($where);
		if( $data )
		{
			return $data;
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * 获得匹配关键字列表
	 * @param 参数1，必须，匹配的字符串
	 */
	function GetKeyMatchList($key)
	{
		$sql = 'SELECT autoreply_temp,autoreply_type FROM '.WMSql::TablePre('@'.$this->table).' WHERE ';
		$sql .= "autoreply_key = '{$key}' AND autoreply_match=1 OR ";
		$sql .= "'{$key}' LIKE CONCAT('%',autoreply_key,'%') AND autoreply_match=2 OR ";
		$sql .= "'{$key}' LIKE CONCAT(autoreply_key,'%') AND autoreply_match=3 OR ";
		$sql .= "'{$key}' LIKE CONCAT('%',autoreply_key) AND autoreply_match=4 ";
		$sql .= 'and autoreply_default=0 ORDER BY autoreply_match';
		return wmsql::Query($sql);
	}
	
	/**
	 * 获得匹配关键字，一条
	 * @param 参数1，必须，匹配的字符串
	 */
	function GetKeyMatchOne($key)
	{
		$list = $this->GetKeyMatchList($key);
		if( !empty($list) && count($list) > 0 )
		{
			return $list[0];
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * 插入记录
	 * @param 参数1，必须，插入的数据
	 */
	function Insert($data)
	{
		$data['autoreply_addtime'] = time();
		return wmsql::Insert( $this->table , $data );
	}
	
	/**
	 * 修改数据
	 * @param 参数1，必须，修改的内容
	 * @param 参数2，必须，内容的id
	 */
	function Update($data,$id)
	{
		$wheresql['autoreply_id'] = $id;
		return wmsql::Update($this->table, $data, $wheresql);
	}
	
	/**
	 * 根据id删除数据
	 * @param 参数1，必须，公众号id
	 */
	function DelById($id)
	{
		return $this->Del(array('autoreply_id'=>$id));
	}
	
	/**
	 * 根据条件删除数据
	 * @param 参数1，必须，删除条件
	 */
	function Del($where)
	{
		return wmsql::Delete($this->table,$where);
	}
}
?>
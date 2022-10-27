<?php
/**
* 作者经验模块模型
*
* @version        $Id: exp.model.php 2017年3月5日 12:01  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class ExpModel
{
	public $table = '@author_exp';
	
	/**
	 * 构造函数
	 */
	function __construct(){}


	/**
	 * 插入作者经验数据
	 * @param 参数1，必须，作者id
	 * @param 参数2，选填，模块
	 * @param 参数3，选填，经验值
	 */
	function Insert($authorId , $module = '' , $exp = '0')
	{
		$data['exp_module'] = $module;
		$data['exp_author_id'] = $authorId;
		$data['exp_number'] = $exp;
		if( $module == '' )
		{
			$module= 'novel,article';
		}
		
		$moduleArr = explode(',', $module);
		foreach ($moduleArr as $k=>$v)
		{
			$data['exp_module'] = $v;
			wmsql::Insert($this->table , $data);
		}
		return true;
	}
	
	/**
	 * 修改作者的经验值
	 * @param 参数1，必须，需要修改的模块
	 * @param 参数2，必须，需要修改的作者
	 * @param 参数3，必须，需要修改的经验值
	 */
	function Update($module , $authorId , $exp)
	{
		if( $authorId > 0 )
		{
			$where['exp_module'] = $module;
			$where['exp_author_id'] = $authorId;
			if( $this->GetOne($module, $authorId) )
			{
				if( $exp > 0 )
				{
					$data['exp_number'] = array('+',$exp);
					return wmsql::Update($this->table, $data, $where);
				}
				else
				{
					return true;
				}
			}
			else
			{
				return $this->Insert($authorId , $module , $exp);
			}
		}
	}
	
	/**
	 * 作者每日登录经验值变更
	 * @param 参数1，必须，作者id
	 * @param 参数2，必须，变更的经验值
	 */
	function LoginExp($aid , $exp)
	{
		if( $exp > 0 )
		{
			$data['exp_number'] = array('+',$exp);
			$where['exp_author_id'] = $aid;
			wmsql::Update($this->table, $data , $where);
		}
		return true;
	}

	/**
	 * 获得作者的经验值
	 * @param 参数1，必须，模块
	 * @param 参数1，必须，作者id
	 */
	function GetOne($module , $authorId)
	{
		$where['table'] = $this->table;
		$where['where']['exp_module'] = $module;
		$where['where']['exp_author_id'] = $authorId;
		return wmsql::GetOne($where);
	}
	
	
	/**
	 * 获得作者的经验值
	 * @param 参数1，必须，模块
	 * @param 参数1，必须，作者id
	 */
	function GetExp($module , $authorId)
	{
		$data = $this->GetOne($module, $authorId);
		return $data['exp_number'];
	}
}
?>
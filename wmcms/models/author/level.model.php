<?php
/**
* 作者等级模块模型
*
* @version        $Id: level.model.php 2017年3月5日 13:21  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class LevelModel
{
	public $table = '@author_level';
	public $expTable = '@author_exp';
	
	/**
	 * 构造函数
	 */
	function __construct(){}

	/**
	 * 获得作者的经验值
	 * @param 参数1，必须，模块
	 * @param 参数2，必须，经验值
	 */
	function GetOne($module , $exp='0')
	{
		$where['table'] = $this->table;
		$where['where']['level_module'] = $module;
		$where['where']['level_start'] = array('<=' , $exp);
		$where['order'] = 'level_order desc';
		$where['limit'] = '1';
		return $data = wmsql::GetOne($where);
	}
	
	
	/**
	 * 获得作者的经验值
	 * @param 参数1，必须，模块
	 * @param 参数2，必须，作者id
	 */
	function GetLevel($module , $exp)
	{
		$data = $this->GetOne($module, $exp);
		return $data['level_name'];
	}
	

	/**
	 * 获得作者的等级
	 * @param 参数1，必须，模块的id
	 * @param 参数2，必须，作者id
	 */
	function GetAuthorLevel($module , $aid)
	{
		$exp = '0';
		if( $aid > 0 )
		{
			$where['table'] = $this->expTable;
			$where['where']['exp_module'] = $module;
			$where['where']['exp_author_id'] = $aid;
			$expData = wmsql::GetOne($where);
			if( $expData )
			{
				$exp = $expData['exp_number'];
			}
		}
		$data = $this->GetOne($module , $exp);
		$data['exp_number'] = $exp;
		$nextData = $this->GetOne($module , $data['level_end']);
		//是否满级
		$data['level_max'] = 1;
		//下一级名
		$data['next']['level_name'] = C('author.par.lv_max',null,'lang');
		//下一级经验
		$data['next']['exp_number'] = 0;
		if( $nextData['level_id'] != $data['level_id'] )
		{
			$data['level_max'] = 0;
			$data['next']['level_name'] = $nextData['level_name'];
    		$data['next']['exp_number'] = $data['level_end']-$exp;
		}
		return $data;
	}
}
?>
<?php
/**
* 模块内容的粉丝模型
*
* @version        $Id: fans_module.model.php 2017年3月18日 18:48  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class Fans_ModuleModel
{
	private $moduleTable = '@fans_module';
	private $fansId;
	
	/**
	 * 构造函数
	 */
	function __construct()
	{
	}
	

	/**
	 * 插入模块粉丝信息
	 * @param 参数1，必须，关注的模块
	 * @param 参数2，必须，关注的用户id
	 * @param 参数3，必须，关注的内容id
	 */
	function Insert( $module , $uid , $cid )
	{
		$data['fans_module'] = $module;
		$data['fans_user_id'] = $uid;
		$data['fans_cid'] = $cid;
		$data['fans_addtime'] = time();
		return wmsql::Insert($this->moduleTable, $data);
	}
	
	
	/**
	 * 更新模块粉丝经验值
	 * @param 参数1，选填，模块id
	 * @param 参数2，选填，用户id
	 * @param 参数3，必须，模块内容id
	 * @param 参数4，必须，变更经验值
	 */
	function Update( $module , $uid , $cid , $exp)
	{
		if( $exp == '0' )
		{
			return false;
		}
		else
		{
			//获得粉丝数据
			$this->GetFans( $module , $uid , $cid );

			$where['fans_id'] = $this->fansId;
			$data['fans_exp'] = array('+' , $exp);
			
			return wmsql::Update( $this->moduleTable , $data , $where);
		}
	}
	
	
	/**
	 * 获得当前模块内容的粉丝信息
	 * @param 参数1，必须，模块id
	 * @param 参数2，必须，用户id
	 * @param 参数3，必须，内容id
	 */
	function GetFans( $module , $uid , $cid )
	{
		if( $uid > 0 && $cid > 0 )
		{
			$where['table'] = $this->moduleTable;
			$where['where']['fans_module'] = $module;
			$where['where']['fans_user_id'] = $uid;
			$where['where']['fans_cid'] = $cid;
			$data = wmsql::GetOne($where);
			if( !$data )
			{
				$data['fans_id'] = $this->Insert($module, $uid, $cid);
			}
			$this->fansId = $data['fans_id'];
			return $data;
		}
	}
}
?>
<?php
/**
* 粉丝数据功能模块类文件
*
* @version        $Id: fans.class.php 2018年8月26日 13:26  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
class fans
{
	static $fansModuleTable = '@fans_module';
	static $fansConsumeTable = '@fans_module_consume';
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
			//fans为粉丝数据获取
			case 'fans':
				$wheresql['table'] = self::$fansModuleTable;
				$wheresql['field'] = 'fans_exp,fans_addtime,fans_user_id,user_nickname,user_head';
				$wheresql['left'][user::$userTabel] = 'fans_user_id=user_id';
				$wheresql['where']['fans_module'] = self::$module;
				$wheresql['where']['fans_cid'] = self::$cid;
				break;
				
			//consume为消费记录数据获取
			case 'consume':
				$wheresql['table'] = self::$fansConsumeTable;
				$wheresql['field'] = 'consume_gold1,consume_gold2,consume_user_id,user_nickname,user_head';
				$wheresql['left'][user::$userTabel] = 'consume_user_id=user_id';
				$wheresql['where']['consume_module'] = self::$module;
				$wheresql['where']['consume_cid'] = self::$cid;
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
			'经验值倒序' =>'fans_exp desc',
			'消费金币1倒序' =>'consume_gold1 desc',
			'消费金币2倒序' =>'consume_gold2 desc',
		);
		return tpl::GetWhere($where,$arr);
	}
}
?>
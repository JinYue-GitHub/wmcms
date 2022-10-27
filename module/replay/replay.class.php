<?php
/**
* 评论类文件
*
* @version        $Id: replay.class.php 2015年8月9日 21:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @update 		  2015年12月28日 16:07
*
*/
class replay
{
	static $module;
	static protected $cid=0;
	static protected $sid=0;
	static protected $whereLabel;
	static protected $url;
	static protected $sum = 0;
	static protected $count = 0;
	static protected $limit;
	static protected $page;
	//评论配置
	static protected $replayConfig;
	//楼层
	static protected $floorArr;
	
	function __construct( $module = '' , $cid = '' , $url = '' , $count = 0 , $sid = 0)
	{
		if ( $module != '' && $cid != '' && $url != '' )
		{
			self::$module = $module;
			self::$cid = $cid;
			self::$url = $url;
			self::$sid = $sid;
		}
		else
		{
			self::$url = tpl::Url('replay_list',array('module'=>$module));
		}
		self::$replayConfig = C('',null,'replayConfig');
		self::$floorArr = explode("\r\n", self::$replayConfig['replay_floor_nickname']);
		self::$count = $count;
		$this->SetWhere();
		$this->SetCount();
		//调用模版处理文件
		new replaylabel();
	}
	
	
	//设置where条件
	function SetWhere()
	{
		//设置评论哪些模块可用,模块简称=>模块全称
		$moduleArr = array(
			'novel'=>'novel',
			'article'=>'article',
			'app'=>'app',
			'diy'=>'diy',
			'zt'=>'zt',
			'link'=>'link',
			'bbs'=>'bbs',
			'picture'=>'picture',
		);

		if ( array_key_exists( self::$module , $moduleArr ) )
		{
			$whereLabel = 'replay_module='.self::$module.';';
			if( self::$cid > 0 )
			{
				$whereLabel .= 'replay_cid='.self::$cid.';';
			}
			$whereLabel .= 'replay_subset_id='.self::$sid.';';
		}
		else
		{
			$whereLabel = 'replay_module=novel;';
		}
		self::$whereLabel = $whereLabel;
		
		tpl::Rep( array('{评论:'=>'{评论:'.$whereLabel,'{评论列表:'=>'{评论列表:'.$whereLabel) , null , '2' );
	}
	
	
	//获取评论数据
	static function GetData( $type = '' , $where = array() )
	{
		//设置需要替换的字段
		$wheresql = self::GetWhere( $where );
		$wheresql['field'] = '@replay_replay.*,user_sex,user_name,user_nickname,user_head';
		$wheresql['table'] = '@replay_replay';
		$wheresql['where']['replay_status'] = '1';
		$wheresql['left']['@user_user'] = 'replay_uid = user_id';
		//如果模块不为空就查询内容
		if( isset($wheresql['where']['replay_module']) )
		{
			global $tableSer;
			$module = $wheresql['where']['replay_module'];
			$moduleTable = $tableSer->tableArr[$module]['table'];
			$moduleTableCId = $tableSer->tableArr[$module]['id'];
			$moduleTableTId = $tableSer->tableArr[$module]['tid'];
			$moduleTableCName = $tableSer->tableArr[$module]['name'];
			$wheresql['field'] = $wheresql['field'].','.$moduleTableCName.' as replay_cname,'.$moduleTableTId.' as replay_tid,c.*';
			if( isset($tableSer->tableArr[$module]['ico']) )
			{
				$wheresql['field'] .= ','.$tableSer->tableArr[$module]['ico'].' as replay_ico';
			}
			$wheresql['left'][$moduleTable.' as c'] = 'replay_cid = '.$moduleTableCId;
		}
		$data = wmsql::GetAll($wheresql);
		
		if( class_exists('page') )
		{
			page::Start( self::$url , self::$count ,$wheresql['limit'] );
		}
		self::$limit = $wheresql['limit'];
		
		return $data;
	}
	
	//设置评论条数
	function SetCount($where = array())
	{
		if( self::$cid == 0 && self::$url == '' )
		{
			$sum = 0;
			$count = 0;
		}
		else
		{
			//设置需要替换的字段
			$wheresql = self::GetWhere( self::$whereLabel );
			$wheresql['field'] = '@replay_replay.*,user_sex,user_name,user_nickname,user_head';
			$wheresql['table'] = '@replay_replay';
			$wheresql['where']['replay_status'] = '1';
			$wheresql['left']['@user_user'] = 'replay_uid = user_id';
	
			//总数量
			$count = wmsql::GetCount($wheresql , 'replay_id');
			
			//参与人数
			unset($wheresql['where']['replay_status']);
			$sum = wmsql::GetCount($wheresql , 'replay_id');
		}
		
		self::$sum = $sum;
		self::$count = $count;
		return true;
	}
	
	//匹配中文条件
	static function GetWhere( $where )
	{
		//设置需要替换的字段
		$arr = array(
			'module' =>'replay_module',
			'模块' =>'replay_module',
			'顺序' =>'replay_time',
			'时间' =>'replay_time desc',
			'最新' =>'replay_time desc',
			'最热' =>'replay_ding desc',
			'最差' =>'replay_cai desc',
		);
		
		return tpl::GetWhere( $where , $arr );
	}
	

	/**
	 * 获取评论配置
	 * @param 参数1，选填，键名
	 */
	static function GetConfig($key='')
	{
		if( !self::$replayConfig )
		{
			self::$replayConfig = C('',null,'replayConfig');
		}
		if( $key != '' )
		{
			return self::$replayConfig[$key];
		}
		else
		{
			return self::$replayConfig;
		}
	}
	
	/**
	 * 获取楼层名字
	 * @param 参数1，选填，楼层数
	 */
	static function GetFloor($key='')
	{
		if( !self::$floorArr )
		{
			self::$floorArr = explode("\r\n", self::GetConfig('replay_floor_nickname'));
		}
	
		if( $key != '' || $key == '0')
		{
			return self::$floorArr[$key];
		}
		else
		{
			return self::$floorArr;
		}
	}
}
?>
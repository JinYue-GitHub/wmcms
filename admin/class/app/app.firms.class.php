<?php
/**
 * 应用厂商的类文件
 *
 * @version        $Id: app.firms.class.php 2016年5月16日 17:56  weimeng
 * @package        WMCMS
 * @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
 * @app           http://www.weimengcms.com
 *
 */
class AppFirms
{
	public $table = '@app_firms';
	
	/**
	 * 获取厂商
	 * @param 参数，选填，a是开发商。o是运营商
	 */
	function GetFirms( $type = '' )
	{
		$where['table'] = $this->table;
		if( $type != '' )
		{
			$where['where']['firms_type'] = array('or',$type.',s');
		}
		
		return wmsql::GetAll($where);
	}
	
	
	/**
	 * 获取厂商类型
	 * @param 参数，选填，a是开发商。o是运营商
	 */
	function GetType( $type = '' )
	{
		$arr = array(
			's'=>'自研自营',
			'a'=>'开发商',
			'o'=>'运营商',
		);
		
		if( $type != '' )
		{
			return $arr[$type];
		}
		else
		{
			return $arr;
		}
	}
	
	
	/**
	 * 检查厂商是否存在
	 * @param 参数1，必须，查询条件
	 */
	function CheckName( $wheresql )
	{
		//厂商名字检查
		$where['table'] = $this->table;
		$where['where'] = $wheresql;
		$data = wmsql::GetOne($where);
		if ( $data )
		{
			return $data;
		}
		else
		{
			return false;
		}
	}
	

	/**
	 * 检查厂商是否存在，如果不存在就加入
	 * @param 参数1，必须，厂商名字
	 * @param 参数2，必须，厂商类型。
	 */
	function CheckInsertFirms($name , $type)
	{
		$where['firms_name'] = $name;
		$where['firms_type'] = $type;
		$arr = $this->CheckName($where);
		//不存在就插入
		if( $arr === false )
		{
			$where['firms_addtime'] = time();
			return wmsql::Insert($this->table, $where);
		}
		else
		{
			return $arr['firms_id'];
		}
	}


	/**
	 * 检查开发商和运营商
	 * @param 参数1，必须，数据
	 */
	function CheckFirms( $data )
	{
		//自研自营检查
		if( $data['a'] == $data['o'] &&  $data['a'] != '')
		{
			$data['a'] = $data['o'] = $this->CheckInsertFirms($data['a'], 's');
		}
		else
		{
			//开发商检查
			if( $data['a'] == '' )
			{
				$data['a'] = '0';
			}
			else
			{
				$data['a'] = $this->CheckInsertFirms($data['a'], 'a');
			}
			//运营商检查
			if( $data['o'] == '' )
			{
				$data['o'] = '0';
			}
			else
			{
				$data['o'] = $this->CheckInsertFirms($data['o'], 'o');
			}
		}
		
		return $data;
	}
}
?>
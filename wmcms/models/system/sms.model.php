<?php
/**
* 短信模版模块模型
*
* @version        $Id: sms.model.php 2021年03月17日 11:27  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class SmsModel
{
	public $table = '@system_sms';
	public $logTable = '@system_sms_log';
	public $statusArr = array('0'=>'禁用','1'=>'使用');
	
	function __construct( $data = array() ){}

	/**
	 * 新增短信模版
	 * @param 参数1，必须，插入数据
	 */
	public function Insert($data)
	{
		$data['sms_time'] = time();
		return wmsql::Insert($this->table, $data);
	}
	
	/**
	 * 修改短信模版
	 * @param 参数1，必须，修改数据
	 * @param 参数2，必须，修改条件
	 */
	public function Update($data,$where)
	{
		return wmsql::Update($this->table, $data,$where);
	}
	
	/**
	 * 删除短信模版
	 * @param 参数1，必须，删除条件
	 */
	public function Del($where)
	{
		return wmsql::Delete($this->table,$where);
	}
	
	/**
	 * 获得所有短信模版
	 * @param 参数1，选填，查询条件
	 */
	public function GetAll($where=array())
	{
		$wheresql['table'] = $this->table;
		$wheresql['where'] = $where;
		return wmsql::GetAll($wheresql);
	}
	
	/**
	 * 获得短信模版，随机获得一条在使用的短信模版
	 * @param 参数1，必须，短信类型
	 */
	public function GetById($id)
	{
		$where['table'] = $this->table;
		$where['where']['sms_id'] = $id;
		$data = wmsql::GetOne($where);
		if( $data )
		{
		    $data['sms_params'] = str::ToHtml($data['sms_params'],true,false);
		}
		return $data;
	}
	
	/**
	 * 获得短信模版，随机获得一条在使用的短信模版
	 * @param 参数1，必须，短信类型
	 * @param 参数2，选填，是否指定API
	 */
	public function GetByType($type,$api='')
	{
		$where['table'] = $this->table;
		$where['where']['sms_status'] = 1;
		$where['where']['sms_type'] = $type;
		$where['where']['sms_api_name'] = $api;
		$data = wmsql::GetOne($where);
		if( $data )
		{
		    $data['sms_params'] = str::ToHtml($data['sms_params'],true,false);
		}
		return $data;
	}
}
?>
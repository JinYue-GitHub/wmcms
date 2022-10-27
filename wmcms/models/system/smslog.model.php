<?php
/**
* 短信日志模块模型
*
* @version        $Id: smslog.model.php 2021年03月17日 11:27  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class SmsLogModel
{
	public $table = '@system_sms_log';
	public $statusArr = array('0'=>'未使用','1'=>'已使用','2'=>'已过期');
	public $sendArr = array('0'=>'失败','1'=>'成功');
	
	function __construct( $data = array() ){}
	
	/**
	 * 写入发送日志
	 * @param 参数1，必须，日志内容
	 */
	public function SendLog($data)
	{
		$data['log_time'] = time();
		$data['log_exptime'] = time()+300;
		return wmsql::Insert($this->table, $data);
	}
	/**
	 * 写入发送错误日志
	 * @param 参数1，必须，短信类型
	 * @param 参数2，必须，发件人
	 * @param 参数3，必须，接受客户端
	 * @param 参数4，必须，错误消息
	 */
	public function SendError($type,$sender,$receive,$remark)
	{
		$data['log_status'] = 2;
		$data['log_type'] = $type;
		$data['log_send'] = 0;
		$data['log_remark'] = $remark;
		$data['log_receive'] = $receive;
		$data['log_sender'] = $sender;
		return $this->SendLog($data);
	}
	/**
	 * 写入发送成功日志
	 * @param 参数1，必须，短信类型
	 * @param 参数2，必须，发件人
	 * @param 参数3，必须，接受客户端
	 * @param 参数4，必须，错误消息
	 */
	public function SendSuccess($type,$sender,$receive,$content)
	{
		$data['log_type'] = $type;
		$data['log_receive'] = $receive;
		$data['log_sender'] = $sender;
		$data['log_content'] = json_encode($content);
		return $this->SendLog($data);
	}
	
	/**
	 * 获得所有发送记录
	 * @param 参数1，选填，查询条件
	 */
	public function GetAll($where=array())
	{
		$where['table'] = $this->table;
		return wmsql::GetAll($where);
	}
	
	/**
	 * 删除短信模版
	 * @param 参数1，必须，删除条件
	 */
	public function Del($where=array())
	{
		return wmsql::Delete($this->table,$where);
	}
	
	/**
	 * 修改日志的状态
	 * @param 参数1，必须，id。
	 * @param 参数1，必须，状态。
	 */
	function LogStatus($id,$status)
	{
	    if( $id == '0' )
	    {
	        return true;
	    }
		return wmsql::Update($this->table, array('log_status'=>$status),array('log_id'=>$id));
	}
	
}
?>
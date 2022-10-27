<?php
/**
* 消息发送日志模型
*
* @version        $Id: msglog.model.php 2022年03月22日 14:20  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class MsgLogModel
{
	public $table = '@system_msg_log';
	public $statusArr = array('0'=>'未使用','1'=>'已使用','2'=>'已过期');
	public $channelArr = array('email'=>'邮件','tel'=>'短信');
	public $sendArr = array('0'=>'失败','1'=>'成功');
	private $emailLogTable = '@system_email_log';
	private $smsLogTable = '@system_sms_log';
        
	function __construct( $data = array() ){}
	
	/**
	 * 写入发送日志
	 * @param 参数1，必须，日志内容
	 */
	public function InsertLog($data)
	{
		$data['log_time'] = time();
		$data['log_exptime'] = time()+300;
		return wmsql::Insert($this->table, $data);
	}
	
	/**
	 * 获取最后一条数据
	 * @param 参数1，必须，日志内容
	 */
	public function GetLast($receive)
	{
	    $where['table'] = $this->table;
		$where['where']['log_receive'] = $receive;
		$where['order'] = 'log_id desc';
		$data = wmsql::GetOne($where);
		if( $data )
		{
		    $data['log_params'] = json_decode($data['log_params'],true);
		}
		return $data;
	}
	
	
	/**
	 * 设置日志状态
	 * @param 参数1，必须，日志索引ID。
	 * @param 参数2，必须，渠道日志id。
	 * @param 参数3，必须，渠道模型。
	 * @param 参数4，必须，需要修改的状态。
	 */
	function LogStatus($logId,$channelId,$channelLogMod,$status)
	{
        $channelLogMod->LogStatus($channelId,$status);
		return wmsql::Update($this->table, array('log_status'=>$status),array('log_id'=>$logId));
	}
	/**
	 * 日志状态设置为已经使用
	 * @param 参数1，必须，日志索引ID。
	 * @param 参数2，必须，渠道日志id。
	 * @param 参数3，必须，渠道模型。
	 */
	function LogUse($logId,$channelId,$channelLogMod)
	{
        return $this->LogStatus($logId,$channelId,$channelLogMod,1);
	}
	/**
	 * 日志状态设置为过期
	 * @param 参数1，必须，日志索引ID。
	 * @param 参数2，必须，渠道日志id。
	 * @param 参数3，必须，渠道模型。
	 */
	function LogExp($logId,$channelId,$channelLogMod)
	{
        return $this->LogStatus($logId,$channelId,$channelLogMod,2);
	}
	
	
	/**
	 * 获得所有发送记录
	 * @param 参数1，选填，查询条件
	 */
	public function GetAll($where=array())
	{
		$where['table'] = $this->table.' as m';
		$where['field'] = 'm.*,e.log_send AS email_log_send,e.log_type AS email_log_type,e.log_remark AS email_log_remark,e.log_sender AS email_log_sender,e.log_content AS email_log_content,
s.log_send AS tel_log_send,s.log_type AS tel_log_type,s.log_remark AS tel_log_remark,s.log_sender AS tel_log_sender,s.log_content AS tel_log_content';
        $where['left'][$this->emailLogTable.' AS e'] = "log_channel='email' AND log_channel_id=e.log_id";
        $where['left'][$this->smsLogTable.' AS s'] = "log_channel='tel' AND log_channel_id=s.log_id";
		return wmsql::GetAll($where);
	}
	
	/**
	 * 删除信息发送日志
	 * @param 参数1，必须，删除条件
	 */
	public function Del($where=array())
	{
		return wmsql::Delete($this->table,$where);
	}
	
	//清空消息发送日志
	public function Clear()
	{
    	wmsql::Exec('TRUNCATE TABLE `@'.$this->table.'`');
    	wmsql::Exec('TRUNCATE TABLE `@'.$this->emailLogTable.'`');
    	wmsql::Exec('TRUNCATE TABLE `@'.$this->smsLogTable.'`');
    	return true;
	}
}
?>
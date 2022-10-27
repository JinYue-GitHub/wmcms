<?php
/**
* 邮箱模型
*
* @version        $Id: email.model.php 2017年6月26日 14:35  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class EmailModel
{
	public $table = '@system_email';
	public $tempTable = '@system_email_temp';
	public $logTable = '@system_email_log';
	public $status = array('0'=>'禁用','1'=>'使用');
	public $sendType = array('1'=>'SMTP服务器发送','2'=>'sendmail发送','3'=>'PHP函数SMTP发送');
	public $logStatus = array('0'=>'等待发送','1'=>'发送成功','2'=>'发送失败');
	
	function __construct( $data = '' ){}

	/**
	 * 获得一条随机邮件配置
	 * @param 参数1，必须，查询条件。
	 */
	function EmailGetRandOne()
	{
		$where['table'] = $this->table;
		$where['where']['email_status'] = '1';
		return wmsql::Rand($where);
	}
	/**
	 * 获得一条邮件配置
	 * @param 参数1，必须，查询条件。
	 */
	function EmailGetOne($wheresql)
	{
		$where['table'] = $this->table;
		if( is_array($wheresql) )
		{
			$where['where'] = $wheresql;
		}
		else
		{
			$where['where']['email_id'] = $wheresql;
		}
		return wmsql::GetOne($where);
	}
	/**
	 * 获得全部邮件设置
	 * @param 参数1，必须，所属的模块
	 * @param 参数2，必须，标签的名字
	 */
	function EmailGetAll($wheresql=array())
	{
		$where['table'] = $this->table;
		if( !empty($wheresql) )
		{
			$where['where'] = $wheresql;
		}
		return wmsql::GetAll($where);
	}
	/**
	 * 删除一条邮件配置
	 * @param 参数1，必须，删除条件。
	 */
	function EmailDel($wheresql)
	{
		if( is_array($wheresql) )
		{
			$where = $wheresql;
		}
		else
		{
			$where['email_id'] = $wheresql;
		}
		return wmsql::Delete($this->table,$where);
	}
	/**
	 * 插入数据
	 * @param 参数1，必须，需要插入的数据
	 */
	function EmailInsert($data)
	{
		return wmsql::Insert($this->table, $data);
	}
	/**
	 * 修改站内站点
	 * @param 参数1，必须，查询条件
	 */
	function EmailUpdate($data,$where)
	{
		return wmsql::Update($this->table, $data, $where);
	}
	
	
	
	/**
	 * 获得一条邮件模版
	 * @param 参数1，必须，查询条件。
	 */
	function TempGetOne($wheresql)
	{
		$where['table'] = $this->tempTable;
		if( is_array($wheresql) )
		{
			$where['where'] = $wheresql;
		}
		else
		{
			$where['where']['temp_id'] = $wheresql;
		}
		return wmsql::GetOne($where);
	}
	/**
	 * 获得全部发信模版
	 * @param 参数1，必须，所属的模块
	 * @param 参数2，必须，标签的名字
	 */
	function TempGetAll($wheresql=array())
	{
		$where['table'] = $this->tempTable;
		if( !empty($wheresql) )
		{
			$where['where'] = $wheresql;
		}
		return wmsql::GetAll($where);
	}
	/**
	 * 删除一条邮件模版
	 * @param 参数1，必须，删除条件。
	 */
	function TempDel($wheresql)
	{
		if( is_array($wheresql) )
		{
			$where = $wheresql;
		}
		else
		{
			$where['temp_id'] = $wheresql;
		}
		return wmsql::Delete($this->tempTable,$where);
	}
	/**
	 * 插入数据
	 * @param 参数1，必须，需要插入的数据
	 */
	function TempInsert($data)
	{
		return wmsql::Insert($this->tempTable, $data);
	}
	/**
	 * 修改站内站点
	 * @param 参数1，必须，查询条件
	 */
	function TempUpdate($data,$where)
	{
		return wmsql::Update($this->tempTable, $data, $where);
	}

	
	/**
	 * 插入邮件记录数据
	 * @param 参数1，必须，需要插入的数据
	 */
	function LogInsert($data)
	{
		$data['log_time'] = time();
		$data['log_sendtime'] = time();
		$data['log_exptime'] = time()+300;
		return wmsql::Insert($this->logTable, $data);
	}
	/**
	 * 获得日志条数
	 * @param 参数1，必须，条件
	 */
	function LogGetCount($wheresql=array())
	{
		$where['table'] = $this->logTable;
		if( !empty($wheresql) )
		{
			$where['where'] = $wheresql;
		}
		return wmsql::GetCount($where,'log_id');
	}
	/**
	 * 获得全部日志
	 * @param 参数1，必须，条件
	 */
	function LogGetAll($where=array())
	{
		$where['table'] = $this->logTable;
		return wmsql::GetAll($where);
	}
	/**
	 * 获得一条日志记录
	 * @param 参数1，必须，查询条件。
	 */
	function LogGetOne($wheresql)
	{
		$where['table'] = $this->logTable;
		if( is_array($wheresql) )
		{
			$where['where'] = $wheresql;
		}
		else
		{
			$where['where']['log_id'] = $wheresql;
		}
		return wmsql::GetOne($where);
	}
	/**
	 * 删除日志记录
	 * @param 参数1，选填，删除条件。
	 */
	function LogDel($where=array())
	{
		return wmsql::Delete($this->logTable, $where);
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
		return wmsql::Update($this->logTable, array('log_status'=>$status),array('log_id'=>$id));
	}
}
?>
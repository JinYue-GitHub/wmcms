<?php
/**
* 微信公众号对话消息模型
*
* @version        $Id: weixin_msg.model.php 2019年03月10日 14:30  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class Weixin_MsgModel
{
	public $table = '@weixin_msg';
	public $fansTable = '@weixin_fans';
	public $accountTable = '@weixin_account';
	//普通消息类型
	public $msgType = array(
			'text'=>'文本消息',
			'tag'=>'标签文本消息',
			'image'=>'图片消息',
			'voice'=>'语音消息',
			'video'=>'视频消息',
			'shortvideo'=>'短视频消息',
			'location'=>'地址位置消息',
			'link'=>'超链接消息',
			'event'=>'事件消息',
	);
	//事件类型
	public $eventType = array(
			'subscribe'=>'关注事件',
			'view'=>'打开网页消息',
			'unsubscribe'=>'取消关注事件',
			'location'=>'上报地理位置事件',
			'click'=>'自定义菜单点击事件',
			'scan'=>'已关注扫码事件',
	);

	
	/**
	 * 构造函数，初始化模块表
	 */
	function __construct( $data = null)
	{
	}
	
	
	/**
	 * 获得数据条数
	 * @param 参数1，必须，查询条件
	 */
	function GetCount($where)
	{
		$where['table'] = $this->table;
		$where['left'][$this->accountTable] = 'account_id=msg_account_id';
		$where['left'][$this->fansTable] = 'msg_from=fans_openid';
		return wmsql::GetCount($where);
	}
	
	/**
	 * 获取条件
	 * @param 参数1，必须，查询条件
	 */
	function GetList($where)
	{
		//获取列表条件
		$where['table'] = $this->table;
		$where['left'][$this->accountTable] = 'account_id=msg_account_id';
		$where['left'][$this->fansTable] = 'msg_from=fans_openid';
		return wmsql::GetAll($where);
	}
	
	
	/**
	 * 获得一条数据
	 * @param 参数1，必须，查询条件
	 */
	function GetOne($where)
	{
		$wheresql['table'] = $this->table;
		$wheresql['left'][$this->accountTable] = 'account_id=msg_account_id';
		$wheresql['left'][$this->fansTable] = 'msg_from=fans_openid';
		$wheresql['where'] = $where;
		return wmsql::GetOne($wheresql);
	}
	
	/**
	 * 获得指定id的数据
	 * @param 参数1，必须，数据id
	 */
	function GetById($id)
	{
		$where['msg_id'] = $id;
		return $this->GetOne($where);
	}
	

	/**
	 * 插入记录
	 * @param 参数1，必须，插入的数据
	 */
	function Insert($data)
	{
		$data['msg_sendtime'] = time();
		return wmsql::Insert( $this->table , $data );
	}
	
	/**
	 * 根据id删除数据
	 * @param 参数1，必须，公众号id
	 */
	function DelById($id)
	{
		return $this->Del(array('msg_id'=>$id));
	}
	
	/**
	 * 根据条件删除数据
	 * @param 参数1，必须，删除条件
	 */
	function Del($where)
	{
		return wmsql::Delete($this->table,$where);
	}
}
?>
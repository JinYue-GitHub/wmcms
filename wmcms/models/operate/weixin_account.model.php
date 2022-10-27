<?php
/**
* 微信公众号模型
*
* @version        $Id: weixin_account.model.php 2019年03月08日 20:51  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class Weixin_AccountModel
{
	public $table = '@weixin_account';
	public $menuTable = '@weixin_menu';
	public $replyTable = '@weixin_autoreply';
	public $msgTable = '@weixin_msg';
	public $mediaTable = '@weixin_media';
	public $status = array(0=>'审核中',1=>'已通过');
	public $access = array(0=>'未接入',1=>'已接入');
	public $follow = array(0=>'不限制',1=>'强制关注');
	public $type = array(1=>'订阅号',2=>'服务号');
	public $auth = array(0=>'未认证',1=>'已认证');
	public $main = array(0=>'非主公众号',1=>'主公众号');

	
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
		return wmsql::GetCount($where);
	}
	
	/**
	 * 获取条件
	 * @param 参数1，必须，查询条件
	 */
	function GetList($where = array())
	{
		//获取列表条件
		$where['table'] = $this->table;
		return wmsql::GetAll($where);
	}
	
	
	/**
	 * 获得一条数据
	 * @param 参数1，必须，查询条件
	 */
	function GetOne($where)
	{
		$wheresql['table'] = $this->table;
		$wheresql['where'] = $where;
		return wmsql::GetOne($wheresql);
	}
	
	/**
	 * 获得指定id的数据
	 * @param 参数1，必须，数据id
	 */
	function GetById($id)
	{
		$where['account_id'] = $id;
		return $this->GetOne($where);
	}
	
	/**
	 * 获得主公众号
	 */
	function GetMain()
	{
		$where['account_main'] = 1;
		return $this->GetOne($where);
	}
	
	/**
	 * 检测公众号是否存在
	 * @param 参数1，必须，查询条件
	 * @param 参数2，选填，是否是查询已经存在的
	 */
	function CheckExists($where,$id=0)
	{
		if( $id > 0 )
		{
			$where['account_id'] = array('!=',$id);
		}
		$data = $this->GetOne($where);
		if( $data )
		{
			return $data;
		}
		else
		{
			return false;
		}
	}

	/**
	 * 插入记录
	 * @param 参数1，必须，插入的数据
	 */
	function Insert($data)
	{
		$data['account_time'] = time();
		$data['account_token'] = md5_16(sha1(md5(json_encode($data))));
		$data['account_aeskey'] = sha1(md5(json_encode($data))).rand('100', '999');
		return wmsql::Insert( $this->table , $data );
	}
	
	/**
	 * 修改数据
	 * @param 参数1，必须，修改的内容
	 * @param 参数2，必须，内容的id
	 */
	function Update($data,$id)
	{
		$wheresql['account_id'] = $id;
		return wmsql::Update($this->table, $data, $wheresql);
	}
	
	/**
	 * 根据id删除数据
	 * @param 参数1，必须，公众号id
	 */
	function DelById($id)
	{
		return $this->Del(array('account_id'=>$id));
	}
	
	/**
	 * 根据条件删除数据，同时删除目录和回复
	 * @param 参数1，必须，删除条件
	 */
	function Del($where)
	{
		wmsql::Delete($this->table,$where);
		//如果是数组
		if( is_array($where['account_id']) )
		{
			$idList = explode(',', $where['account_id'][1]);
			foreach ($idList as $v)
			{
				wmsql::Delete($this->menuTable,array('menu_account_id'=>$v));
				wmsql::Delete($this->replyTable,array('autoreply_account_id'=>$v));
				wmsql::Delete($this->msgTable,array('msg_account_id'=>$v));
				wmsql::Delete($this->mediaTable,array('media_account_id'=>$v));
			}
		}
		//不是数组
		else
		{
			wmsql::Delete($this->menuTable,array('menu_account_id'=>$where['account_id']));
			wmsql::Delete($this->replyTable,array('autoreply_account_id'=>$where['account_id']));
			wmsql::Delete($this->msgTable,array('msg_account_id'=>$where['account_id']));
			wmsql::Delete($this->mediaTable,array('media_account_id'=>$where['account_id']));
		}
	}
}
?>
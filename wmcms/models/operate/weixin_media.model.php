<?php
/**
* 微信公众号素材模型
*
* @version        $Id: weixin_media.model.php 2019年03月10日 20:17  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class Weixin_MediaModel
{
	public $table = '@weixin_media';
	public $accountTable = '@weixin_account';
	//素材类型
	public $type = array(
			'image'=>'图片',
			'voice'=>'语音消息',
			'video'=>'视频消息',
			'thumb'=>'缩略图',
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
		$where['left'][$this->accountTable] = 'account_id=media_account_id';
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
		$where['left'][$this->accountTable] = 'account_id=media_account_id';
		return wmsql::GetAll($where);
	}
	
	
	/**
	 * 获得一条数据
	 * @param 参数1，必须，查询条件
	 */
	function GetOne($where)
	{
		$wheresql['table'] = $this->table;
		$wheresql['left'][$this->accountTable] = 'account_id=media_account_id';
		$wheresql['where'] = $where;
		return wmsql::GetOne($wheresql);
	}
	
	/**
	 * 获得指定id的数据
	 * @param 参数1，必须，数据id
	 */
	function GetById($id)
	{
		$where['media_id'] = $id;
		return $this->GetOne($where);
	}
	

	/**
	 * 插入记录
	 * @param 参数1，必须，插入的数据
	 */
	function Insert($data)
	{
		$data['media_time'] = time();
		$id = wmsql::Insert( $this->table , $data );
		//上传素材到微信
		return $id;
	}
	
	/**
	 * 根据id删除数据
	 * @param 参数1，必须，公众号id
	 */
	function DelById($id)
	{
		return $this->Del(array('media_id'=>$id));
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
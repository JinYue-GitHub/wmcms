<?php
/**
* 微信粉丝模型
*
* @version        $Id: weixin_fans.model.php 2019年03月12日 22:14  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class Weixin_FansModel
{
	public $table = '@weixin_fans';
	public $accountTable = '@weixin_account';
	public $sex = array(0=>'未知',1=>'男',2=>'女');
	public $subscribe = array(0=>'未关注',1=>'已关注');

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
		$where['left'][$this->accountTable] = 'account_id=fans_account_id';
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
		$where['left'][$this->accountTable] = 'account_id=fans_account_id';
		return wmsql::GetAll($where);
	}
	
	
	/**
	 * 获得一条数据
	 * @param 参数1，必须，查询条件
	 */
	function GetOne($where)
	{
		$wheresql['table'] = $this->table;
		$wheresql['left'][$this->accountTable] = 'account_id=fans_account_id';
		$wheresql['where'] = $where;
		return wmsql::GetOne($wheresql);
	}
	
	/**
	 * 获得指定id的数据
	 * @param 参数1，必须，数据id
	 */
	function GetById($id)
	{
		$where['fans_id'] = $id;
		return $this->GetOne($where);
	}
	
	/**
	 * 获得指定openid的数据
	 * @param 参数1，必须，公众号id
	 * @param 参数2，必须，openid
	 */
	function GetByOpenid($aid,$openid)
	{
		$where['fans_account_id'] = $aid;
		$where['fans_openid'] = $openid;
		return $this->GetOne($where);
	}
	
	
	/**
	 * 关注事件操作
	 * @param 参数1，必须，公众号id
	 * @param 参数2，必须，用户openid
	 * @param 参数3，必须，用户数据
	 */
	function Subscribe($aid,$openid,$data)
	{
		$userData = $this->GetByOpenid($aid, $openid);
		//存在数据就更新头像和关注时间
		if( $userData )
		{
			$updateData['fans_subscribe'] = 1;
			$updateData['fans_headimgurl'] = GetKey($data,'headimgurl');
			$updateData['fans_subscribe_time'] = time();
			return $this->Update($userData['fans_id'], $updateData);
		}
		//不存在就插入数据
		else
		{
			$saveData['fans_account_id'] = $aid;
			$saveData['fans_openid'] = $openid;
			$saveData['fans_unionid'] = GetKey($data,'unionid');
			$saveData['fans_nickname'] = $data['nickname'];
			$saveData['fans_headimgurl'] = $data['headimgurl'];
			$saveData['fans_sex'] = $data['sex'];
			$saveData['fans_country'] = $data['country'];
			$saveData['fans_province'] = $data['province'];
			$saveData['fans_city'] = $data['city'];
			$saveData['fans_remark'] = $data['remark'];
			$saveData['fans_json'] = json_encode($data);
			return $this->Insert($saveData);
		}
	}
	
	/**
	 * 取消关注修改操作
	 * @param 参数1，必须，公众号id
	 * @param 参数2，必须，用户openid
	 */
	function UnSubscribe($aid,$openid)
	{
		$userData = $this->GetByOpenid($aid, $openid);
		//存在数据就更新取消关注的信息
		if( $userData )
		{
			$data['fans_subscribe'] = 0;
			$data['fans_unsubtime'] = time();
			return $this->Update($userData['fans_id'], $data);
		}
	}
	

	/**
	 * 插入记录
	 * @param 参数1，必须，插入的数据
	 */
	function Insert($data)
	{
		$data['fans_time'] = time();
		$data['fans_subscribe_time'] = time();
		$id = wmsql::Insert( $this->table , $data );
		return $id;
	}
	
	/**
	 * 修改数据
	 * @param 参数1，必须，变更记录的id
	 * @param 参数2，必须，修改的内容
	 */
	function Update($id,$data)
	{
		$where['fans_id'] = $id;
		return wmsql::Update($this->table, $data, $where);
	}
	
	/**
	 * 根据id删除数据
	 * @param 参数1，必须，公众号id
	 */
	function DelById($id)
	{
		return $this->Del(array('fans_id'=>$id));
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
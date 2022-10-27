<?php
/**
* 评论模块模型
*
* @version        $Id: replay.model.php 2016年5月27日 10:31  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class ReplayModel
{
	public $table = '@replay_replay';
	//操作的模块
	public $module;
	//模块表
	public $moduleTable;
	//内容id
	public $cid;
	//排序方式
	public $order;
	
	/**
	 * 构造函数，初始化模块表
	 */
	function __construct( $data = '' )
	{
		global $tableSer;
		$this->module = GetKey($data, 'module');
		$this->cid = GetKey($data, 'cid');
		$this->moduleTable = $tableSer->GetTable($this->module);
	}



	/**
	 * 获得内容的条数
	 */
	function GetContentCount()
	{
		return GetContentCount($this->moduleTable['table'] , $this->moduleTable['id'] , $this->cid);
	}
	
	
	/**
	 * 获得上层楼的楼号
	 * @param 参数1，必须，评论id
	 */
	function GetById($id)
	{
		$where['table'] = $this->table;
		$where['field'] = $this->table.'.*,user_nickname';
		$where['left']['@user_user'] = 'replay_uid=user_id';
		$where['where']['replay_id'] = $id;
		$where['where']['replay_module'] = $this->module;
		$where['where']['replay_cid'] = $this->cid;
		$data = wmsql::GetOne($where);
		return $data;
	}

	/**
	 * 获得当前评论的总回复数量
	 * @param 参数1，必须，评论id
	 */
	function GetPidCount($pid)
	{
		$where['table'] = $this->table;
		$where['where']['replay_pid'] = $pid;
		$where['where']['replay_module'] = $this->module;
		$where['where']['replay_cid'] = $this->cid;
		$data = wmsql::GetCount($where);
		return $data;
	}
	
	
	/**
	 * 修改数据
	 * @param 参数1，必须，需要修改的数据 $data
	 * @param 参数2，必须，修改的条件 $where
	 * @return Ambigous <boolean, string>
	 */
	function Update($data,$where)
	{
		return wmsql::Update($this->table, $data, $where);
	}
	
	
	/**
	 * 获得上层楼的楼号
	 */
	function GetTopFloor()
	{
		$where['table'] = $this->table;
		$where['field'] = 'replay_floor';
		$where['where']['replay_module'] = $this->module;
		$where['where']['replay_cid'] = $this->cid;
		$where['order'] = 'replay_id desc';
		$data = wmsql::GetOne($where);
		if( $data )
		{
			return $data['replay_floor']+1;
		}
		else
		{
			return '1';
		}
	}
	
	
	/**
	 * 检查同一个ip上次评论的内容
	 * @param 参数1，必须，评论的模块
	 * @param 参数2，选填，传入的时间间隔秒。不为空则进行检测
	 */
	function CheckPre($module , $checkTime = 0 )
	{
		$where['table'] = '@replay_replay';
		$where['where']['replay_module'] = $module;
		$where['where']['replay_time'] = array( '>' , strtotime('today') );
		$where['where']['replay_ip'] = GetIp();
		$where['order'] = 'replay_id desc';
		$data = wmsql::GetOne($where);
		
		//检查参数大于0
		if( $checkTime > 0 )
		{
			//当前时间减上次评论的时间是否大于传入的时间
			$topTime =  time() - $data['replay_time'];
			if ( $topTime < $checkTime )
			{
				return false;
			}
			else
			{
				return true;
			}
		}
		//否则直接返回数据
		else
		{
			return $data;
		}	
	}
	
	
	/**
	 * 获得今天评论的条数
	 */
	function GetTodayCount()
	{
		$where['table'] = $this->table;
		$where['where']['replay_module'] = $this->module;
		$where['where']['replay_time'] = array( '>' , strtotime(date('Y-m-d')) );
		$where['where']['replay_ip'] = GetIp();
		$todayCount = wmsql::GetCount( $where , 'replay_id' );
		return $todayCount;
	}
	
	

	/**
	 * 插入操作记录
	 */
	function Insert( $data )
	{
		$data['replay_module'] = $this->module;
		$data['replay_cid'] = $this->cid;
		$data['replay_time'] = time();
		$data['replay_ip'] = GetIp();
		
		$result = wmsql::Insert( $this->table , $data );
		return $result;
	}
	
	/**
	 * 删除数据
	 * @param 参数1，选填，评论的id
	 */
	function Del( $id = '')
	{
		if( $id == '' )
		{
			$where['replay_module'] = $this->module;
			$where['replay_cid'] = $this->cid;
		}
		else
		{
			$where['replay_id'] = $id;
		}
		wmsql::Delete($this->table , $where);
	}
	
	
	
	/**
	 * 内容表顶踩自增
	 */
	function ContentInc()
	{
		wmsql::Inc( $this->moduleTable['table'] , $this->moduleTable['field'].'replay' , $this->moduleTable['id'].'='.$this->cid);
	}
	
	
	
	/**
	 * 获得评论列表
	 * @param 参数1，选填，当前页数
	 * @param 参数2，选填，每页多少条
	 * @param 参数3，选填，查询的条件
	 * @param 参数4，选填，查询的方式，1为列表，2为题主模式
	 */
	function GetList($page = '1', $pageCount = '10' , $wheresql='',$selectType='1')
	{
		//查询数据总条数
		$where['table'] = $this->table;
		$where['left']['@user_user'] = 'replay_uid=user_id';
		$where['where'] = $wheresql;
		$where['where']['replay_module'] = $this->module;
		if( $this->cid > 0 )
		{
			$where['where']['replay_cid'] = $this->cid;
		}
		//所有通过审核的数据条数
		$where['where']['replay_status'] = '1';
		$pageArr = page::Format( wmsql::GetCount( $where , 'replay_id' ), $pageCount , $page);
		//参数总人数，总评论数量
		$countWhere = $where;
		if( $selectType == '1' )
		{
			unset($countWhere['where']['replay_status']);
			$pageArr['sum'] = wmsql::GetCount( $countWhere , 'replay_id' );
		}
		else
		{
			unset($countWhere['where']['replay_status']);
			//如果不是回复列表就去除条件
			if( $countWhere['where']['replay_pid'] == '0' )
			{
				unset($countWhere['where']['replay_pid']);
			}
			$pageArr['sum'] = wmsql::GetCount( $countWhere , 'replay_id' );
			$pageArr['datacount'] = $pageArr['sum'];
		}
		
		//查询数据列表
		$where['order'] = $this->order;
		$where['limit'] = $pageArr['limit'];
		$where['field'] = 'user_head,replay_id,replay_cid,replay_pid,replay_rid,replay_uid,replay_nickname,replay_ruid,replay_rnickname,replay_content,replay_count,replay_ding,replay_cai,replay_time,replay_ip';
		//如果模块不为空就查询内容
		if( isset($where['where']['replay_module']) )
		{
			global $tableSer;
			$module = $where['where']['replay_module'];
			$moduleTable = $tableSer->tableArr[$module]['table'];
			$moduleTableCId = $tableSer->tableArr[$module]['id'];
			$moduleTableTId = $tableSer->tableArr[$module]['tid'];
			$moduleTableCName = $tableSer->tableArr[$module]['name'];
			$where['field'] = $where['field'].','.$moduleTableCName.' as replay_cname,'.$moduleTableTId.' as replay_tid,c.*';
			if( isset($tableSer->tableArr[$module]['ico']) )
			{
				$where['field'] .= ','.$tableSer->tableArr[$module]['ico'].' as replay_ico';
			}
			$where['left'][$moduleTable.' as c'] = 'replay_cid = '.$moduleTableCId;
		}
		$pageArr['data'] = wmsql::GetAll($where);
		
		return $pageArr;
	}
	
	/**
	 * 获得评论回复列表
	 * @param 参数1，选填，当前页数
	 * @param 参数2，选填，每页多少条
	 * @param 参数3，选填，查询的条件
	 */
	function GetReplayList($page = '1', $pageCount = '10' , $wheresql='')
	{
		//查询数据列表
		$where['table'] = $this->table.' as a';
		$where['field'] = 'a.*,user_birthday,user_browse,user_exp,user_head,user_id,user_name,user_nickname,user_sex,user_sign';
		$where['left']['@user_user'] = 'replay_uid=user_id';
		$where['where'] = $wheresql;
		$data = wmsql::GetAll($where);
		return $data;
	}
	
	/**
	 * 替换评论内容中的表情
	 * @param 评论内容数组
	 * @param 用户中心地址
	 * @param 默认头像
	 */
	function RepReplayFace($data , $furl='' , $defaultHead='')
	{
		//寻找替换表情标签
		$faceArr = tpl::Tag('{face:[a]|[a]}', $data['replay_content']);
		if(empty($faceArr[0][0]))
		{
			$faceArr = tpl::Tag('&#123;face:[a]|[a]&#125;', $data['replay_content']);
		}
		
		//表情是否存在
		if ( isset($faceArr[0][0]) )
		{
			foreach ($faceArr[0] as $key=>$val)
			{
				$path = str::ClearInclude($faceArr[1][$key]);
				$file = str::ClearInclude($faceArr[2][$key]);
				$facePath = '/files/face/'.$path.'/'.$file.'.gif';
				if( file_exists(WMROOT.$facePath) )
				{
					$data['replay_content'] = str_replace($val, '<img src="'.$facePath.'" style="max-height: 30px;max-width:30px;display:inline;vertical-align: middle;" />', $data['replay_content']);
				}
			}
		}
		
		if( $data['user_head'] == '' )
		{
			$data['user_head'] = $defaultHead;
		}
		$data['fhome'] = 'javascript:void(0);';
		$data['rfhome'] = 'javascript:void(0);';
		
		if( $data['replay_uid'] != '0' )
		{
			$data['fhome'] = str_replace('{uid}', $data['replay_uid'], $furl);
		}
		if( isset($data['replay_ruid']) && $data['replay_ruid'] != '0' )
		{
			$data['rfhome'] = str_replace('{uid}', $data['replay_ruid'], $furl);
		}
		return $data;
	}
	
	
	/**
	 * 过滤评论
	 * @param 评论数组
	 * @param 用户默认头像
	 */
	function Filter($data,$head)
	{
		if( $data )
		{
			$furl = tpl::Url('user_fhome');
			foreach ($data as $k=>$v)
			{
				//替换评论内容的表情
				$newData = $this->RepReplayFace($v,$furl,$head);
				
				$data[$k]['replay_content'] = $newData['replay_content'];
				$data[$k]['fhome'] = $newData['fhome'];
				$data[$k]['rfhome'] = $newData['rfhome'];
				//隐藏字段
				$data[$k] = str::HideField($data[$k],'novel_path,novel_status,novel_sign_id,article_save_type,article_save_path');
			}
		}
		return $data;
	}
	
	/**
	 * 根据子集id获得当前自己的评论数量
	 * @param 参数1，必须，模型 $module
	 * @param 参数2，必须，内容id $cid
	 * @param 参数3，必须，子集id $sid
	 * @return multitype:
	 */
	function GetSubsetCount($module,$cid,$sid)
	{
		$where['table'] = $this->table;
		$where['field'] = 'replay_segment_id,COUNT(replay_segment_id) as count';
		$where['where']['replay_module'] = $module;
		$where['where']['replay_cid'] = $cid;
		$where['where']['replay_subset_id'] = $sid;
		$where['group'] = 'replay_segment_id';
		$where['order'] = 'replay_id';
		$data = wmsql::GetAll($where);
		return $data;
	}
}
?>
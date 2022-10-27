<?php
/**
* 论坛模型
*
* @version        $Id: bbs.model.php 2016年5月26日 22:01  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class BbsModel
{
	public $bbsTable = '@bbs_bbs';
	public $typeTable = '@bbs_type';
	public $modTable = '@bbs_moderator';
	
	public $data;
	public $uid;
	
	public $bid;
	public $type;
	
	/**
	 * 构造函数
	 */
	function __construct()
	{
	}
	
	
	/**
	 * 获得数据条数
	 * @param 参数1，必须，条件
	 */
	function GetCount( $wheresql )
	{
		$where['table'] = $this->bbsTable;
		$where['where'] = $wheresql;
		return wmsql::GetCount($where);
	}
	
	
	/**
	 * 获得主题内容
	 * @param 参数1，必须，查询条件
	 */
	function GetOne( $wheresql )
	{
		$where['field'] = 'b.*,t.*,user_nickname,user_name,user_sex,user_head,user_sign';
		$where['table'][$this->bbsTable] = 'b';
		$where['left']['@bbs_type as t'] = 't.type_id=b.type_id';
		$where['left']['@user_user as u'] = 'u.user_id=b.user_id';
		//如果条件是纯数字
		if( str::Number($wheresql) )
		{
			$where['where']['bbs_id'] = $wheresql;
		}
		else
		{
			$where['where'] = $wheresql;
		}
		
		$this->data = wmsql::GetOne($where);
		return $this->data;
	}
	
	
	/**
	 * 替换帖子内容
	 * @param 参数1，必须，帖子内容
	 * @param 参数2，必须，帖子作者id
	 * @param 参数3，必须，帖子id
	 */
	function RepContent($content , $uid , $cid)
	{
		$downMod = NewModel('down.down');
		return $downMod->RepContent('bbs','',$content , $cid, $uid);
	}
	

	/**
	 * 检查用户今日的发帖数量
	 * @param 参数1，必须，用户id
	 */
	function CheckTodayPost( $uid )
	{
		$where['user_id'] = $uid;
		$where['bbs_time'] = array('>',strtotime('today'));
		return $this->GetCount($where);
	}
	
	
	/**
	 * 检查标题是否已经存在了
	 * @param 参数1，必须，帖子标题
	 * @param 参数2，选填，帖子的id
	 */
	function CheckTitle( $title , $bid = '')
	{
		$where['bbs_title'] = $title;
		if( $bid != '' )
		{
			$where['bbs_id'] = array( '<>' , $bid);
		}
		return $this->GetCount($where);
	}
	
	
	/**
	 * 检查帖子是否开启了回帖并且有权限回复
	 * @param 参数1，必须，帖子的id
	 * @param 参数2，选填，用户的id，不填则引用当前登录的用户id
	 */
	function CheckContentModerator( $bid , $uid = '')
	{
		$bbsData = $this->GetOne($bid);

		//没有主题数据
		if( !$bbsData )
		{
			return false;
		}
		//关闭了回帖，并且不是管理员
		else if ( !$this->CheckModerator( $bbsData['type_id'] ) )
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	
	/**
	 * 检查论坛版块的版主
	 * @param 参数1，选填，论坛版块的id。
	 * @param 参数2，选填，用户id
	 */
	function CheckModerator( $tid = '' , $uid = '' )
	{
		//检查版块是否存在
		$where['table'] = $this->typeTable;
		$where['where']['type_id'] = $tid;
		$data = wmsql::GetOne($where);
		
		if( !$data )
		{
			return false;
		}
		else
		{
			$pid = $tid.','.$data['type_pid'];
			
			//设置用户id
			if( $uid == '' )
			{
				$uid = user::GetUid();	
			}
	
			//如果传入的用户id和设置的用户id都为空就进入用户id获取验证
			if( $uid == 0 )
			{
				return false;
			}
			//如果版块id为空
			else if( $pid == '' )
			{
				return false;
			}
			else
			{
				$where['table'] = $this->modTable;
				$where['where']['user_id'] = $uid;
				$where['where']['type_id'] = array( 'lin' , $pid );
				$count = WMSql::GetCount($where,'moderator_id');
				if ( $count == 0 )
				{
					return false;
				}
				else
				{
					return true;
				}
			}
		}
	}
	
	
	
	/**
	 * 修改主题信息
	 * @param 参数1，必须，帖子id
	 * @param 参数2，修改类型，replay，read，post
	 */
	function UpBbsInfo( $bid = '' , $type = 'replay' )
	{
		if( $bid != '' )
		{
			$this->bid = $bid;
		}
		$this->type = $type;
		//帖子数据修改
		$this->BbsUp();
		$this->TypeUp();
	}

	
	/**
	 * 帖子信息更新
	 * @param 参数1，必须，帖子id
	 * @param 参数2，修改类型，replay，read，post
	 */
	function BbsUp()
	{
		//修改帖子评论
		if ( $this->type == 'replay' )
		{
			//更新主题信息
			$bbsData = array(
				'bbs_replay_time'=>time(),
				'bbs_replay_uid'=>user::GetUid(),
				'bbs_replay_nickname'=>user::GetNickname(),
			);
		}
		//更新帖子阅读
		else if ( $this->type == 'read' )
		{
			$bbsData = array('bbs_read'=>array('+',1));
		}
		else
		{
			return false;
		}
	
		wmsql::Update( $this->bbsTable , $bbsData , 'bbs_id='.$this->bid );
	}
	
	/**
	 * 版块信息更新
	 * @param 参数1，必须，帖子id
	 * @param 参数2，修改类型，replay，read，post
	 */
	function TypeUp()
	{
		//设置数据
		$upData['type_sum_'.$this->type] = array('+',1);
		$upData['type_today_'.$this->type] = array('+',1);

		//修改评论信息
		if ( $this->type == 'replay' && $this->data['type_last_replay'] < strtotime('today') )
		{
			$upData['type_last_replay'] = time();
			$upData['type_today_replay'] = 1;
		}
		//修改阅读信息
		else if ( $this->type == 'read' && $this->data['type_uptime'] < strtotime('today') )
		{
			$upData['type_uptime'] = time();
			$upData['type_today_read'] = 1;
		}
		//修改阅读信息
		else if ( $this->type == 'post' && $this->data['type_last_post'] < strtotime('today') )
		{
			$upData['type_last_post'] = time();
			$upData['type_today_post'] = 1;
		}
		else if( $this->type != 'replay' && $this->type != 'read')
		{
			return false;
		}

		$where['type_id'] = array( 'or' , $this->data['type_id'].','.$this->data['type_pid'] );

		//更新版块信息
		wmsql::Update( $this->typeTable , $upData , $where );
	}
	
	
	/**
	 * 插入主题
	 */
	function Insert( $data )
	{
		$data['bbs_time'] = time();
		$data['bbs_replay_time'] = time();
		return wmsql::Insert($this->bbsTable, $data);
	}
	
	
	/**
	 * 修改主题
	 */
	function Save( $data , $where )
	{
		return wmsql::Update($this->bbsTable, $data, $where);
	}
	
	/**
	 * 删除主题
	 */
	function Del( $id )
	{
		if( $id == '' )
		{
			return false;
		}
		else
		{
			$where['bbs_id'] = $id;
		}
		return wmsql::Delete($this->bbsTable , $where);
	}
}
?>
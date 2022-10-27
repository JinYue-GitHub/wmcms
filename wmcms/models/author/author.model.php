<?php
/**
* 作者模块模型
*
* @version        $Id: author.model.php 2016年12月19日 22:01  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class AuthorModel
{
	public $authorConfig;
	public $authorTable = '@author_author';
	public $novelTable = '@novel_novel';
	public $applyTable = '@author_apply';
	public $expTable = '@author_exp';
	public $userTable = '@user_user';
	
	/**
	 * 构造函数
	 */
	function __construct()
	{
		$this->authorConfig = GetModuleConfig('author');
	}


	/**
	 * 删除一条数据
	 * @param 参数1，必须，删除的条件
	 */
	function Delete($where)
	{
		if( !is_array($where) )
		{
			$where = $this->GetWhere();
		}
		return wmsql::Delete($this->authorTable , $where);
	}
	
	/**
	 * 插入作者数据
	 * @param 参数1，必须，插入的数据
	 */
	function Insert($data)
	{
		if( $data['author_info'] == '' )
		{
			$data['author_info'] = $this->authorConfig['author_default_info'];
		}
		if( $data['author_notice'] == '' )
		{
			$data['author_notice'] = $this->authorConfig['author_default_notice'];
		}
		$data['author_time'] = time();
		return wmsql::Insert($this->authorTable, $data);
	}
	
	
	/**
	 * 检查作者笔名是否被注册了
	 * @param 参数1，必须，作者笔名
	 * @param 参数1，玄天，作者的id
	 */
	function CheckNickName($nickName , $uid = 0)
	{
		//作者表
		$where['table'] = $this->authorTable;
		$where['where']['author_nickname'] = $nickName;
		if( $uid > 0 )
		{
			$where['where']['user_id'] = array('<>' , $uid);
		}
		//小说表
		$wheresql['table'] = $this->novelTable;
		$wheresql['where']['novel_author'] = $nickName;
		if( wmsql::GetCount($where) == 0 && wmsql::GetCount($wheresql) == 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	


	/**
	 * 申请作者
	 * @param 参数1，必须，申请的数据
	 * @param 参数2，选填，作者的数据
	 */
	function ApplyAuthor($data , $authorData = '')
	{
		$insertData['user_id'] = C('uid' , '' , $data);
		$insertData['author_status'] = $this->authorConfig['apply_author_status'];
		$insertData['author_info'] = $this->authorConfig['author_default_info'];
		$insertData['author_notice'] = $this->authorConfig['author_default_notice'];
		$insertData['author_nickname'] = C('nickname' , '' , $data);
		$insertData['author_time'] = time();
		
		//如果作者数据存在就修改数据
		if( $authorData )
		{
			$where['author_id'] = $authorData['author_id'];
			wmsql::Update($this->authorTable, $insertData, $where);
			return $authorData['author_id'];
		}
		else
		{
			$authorId = wmsql::Insert($this->authorTable, $insertData);
			if( $authorId > 0 )
			{
				//插入等级
				$expData['exp_module'] = 'novel';
				$expData['exp_author_id'] = $authorId;
				wmsql::Insert($this->expTable, $expData);
			}
			return $authorId;
		}
	}
	
	
	/**
	 * 修改作者的资料
	 * @param 参数1，必须，需要修改的资料
	 * @param 参数2，选填，修改的条件
	 */
	function UpdateAuthor($data , $wheresql = '')
	{
		if( is_array($wheresql))
		{
			$where = $wheresql;
		}
		else if ( $wheresql != '' )
		{
			$where['author_id'] = $wheresql;
		}
		else
		{
			$where['user_id'] = user::GetUid();
		}
		return wmsql::Update($this->authorTable, $data, $where);
	}
	
	/**
	 * 修改上次登录时间
	 * @param 参数1，必须，作者id
	 */
	function UpLoginTime($aid)
	{
		$data['author_toptime'] = time();
		return $this->UpdateAuthor($data,$aid);
	}
	
	/**
	 * 获得作者的资料
	 * @param 参数1，选填用户的id
	 * @param 参数2，选填，id类型，1为用户id，2为作者id
	 */
	function GetAuthor($uid=0 , $type = 1)
	{
		if( $uid == 0 )
		{
			$uid = user::GetUid();
		}
		
		$where['table'] = $this->authorTable.' as a';
		$where['left'][$this->userTable.' as u'] = 'a.user_id = u.user_id';
		if( $type == 1 )
		{
			$where['where']['a.user_id'] = $uid;
		}
		else
		{
			$where['where']['author_id'] = $uid;
		}
		return wmsql::GetOne($where);
	}
	
	
	/**
	 * 根据作者id获取用户id
	 * @param 参数1，必须，作者id
	 */
	function GetUidByAid($aid)
	{
		$where['table'] = $this->authorTable;
		$where['where']['author_id'] = $aid;
		$data = wmsql::GetOne($where);
		if( $data )
		{
			return $data['user_id'];
		}
		else
		{
			return 0;
		}
	}
	
	
	/**
	 * 获得所有作者
	 * @param 参数1，必须，查询条件
	 */
	function GetAll($wheresql)
	{
		$where['table'] = $this->authorTable;
		$where['where'] = $wheresql;
		return wmsql::GetAll($where);
	}
	
	
	/**
	 * 检查是否是作者
	 * @param 参数1，必须，没有登录时候的提示
	 * @param 参数2，必须，不是作者的时候提示
	 * @param 参数3，必须，是否是ajax返回
	 */
	function CheckAuthor($noLogin , $noAuthor , $ajax)
	{
		//是否登录了
		str::EQ( user::GetUid() , 0 , $noLogin );
		//是否是作者
		$author = $this->GetAuthor();
		if( !$author )
		{
			ReturnData($noAuthor , $ajax);
		}
		return $author;
	}
	
	
	/**
	 * 作者资金变更
	 * @param 参数1，必须，变更的数据
	 */
	function CapitalChange($log , $type = '1')
	{
		//获得作者的用户id
		$authorData = $this->GetAuthor($log['aid'] , 2);
		$authorUid = $authorData['user_id'];

		//如果是计算模式就写入结算表
		if( isset($this->authorConfig['author_income_type']) && $this->authorConfig['author_income_type']=='2' )
		{
			$reportMod = NewModel('finance.finance_report');
			return $reportMod->InsertReportByLog( $authorUid , $log , $log['gold1'] , $log['gold2'] , $type);
		}
		//如果是直接入账变更作者的用户id的资金
		else if( $authorUid > 0 )
		{
			$userMod = NewModel('user.user');
			return $userMod->CapitalChange( $authorUid , $log , $log['gold1'] , $log['gold2'] , $type);
		}
		return true;
	}
}
?>
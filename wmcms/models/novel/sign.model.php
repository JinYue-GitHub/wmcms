<?php
/**
* 小说签约模型
*
* @version        $Id: sign.model.php 2017年3月12日 18:17  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class SignModel
{
	public $signTable = '@novel_sign';
	public $novelTable = '@novel_novel';
	
	
	/**
	 * 构造函数
	 */
	function __construct(){}

	
	/**
	 * 获得最新签约的小说信息
	 * @param 参数1，必须，条件
	 */
	function Insert( $data )
	{
		$data['sign_time'] = time();
		return wmsql::Insert($this->signTable, $data);
	}
	
	
	/**
	 * 获得最新签约的小说信息
	 * @param 参数1，必须，条件
	 */
	function GetLastOne( $nid )
	{
		$where['table'] = $this->signTable;
		$where['where']['sign_novel_id'] = $nid;
		$where['order'] = 'sign_id desc';
		return wmsql::GetOne($where);
	}
	
	
	/**
	 * 修改小说的签约状态
	 * @param 参数1，必须，小说id
	 * @param 参数2，必须，签约类型
	 * @param 参数3，选填，如果为分成销售就必须
	 */
	function SetNovelSign( $nid , $copy = '0' , $sid = '0' )
	{
		$data['novel_copyright'] = $copy;
		$data['novel_sign_id'] = $sid;
		$where['novel_id'] = $nid;
		return wmsql::Update($this->novelTable, $data, $where);
	}

	/**
	 * 删除一条数据
	 */
	function Delete($wheresql)
	{
		if( !is_array($wheresql) )
		{
			$where['sign_id'] = $wheresql;
		}
		else
		{
			$where = $wheresql;
		}
		return wmsql::Delete($this->signTable , $where);
	}

	/**
	 * 获得作者签约小说数量
	 * @param 参数1，必须，作者ID
	 */
	function GetAuthorSign( $aid )
	{
		$where['table'] = $this->signTable;
		$where['left'][$this->novelTable] = array('right','sign_novel_id=novel_id');
		$where['where']['sign_status'] = 1;
		$where['where']['author_id'] = $aid;
		return wmsql::GetCount($where);
	}
}
?>
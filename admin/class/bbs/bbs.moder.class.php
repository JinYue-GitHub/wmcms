<?php
/**
 * 论坛版主的类文件
 *
 * @version        $Id: bbs.moder.class.php 2016年6月5日 22:30  weimeng
 * @package        WMCMS
 * @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
 * @bbs           http://www.weimengcms.com
 *
 */
class BbsModer
{
	public $table = '@bbs_moderator';
	

	
	function GetModerUids($tid)
	{
		$where['type_id'] = $tid;
		$data = $this->GetModer($where);
		if( $data )
		{
			$uids = '';
			$i = 1;
			foreach ($data as $k=>$v)
			{
				$uids .= $v['user_id'];
				if( $i < count($data) )
				{
					$uids .= ',';
				}
				$i++ ;
			}
			return $uids;
		}
	}
	/**
	 * 获得当前版块所有版主id
	 */
	function GetModer( $where = array() )
	{
		$wheresql['table'] = $this->table;
		$wheresql['where'] = $where;
		
		$data = wmsql::GetAll($wheresql);
		return $data;
	}
}
?>
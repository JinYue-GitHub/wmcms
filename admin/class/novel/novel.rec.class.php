<?php
/**
 * 小说推荐的类文件
 *
 * @version        $Id: novel.rec.class.php 2016年4月28日 10:17  weimeng
 * @package        WMCMS
 * @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
 * @link           http://www.weimengcms.com
 *
 */
class NovelRec
{
	public $table = '@novel_rec';
	
	//设置推荐
	/**
	 * 设置小说的推荐属性
	 * @param 参数1，必填，小说id
	 * @param 参数2，必填，推荐的属性数组
	 * @param 参数2，选填，是多条数据修改还是单条数据修改
	 */
	function SetRec( $nid , $rec , $isOne = true)
	{
		//如果字段数量为0就删除推荐
		if( count($rec) == 0 )
		{
			$where['rec_nid'] = $nid;
			wmsql::Delete($this->table,$where);
		}
		else if ( empty($rec) || (isset($rec['rec_rt']) && GetKey($rec,'rec_rt') == '' && count($rec) <= 1) )
		{
			return false;
		}
		else
		{
			$recWhere['table'] = $this->table;
			$recWhere['where']['rec_nid'] = $nid;
			$recData = WMSql::GetOne($recWhere);
	
			//存在推荐的
			if( $recData )
			{
				//如果不是单条数据修改
				if( !$isOne )
				{
					//先取消所有推荐
					$cancelData['rec_icr'] = $cancelData['rec_ibr'] = $cancelData['rec_ir'] = 
					$cancelData['rec_ccr'] = $cancelData['rec_cbr'] = $cancelData['rec_cr'] = '0';
					wmsql::Update($this->table, $cancelData, $recWhere['where']);
				}
				//在加上推荐
				wmsql::Update($this->table, $rec, $recWhere['where']);
			}
			//如果行数大于1
			else if ( ( count($rec) >= 1 ))
			{
				$rec['rec_nid'] = $nid;
				$rec['rec_time'] = time();
				wmsql::Insert($this->table, $rec);
			}
		}
	}
}
?>
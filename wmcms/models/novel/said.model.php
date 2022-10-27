<?php
/**
* 小说大本章的作者说模型
*
* @version        $Id: said.model.php 2021年09月03日 19:59  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class SaidModel
{
	//作者说表
	public $saidTable = '@novel_author_said';
	//小说表
	public $novelTable = '@novel_novel';

	
	/**
	 * 构造函数
	 */
	function __construct(){}
	

	/**
	 * 删除数据
	 */
	function Delete($wheresql)
	{
		if( !is_array($wheresql) )
		{
			$where['said_id'] = $wheresql;
		}
		else
		{
			$where = $wheresql;
		}
		return wmsql::Delete($this->saidTable , $where);
	}
	
	/**
	 * 获得一条数据
	 * @param 参数1，必须，查询条件
	 */
	function GetOne($where)
	{
		$wheresql['table'] = $this->saidTable;
		$wheresql['where'] = $where;
		return wmsql::GetOne($wheresql);
	}
	
	/**
	 * 根据小说和作者id获得大纲
	 * @param 参数1，必须，小说id
     * @param 参数2，必须，内容id
	 * @param 参数3，选填，作者id
	 */
	function GetByNid($nid,$cid,$aid=0)
	{
		if( $aid > 0 )
		{
			$where['table'] = $this->novelTable;
			$where['where']['novel_id'] = $nid;
			$where['where']['author_id'] = $aid;
			if( wmsql::GetCount($where) == 0 )
			{
				return array();
			}
		}
		$wheresql['novel_id'] = $nid;
		$wheresql['chapter_id'] = $cid;
		return $this->GetOne($wheresql);
	}

    /**
     * 根据内容id删除作者说
     * @param 参数1，必须，小说id
     * @param 参数2，必须，内容id
     */
	function DeleteByCid($nid,$cid)
    {
        $where['novel_id'] = $nid;
        $where['chapter_id'] = $cid;
        return wmsql::Delete($this->saidTable,$where);
    }

    /**
     * 根据内容id更新作者说
     * @param 参数1，必须，小说id
     * @param 参数2，必须，内容id
     * @param 参数3，选填，作者说内容
     */
	function UpdateByCid($nid,$cid,$said='')
    {
        $data = $this->GetByNid($nid,$cid);
        //如果内容存在，并且作者说为空就删除
        if( $data && empty($said) )
        {
            wmsql::Delete($this->saidTable,array('said_id'=>$data['said_id']));
        }
        //如果内容存在，并且作者说为空就修改
        else if( $data && !empty($said) )
        {
            $where['said_id'] = $data['said_id'];
            $data['said_content'] = $said;
            $data['said_uptime'] = time();
            wmsql::Update($this->saidTable,$data,$where);
        }
        //如果内容不存在，并且作者说不为空就新增
        else if( !$data && !empty($said) )
        {
            $data['novel_id'] = $nid;
            $data['chapter_id'] = $cid;
            $data['said_content'] = $said;
            $data['said_uptime'] = time();
            wmsql::Insert($this->saidTable,$data);
        }
        return true;
    }
}
?>
<?php
/**
* 作者数据统计模型
*
* @version        $Id: statistics.model.php 2022年04月05日 09:41  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class StatisticsModel
{
    //小说表
	public $novelTable = '@novel_novel';
	//书架、订阅表
	public $collTable = '@user_coll';
	//打赏表
	public $rewardTable = '@novel_rewardlog';
	//评论表
	public $replayTable = '@replay_replay';
	//阅读表
	public $readTable = '@user_read_log';
	//章节表
	public $chapterTable = '@novel_chapter';
	//票类表
	public $ticketTable = '@user_ticket_log';
	//时间字段映射
	private $fieldMap = array(
	    'click'=>array(
	        'today'=>array('field'=>'novel_todayclick','time'=>'novel_clicktime'),
    	    'week'=>array('field'=>'novel_weekclick','time'=>'novel_clicktime'),
    	    'month'=>array('field'=>'novel_monthclick','time'=>'novel_clicktime'),
    	    'year'=>array('field'=>'novel_yearclick','time'=>'novel_clicktime'),
    	    'all'=>array('field'=>'novel_allclick','time'=>'novel_clicktime'),
	    ),
	    'rec'=>array(
	        'today'=>array('field'=>'novel_todayrec','time'=>'novel_rectime'),
    	    'week'=>array('field'=>'novel_weekrec','time'=>'novel_rectime'),
    	    'month'=>array('field'=>'novel_monthrec','time'=>'novel_rectime'),
    	    'year'=>array('field'=>'novel_yearrec','time'=>'novel_rectime'),
    	    'all'=>array('field'=>'novel_allrec','time'=>'novel_rectime'),
	    ),
	    'coll'=>array(
	        'today'=>array('field'=>'novel_todaycoll','time'=>'novel_colltime'),
    	    'week'=>array('field'=>'novel_weekcoll','time'=>'novel_colltime'),
    	    'month'=>array('field'=>'novel_monthcoll','time'=>'novel_colltime'),
    	    'year'=>array('field'=>'novel_yearcoll','time'=>'novel_colltime'),
    	    'all'=>array('field'=>'novel_allcoll','time'=>'novel_colltime'),
	    ),
    );
	
	/**
	 * 构造函数
	 */
	function __construct(){}
	

	/**
	 * 获得小说排名数据统计
	 * @param 参数1，必须，统计字段
	 * @param 参数2，必须，统计时间
	 * @param 参数3，必须，小说id
	 * @param 参数4，必须，作者id
	 */
	function GetNovelRank($rankType='',$timeType='today',$nid=0,$aid=0)
	{
	    foreach ( explode(',',$timeType) as $time)
	    {
    	    $sql = $this->GetNovelSql($rankType,$time,$nid);
    	    
            //关闭检查子查询
            wmsql::$checkSql = false;
    	    $rank = wmsql::Query($sql);
            //清空当前表设置的参数
            wmsql::Clear();
            if( $rank )
            {
                //如果是小说数据
    	        if( $nid>0 )
    	        {
    	            $rank = array_column($rank,null,'novel_id');
    	            if( isset($rank[$nid]) )
    	            {
    	                $rank = $rank[$nid];
    	            }
    	        }
    	        //如果是作者排名
    	        else
    	        {
    	            $rank = array_column($rank,null,'author_id');
    	            if( isset($rank[$aid]) )
        	        {
        	            $rank = $rank[$aid];
        	        }
    	        }
    	        
    	        if( $rank )
    	        {
                    unset($rank['author_id']);
                    unset($rank['novel_id']);
    	        }
            }
            $result[$time] = $rank;
	    }
	    return $result;
	}
	/**
	 * 获得小说排名的查询sql
	 * @param 参数1，必须，统计字段
	 * @param 参数1，必须，统计时间
	 * @param 参数1，必须，小说id
	 */
	private function GetNovelSql($rankType,$type,$nid=0)
	{
	    $field = $this->fieldMap[$rankType][$type]['field'];
	    $time = $this->fieldMap[$rankType][$type]['time'];
	    if( $type == 'all' )
	    {
	        $where = '1=1';
	    }
	    else
	    {
    	    $timeSer = NewClass('time');
    	    $where = $time.'>'.$timeSer->GetTime($type);
	    }
	    //自增排名
	    $curRank = '@curRank := @curRank + 1 AS `cur_rank`';
	    //普通并列排名
	    $prceRank = 'CASE  WHEN @prveVal = rankField THEN @prveRank ELSE @prveRank := @prveRank + 1 END AS `prve_rank`';
	    //高级并列排名
	    $rank = 'CASE WHEN @prveVal = rankField THEN @rank ELSE @rank := @curRank END AS `rank`';
	    //排名字段
	    $rankField = '@prveVal := rankField AS rankField';
	    //查询数据源表
	    $selectForm = '(select *,('.$field.') AS rankField from '.wmsql::CheckTable($this->novelTable).')';
	    if( $nid == 0 )
	    {
	        $selectForm = '(SELECT novel_id,SUM('.$field.') AS rankField,author_id,'.$time.' FROM '.wmsql::CheckTable($this->novelTable).' where '.$where.' GROUP BY author_id)';
	    }
        $sql = 'SELECT novel_id,author_id,'.$curRank.','.$prceRank.','.$rank.','.$rankField
                .' FROM '.$selectForm.' AS n,(SELECT @curRank := 0, @prveRank := 0,@rank := 0,@prveVal := NULL) AS s
                WHERE '.$where.' ORDER BY rankField DESC';
        return $sql;
	}
	
	
	/**
	 * 获得小说排名数据统计
	 * @param 参数1，必须，统计字段
	 * @param 参数1，必须，统计时间
	 * @param 参数1，选填，小说id
	 * @param 参数1，选填，小说id集合
	 */
	function GetColl($type='',$timeType='today',$nid=0,$nids=array())
	{
	    $timeSer = NewClass('time');
	    $table = $this->collTable;
	    //打赏
	    if( $type == 'reward' )
	    {
    	    $table = $this->rewardTable;
    	    $cidField = 'log_nid';
    	    $wheresql = array();
    	    $timeField = 'log_time';
	    }
	    //评论
	    else if( $type == 'replay' )
	    {
    	    $table = $this->replayTable;
    	    $cidField = 'replay_cid';
    	    $wheresql['replay_module'] = 'novel';
    	    $timeField = 'replay_time';
	    }
	    //阅读
	    else if( $type == 'read' )
	    {
    	    $table = $this->readTable;
    	    $cidField = 'read_nid';
    	    $wheresql['read_module'] = 'novel';
    	    $timeField = 'read_time';
	    }
	    //默认收藏表
	    else
	    {
    	    $cidField = 'coll_cid';
    	    $wheresql['coll_type'] = $type;
    	    $timeField = 'coll_time';
	    }
	    
	    foreach ( explode(',',$timeType) as $time)
	    {
	        $where = array();
	        //最高或者平均
	        if( $time == 'max' || $time == 'avg' )
	        {
        	    $where['table'] = $table;
    			$where['field'] = "count(*) AS `count`,FROM_UNIXTIME({$timeField},'%Y-%m-%d') AS `day`";
        	    $where['where'] = $wheresql;
    			$where['order'] = '`count` desc';
			    if( $time == 'avg' )
			    {
    		    	$where['order'] = '`day` desc';
			    }
			    $where['group'] = 'day';
    			if( $nid > 0 )
    			{
    			    $where['where'][$cidField] = $nid;
    			}
    			else
    			{
    			    $where['where'][$cidField] = array('lin',implode(',',$nids));
    			}
    			$list = wmsql::GetAll($where);
        	    $result[$time] = 0;
    			if( $list )
    			{
    			    if( $time == 'avg' )
    			    {
    			        //最后一条到今天的日期
                        $days = round((strtotime(date("Y-m-d"))-strtotime($list[count($list)-1]['day']))/3600/24);
        	            $result[$time] = intval(array_sum(array_column($list,'count'))/($days+1),0); 
    			    }
    			    else
    			    {
        	            $result[$time] = $list[0];
    			    }
    			}
	        }
	        else
	        {
        	    $where['table'] = $table;
    	        $where['where'] = $wheresql;
        	    if( $time != 'all' )
        	    {
        	        $where['where'] = array_merge($where['where'],$timeSer->GetWhere(array($timeField=>$time)));
        	    }
        	    if( $nid > 0 )
    			{
    			    $where['where'][$cidField] = $nid;
    			}
    			else
    			{
    			    $where['where'][$cidField] = array('lin',implode(',',$nids));
    			}
        	    $result[$time] = wmsql::GetCount($where);
	        }
	    }
	    return $result;
	}
	//打赏
	function GetReward($timeType='today',$nid=0,$nids=array())
	{
	    return $this->GetColl('reward',$timeType,$nid,$nids);
	}
	//评论
	function GetReplay($timeType='today',$nid=0,$nids=array())
	{
	    return $this->GetColl('replay',$timeType,$nid,$nids);
	}
	//阅读数据
	function GetRead($timeType='today',$nid=0,$nids=array())
	{
	    return $this->GetColl('read',$timeType,$nid,$nids);
	}
	//章节
	function GetChapter($timeType='today',$nid=0,$nids=array())
	{
	    $timeSer = NewClass('time');
	    foreach ( explode(',',$timeType) as $time)
	    {
	        $where = array();
	        //最高或者平均
	        if( $time == 'max' || $time == 'avg' )
	        {
        	    $where['table'] = $this->chapterTable;
    			$where['field'] = "sum(chapter_number) AS `sum`,FROM_UNIXTIME(chapter_time,'%Y-%m-%d') AS `day`";
    			$where['order'] = '`sum` desc';
			    $where['group'] = '`day`';
			    $where['where']['chapter_nid'] = $nid;
    			if( $nid == 0 )
    			{
    			    $where['where']['chapter_nid'] = array('lin',implode(',',$nids));
    			}
    			$list = wmsql::GetAll($where);
        	    $result[$time] = 0;
    			if( $list )
    			{
    			    if( $time == 'avg' )
    			    {
    			        //最后一条到今天的日期
                        $days = round((strtotime(date("Y-m-d"))-strtotime($list[count($list)-1]['day']))/3600/24);
        	            $result[$time] = intval(array_sum(array_column($list,'sum'))/($days+1),0); 
    			    }
    			    else
    			    {
        	            $result[$time] = $list[0];
    			    }
    			}
	        }
	        else
	        {
        	    $where['table'] = $this->chapterTable;
        	    $where['field'] = 'chapter_number';
        	    if( $time != 'all' )
        	    {
        	        $where['where'] = $timeSer->GetWhere(array('chapter_time'=>$time));
        	    }
		        $where['where']['chapter_nid'] = $nid;
        	    if( $nid == 0 )
    			{
    			    $where['where']['chapter_nid'] = array('lin',implode(',',$nids));
    			}
        	    $result[$time] = wmsql::GetSum($where);
	        }
	    }
	    return $result;
	}
	
	
	/**
	 * 获得小说票类数据统计
	 * @param 参数1，必须，统计类型
	 * @param 参数2，必须，统计时间
	 * @param 参数3，必须，小说id
	 * @param 参数4，选填，小说id集合
	 */
	function GetTicket($rankType='',$timeType='today',$nid=0)
	{
	    $result = array();
        if( $nid>0 )
        {
    	    foreach ( explode(',',$timeType) as $time)
    	    {
        	    $sql = $this->GetTicketSql($rankType,$time,$nid);
                //关闭检查子查询
                wmsql::$checkSql = false;
        	    $rank = wmsql::Query($sql);
                //清空当前表设置的参数
                wmsql::Clear();
                if( $rank )
                {
    	            $rank = array_column($rank,null,'log_cid');
    	            if( isset($rank[$nid]) )
    	            {
                        $result[$time] = $rank[$nid];
                        continue;
    	            }
                }
                $result[$time] = false;
    	    }
    	}
	    return $result;
	}
	/**
	 * 获得小说排名的查询sql
	 * @param 参数1，必须，统计字段
	 * @param 参数1，必须，统计时间
	 * @param 参数1，必须，小说id
	 */
	private function GetTicketSql($rankType,$type,$nid=0)
	{
	    $table = wmsql::CheckTable($this->ticketTable);
	    $where = 'log_module = "novel" and log_status = 2';
	    if( $type == 'all' )
	    {
	        $where .= ' and 1=1';
	    }
	    else
	    {
    	    $timeSer = NewClass('time');
    	    $where .= ' and log_time >'.$timeSer->GetTime($type);
	    }
	    $field = 'log_'.$rankType;
	    
	    //自增排名
	    $curRank = '@curRank := @curRank + 1 AS `cur_rank`';
	    //普通并列排名
	    $prceRank = 'CASE  WHEN @prveVal = rankField THEN @prveRank ELSE @prveRank := @prveRank + 1 END AS `prve_rank`';
	    //高级并列排名
	    $rank = 'CASE WHEN @prveVal = rankField THEN @rank ELSE @rank := @curRank END AS `rank`';
	    //排名字段
	    $rankField = '@prveVal := rankField AS rankField';
	    //查询数据源表
	    $selectForm = '(SELECT log_cid,SUM('.$field.') AS rankField,log_time FROM '.$table.' where '.$where.' GROUP BY log_cid)';
	    
        $sql = 'SELECT log_cid,'.$curRank.','.$prceRank.','.$rank.','.$rankField.' FROM '.$selectForm.' AS n,(SELECT @curRank := 0, @prveRank := 0,@rank := 0,@prveVal := NULL) AS s ORDER BY rankField DESC';
        return $sql;
	}
	
}
?>
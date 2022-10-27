<?php
/**
* 时间数据操作类
*
* @version        $Id: time.class.php 2018年1月6日 13:31  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
class time{
	private $whereStr = array(
			'today','lastday','week','lastweek',
			'month','lastmonth','year','lastyear',
			'lastseven','lastthirty',
			'本日数据','昨日数据','本周数据','上周数据',
			'本月数据','上月数据','本年数据','去年数据',
			'近七天数据','进三十天数据');

	/**
	 * 根据列表数据循环出时间数据
	 * @param 参数1，必须，列表数据
	 * @param 参数2，需要进行数据操作的字段。
	 * @param 参数2，data：需要操作的键值。
	 * @param 擦数2，time：时间字段
	 */
	function GetListTimeData($list,$filedArr)
	{
		$data['all'] = $data['today'] = $data['yesterday'] = 0;
		if($list)
		{
			foreach ($list as $k=>$v)
			{
				//今天
				if( date('Y-m-d',$v[$filedArr[1]]) == date('Y-m-d',time()) )
				{						
					$data['today'] = __Opera( $data['today'] , $v[$filedArr[0]] );
					if( isset($data['todayTime'][date('G',$v[$filedArr[1]])]) )
					{
						$data['todayTime'][date('G',$v[$filedArr[1]])] += $v[$filedArr[0]];
					}
					else
					{
						$data['todayTime'][date('G',$v[$filedArr[1]])] = $v[$filedArr[0]];
					}
				}
				//昨天
				if( date('Y-m-d',$v[$filedArr[1]]) == date("Y-m-d",strtotime("-1 day")) )
				{
					$data['yesterday'] = __Opera( $data['yesterday'] , $v[$filedArr[0]] );
					if( isset($data['yesterdayTime'][date('G',$v[$filedArr[1]])]) )
					{
						$data['yesterdayTime'][date('G',$v[$filedArr[1]])] += $v[$filedArr[0]];
					}
					else
					{
						$data['yesterdayTime'][date('G',$v[$filedArr[1]])] = $v[$filedArr[0]];
					}
				}
				//总
				$data['all'] += $v[$filedArr[0]];
			}
		}

		//循环实时数据
		for($i=1;$i<=24;$i++){
			if( empty($data['todayTime'][$i]) )
			{
				$todayTime[$i]=0;
			}
			else
			{
				$todayTime[$i]=$data['todayTime'][$i];
			}
		
			if( empty($data['yesterdayTime'][$i]) )
			{
				$yesterdayTime[$i]=0;
			}
			else
			{
				$yesterdayTime[$i]=$data['yesterdayTime'][$i];
			}
		}
		$data['todayTime'] = $todayTime;
		$data['yesterdayTime'] = $yesterdayTime;
		
		return $data;
	}
	
	/**
	 * 从模版中的条件值获取时间类型替换
	 * @param string $where 条件
	 * @return string
	 */
	function GetWhere($where)
	{
		if( $where )
		{
			foreach ($where as $key=>$val)
			{
				//如果传入的字符串存在当前时间数组中
				if( in_array($val,$this->whereStr) )
				{
					switch ($val)
					{
						case '本日数据':
						case 'today':
							$where[$key] = array('>',$this->GetToDay());
							break;
						case '昨日数据':
						case 'lastday':
							$where[$key] = array('between',$this->GetLastDay().','.$this->GetToDay());
							break;
						case '近七天数据':
						case 'lastseven':
							$where[$key] = array('>',$this->GetLastDay(7));
							break;
						case '进三十天数据':
						case 'lastthirty':
							$where[$key] = array('>',$this->GetLastDay(30));
							break;
						case '本周数据':
						case 'week':
							$where[$key] = array('>',$this->GetWeek());
							break;
						case '本月数据':
						case 'month':
							$where[$key] = array('>',$this->GetMonth());
							break;
						case '本年数据':
						case 'year':
							$where[$key] = array('>',$this->GetYear());
							break;
						case '上周数据':
						case 'lastweek':
							$where[$key] = array('between',$this->GetLastWeek().','.$this->GetWeek());
							break;
						case '上月数据':
						case 'lastmonth':
							$where[$key] = array('between',$this->GetLastMonth().','.$this->GetMonth());
							break;
						case '去年数据':
						case 'lastyear':
							$where[$key] = array('between',$this->GetLastYear().','.$this->GetYear());
							break;
					}
				}
			}
		}
		return $where;
	}
	
	//获得时间戳
	function GetTime($type)
	{
	    switch($type)
	    {
	        case 'today':
	            return $this->GetToDay();
	            break;
	        case 'week':
	            return $this->GetWeek();
	            break;
	        case 'month':
	            return $this->GetMonth();
	            break;
	        case 'year':
	            return $this->GetYear();
	            break;
	    }
    }
	//获得今日的时间戳
	function GetToDay()
	{
		return strtotime(date("Ymd"));
	}
	//获得昨日(最近几)天的时间戳
	function GetLastDay($day=1)
	{
		if( $day > 1 )
		{
			$day = $day - 1;
		}
		return strtotime(date('Y-m-d', strtotime('-'.$day.' Day')));
	}
	//获得本周的时间戳
	function GetWeek()
	{
		if( date("w")==0 )
		{
			return strtotime("-1 week Monday");
		}
		else
		{
			return strtotime("-1 week Monday");
		}
	}
	//获得上周的时间戳
	function GetLastWeek()
	{
		if( date("w")==0 )
		{
			return strtotime("-2 week Monday");
		}
		else
		{
			return strtotime("-1 week Monday");
		}
	}
	//获得本月的时间戳
	function GetMonth()
	{
		return strtotime(date("Ym01"));
	}
	//获得上月的时间戳
	function GetLastMonth()
	{
		return strtotime(date('Y-m-01',strtotime("-1 Month")));
	}
	//获得本年的时间戳
	function GetYear()
	{
		return strtotime(date("Y0101"));
	}
	//获得去年的时间戳
	function GetLastYear()
	{
		return strtotime(date('Y-01-01',strtotime("-1 Year")));
	}
}
?>
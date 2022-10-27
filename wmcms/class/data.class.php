<?php
/**
 * 数据统计的类文件
 *
 * @version        $Id: data.class.php 2021年01月13日 14:30  weimeng
 * @package        WMCMS
 * @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
 * @link           http://www.weimengcms.com
 *
 */
class data
{
	public $type;
	//显示周几
	public $showWeek =  false;
	//显示日期
	public $showDate =  false;
	
	/**
	 * 获得时间的类型
	 */
	function GetTimeType()
	{
		$arr = array(
			'week'=>'本周数据',
			'month'=>'本月数据',
			'year'=>'本年数据',
		);
		
		return $arr;
	}
	
	
	/**
	 * 获得时间的数组格式
	 * @param 参数1，必须，时间类型
	 */
	function GetTime( $type )
	{
		$this->type = $type;
		//统计的时间类型
		switch ($type)
		{
			//按周查看
			case "week":
				$timeArr = array("周一","周二","周三","周四","周五","周六","周日");
				break;
		
			case "month":
				$nowDay = date('t');
				for($i =1 ; $i <= $nowDay ; $i++)
				{
					$timeArr[] = $i.'号';
				}
				break;
		
			default:
				$timeArr = array("一月","二月","三月","四月","五月","六月","七月","八月","九月","十月","十一月","十二月");
				break;
		}
		
		return $timeArr;
	}
	
	/**
	 * 获取数据
	 * @param 参数1，必须，数据表
	 * @param 参数2，必须，表id字段
	 * @param 参数3，必须，表的时间字段
	 * @param 参数4，选填，获取数据增长的时间
	 * @param 参数5，选填，附加条件
	 */
	function GetCount( $table , $fieldObj , $time , $type = '',$wheresql=array())
	{
		if( $type == '' )
		{
			$type = $this->type;
		}
		$where['table'] = '@'.$table;
		//如果求和的字段是数组
		if( is_array($fieldObj) )
		{
			$id = $fieldObj[0];
			$group = $id.',';
			$field = ','.$id;
		}
		else
		{
			$field = $group = '';
			$id = $fieldObj;
		}
		
		//对时间进行区分设置条件
		switch ($type)
		{
			case "week":
				$sunI = 7;
				$where['field'] = "count({$id}) AS count,dayofweek(FROM_UNIXTIME({$time})) AS month".$field;
				$where['where'] = $wheresql;
				$where['where'][$time] = array('string',"WEEKOFYEAR(FROM_UNIXTIME({$time},'%Y%m%d')) = WEEKOFYEAR(NOW()) and FROM_UNIXTIME({$time},'%Y')=FROM_UNIXTIME(UNIX_TIMESTAMP(NOW()),'%Y')");
				$where['group'] = "dayofweek(FROM_UNIXTIME({$time},'%Y%m%d'))";
				break;
				
			case "month":
				$sunI = date('t');
				$where['field'] = "count({$id}) AS count,FROM_UNIXTIME({$time},'%d') AS month".$field;
				$where['where'] = $wheresql;
				$where['where'][$time] = array('string',"FROM_UNIXTIME({$time},'%Y%m') = FROM_UNIXTIME(UNIX_TIMESTAMP(NOW()),'%Y%m')");
				$where['group'] = $group."FROM_UNIXTIME({$time},'%d')";
				break;
			
			default:
				$sunI = 12;
				$where['field'] = "count({$id}) AS count,FROM_UNIXTIME({$time},'%m') AS month".$field;
				$where['where'] = $wheresql;
				$where['group'] = "FROM_UNIXTIME({$time},'%m')";
				break;
		}
		$arr = wmsql::GetAll($where);
		if( !$arr && !is_array($fieldObj))
		{
			$arr[0] = array('month'=>0,'count'=>0);
		}
		$newArr = $newData = array();
		//对数据进行键值对重组
		if( $arr )
		{
			if( is_array($fieldObj) )
			{
				foreach ($arr as $k=>$v)
				{
					$data[$v[$id]][] = $v;
				}
			}
			else
			{
				$data[] = $arr;
			}
			foreach ($data as $key=>$val)
			{
				foreach ($val as $k=>$v){
					if( $type == 'week' )
					{
						$newArr[$key][$v['month']-1] = $v['count'];
					}
					else
					{
						$newArr[$key][$v['month']] = $v['count'];
					}
				}
			}
		}
		
		//$newArr 为空的情况
		//数据整合，对空年月设置为0
		if( $newArr )
		{
			foreach ($newArr as $key=>$val)
			{
				for( $i=1 ; $i <= $sunI ; $i++ )
				{
					$k = $i;
					//如果是每年的月份数据就加上0
					if( $type == 'year' || $type == 'month' )
					{
						if( $i < 10 )
						{
							$k = '0'.$i;
						}
					}
					//因为查询出的数据从周日为1开始计算，如果重组的时候减一为0，周日7就使用0的键
					if( $type == 'week' && $i == 7)
					{
						$k = '0';
					}
					
					//数据行数
					$count = str::CheckElse( GetKey($newArr,$key.','.$k),  ''  , '0' , GetKey($newArr,$key.','.$k) );
					$res = array();
					//显示日期
					if( $this->showDate == true )
					{
						$res['count'] = $count;
						if( $type == 'week' )
						{
							$res['date'] = date('Y-m-d' ,strtotime( '+' . ($i-date('w')) .' days'));
						}
						else if( $type == 'month' )
						{
							$res['date'] = date('Y-m-d' ,strtotime( '+' . ($i-date('d')) .' days'));
						}
					}
					//显示在周
					if( $this->showWeek == true )
					{
						if( $type == 'week' )
						{
							$res['week'] = $i;
						}
						else if( $type == 'month' )
						{
							$res['week'] = date('w',strtotime( '+' . ($i-date('d')) .' days'));
							$res['week'] = $res['week'] > 0?$res['week']:7;
						}
					}
					//设置当前值
					if( empty($res) )
					{
						$newData[$key][$i] = $count;
					}
					else
					{
						$newData[$key][$i] = $res;
					}
				}
			}
		}
		else
		{
			for( $i=1 ; $i <= $sunI ; $i++ )
			{
				$k = $i;
				//如果是每年的月份数据就加上0
				if( $type == 'year' || $type == 'month' )
				{
					if( $i < 10 )
					{
						$k = '0'.$i;
					}
				}
				//因为查询出的数据从周日为1开始计算，如果重组的时候减一为0，周日7就使用0的键
				if( $type == 'week' && $i == 7)
				{
					$k = '0';
				}
				
				//数据行数
				$count = 0;
				$res = array();
				//显示日期
				if( $this->showDate == true )
				{
					$res['count'] = $count;
					if( $type == 'week' )
					{
						$res['date'] = date('Y-m-d' ,strtotime( '+' . ($i-date('w')) .' days'));
					}
					else if( $type == 'month' )
					{
						$res['date'] = date('Y-m-d' ,strtotime( '+' . ($i-date('d')) .' days'));
					}
				}
				//显示在周
				if( $this->showWeek == true )
				{
					if( $type == 'week' )
					{
						$res['week'] = $i;
					}
					else if( $type == 'month' )
					{
						$res['week'] = date('w',strtotime( '+' . ($i-date('d')) .' days'));
						$res['week'] = $res['week'] > 0?$res['week']:7;
					}
				}
				//设置当前值
				if( empty($res) )
				{
					$newData[0][$i] = $count;
				}
				else
				{
					$newData[0][$i] = $res;
				}
			}
		}
		return $newData;
	}
	
	
	function GetList( $table , $fieldObj , $time , $type = '',$wheresql=array(),$returnKey='')
	{
		$data = $this->GetCount($table ,$fieldObj , $time , $type , $wheresql);
		if( empty($returnKey) )
		{
			return $data;
		}
		if( isset($data[$returnKey]) )
		{
			return $data[$returnKey];
		}
		if( count($data) == 1 && isset($data[0]) )
		{
			return $data[0];
		}
		return $data;
	}
}
?>
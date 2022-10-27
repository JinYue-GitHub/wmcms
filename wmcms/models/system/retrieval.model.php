<?php
/**
* 分类检索模块模型
*
* @version        $Id: retrieval.model.php 2017年6月16日 21:36  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class RetrievalModel
{
	public $table = '@system_retrieval';
	public $typeTable = '@system_retrieval_type';
	
	function __construct( $data = '' ){}

	/**
	 * 根据获得状态数据
	 * @param 参数1，必须，数据id或者查询条件
	 */
	function GetStatus($type='')
	{
		$arr = array(0=>'隐藏',1=>'显示');
		if($type == '' )
		{
			return $arr;
		}
		else
		{
			return $arr[$type];
		}
	}
	/**
	 * 根据获得字段条件类型
	 * @param 参数1，必须，数据id或者查询条件
	 */
	function GetWhereType($type='')
	{
		$arr = array('-1'=>'倒序','0'=>'顺序','1'=>'等于','2'=>'小于','3'=>'大于',
				'4'=>'区间大小','5'=>'首字母','6'=>'相似','7'=>'区间开头','8'=>'集合-少对多','9'=>'集合-多对少');
		if($type == '' )
		{
			return $arr;
		}
		else
		{
			return $arr[$type];
		}
	}
	
	/**
	 * 获得筛选的条件
	 * @param 参数1，必须，所属的模块
	 * @param 参数2，选填，是否带入了where条件
	 * @param 参数3，选填，返回类型，1为模版条件，2为数组。
	 */
	function GetWhere($module , $oldWhere=array() , $type='1')
	{
		$str = '';
		$where = array();
		//查询出来所有的筛选条件
		$wheresql['field']= 'type_par,retrieval_id,retrieval_field,retrieval_type,retrieval_value';
		$wheresql['where']['type_module'] = $module;
		$reData = $this->GetAll($wheresql);
		if( $reData )
		{
			foreach ($reData as $k=>$v)
			{
				$par = Get($v['type_par'],'-1');
				//如果不是全部，并且条件id相等
				if( $par != '-1' && $v['retrieval_id'] == $par)
				{
					switch ($v['retrieval_type'])
					{
						//倒序
						case '-1':
							$str .= '排序='.$v['retrieval_field'].' desc;';
							$where['order'] = $v['retrieval_field'].' desc';
							break;
						//顺序
						case '0':
							$str .= '排序='.$v['retrieval_field'].';';
							$where['order'] = $v['retrieval_field'];
							break;
						//小于
						case '2':
							$str .= $v['retrieval_field'].'=[<->'.$v['retrieval_value'].'];';
							$where[$v['retrieval_field']] = array('<',$v['retrieval_value']);
							break;
						//大于
						case '3':
							$str .= $v['retrieval_field'].'=[>->'.$v['retrieval_value'].'];';
							$where[$v['retrieval_field']] = array('>',$v['retrieval_value']);
							break;
						//区间大小
						case '4':
							$str .= $v['retrieval_field'].'=[between->'.$v['retrieval_value'].'];';
							$where[$v['retrieval_field']] = array('between',$v['retrieval_value']);
							break;
						//首字母
						case '5':
							$str .= $v['retrieval_field'].'=[endlike->'.$v['retrieval_value'].'];';
							$where[$v['retrieval_field']] = array('<',$v['retrieval_value']);
							break;
						//相似
						case '6':
							$str .= $v['retrieval_field'].'=[like->'.$v['retrieval_value'].'];';
							$where[$v['retrieval_field']] = array('like',$v['retrieval_value']);
							break;
						//区间开头
						case '7':
							$str .= $v['retrieval_field'].'=[betweenlike->'.$v['retrieval_value'].'];';
							$where[$v['retrieval_field']] = array('<',$v['retrieval_value']);
							break;
						//在集合中查找，find_in_set(字符串,字段);
						case '8':
							$str .= $v['retrieval_field'].'=[rin->'.$v['retrieval_value'].'];';
							$where[$v['retrieval_field']] = array('lin',$v['retrieval_value']);
							break;
						//在集合中查找，find_in_set(字段,字符串);
						case '9':
							$str .= $v['retrieval_field'].'=[lin->'.$v['retrieval_value'].'];';
							$where[$v['retrieval_field']] = array('rin',$v['retrieval_value']);
							break;
						//等于
						default:
							$str .= $v['retrieval_field'].'='.$v['retrieval_value'].';';
							$where[$v['retrieval_field']] = $v['retrieval_value'];
							break;
					}
				}
			}
		}
		
		//如果旧的条件存在并且新的条件存在
		if( $oldWhere && $where)
		{
			$where = array_merge($oldWhere,$where);
		}
		//旧的存在，新的不存在
		else if( $oldWhere )
		{
			$where = $oldWhere;
		}
		
		if( $type == '1' )
		{
			$where = $str;
		}
		return $where;
	}
	
	/**
	 * 根据获得一条数据
	 * @param 参数1，必须，数据id或者查询条件
	 */
	function GetOne( $wheresql )
	{
		$where['table'] = $this->table;
		if( !is_array($wheresql) )
		{
			$where['where']['retrieval_id'] = $wheresql;
		}
		else
		{
			$where['where'] = $wheresql;
		}
		return wmsql::GetOne($where);
	}
	
	/**
	 * 插入数据
	 * @param 参数1，必须，需要插入的数据
	 */
	function Insert($data)
	{
		return wmsql::Insert($this->table, $data);
	}
	
	/**
	 * 根据获得全部相关数据条数
	 * @param 参数1，必须，查询条件
	 */
	function GetCount($where)
	{
		$where['table'] = $this->table;
		//数据条数
		return wmsql::GetCount($where , 'retrieval_id');
	}
	
	/**
	 * 根据获得全部相关数据
	 * @param 参数1，选填，查询条件
	 */
	function GetAll($wheresql='')
	{
		if( is_array($wheresql) )
		{
			$where = $wheresql;
		}
		$where['table'] = $this->table;
		$where['left'][$this->typeTable] = 'type_id=retrieval_type_id';
		if( !isset($where['order']) )
		{
			$where['order'] = 'retrieval_order';
		}
		return wmsql::GetAll($where);
	}
	
	/**
	 * 修改站内站点
	 * @param 参数1，必须，查询条件
	 */
	function Update($data,$where)
	{
		return wmsql::Update($this->table, $data, $where);
	}
	
	/**
	 * 删除数据
	 * @param 参数1，必须，查询条件
	 */
	function Del($where='')
	{
		return wmsql::Delete($this->table,$where);
	}
	
	

	/**
	 * 根据获得全部分类
	 * @param 参数1，选填，查询条件
	 */
	function GetType($wheresql='')
	{
		if( is_array($wheresql) )
		{
			$where = $wheresql;
		}
		$where['table'] = $this->typeTable;
		if( !isset($where['order']) )
		{
			$where['order'] = 'type_order';
		}
		return wmsql::GetAll($where);
	}

	/**
	 * 根据获得分类类型
	 * @param 参数1，选填，类型id
	 */
	function GetTypeType($val='')
	{
		$arr = array('1'=>'条件','2'=>'排序');
		if( $val != '' )
		{
			return $arr[$val];
		}
		else
		{
			return $arr;
		}
	}
	
	/**
	 * 修改检索分类信息
	 * @param 参数1，必填，修改的数据
	 * @param 参数2，必填，修改的条件
	 */
	function TypeUpdate($data,$where)
	{
		return wmsql::Update($this->typeTable, $data, $where);
	}
}
?>
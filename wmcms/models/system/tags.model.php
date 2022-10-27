<?php
/**
* 内容标签模块模型
*
* @version        $Id: tags.model.php 2016年12月31日 12:31  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class TagsModel
{
	public $table = '@system_tags';
	public $typeTable = '@system_tags_type';
	
	function __construct( $data = '' ){}

	/**
	 * 获得作者推荐状态
	 * @param 参数1，必须，推荐的值
	 */
	function GetAuthorRec($rec='')
	{
		$arr = array(0=>'未推荐',1=>'推荐');
		if($rec == '' )
		{
			return $arr;
		}
		else
		{
			return $arr[$rec];
		}
	}

	/**
	 * 根据id获得数据
	 * @param 参数1，必须，标签id
	 */
	function GetById($id)
	{
		$where['table'] = $this->table;
		$where['where']['tags_id'] = $id;
		return wmsql::GetOne($where);
	}

	/**
	 * 根据id获得数据
	 * @param 参数1，必须，标签id
	 */
	function GetByName($module,$name)
	{
		$where['table'] = $this->table;
		$where['where']['tags_name'] = $name;
		$where['where']['tags_module'] = $module;
		return wmsql::GetOne($where);
	}
	
	/**
	 * 插入数据
	 * @param 参数1，必须，插入的内容
	 * @param 参数2，选填，是否设置tag
	 */
	function Insert($data,$setTag=true)
	{
		$pinyinSer = NewClass('pinyin');
		$data['tags_pinyin'] = $pinyinSer->topy($data['tags_name']);
		$data['tags_time'] = time();
    	$insertId = wmsql::Insert($this->table,$data);
		if( $setTag == false )
		{
		    return $insertId;
		}
		else
		{
        	return $this->SetTags( $data['tags_module'] , $data['tags_name'] );
		}
	}
	
	/**
	 * 修改数据
	 * @param 参数1，必须，修改的内容
	 * @param 参数2，必须，修改的条件
	 * @param 参数3，选填，所属模块
	 */
	function Update($data,$where,$module='')
	{
	    if( isset($data['tags_name']) )
	    {
    		//设置拼音
    		$pinyinSer = NewClass('pinyin');
    		$data['tags_pinyin'] = $pinyinSer->topy($data['tags_name']);
    		wmsql::Update($this->table,$data,$where);
    		//设置标签
    		return $this->SetTags( $module , $data['tags_name'] );
	    }
	    return wmsql::Update($this->table,$data,$where);
	}
	
	/**
	 * 修改数据
	 * @param 参数1，必须，删除的条件
	 */
	function Del($where)
	{
	    return wmsql::Delete($this->table,$where);
	}
	
	/**
	 * 写入tag标签的数据
	 * @param 参数1，必须，所属的模块
	 * @param 参数2，必须，标签的名字(可以多个)
	 * @param 参数3，选填，附加数据
	 */
	function SetTags( $module , $name , $extData = array())
	{
	    $tagArr = [];
		$nameArr = explode(',', $name);
		$where['table'] = $this->table;
		$where['where']['tags_module'] = $module;
		$where['where']['tags_name'] = array('lin',$name);
		$data = wmsql::GetAll($where);
		if( $data )
		{
	        $data = array_column($data,'tags_id','tags_name');
		}
		//获得标签
		foreach ($nameArr as $k=>$v)
		{
		    //不存在就添加
		    if( !isset($data[$v]) )
	        {
	            //判断当前标签数量，并且更新
	            $inserData = array_merge(array('tags_name'=>$v,'tags_module'=>$module),$extData);
	            $tagArr[$v] = $this->Insert($inserData,false);
	        }
	        //存在就写入数组
	        else
	        {
	            $tagArr[$v] = $data[$v];
	        }
		}
		
	    //更新标签数据量
		foreach ($tagArr as $k=>$v)
		{
		    global $tableSer;
			$tableWhere[$tableSer->tableArr[$module]['tag']] = array('rin',$k);
			$count = $tableSer->GetCount($module,$tableWhere);
			$this->Update(array('tags_data'=>$count), array('tags_id'=>$v));
		}
		return true;
	}
	
	/**
	 * 根据获得全部相关数据条数
	 * @param 参数1，必须，查询条件
	 */
	function GetCount($where)
	{
		$where['table'] = $this->table;
		//数据条数
		return wmsql::GetCount($where , 'tags_id');
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
		$where['left'][$this->typeTable] = 'tags_type_id=type_id';
		if( !isset($where['order']) )
		{
			$where['order'] = 'tags_data desc,tags_id desc';
		}
		return wmsql::GetAll($where);
	}
	
	/**
	 * 根据获得全部相关数据
	 * @param 参数1，必须，模块
	 * @param 参数2，必须，标签名字多个
	 * @param 参数3，选填，是否是推荐标签
	 */
	function GetByNameKey($module,$name='',$rec=1)
	{
	    if( !empty($name) )
	    {
    		$where['table'] = $this->table;
    		$where['where']['tags_author_rec'] = $rec;
    		$where['where']['tags_module'] = $module;
    		$where['where']['tags_name'] = array('rin',$name);
    		$data = wmsql::GetAll($where);
    		if( $data )
    		{
    	        return array_column($data,'tags_name');
    		}
	    }
		return array();
	}
}
?>
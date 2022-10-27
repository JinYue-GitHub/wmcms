<?php
/**
* 搜索类标签处理文件
*
* @version        $Id: search.class.php 2015年9月19日 11:24  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2015年12月26日 15:24 weimeng
*
*/
class search
{
	protected static $table = '@search_search';
	function __construct()
	{
		new searchlabel();
	}
	
	
	static function GetData( $type , $where )
	{
		$wheresql = self::GetWhere( $where );
		switch ( $type )
		{
			case 'hot':
				$wheresql['table'] = self::$table;
				break;
		}

		$data = wmsql::GetAll($wheresql);
		
		return $data;
	}
	
	
	/**
	 * 获得字符串中的条件sql
	 * 返回值字符串
	 * @param 参数1：需要查询的字符串。
	 **/
	static function GetWhere($where)
	{
		//设置需要替换的字段
		$arr = array(
			'id' =>'search_id desc',
			'搜索量' =>'search_count desc',
			
			'推荐' =>'search_rec',
			'是' =>'1',
			'否' =>'0',
		);
	
		return tpl::GetWhere($where,$arr);
	}
	
	/**
	 * 关键词搜索次数增加
	 * @param 参数1，必须，搜索的模块。
	 */
	static function SearchNumber( $module )
	{
		$type = C( 'page.type' );
		$key = C( 'page.key' );
		$dataCount = page::$dataCount;
		$table = self::$table;
				
		$keyArr = explode( ' ' , $key );
		foreach ( $keyArr as $k=>$v )
		{
			if( !empty($v) && trim($v) != '' )
			{
				$where['table'] = $table;
				$where['where']['search_module'] = $module;
				$where['where']['search_type'] = $type;
				$where['where']['search_key'] = $v;
				
				//查询关键字是否存在。
				$count = wmsql::GetCount( $where , 'search_id' );
	
				//有数据就更新。
				if( $count > 0 )
				{
					$data['search_data'] = $dataCount;
					$data['search_count'] = array('+',1);
					$data['search_time'] = time();
					wmsql::Update($table, $data, $where['where']);
				}
				//没有就插入数据
				else
				{
					$data = $where['where'];
					$data['search_data'] = $dataCount;
					$data['search_time'] = time();
					
					wmsql::Insert( $table , $data );
				}

				//如果是标签搜索就进行标签表搜索次数增加
				if( $type == '3' )
				{
					self::TagSearch( $module , $v , $dataCount);
				}
			}
		}
	}
	
	
	/**
	 * 标签表的搜索次数增加
	 * @param 参数1，搜索的模块。
	 * @param 参数2，搜索的标签。
	 */
	static function TagSearch($module , $key , $dataCount)
	{
	    $tagsMod = NewModel('system.tags');
	    $data = $tagsMod->GetByName($module,$key);
		//有数据就更新。
		if( $data > 0 )
		{
			$tagData['tags_search'] = array('+',1);
			$tagsMod->Update($tagData,array('tags_id'=>$data['tags_id']));
		}
		//没有就插入数据
		else
		{
		    $tagsMod->SetTags($module,$key);
		}
		return true;
	}
	
	
	//搜索类型判断
	static function GetType( $val )
	{
		switch ( $val )
		{
			case '1':
				$type = C( 'serach.par.type_1' , null , 'lang' );
				break;
				
			case '2':
				$type = C( 'serach.par.type_2' , null , 'lang' );
				break;
				
			case '3':
				$type = C( 'serach.par.type_3' , null , 'lang' );
				break;
		}
		
		return $type;
	}
}
?>
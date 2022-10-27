<?php
/**
* 模块的表类
*
* @version        $Id: moduletable.class.php 2016年5月10日 16:03 weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
//自动new一个对象
$tableSer = new ModuleTable();
class ModuleTable {
	//模块类型
	public $module;
	//数据的id
	public $id;
	//表的数据
	public $table;
	//模块表的数组
	public $tableArr;
	
	//构造函数，new的时候自动设置路径
	function __construct()
	{
		$this->tableArr = array(
			//app表
			'app'=> array(
				'table'=>'@app_app',
				'id'=>'app_id',
				'tid'=>'type_id',
				'name'=>'app_name',
				'field'=>'app_',
				'pinyin'=>'app_pinyin',
				'ico'=>'app_ico',
				'tag'=>'app_tags',
				'time'=>'app_addtime',
			),
			//app分类表
			'apptype'=> array(
				'table'=>'@app_type',
				'id'=>'type_id',
				'pid'=>'type_pid',
				'name'=>'type_name',
				'field'=>'type_',
				'pinyin'=>'type_pinyin',
			),
			//about表
			'about'=> array(
				'table'=>'@about_about',
				'id'=>'about_id',
				'tid'=>'type_id',
				'name'=>'about_name',
				'field'=>'about_',
				'pinyin'=>'about_pinyin',
				'time'=>'about_time',
			),
			//about分类表
			'abouttype'=> array(
				'table'=>'@about_type',
				'id'=>'type_id',
				'pid'=>'type_pid',
				'name'=>'type_name',
				'field'=>'type_',
				'pinyin'=>'type_pinyin',
			),
			//文章表
			'article'=> array(
				'table'=>'@article_article',
				'id'=>'article_id',
				'tid'=>'type_id',
				'name'=>'article_name',
				'field'=>'article_',
				'time'=>'article_addtime',
				'tag'=>'article_tags',
				'simg'=>'article_simg',
			),
			//文章分类表
			'articletype'=> array(
				'table'=>'@article_type',
				'id'=>'type_id',
				'pid'=>'type_pid',
				'name'=>'type_name',
				'field'=>'type_',
				'pinyin'=>'type_pinyin',
			),
			//小说表
			'novel'=> array(
				'table'=>'@novel_novel',
				'id'=>'novel_id',
				'tid'=>'type_id',
				'name'=>'novel_name',
				'field'=>'novel_',
				'time'=>'novel_uptime',
				'pinyin'=>'novel_pinyin',
				'tag'=>'novel_tags',
				'simg'=>'novel_cover',
			),
			//小说分类表
			'noveltype'=> array(
				'table'=>'@novel_type',
				'id'=>'type_id',
				'pid'=>'type_pid',
				'name'=>'type_name',
				'field'=>'type_',
				'pinyin'=>'type_pinyin',
			),
			//图片表
			'picture'=> array(
				'table'=>'@picture_picture',
				'id'=>'picture_id',
				'tid'=>'type_id',
				'name'=>'picture_name',
				'field'=>'picture_',
				'time'=>'picture_time',
				'tag'=>'picture_tags',
				'simg'=>'picture_simg',
			),
			//图片分类表
			'picturetype'=> array(
				'table'=>'@picture_type',
				'id'=>'type_id',
				'pid'=>'type_pid',
				'name'=>'type_name',
				'field'=>'type_',
				'pinyin'=>'type_pinyin',
			),
			//论坛表
			'bbs'=> array(
				'table'=>'@bbs_bbs',
				'id'=>'bbs_id',
				'tid'=>'type_id',
				'name'=>'bbs_title',
				'field'=>'bbs_',
				'tag'=>'bbs_tags',
				'time'=>'bbs_replay_time',
			),
			//论坛分类表
			'bbstype'=> array(
				'table'=>'@bbs_type',
				'id'=>'type_id',
				'tid'=>'type_id',
				'pid'=>'type_pid',
				'name'=>'type_name',
				'field'=>'type_',
				'pinyin'=>'type_pinyin',
			),
			//友链表
			'link'=> array(
				'table'=>'@link_link',
				'id'=>'link_id',
				'tid'=>'type_id',
				'name'=>'link_name',
				'field'=>'link_',
				'time'=>'link_jointime',
			),
			//友链分类表
			'linktype'=> array(
				'table'=>'@link_type',
				'id'=>'type_id',
				'pid'=>'type_pid',
				'name'=>'type_name',
				'field'=>'type_',
				'pinyin'=>'type_pinyin',
			),
			//专题表
			'zt'=> array(
				'table'=>'@zt_zt',
				'id'=>'zt_id',
				'tid'=>'type_id',
				'name'=>'zt_name',
				'field'=>'zt_',
				'time'=>'zt_time',
			),
			//专题分类表
			'zttype'=> array(
				'table'=>'@zt_type',
				'id'=>'type_id',
				'pid'=>'type_pid',
				'name'=>'type_name',
				'field'=>'type_',
				'pinyin'=>'type_pinyin',
			),
			//评论表
			'replay'=> array(
				'table'=>'@replay_replay',
				'id'=>'replay_id',
				'name'=>'replay_content',
				'field'=>'replay_',
				'time'=>'replay_time',
			),
			//幻灯片表
			'flash'=> array(
				'table'=>'@flash_flash',
				'id'=>'flash_id',
				'tid'=>'type_id',
				'name'=>'flash_name',
				'field'=>'flash_',
				'time'=>'flash_time',
			),
			//幻灯片分类表
			'flashtype'=> array(
				'table'=>'@flash_type',
				'id'=>'type_id',
				'pid'=>'type_pid',
				'name'=>'type_name',
				'field'=>'type_',
				'pinyin'=>'type_pinyin',
			),
		);
	}
	
	
	/**
	 * 获得模块表的数组
	 * @param 参数1，选填，模块类型
	 */
	function GetTable( $module = '' )
	{
		if( $module == '' )
		{
			$module = $this->module;
		}
		
		if( $module == '' )
		{
			return $this->tableArr;
		}
		else if( is_array(GetKey($this->tableArr,$module)) )
		{
			return $this->table = $this->tableArr[$module];
		}
		else
		{
			return false;
		}
	}
	



	/**
	 * 获得相应模块的标题
	 * @param 参数1，必须，模块
	 * @param 参数2，必须，文章id
	 * @param 参数3，选填，是否返回全部字段
	 */
	function GetTypeName( $module = '' , $id = '' , $type = '0')
	{
		if( $id == '' )
		{
			return '模块数据ID为空！';
		}
		else
		{
			$where['table'] = '@'.$module.'_type';
			//是否返回全部字段
			if( $type == '0' )
			{
				$where['file'] = 'type_name';
			}
			$where['where']['type_id'] = $id;
			
			$data = wmsql::GetOne($where);
			if( $data )
			{
				//是否返回全部字段
				if( $type == '0' )
				{
					return $data['type_name'];
				}
				else
				{
					return $data;
				}
			}
			else
			{
				return '此模块数据不存在！';
			}
		}
	}
	
	/**
	 * 获得分类信息
	 * @param 参数1，必须，模块
	 * @param 参数2，必须，文章id
	 */
	function GetType( $module = '' , $id = '')
	{
		return $this->GetTypeName( $module , $id , 1);
	}
	
	
	/**
	 * 获得相应模块的内容标题
	 * @param 参数1，必须，模块
	 * @param 参数2，必须，文章id
	 */
	function GetContentName( $module = '' , $id = '' )
	{
		if( $id == '' )
		{
			return '模块数据ID为空！';
		}
		else
		{
			$this->GetTable( $module );
			
			$where['table'] = $this->table['table'];
			$where['file'] = $this->table['name'];
			$where['where'][$this->table['id']] = $id;
			
			$data = wmsql::GetOne($where);
			if( $data )
			{
				return $data[$this->table['name']];
			}
			else
			{
				return '此模块数据不存在！';
			}
		}
	}
	
	

	/**
	 * 获得相应查询条件
	 * @param 参数1，模块
	 * @param 参数2，条件
	 */
	function GetWhere($module , $wheresql='')
	{
		if( $this->GetTable($module) === false )
		{
			return false;
		}
		
		$where['table'] = $this->table['table'];
	
		if( is_array($wheresql) )
		{
			$where['where'] = $wheresql;
		}
		else
		{
			$where['where'][$this->table['id']] = $wheresql;
		}
		return $where;
	}
	
	/**
	 * 获得相应模块的数据量
	 * @param 参数1，模块
	 * @param 参数2，条件
	 */
	function GetCount( $module = '' , $wheresql = '' )
	{
		$where = $this->GetWhere($module , $wheresql);
		if( $where === false )
		{
			return false;
		}
		return wmsql::GetCount($where);
	}
	


	/**
	 * 获得相应模块的数据量
	 * @param 参数1，模块
	 * @param 参数2，条件
	 */
	function GetData( $module = '' , $wheresql = '' )
	{
		$where = $this->GetWhere($module , $wheresql);
		if( $where === false )
		{
			return false;
		}
		return wmsql::GetOne($where);
	}
}
?>
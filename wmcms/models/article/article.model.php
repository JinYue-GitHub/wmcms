<?php
/**
* 文章信息模型
*
* @version        $Id: article.model.php 2018年2月11日 20:37  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class ArticleModel
{
	//分类表
	public $typeTable = '@article_type';
	//内容表
	public $articleTable = '@article_article';
	//文章的配置
	public $articleConfig,$articleSaveType,$articleSavePath;
	
	/**
	 * 构造函数
	 */
	function __construct()
	{
		//获取配置文件
		$this->articleConfig = GetModuleConfig('article');
		//保存类型和路径
		$this->articleSaveType = $this->articleConfig['data_save_type'];
		$this->articleSavePath = WMROOT.$this->articleConfig['data_save_path'];
	}
	

	/**
	 * 获得所有文章属性分类
	 */
	function GetSaveType($k = '')
	{
		$arr = array(
			'1'=>'数据库',
			'2'=>'文件',
		);
		if( $k != '' )
		{
			return $arr[$k];
		}
		else
		{
			return $arr;
		}
	}
	
	/**
	 * 插入文章信息
	 * @param 参数1，必须，条件
	 */
	function Insert( $data )
	{
		//不保存
		if( $this->articleSaveType == '2' )
		{
			$content = $data['article_content'];
			$data['article_content'] = '';
		}
		$insertId = wmsql::Insert($this->articleTable, $data);
		
		//保存文件
		if( $this->articleSaveType == '2' )
		{
			$saveData['article_save_type'] = 2;
			$saveData['article_save_path'] = str_replace(WMROOT,'',$this->GetArticlePath($data['type_id'], $insertId));
			$where['article_id'] = $insertId;
			wmsql::Update($this->articleTable, $saveData, $where);
			$this->SaveTXT($content, $data['type_id'], $insertId);
		}
		return $insertId;
	}

	/**
	 * 修改小说内容
	 * @param 参数1，必须，修改的内容
	 * @param 参数2，必须，查询到条件
	 */
	function Update($data , $whereArr)
	{
		if( !is_array($whereArr) )
		{
			$where['article_id'] = $whereArr;
		}
		else
		{
			$where = $whereArr;
		}
		
		//保存文件
		if( $this->articleSaveType == '2' && isset($data['article_content']) )
		{
			$articleData = $this->GetOne($where);
			$content = $data['article_content'];
			$data['article_content'] = '';
			$this->SaveTXT($content, $articleData['type_id'], $articleData['article_id'], $articleData['article_save_path']);
		}
		return wmsql::Update($this->articleTable, $data, $where);
	}
	
	
	/**
	 * 删除文章
	 * @param 参数1，必须，查询的条件
	 */
	function Delete($whereArr)
	{
		if( !is_array($whereArr) )
		{
			$where['article_id'] = $whereArr;
		}
		else
		{
			$where = $whereArr;
		}
		
		//删除文档
		$wheresql['table'] = $this->articleTable;
		$wheresql['field'] = 'article_id,type_id,article_save_type,article_save_path';
		$wheresql['where'] = $where;
		$list = wmsql::GetAll($wheresql);
		if( $list )
		{
			foreach ($list as $k=>$v)
			{
				if( $v['article_save_type'] == '2' && $v['article_save_path'] != '' )
				{
					file::DelFile(WMROOT.$v['article_save_path']);
				}
			}
		}
		//删除记录
		wmsql::Delete($this->articleTable,$where);
		
		return true;
	}
	
	/**
	 * 检查小说名字是否存在
	 * @param 参数1，必须，小说的名字
	 * @param 参数2，选填，小说的id
	 */
	function CheckName( $name , $id = '0' )
	{
		$where['article_name'] = $name;
		if( $id > 0 )
		{
			$where['article_id'] = array( '<>' , $id);
		}
		return $this->GetCount($where);
	}
	
	
	/**
	 * 获得数据条数
	 * @param 参数1，必须，查询条件
	 */
	function GetCount($where)
	{
		$wheresql['table'] = $this->articleTable;
		$wheresql['where'] = $where;
		return wmsql::GetCount($wheresql);
	}

	/**
	 * 获得一条数据
	 * @param 参数1，必须，查询条件
	 */
	function GetOne($where)
	{
		$wheresql['table'] = $this->articleTable.' as a';
		$wheresql['left'][$this->typeTable.' as t'] = 'a.type_id=t.type_id';
		if( is_array($where) )
		{
			$wheresql['where'] = $where;
		}
		else
		{
			$wheresql['where']['article_id'] = $where;
		}
		$data = wmsql::GetOne($wheresql);
		
		//如果存在数据，并且保存类型为文件且路径不为空。
		if( $data && $data['article_save_type'] == '2' && $data['article_save_path'] != '' )
		{
			$data['article_content'] = $this->GetTXT($data['type_id'], $data['article_id']);
		}
		return $data;
	}
	

	/**
	 * 替换文章内容
	 * @param 参数1，必须，内容
	 * @param 参数3，必须，文章id
	 */
	function RepContent($content , $cid)
	{
		$downMod = NewModel('down.down');
		return $downMod->RepContent('editor','article',$content , $cid);
	}
	

	/**
	 * 获得文章文件路径
	 * @param 参数1，必须，文章分类id
	 * @param 参数2，必须，文章id
	 * @param 参数3，选填，是否带有文件路径
	 */
	function GetArticlePath($tid , $aid , $path='')
	{
		if( empty($path) )
		{
			return tpl::Rep(array('tid'=>$tid,'aid'=>$aid) , $this->articleSavePath);
		}
		else
		{
			return WMROOT.$path;
		}
	}
	
	
	/**
	 * 获得文章的内容
	 * @param 参数1，必须，文章分类id
	 * @param 参数2，必须，文章id
	 * @param 参数3，选填，是否带有文件路径
	 */
	function GetTXT($tid,$aid,$path='')
	{
		$txtPath = $this->GetArticlePath($tid, $aid,$path);
		if( file_exists($txtPath) )
		{
			return file_get_contents($txtPath);
		}
		else
		{
			return 'file not found';
		}
	}

	/**
	 * 保存文章到TXT
	 * @param 参数1，必须，文章分类id
	 * @param 参数2，必须，文章id
	 */
	function SaveTXT($fileContent,$tid,$aid,$path='')
	{
		$txtPath = $this->GetArticlePath($tid, $aid,$path);
		if( file::CreateFile($txtPath, $fileContent,'1') )
		{
			return $txtPath;
		}
		else
		{
			return false;
		}
	}
}
?>
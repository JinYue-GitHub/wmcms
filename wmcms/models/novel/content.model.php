<?php
/**
* 小说内容模型
*
* @version        $Id: content.model.php 2019年05月18日 10:56  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class ContentModel
{
	//分类表
	public $chapterTable = '@novel_chapter';
	//内容表
	public $contentTable = '@novel_content';
	
	//构造函数
	public function __construct()
	{
	}
	

	/**
	 * 删除小说的内容数据条数据
	 */
	function Delete($wherePar)
	{
		if( is_array($wherePar) && count($wherePar) == 1 && isset($wherePar['chapter_id']) )
		{
		    if( is_array($wherePar['chapter_id']) )
		    {
			    $where = 'c.chapter_id IN('.$wherePar['chapter_id'][1].')';
		    }
		    else
		    {
			    $where = 'c.chapter_id IN('.$wherePar['chapter_id'].')';
		    }
		}
		else
		{
			if( is_array($wherePar) )
			{
				$nid = $wherePar[1];
			}
			else
			{
				$nid = $wherePar;
			}
			$where = 'c.chapter_nid IN('.$nid.')';
		}
		$sql = 'DELETE cc FROM '.wmsql::CheckTable($this->contentTable).' AS cc INNER JOIN '.wmsql::CheckTable($this->chapterTable).' AS c ON c.chapter_cid=cc.content_id WHERE '.$where;
		return wmsql::exec( $sql );
	}
}
?>
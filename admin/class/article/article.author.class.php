<?php
/**
 * 文章的作者类文件
 *
 * @version        $Id: article.author.class.php 2016年4月24日 14:37  weimeng
 * @package        WMCMS
 * @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
 * @link           http://www.weimengcms.com
 *
 */
class ArticleAuthor
{
	public $table = '@article_author';
	
	/**
	 * 写入文章作者来源数据
	 * @param 参数1，必须，数据类型，a为作者，s为来源
	 * @param 参数2，必须，数据的值
	 */
	function SetData( $type , $name )
	{
		$where['table'] = $this->table;
		$where['where']['author_type'] = $type;
		$where['where']['author_name'] = $name;
		$count = wmsql::GetCount($where);
		
		//存在数据就自增
		if( $count > 0 )
		{
			wmsql::Inc($this->table, 'author_data', $where['where']);	
		}
		//否则就插入新的数据
		else
		{
			wmsql::Insert($this->table, $where['where']);
		}
	}
}
?>
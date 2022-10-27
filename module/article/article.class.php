<?php
/**
* 文章系统类文件
*
* @version        $Id: article.class.php 2015年12月18日 16:00  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2015年12月29日 9:14 weimeng
*
*/
class article
{
	function __construct()
	{
		//调用标签构造函数
		if (class_exists('articlelabel'))
		{
			new articlelabel();
		}
	}


	/**
	* 根据所得到的条件查询数据
	* @param 参数1，字符串，type为列表页数据获取，content为内容页数据获取
	* @param 参数2，传递的sql条件
	* @param 参数3，选填，没有数据的提示字符串
	**/
	static function GetData( $type , $where='' , $errInfo='' )
	{
		$wheresql = self::GetWhere($where);
		
		//type为列表页数据获取
		switch ($type)
		{
			//列表页获取
			case 'type':
				$wheresql['table']['@article_type'] = 't';
				$wheresql['where']['type_status'] = '1';
				$wheresql['field'] = 't.*';
				break;
			
			//content为内容页数据获取
			case 'content':
				$wheresql['table']['@article_article'] = 'a';
				$wheresql['left']['@article_type as t'] = 't.type_id=a.type_id';
				$wheresql['field'] = 'a.*,t.*';
				//不检查文章状态
				if( C('page.article_check_status') !== 0 )
				{
					$wheresql['where']['article_status'] = '1';
				}
				
				//分页处理
				if( GetKey($wheresql,'list') )
				{
					if( C('page.pagetype') == 'article_type' )
					{
						//获得当前列表页的分类
						$typeWhere['table'] = '@article_type as t';
						$typeWhere['field'] = 'type_id';
						if( isset($wheresql['where']['type_pid']) )
						{
							$typeWhere['where']['type_pid'] = $wheresql['where']['type_pid'];
						}
						if( isset($wheresql['where']['t.type_id']) )
						{
							$typeWhere['where']['type_id'] = $wheresql['where']['type_id'];
						}
						$tidArr = wmsql::GetAll($typeWhere);
						$tids = '0';
						if( $tidArr )
						{
							$tids = str::ArrToStr( $tidArr , ',' ,null,null,'type_id');
						}
						
						//获取当前页面的数据总数
						unset($wheresql['where']['type_pid']);
						$countWhere = $wheresql;
						unset($countWhere['left']);
						$countWhere['where']['a.type_id'] = array('in-id',$tids);
						$count = wmsql::GetCount($countWhere);
						//设置列表查询的条件
						$wheresql['where']['a.type_id'] = array('in-id',$tids);
					}
					else
					{
						$count = wmsql::GetCount($wheresql);
					}
					page::Start( C('page.listurl') , $count , $wheresql['limit'] );
				}
				break;
				
			default:
				tpl::ErrInfo( C('system.module.getdata_no' , null , 'lang' ) );
				break;
		}

		$data = wmsql::GetAll($wheresql);

		//如果数组为空并且错误提示不为空则输出错误提示。
		if( $type == 'type' && ( GetKey($where,'t.type_id') == '0' ||  GetKey($where,'t.type_pinyin') == 'all') )
		{
			$data[0] = array(
				'type_name'=>'全部分类',
				'type_cname'=>'全部',
				'type_id'=>'0',
				'type_pid'=>'0',
				'type_topid'=>'0',
				'type_pinyin'=>'all',
				'type_info'=>'',
				'type_title'=>'',
				'type_key'=>'',
				'type_desc'=>'',
			);
		}
		else if( !$data && $errInfo != '' )
		{
			tpl::ErrInfo($errInfo);
		}
		if( $type == 'content' && $data )
		{
			$articleMod = NewModel('article.article');
			//对内容进行还原
			foreach( $data as $k=>$v )
			{
				if( $v['article_save_type'] == '2')
				{
					$data[$k]['article_content'] = $articleMod->GetTXT($v['type_id'],$v['article_id'],$v['article_save_path']);
				}
			}
		}
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
			'tid' =>'t.type_id',
			'type_id' =>'t.type_id',
			'分类排序' =>'type_order',
			'分类顺序' =>'t.type_id',
			'分类倒序' =>'t.type_id desc',
			'父级分类' =>'type_topid',
			'允许投稿' =>'type_add',
			'文章作者id' =>'article_author_id',
				
			'id' =>'article_id',
			'阅读' =>'article_read desc',
			'顶' =>'article_ding desc',
			'踩' =>'article_cai desc',
			'回复' =>'article_replay desc',
			'时间' =>'article_addtime desc',
			'权重顺序' =>'article_weight,article_id desc',
			'权重倒序' =>'article_weight desc,article_id desc',

			'推荐' =>'article_rec',
			'头条' =>'article_head',
			'加粗' =>'article_strong',

			'是' =>'1',
			'否' =>'0',
		);

		return tpl::GetWhere($where,$arr);
	}
	
	/**
	 * url参数匹配
	 * @param 参数1，必填，当前页面参数的类型
	 * @param 参数2，必填，参数的值。
	 * @param 参数3，选填，是否有where条件了
	 */
	static function GetPar( $type , $par , $where = array())
	{
		switch ($type)
		{
			case 'type':
				$parName['id'] = 't.type_id';
				$parName['pinyin'] = 't.type_pinyin';
				break;

			case 'content':
				$parName['id'] = 'article_id';
				$parName['pinyin'] = 'article_id';
				break;
		}
		return CheckPar(  $parName , $par , $where );
	}
	
	
	/**
	 * 获得真实的文章内容链接
	 * @param 参数1，必须，内容数组。
	 */
	static function GetUrl( $v )
	{
		if ( $v['article_url'] != '' )
		{
			$url = $v['article_url'];
		}
		else
		{
			$url = tpl::url( 'article_article' , array('aid'=>$v['article_id'],'tid'=>$v['type_id'],'tpinyin'=>$v['type_pinyin']));
		}
		
		return $url;
	}
	
	
	/**
	 * 获得文章的所领略图
	 * @param 参数1，必须,缩略图的地址
	 */
	static function GetSimg( $path = '' )
	{
		if ( $path == '' )
		{
			$path = C('default_simg',null,'articleConfig');
		}
		
		return $path;
	}
	
	
	/**
	 * 获得文章属性
	 * @param 参数1，必须，标签名字
	 * @param 参数2，必须，当恰属性的值
	 * @param 参数3，必须，模版的字符串
	 */
	static function GetAttr( $label = '是否推荐' , $val='' , $str = '' )
	{
		if( $val == '1' )
		{
			$arr = array($label=>'','/'.$label=>'');
			$str = tpl::Rep( $arr , $str);
		}
		else
		{
			$arr = array('{'.$label.'}[a]{/'.$label.'}'=>'');
			$str = tpl::Rep( $arr , $str , 3);
		}
		
		return $str;
	}
	
	
	/**
	 * 获得文章的样式
	 * @param 参数1，选填，是否加粗
	 * @param 参数2，选填，是否有背景颜色
	 */
	static function GetStyle( $strong = '0' , $color = '#000000' )
	{
		$styles = '';
		$isstyle = false;

		//加粗
		if( $strong == '1' )
		{
			$styles.= 'font-weight:bold;';
			$isstyle = true;
		}
		//颜色
		if( $color <> '#000000' )
		{
			//如果有样式则显示样式
			$styles.='color:'.$color.';';
			$isstyle=true;
		}
		
		if( $isstyle )
		{
			$styles = 'style="'.$styles.'"';
		}
		
		return $styles;
	}
	


	/**
	 * 获得文章状态
	 * @param 参数1，必须，状态码
	 */
	static function GetStatus( $sta )
	{
		return C( 'article.par.status_'.$sta , null , 'lang');
	}
}
?>
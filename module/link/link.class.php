<?php
/**
* 友链系统类文件
*
* @version        $Id: link.class.php 2015年12月18日 16:00  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2016年1月5日 21:03 weimeng
*
*/
class link
{
	/**
	 * 构造函数
	 * @param 参数1，选填，是否自动载入标签类
	 * @param string $labelLoad
	 */
	function __construct( $labelLoad = true )
	{
		if( $labelLoad )
		{
			//调用标签构造函数
			new linklabel();
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
				$wheresql['table']['@link_type'] = 't';
				$wheresql['field'] = 't.*';
				break;
			
			//content为内容页数据获取
			case 'content':
				$wheresql['table']['@link_link'] = 'a';
				$wheresql['left']['@link_type as t'] = "a.type_id = t.type_id";
				$wheresql['where']['link_status'] = "1";
				$wheresql['field'] = 'a.*,t.*';
				
				//分页处理
				if( GetKey($wheresql,'list') )
				{
					page::Start( C('page.listurl') , wmsql::GetCount($wheresql) , $wheresql['limit'] );
				}
				break;
				
			default:
				tpl::ErrInfo( C('system.module.getdata_no' , null , 'lang' ) );
				break;
		}
		
		$data = wmsql::GetAll($wheresql);

		
		//如果数组为空并且错误提示不为空则输出错误提示。
		if( $type == 'type' && ( GetKey($where,'t.type_id') == '0' || GetKey($where,'t.type_pinyin') == 'all') )
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
			'分类' =>'t.type_id',
			'tpinyin' =>'t.type_pinyin',
			'分类排序' =>'type_order',
			'分类顺序' =>'t.type_id',
			'分类倒序' =>'t.type_id desc',
			'分类推荐' =>'t.type_rec',
				
			'排序' =>'a.link_order',
			'固链' =>'a.link_fixed',
			'推荐' =>'a.link_rec',
			'最后点入' =>'a.link_lastintime desc',
			'最后点出' =>'a.lastouttime desc',
			'加入时间' =>'a.link_id desc',
			'日点入' =>'a.link_inday desc',
			'日点出' =>'a.link_outday desc',
			'周点入' =>'a.link_inweek desc',
			'周点出' =>'a.link_outweek desc',
			'月点入' =>'a.link_inmonth desc',
			'月点出' =>'a.link_outmonth desc',
			'总点入' =>'a.link_insum desc',
			'总点出' =>'a.link_outsum desc',
				
			'是' =>'1',
			'否' =>'0',
		);

		return tpl::GetWhere($where,$arr);
	}
	
	
	/**
	 * url参数匹配
	 * @param 参数1，必填，当前页面参数的类型
	 * @param 参数2，必填，参数的值。
	 */
	static function GetPar( $type , $par , $where = array())
	{
		//参数是否为数字的变量。
		switch ($type)
		{
			case 'type':
				$parName['id'] = 't.type_id';
				$parName['pinyin'] = 't.type_pinyin';
				break;

			case 'content':
				$parName['id'] = 'link_id';
				$parName['pinyin'] = 'link_pinyin';
				break;
		}

		return CheckPar(  $parName , $par , $where );
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
}
?>
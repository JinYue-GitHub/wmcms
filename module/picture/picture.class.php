<?php
/**
* 图集系统类文件
*
* @version        $Id: picture.class.php 2015年12月18日 16:00  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2016年1月4日 10:53 weimeng
*
*/
class picture
{
	function __construct()
	{
		//调用标签构造函数
		if (class_exists('picturelabel'))
		{
			new picturelabel();
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
				$wheresql['table']['@picture_type'] = 't';
				$wheresql['field'] = 't.*';
				break;
			
			//content为内容页数据获取
			case 'content':
				$wheresql['table']['@picture_picture'] = 'a';
				$wheresql['left']['@picture_type as t'] = 't.type_id=a.type_id';
				$wheresql['where']['picture_status'] = '1';
				$wheresql['field'] = 'a.*,t.*';
				
				//分页处理
				if( GetKey($wheresql,'list') )
				{
					page::Start( C('page.listurl') , wmsql::GetCount($wheresql) , $wheresql['limit'] );
				}
				break;
				
			//picture为图集内容获取
			case 'picture':
				$wheresql['field'] = 'upload_alt,upload_simg,upload_img,upload_size';
				$wheresql['table'] = '@upload';
				$wheresql['where']['upload_module'] = 'picture';
				$wheresql['where']['upload_cid'] = C('page.pid');
				$wheresql['where']['upload_ext'] = array('in','jpg,gif,png,jpeg,bmp');
				//分页处理
				page::Start( C('page.listurl') , wmsql::GetCount($wheresql) , $wheresql['limit'] );
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
			'分类排序' =>'type_order',
			'分类顺序' =>'t.type_id',
			'分类倒序' =>'t.type_id desc',
			'父级分类' =>'type_topid',
	
			'id' =>'picture_id',
			'阅读' =>'picture_read desc',
			'顶' =>'picture_ding desc',
			'踩' =>'picture_cai desc',
			'回复' =>'picture_replay desc',
			'时间' =>'picture_time desc',
		);

		return tpl::GetWhere($where,$arr);
	}
	
	
	/**
	 * url参数匹配
	 * @param 参数1，必填，当前页面参数的类型
	 * @param 参数2，必填，参数的值。
	 */
	static function GetPar( $type , $par , $where = array() )
	{
		//参数是否为数字的变量。
		switch ($type)
		{
			case 'type':
				$parName['id'] = 't.type_id';
				$parName['pinyin'] = 't.type_pinyin';
				break;

			case 'content':
				$parName['id'] = 'picture_id';
				$parName['pinyin'] = 'picture_pinyin';
				break;
		}

		return CheckPar(  $parName , $par , $where );
	}
	
	


	/**
	 * 获得图集属性
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
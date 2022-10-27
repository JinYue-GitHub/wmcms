<?php
/**
* 应用系统类文件
*
* @version        $Id: app.class.php 2015年12月18日 16:00  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2015年12月18日 16:00 weimeng
*
*/
class app
{
	function __construct()
	{
		//调用标签构造函数
		new applabel();
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
				$wheresql['table']['@app_type'] = 't';
				$wheresql['field'] = 't.*';
				break;
			
			//content为内容页数据获取
			case 'content':
				$wheresql['field'] = 'a.*,t.*';
				$wheresql['field'] .= ',au.firms_name as au_name,pa.firms_name as pa_name';
				$wheresql['table']['@app_app'] = 'a';
				$wheresql['left']['@app_type as t'] = "a.type_id = t.type_id";
				$wheresql['left']['@app_firms as au'] = "a.app_aid = au.firms_id";
				$wheresql['left']['@app_firms as pa'] = "a.app_oid = pa.firms_id";
				$wheresql['where']['app_status'] = "1";
				//分页处理
				if( GetKey($wheresql,'list') )
				{
					page::Start( C('page.listurl') , wmsql::GetCount($wheresql) , $wheresql['limit'] );
				}
				break;
				
			//attr为应用属性获取
			case 'attr':
				$wheresql['table']['@app_attr'] = '';
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
			'tpinyin' =>'t.type_pinyin',
			'分类排序' =>'type_order',
			'分类顺序' =>'t.type_id',
			'分类倒序' =>'t.type_id desc',
	
			'id' =>'app_id',
			'aid' =>'app_id',
			'app_id' =>'app_id',
			'apinyin' =>'app_pinyin',
			'应用排序' =>'app_id',
			'阅读' =>'app_read desc',
			'顶' =>'app_ding desc',
			'踩' =>'app_cai desc',
			'回复' =>'app_replay desc',
			'时间' =>'app_addtime desc',
			'星级' =>'app_start desc',
			'评分' =>'app_score desc',
			'大小' =>'app_size desc',
			'推荐'=>'app_rec',
			'汉化'=>'app_tocn',

			'资料类型'=>'attr_type',
			'资费'=>'c',
			'平台'=>'p',
			'语言'=>'l',
				
			'是'=>'1',
			'否'=>'0',
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
				$parName['id'] = 'app_id';
				$parName['pinyin'] = 'app_pinyin';
				break;
		}

		return CheckPar(  $parName , $par , $where );
	}
	


	/**
	 * 获得应用属性
	 * @param 参数1，必填，布尔值
	 */
	static function GetAttr( $type , $val ){
		if( $val == '1' || $val != '' )
		{
			if( $type == 'tocn' )
			{
				return C('app.attr.'.$type,null,'lang');
			}
			else
			{
				return $val;
			}
		}
		else
		{
			return C('app.attr.no_'.$type,null,'lang');
		}
	}
}
?>
<?php
/**
* 下载系统类文件
*
* @version        $Id: down.label.php 2017年4月28日 10:00  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
class down
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
			new downlabel();
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
				$wheresql['table']['@bbs_type'] = 't';
				$wheresql['field'] = 't.*';
				break;
			
			//content为内容页数据获取
			case 'content':
				$wheresql['field'] = 'a.*,t.*,user_nickname,user_name,user_sex,user_head,user_sign';
				$wheresql['table']['@bbs_bbs'] = 'a';
				$wheresql['left']['@bbs_type as t'] = 't.type_id=a.type_id';
				$wheresql['left']['@user_user as u'] = 'u.user_id=a.user_id';

				if ( self::GetModerator(C('page.data.type_pid')) === false )
				{
					$wheresql['where']['bbs_status'] = '1';
				}
				break;
				
			//topcontent为置顶主题数据获取
			case 'topcontent':
				$wheresql['field'] = 'a.*,t.*,user_nickname,user_name,user_sex,user_head,user_sign';
				$wheresql['table']['@bbs_bbs'] = 'a';
				$wheresql['left']['@bbs_type as t'] = 't.type_id=a.type_id';
				$wheresql['left']['@user_user as u'] = 'u.user_id=a.user_id';
				$wheresql['where']['bbs_top'] = array('string','bbs_top = 1  or (bbs_top=2 and type_topid='.C('page.data.type_topid').') or (bbs_top=3 and a.type_id='.C('page.data.type_id').')');
				
				if ( self::GetModerator(C('page.data.type_pid')) === false )
				{
					$wheresql['where']['bbs_status'] = '1';
				}
				break;

			//帖子内容页数据获取
			case 'bbs':
				$wheresql['field'] = 'a.*,t.*,type_info,user_nickname,user_name,user_sex,user_head,user_sign';
				$wheresql['table']['@bbs_bbs'] = 'a';
				$wheresql['left']['@bbs_type as t'] = 't.type_id=a.type_id';
				$wheresql['left']['@user_user as u'] = 'u.user_id=a.user_id';
				return wmsql::GetOne($wheresql);
				break;
					
			//管理员权限获取
			case 'moderator':
				$wheresql['table']['@bbs_moderator'] = 't';
				break;
				
			default:
				tpl::ErrInfo( C('system.module.getdata_no' , null , 'lang' ) );
				break;
		}

		//分页处理
		if( GetKey($wheresql,'list') )
		{
			page::Start( C('page.listurl') , wmsql::GetCount($wheresql) , $wheresql['limit'] );
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
			'id' =>'bbs_id',
			'type_id' =>'t.type_id',
			'父级分类' =>'type_topid',
			'版块父id' =>'type_topid',
			'版块排序' =>'type_order',
			'版块顺序' =>'t.type_id',
			'版块倒序' =>'t.type_id desc',
			'版块总发帖' =>'type_sum_post desc',
			'版块总回帖' =>'type_sum_replay desc',
			'版块总浏览' =>'type_sum_read desc',
			'版块日发帖' =>'type_today_post desc',
			'版块日回帖' =>'type_today_replay desc',
			'版块日浏览' =>'type_today_read desc',
		);

		return tpl::GetWhere($where,$arr);
	}
}
?>
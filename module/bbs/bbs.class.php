<?php
/**
* 论坛系统类文件
*
* @version        $Id: bbs.class.php 2015年12月18日 16:00  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2016年1月28日 15:23 weimeng
*
*/
class bbs
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
			new bbslabel();
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
				//如果用户id大于0
				if( user::GetUid()>0 )
				{
				    $wheresql['where']['a.user_id'] = array('string','1=1 or (a.user_id='.user::GetUid().' and bbs_status=0)');
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

			'推荐' =>'bbs_rec',
			'精华' =>'bbs_es',
			'置顶' =>'bbs_top',
			'全站置顶' =>'1',
			'分类置顶' =>'2',
			'当前置顶' =>'3',
			'全部置顶' =>'[>->0]',
			'发帖时间' =>'bbs_time desc',
			'最后回帖' =>'bbs_replay_time desc',
			'回帖时间' =>'bbs_replay_time desc',
			'浏览量' =>'bbs_read desc',
			'回帖量' =>'bbs_replay desc',
			'是否有图' =>'bbs_simg',
			'有图片' =>'[null->is not]',
			'无图片' =>'[null->is]',
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
	static function GetPar( $type , $par , $where = array() )
	{
		//参数是否为数字的变量。
		switch ($type)
		{
			case 'type':
				$parName['id'] = 't.type_id';
				break;

			case 'content':
				$parName['id'] = 'bbs_id';
				break;
		}

		return CheckPar(  $parName , $par , $where );
	}
	
	/**
	 * 获得帖子属性
	 * @param 参数1，必须，标签名字
	 * @param 参数2，必须，当恰属性的值
	 * @param 参数3，必须，模版的字符串
	 * @param 参数4，必须，预设的值
	 */
	static function GetAttr( $label = '推荐' , $val='' , $str = '' , $dVal = '')
	{
		//置顶状态标签
		if( ($label == '置顶' || $label == '置顶状态' ) && $val > 0)
		{
			switch ($val)
			{
				case '1':
					$top = C('bbs.top_1',null,'lang');
					break;
				case '2':
					$top = C('bbs.top_2',null,'lang');
					break;
				case '3':
					$top = C('bbs.top_3',null,'lang');
					break;
			}
			$arr = array($label=>'','/'.$label=>'','置顶类型'=>$top);
			$str = tpl::Rep( $arr , $str);
			return $str;
		}
		//帖子状态标签
		else if( $label == '帖子状态' || $label == '状态' )
		{
			switch ($val)
			{
				case '0':
					$top = C('bbs.status_0',null,'lang');
					break;
				case '1':
					$top = C('bbs.status_1',null,'lang');
					break;
			}
			if ( user::GetUid() == 0 )
			{
				$arr = array('{'.$label.'}[a]{/'.$label.'}'=>'');
				$str = tpl::Rep( $arr , $str , 3);
			}
			else
			{
				$arr = array($label=>'','/'.$label=>'','状态'=>$top);
				$str = tpl::Rep( $arr , $str);
			}
			return $str;
		}
		//帖子是否有图
		else if( $label == '有图')
		{
			if( $val == '' )
			{
				$arr = array('{'.$label.'}[a]{/'.$label.'}'=>'');
				$str = tpl::Rep( $arr , $str , 3);
			}
			else
			{
				$arr = array($label=>'','/'.$label=>'');
				$str = tpl::Rep( $arr , $str);
			}
			return $str;
		}
		//其他标签
		else
		{
			if( ($val == '1' && $dVal == '' ) || ( $dVal != '' && $val == $dVal))
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
	
	
	/**
	 * 获得版主信息
	 * @param 参数1，必须，版块的pid
	 */
	static function GetModerator( $topid )
	{
		$uid = user::GetUid();
		if ( $uid > 0 )
		{
			$where['table'] = '@bbs_moderator';
			$where['where']['user_id'] = $uid;
			$where['where']['type_id'] = array( 'in' , $topid );
			$data = WMSql::GetCount($where,'moderator_id');

			if ( $data == 0 )
			{
				return false;
			}
			else
			{
				return true;
			}
		}
		else
		{
			return false;
		}
	}
	
	
	/**
	 * 发帖获得选中的值
	 * @param 参数1，必须，属性值
	 */
	static function PostCheck( $val )
	{
		switch ( $val )
		{
			case '1':
				return 'checked';
			default:
				return '';
		}
	}
}
?>
<?php
/**
* 链接/友链点击处理
*
* @version        $Id: index.php 2015年8月9日 21:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2016年1月6日 16:30 weimeng
*
*/
//客服端信息获取
$ClassArr = array('client');
//引入模块公共文件
require_once 'link.common.php';

$lid = Get('lid/i');
$t = Get('t');
$url = Get('url');
//如果是普通出站,无需http
if( $t == 'out' && $url != '' )
{
	if ( $linkConfig['click_type'] == 0 )
	{
		header("Location: ".$url);
		die();
	}
	//链接跳转页面
	else
	{
		$data['url'] = $url;
		C('page' ,  array(
			'pagetype'=>'index' ,'data'=>$data ,'dtemp'=>'link/jump.html',
			'label'=>'linklabel','label_fun'=>'JumpLabel',
		));
		//设置seo信息
		tpl::GetSeo();
		//创建模版并且输出
		$tpl=new tpl();
		$tpl->display();
	}
}
//友链点击操作
else
{
	$str = str::Int( $lid , $lang['link']['par']['lid_err']);

	if( $t !='out' && $t!='in')
	{
		tpl::ErrInfo( $lang['link']['par']['type_err'] );
	}

	//查询友链数据
	$wheresql['table'] = '@link_link as l';
	$wheresql['where']['link_id'] = $lid;
	$wheresql['left']['@link_type as t'] = 't.type_id = l.type_id';
	$data = wmsql::GetOne($wheresql);

	if ( !$data )
	{
		tpl::ErrInfo( $lang['link']['par']['link_no'] );
	}
	else
	{
		$u = new client();
		//获得浏览器信息
		$userAgent = $u->Get_Useragent();
		
		//是否在审核中
		str::EQ( $data['link_status'], 0 , $lang['link']['par']['status_0'] );
		
		//来源和域名不一样，或者url为空直接跳转首页
		if ( ( GetDomain(GetKey($_SERVER,'HTTP_REFERER')) != GetDomain($data['link_url']) && $t=='in' ) || $data['link_url'] == '' )
		{
			header("Location: ".DOMAIN);
			exit;
		}
		else
		{
			//防刷时间检查
			if( time() - $data['link_last'.$t.'time'] > $linkConfig['ref_time'] )
			{
				//关闭ua检查或者开启ua检查并且为手机的ua就执行
				if( $linkConfig['check_ua'] == '0' || ( $linkConfig['check_ua'] == '1'  && ( $userAgent[4] == 'android' || $userAgent[4] == 'iphone' || $userAgent[4] == 'ipad')) )
				{
					$count = 0;
					//是否开启单个ip一天一次的检查
					if( $linkConfig['check_oneip'] == '1' )
					{
						$wheresql = array();
						$wheresql['table'] = '@link_click';
						$wheresql['where']['click_ip'] = $u->Get_Ip_Addr();
						$wheresql['where']['click_time'] = array('>',strtotime('today'));
						$count = wmsql::GetCount($wheresql);
					}
					//修改当前点击
					if( $count == 0 )
					{
						$nowTime = time();
						$arr['link_last'.$t.'time'] = $nowTime;
						$arr['link_'.$t.'day'] = 1;
						$arr['link_'.$t.'week'] = 1;
						$arr['link_'.$t.'month'] = 1;
						$arr['link_'.$t.'year'] = 1;
						//不是今年的数据
						if( date('Y',$nowTime) != date('Y', $data['link_last'.$t.'time']) )
						{
							$arr['link_'.$t.'sum'] = array('+',1);
						}
						//不是本月数据
						else if( date('m',$nowTime) != date('m', $data['link_last'.$t.'time']) )
						{
							$arr['link_'.$t.'year'] = array('+',1);
							$arr['link_'.$t.'sum'] = array('+',1);
						}
						//不是本周数据
						else if( date('W',$nowTime) != date('W', $data['link_last'.$t.'time']) )
						{
							$arr['link_'.$t.'month'] = array('+',1);
							$arr['link_'.$t.'year'] = array('+',1);
							$arr['link_'.$t.'sum'] = array('+',1);
						}
						//不是本日数据
						else if( date('d',$nowTime) != date('d', $data['link_last'.$t.'time']) )
						{
							$arr['link_'.$t.'week'] = array('+',1);
							$arr['link_'.$t.'month'] = array('+',1);
							$arr['link_'.$t.'year'] = array('+',1);
							$arr['link_'.$t.'sum'] = array('+',1);
						}
						//否则全部+1
						else
						{
							$arr['link_'.$t.'day'] = array('+',1);
							$arr['link_'.$t.'week'] = array('+',1);
							$arr['link_'.$t.'month'] = array('+',1);
							$arr['link_'.$t.'year'] = array('+',1);
							$arr['link_'.$t.'sum'] = array('+',1);
						}
						
						//进行修改数据
						wmsql::Update( '@link_link' , $arr , 'link_id='.$lid);
					}
				}
			}
		}


		//是否开启点击记录
		if( $linkConfig['click_log'] == '1' )
		{
			//ip统计
			if( $linkConfig['getip_open'] =='1' )
			{
				$ip = $u->Get_Ip_Addr();
			}
			else
			{
				$ip = $lang['link']['par']['ip_close'];
			}
			//地理位置统计
			if( $linkConfig['getadress_open'] == '1' )
			{
				$ipArr = $u->get_onlineip();
				if( $ipArr['data']['region'] == $ipArr['data']['city'] )
				{
					$address = $ipArr['data']['country'].$ipArr['data']['region'];
				}
				else
				{
					$address = $ipArr['data']['country'].$ipArr['data']['region'].$ipArr['data']['city'];
				}
			}
			else
			{
				$address = $lang['link']['par']['address_close'];
			}
			//插入点击记录
			$clickData = array();
			$clickData['click_type'] = $t;
			$clickData['click_lid'] = $lid;
			$clickData['click_ua'] = $_SERVER['HTTP_USER_AGENT'];
			$clickData['click_ip'] = $ip;
			$clickData['click_adress'] = $address;
			$clickData['click_browser'] = $userAgent['1'];
			$clickData['click_browser_ver'] = $userAgent['2'];
			$clickData['click_system'] = $userAgent['3'];
			$clickData['click_system_ver'] = $userAgent['5'];
			$clickData['click_time'] = time();
			wmsql::Insert( '@link_click' , $clickData);
		}
		
		//开始执行跳转
		if( $t == 'out' )
		{
			header("Location: ".$data['link_url']);
		}
		else if( $t == 'in' )
		{
			//友链点入跳转
			if( $data['link_in_jump'] != '' )
			{
				$url = $data['link_in_jump'];
			}
			//友链分类点入跳转设置
			else if( $data['type_in_jump'] != '' )
			{
				$url = $data['type_in_jump'];
			}
			//友链总设置，如果点入跳转不为空
			else if( $linkConfig['in_jump'] != '' )
			{
				$url = $linkConfig['in_jump'];
			}
			//否则就是首页
			else
			{
				$url = DOMAIN;
			}
			
			header("Location: ".$url);
		}
		exit;
	}
}
?>
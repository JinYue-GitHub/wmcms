<?php
/**
* 友链ajax请求处理
*
* @version        $Id: link.php 2015年8月15日 10:37  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime		  2016年1月6日 14:28 weimeng
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

//是否开启申请
str::EQ( $linkConfig['join'] , 0 , $lang['link']['join_close'] , $ajax );

$name = str::IsEmpty( Post('name') , $lang['link']['name_no'] );
$cname = Post('cname');
$tid = str::Int( Post('tid') , $lang['link']['tid_no']);
$url = str::CheckUrl( Post('url') , $lang['link']['url_err'] );
$qq = Post('qq');
$info = Post('info');


//new数据模型
$linkMod = NewModel('link.link');

//判断name是否存在
$linkMod->where['link_name'] = $name;
$count = $linkMod->CheckData();
//如果大于0就是存在
str::RT( $count , 0 , $lang['link']['name_exist'] , $ajax );


//设置数据
$linkMod->data['type_id'] = $tid;
$linkMod->data['link_status'] = $linkConfig['join_status'];
$linkMod->data['link_name'] = $name;
$linkMod->data['link_cname'] = $cname;
$linkMod->data['link_qq'] = $qq;
$linkMod->data['link_url'] = $url;
$linkMod->data['link_info'] = $info;
$linkMod->data['link_jointime'] = time();
//插入数据
$result = $linkMod->Insert();


//加入失败
if( !$result )
{
	ReturnData( $lang['link']['operate']['join']['fail'] , $ajax);
}
//加入成功
else
{
	//判断后台设置的友链加入的默认状态
	switch ( $linkConfig['join_status'] )
	{
		//状态等于0就提示等待审核
		case "0":
			$str = $lang['link']['status_0'];
			break;
	
		//状态等于1就提示审核通过
		default:
			$str = $lang['link']['status_1'];
			break;
	}
	
	
	$url = DOMAIN.tpl::Url( 'link_link' , array('lid'=>$result,'t'=>'in') );
	
	//提示数组
	$infoArr['info'] = $str;
	$infoArr['gourl'] = tpl::Url( 'link_index' );
	$infoArr['html'] = tpl::Rep(array('url'=>$url,'index'=>tpl::Url( 'link_index' ))  , $lang['link']['operate']['join']['html']);
	
	//设置返回的数组
	$data['url'] = $url;
	
	ReturnData( $infoArr , $ajax , 200 , $data);
}	

?>
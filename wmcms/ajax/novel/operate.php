<?php
/**
* 获得互动操作的模版
*
* @version        $Id: operate.php 2016年9月15日 21:00  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

$nid = str::Int(Request('nid' , '0'));

//设置页面信息
C('page',array('pagetype'=>'index','dtemp'=>$file.'.html','tab'=>Request('tab')));
tpl::GetSeo();

//new一个模版类，然后输出
$tpl=new tpl();

//道具单功能模块
IncModule('props');
//推荐票数据
$ticketMod = NewModel('user.ticket');
$ticketData = $ticketMod->GetTicket(user::GetUid() , 'novel');
//替换标签
$arr = array(
	'nid'=>$nid,
	'月票'=>$ticketData['ticket_month'],
	'推荐票'=>$ticketData['ticket_rec'],
);
tpl::Rep($arr);


$tpl->display();
?>
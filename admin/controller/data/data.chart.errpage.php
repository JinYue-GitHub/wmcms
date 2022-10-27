<?php
/**
* 错误页面记录控制器
*
* @version        $Id: data.chart.errpage.php 2017年6月8日 23:25  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$dataSer = AdminNewClass('data.chart');

//统计的时间类型
$timeArr = $dataSer->GetTime('month');

//指数数据
$data = $dataSer->GetCount( 'seo_errpage' , array('errpage_code') , 'errpage_time' );
?>
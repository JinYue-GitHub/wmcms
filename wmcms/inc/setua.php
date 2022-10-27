<?php
/**
* 设置模版版本
*
* @version        $Id: setua.php 2015年8月11日 21:36  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
require 'common.inc.php';

//如果有绑定的域名则跳转到域名
$pt = Request('pt');
$local = WEB_URL;

if( $pt != '' )
{
	for($i=1;$i<=4;$i++)
	{
		if( C('config.web.tpmark'.$i) == $pt && C('config.web.domain'.$i) <> '')
		{
			$local = C('config.web.domain'.$i);
		}
	}
}

header("Location: ".TCP_TYPE."://".$local); 
exit;
?>
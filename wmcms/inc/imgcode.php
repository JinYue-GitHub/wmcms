<?php
$C['module']['inc']['class']=array('img','str','file');
require_once 'common.inc.php';
$base64 = 0;
if( Request('session_id') != '' )
{
	SetSessionId(Request('session_id'));
}
if( Request('base64') == '1' )
{
	$base64 = 1;
}
$imgCode = Img::ImgCode(4,20,0,0,$base64);
echo $imgCode;
die();
?>
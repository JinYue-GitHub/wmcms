<?php
header('Content-Type:text/html;charset=utf-8'); 
if (version_compare("5.3", PHP_VERSION, ">")) {
	die("PHP >= 5.3 or greater is required!!!");
}
//定义系统常量
define('WMMANAGER', false);
date_default_timezone_set('PRC');
$wmroot = __dir__.'/../wmcms/';
define('WMINC', $wmroot.'inc/' );
define('WMCONFIG',$wmroot.'config/');
require_once WMCONFIG.'define.config.php';
require_once WMCONFIG.'api.config.php';
require_once WMCONFIG.'web.config.php';
require_once WMINC.'function.php';
require_once WMCLASS.'file.class.php';
require_once WMCLASS.'str.class.php';
require_once 'module.php';
//网站当前的访问http类型
define('HTTP_TYPE',GetHttpType());
define('TCP_TYPE',GetHttpType());


$title = $step = '';
$a = Post('action');
//是否安装过了 
if(file_exists(WMCONFIG.'install.lock.txt') && $a !='step5')
{
	$file = 'lock.php';
}
else if( str_replace('/','',str_replace('index.php','',$_SERVER['PHP_SELF'])) != 'install' )
{
	$file = 'second_dir.php';
}
else if(!file_exists('sql.sql') )
{
	$file = 'sql.php';
}
/*
else if($_SERVER['HTTP_HOST'] == '127.0.0.1' || $_SERVER['HTTP_HOST']=='localhost')
{
	echo '请使用域名访问安装';
	exit;
}*/
else
{
	//执行安装步骤
	switch ($a)
	{
		case '':
			$file = 'step1.php';
			$title = 'Step 1 阅读安装协议';
			$step = 'step2';
			break;

		case 'step2':
			$file = 'step2.php';
			$title = 'Step 2 运行环境检查';
			$step = 'step3';
			break;
				
		case 'step3':
			$file = 'step3.php';
			$title = 'Step 3 填写数据库信息与创始人信息';
			$step = 'step4';
			break;
				
		case 'step4':
			$file = 'step4.php';
			$title = 'Step 4 阅读安装协议';
			$step = 'step5';
			break;
				
		case 'step5':
			$file = 'step5.php';
			$title = 'Step 5 完成操作';
			break;
	}
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8" />
<title>wmcms 安装程序 <?php echo $title?></title>
<link rel="stylesheet" href="Content/install.css" />
<!--[if IE]>
<script src="Scripts/html5.js" type="text/javascript"></script>
<![endif]-->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
	<div class="wrap">
	<header class="header">
		<div class="head">
			<h1 class="logo_install">安装</h1>
			<div class="version">版本:v<?php echo WMVER;?>  UTF-8 (<?php echo WMVER_TIME?>)</div>
			
		</div>
	</header>
	<section class="section">
		<?php require $file;?>
	</section>
	</div>
	<footer class="footer">
		&copy; 2014-2018 <a href="<?php echo WMURL;?>">wmcms</a>（wmcms团队版权所有）
	</footer>
</body>
</html>

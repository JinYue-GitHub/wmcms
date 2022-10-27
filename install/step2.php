<?php if(!defined('WMINC'))die();?>
<?php
//初始化下一步
$next=true;
$os=PHP_OS;
$phpver=floatval(PHP_VERSION);
if($phpver>=5.3){
	if($phpver>=5.4 && $phpver<=7.2){
		$phps='correct';
	}else{
		$phps='error';
	}
}else{
	$next=false;
	$phps='error';
}
if( class_exists('PDO') ){
	$pdover='支持';
	$pdos='correct';
}else{
	$next=false;
	$pdover = '不支持';
	$pdos='error';
}

function checkfolder($dirname){
	Global $next;
	$fd=@opendir($dirname);
	if($fd===false){
		echo '<span class="error_span">×</span>不可读,不可写';
		$next=false;
	}else{
		if(is_writable($dirname)){
			echo '<span class="correct_span">&radic;</span>可写';
		}else{
			echo '<span class="error_span">×</span>可读,不可写';
			$next=false;
		}
	}
}
//gd库
if(function_exists('gd_info')){
	$gd='<span class="correct_span">&radic;</span>可用';
}else{
	$gd='<span class="error_span">×</span>不可用';
}
//curl库
if(function_exists('curl_init')){
	$curl='<span class="correct_span">&radic;</span>可用';
}else{
	$curl='<span class="error_span">×</span>不可用';
}

//openssl库
if(get_extension_funcs('openssl')){
	$openssl='<span class="correct_span">&radic;</span>可用';
}else{
	$openssl='<span class="error_span">×</span>不可用';
}
//sockets库
if(get_extension_funcs('sockets')){
	$sockets='<span class="correct_span">&radic;</span>可用';
}else{
	$sockets='<span class="error_span">×</span>不可用';
}
//short_open_tag建议关闭
if(get_extension_funcs('short_open_tag')){
	$opentag ='<span class="error_span">&radic;</span>可用';
}else{
	$opentag ='<span class="correct_span">×</span>不可用';
}

//ZIP库
if(class_exists('ZipArchive')){
	$zips='<span class="correct_span">&radic;</span>可用';
}else{
	$zips='<span class="error_span">×</span>不可用';
}
//root方法
if( @$_SERVER['DOCUMENT_ROOT'] != '' ){
	$root='<span class="correct_span">&radic;</span>可用';
}else{
	$root='<span class="error_span">×</span>不可用';
}
?>
<div class="step">
	<ul>
	<li class="current"><em>1</em>检测环境</li>
	<li><em>2</em>创建数据</li>
	<li><em>3</em>完成安装</li>
	</ul>
</div>

<div class="server">
	<table width="100%">
	<tr>
		<td class="td1">环境检测</td>
		<td class="td1" width="30%">当前服务器</td>
		<td class="td1" width="22%">推荐配置</td>
		<td class="td1" width="22%">最低要求</td>
	</tr>
	<?php
	if($_SERVER['HTTP_HOST'] == '127.0.0.1' || $_SERVER['HTTP_HOST']=='localhost'){
		echo '<tr><td class="td1" style="color:red" colspan="4">安装测试可以使用本机IP，正式环境下请使用域名安装，否则无法使用云服务、在线升级等功能。</td></tr>';
	}
	?>
	<tr><td class="td1" style="color:red" colspan="4">强烈建议使用真实域名访问安装，否则无法正常进行在线更新、下载模版和应用等操作</td></tr>
	<tr>
		<td>操作系统</td>
		<td><span class="correct_span">&radic;</span><?php echo $os;?></td>
		<td>类UNIX</td>
		<td>不限制</td>
	</tr>
	<tr>
		<td>PHP版本</td>
		<td><span class="<?php echo $phps;?>_span">&radic;</span><?php echo $phpver;?></td>
		<td><= 7.2</td>
		<td>5.3</td>
	</tr>
	<tr>
		<td>PDO组件</td>
		<td><span class="<?php echo $pdos;?>_span">&radic;</span><?php echo $pdover;?></td>
		<td>必须支持</td>
		<td>必须支持</td>
	</tr>
	<tr>
		<td>MYSQL</td>
		<td><span class="error_span">&radic;</span>推荐使用[不检测本机]</td>
		<td><= 8.2</td>
		<td>5.2</td>
	</tr>
	</table>

	<table width="100%">
	<tr>
		<td class="td1">常用函数库检测</td>
		<td class="td1" width="20%">当前状态</td>
		<td class="td1" width="35%">建议</td>
	</tr>
	<tr>
		<td>short_open_tag</td>
		<td><?=$opentag?></td>
		<td>建议关闭，落后影响性能的tag表现</td>
	</tr>
	<tr>
		<td>DOCUMENT_ROOT</td>
		<td><?=$root?></td>
		<td>建议启用，精准判断站点路径</td>
	</tr>
	<tr>
		<td>OpenSSL组件</td>
		<td><?=$openssl?></td>
		<td>建议启用，发送邮件、支付等必备</td>
	</tr>
	<tr>
		<td>Sockets组件</td>
		<td><?=$sockets?></td>
		<td>建议启用，发送邮件、支付必备</td>
	</tr>
	<tr>
		<td>GD图片处理库</td>
		<td><?=$gd?></td>
		<td>建议启用，图片处理功能库</td>
	</tr>
	<tr>
		<td>curl地址库</td>
		<td><?=$gd?></td>
		<td>建议启用，采集功能必备库</td>
	</tr>
	<tr>
		<td>ZIP压缩文件库</td>
		<td><?=$zips?></td>
		<td>建议启用，在线升级必须</td>
	</tr>
	</table>
	
	
	<table width="100%">
	<tr>
		<td class="td1">目录、文件权限检查</td>
		<td class="td1" width="20%">当前状态</td>
		<td class="td1" width="35%">所需状态</td>
	</tr>
	<tr>
		<td colspan="3" style="color:red">请给本程序安装文件夹设置可读可写(Windows)或者0777(Linux)权限</td>
	</tr>
	<tr>
		<td>/wmcms/config/</td>
		<td><?php checkfolder('../wmcms/config/')?></td>
		<td><span class="correct_span">&radic;</span>可读可写</td>
	</tr>
	<tr>
		<td>/install/</td>
		<td><?php checkfolder('../install/')?></td>
		<td><span class="correct_span">&radic;</span>可读可写</td>
	</tr>
	<tr>
		<td>/cache/</td>
		<td><?php checkfolder('../cache/')?></td>
		<td><span class="correct_span">&radic;</span>可读可写</td>
	</tr>
	<tr>
		<td>/files/</td>
		<td><?php checkfolder('../files/')?></td>
		<td><span class="correct_span">&radic;</span>可读可写</td>
	</tr>
	<tr>
		<td>/upload/</td>
		<td><?php checkfolder('../upload/')?></td>
		<td><span class="correct_span">&radic;</span>可读可写</td>
	</tr>
	<tr>
		<td>/module/</td>
		<td><?php checkfolder('../module/')?></td>
		<td><span class="correct_span">&radic;</span>可读可写</td>
	</tr>
	<tr>
		<td>/templates/</td>
		<td><?php checkfolder('../templates/')?></td>
		<td><span class="correct_span">&radic;</span>可读可写</td>
	</tr>
	</table>
</div>


<div class="bottom tac">
	<form action="index.php" id="form" method="post">
		<input type="hidden" name="action" value="step3">
	</form>
	<a href="javascript:;" onclick="document.getElementById('form').action.value='step2';document.getElementById('form').submit();return false;" class="btn">重新检测</a>
	<?php
	if(!$next){
		echo '<a href="javascript:;" class="btn_old">请检查</a>';
	}else{
		echo '<a href="javascript:;" onclick="document.getElementById(\'form\').submit();return false;" class="btn">下一步</a>';
	}
	?>
</div>
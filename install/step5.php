<?php if(!defined('WMINC'))die();?>
<?php
//安装文件改名
rename('index.php',"index".md5(rand(0,99999).time()).".php");
//rename(WMROOT.'/install',WMROOT.'/install'.md5(rand(0,99999).time()));
?>
<div class="main cc">
	<div class="success_tip">
		<a href="/index.php" class="f16 b">安装完成，点击进入</a>
		<p>安装完成后请删除install文件夹。<span style="color:red">并且后台生成一次api配置接口。</span><br/>浏览器会自动跳转，无需人工干预</p>
	</div>
</div>
<script type="text/javascript">
setTimeout(function(){window.location.href="/index.php"}, 3000);
</script>
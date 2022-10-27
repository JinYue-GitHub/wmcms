<?php if(!defined('WMINC'))die();?>
<div class="main cc">
<textarea class="pact" readonly="readonly" style="resize:none"><?php echo @file_get_contents('listen.txt');?></textarea>
</div>

<div class="bottom tac">
	<form action="index.php" id="form" method="post">
		<input type="hidden" name="action" value="step2">
	</form>

	<a href="javascript:;" onClick="document.getElementById('form').submit();return false;" class="btn">接 受</a>
</div>
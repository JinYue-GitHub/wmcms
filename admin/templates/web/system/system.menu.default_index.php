<div class="tableContent">
	<div id="<?php echo $cFun;?>" class="hide">
		<form action="index.php?a=yes&c=system.menu.menu&t=default_index" data-reload="false" data-toggle="validate" method="post">
			<div style="text-align:center;">
			    <div class="pt-20">
					控制器名：<input type="text" name="index" value="<?php echo $defaultIndex;?>">
				</div>
			    <div class="pt-20">
					注意：设置时请确保当前账户拥有该控制器的权限。
				</div>
			    <div class="pt-20">
					<button type="submit" class="btn-green" data-icon="location-arrow">确定</button>
					<button type="button" class="btn-close" data-icon="close">取消</button>
				</div>
			</div>
		</form>
	</div>
</div>

<script type="text/javascript">
var ajaxOptions=new Array();
ajaxOptions['target'] = "#<?php echo $cFun;?>";
ajaxOptions['title'] = "设置默认首页";
ajaxOptions['width'] = "300";
ajaxOptions['height'] = "180";
ajaxOptions['loadingmask'] = "true";
ajaxOptions['mask'] = "true";
$(document).dialog(ajaxOptions);

$(document).on('bjui.beforeCloseDialog', function(e) {
    $(this).navtab("closeCurrentTab");	// 刷新Tab页面
})
</script>

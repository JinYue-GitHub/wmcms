<style>
.<?php echo $cFun?>Table{margin-bottom:20px}
</style>
<div class="bjui-pageContent">
	<form name="form1" action="index.php?a=yes&c=system.cache&t=config" method="post" data-toggle="validate">
	
    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
      <thead><tr><th colspan="6" style="text-align:left;"><b>清除缓存</b></th></tr></thead>
      <tr>
        <td>技巧说明：</td>
        <td style="line-height: 23px;font-size: 14px;">当站点进行了数据恢复、升级或者工作出现异常的时候，您可以使用本功能清除缓存。<br/>
清除缓存会让系统压力升高，请尽量在低峰时段进行。<br/>
页面缓存：更新系统的页面缓存机制留下的缓存文件。<br/>
区块缓存：更新系统的区块缓存机制留下的缓存文件。<br/>
SQL缓存：更新系统的SQL缓存机制留下的缓存文件。<br/>
日志文件：系统支付日志、微信请求、系统运行信息等留下的日志文件。
        </td>
      </tr>
      <tr>
        <td width="200">缓存选择：</td>
        <td colspan="6">
        	<input type="checkbox" name="page" value="1" data-toggle="icheck" data-label="页面缓存">
        	<input type="checkbox" name="block" value="1" data-toggle="icheck" data-label="区块缓存">
        	<input type="checkbox" name="sql" value="1" data-toggle="icheck" data-label="SQL缓存">
        	<input type="checkbox" name="log" value="1" data-toggle="icheck" data-label="日志文件">
        </td>
      </tr>
      <tr>
        <td width="200">清除操作：</td>
        <td colspan="6">
        	<button type="button" id="<?php echo $cFun;?>sub" class="btn btn-green" data-icon="refresh"><i class="fa fa-refresh"></i> 确认清除</button>
        	<button type="button" id="<?php echo $cFun;?>close" class="btn btn-orange" data-icon="undo"><i class="fa fa-refresh"></i> 取消操作</button>
        </td>
      </tr>
    </table>

  </form>
</div>

<script>
$(document).ready(function(){
	$('#<?php echo $cFun;?>close').click(function(){
		$(this).navtab("closeTab",'cache-clear');
	});

	$('#<?php echo $cFun;?>sub').click(function(){
		var page = $('[name="page"]').is(':checked') ? page = 1 : page = 0;
		var block = $('[name="block"]').is(':checked') ? block = 1 : block = 0;
		var sql = $('[name="sql"]').is(':checked') ? sql = 1 : sql = 0;
		var log = $('[name="log"]').is(':checked') ? log = 1 : log = 0;
		if( !page && !block && !sql && !log){
			$(this).alertmsg('error', '对不起，至少请选择一种缓存机制!');
		}else{
			var op=new Array();
			var data=new Object();
			data.page = page;
			data.block = block;
			data.sql = sql;
			data.log = log;
			op['type'] = 'POST';
			op['data'] = data;
			op['url'] = "index.php?a=yes&c=system.cache&t=clear";
			$(this).bjuiajax('doAjax', op);
		}
	});
});
</script>

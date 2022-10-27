<style>
.<?php echo $cFun?>Table{margin-bottom:20px}
</style>
<div class="bjui-pageContent">
	<form name="form1" action="index.php?a=yes&c=system.config.config&t=update" method="post" data-toggle="validate">
	
    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
      <thead><tr><th colspan="2" style="text-align:left;"><b>更新网站配置</b></th></tr></thead>
      <tr>
        <td>说明：</td>
        <td style="line-height: 23px;font-size: 14px;">首次安装使用或者网站配置修改后无效请手动更新网站配置</td>
      </tr>
      <tr>
        <td width="150">清除操作：</td>
        <td>
        	<button type="submit" class="btn btn-green" data-icon="refresh"><i class="fa fa-refresh"></i> 更新网站配置</button>
        </td>
      </tr>
    </table>

  </form>
</div>

<style>
.barline{float:left;margin-right:5px;width:200px;border:1px solid #4DBF7D;text-align:center;height:16px;font-size:8px;border-radius:8px;}
.barline_text_box{line-height:16px;background:#4DBF7D;border-radius:8px;width:0px;color: #3045E6;}
.barline_perc{line-height:16px;}
.barline_msg{margin-top: 11px;}
.<?php echo $cFun;?>sptype_info{display:none}
</style>
<div class="bjui-pageContent">
	<form name="form1" action="index.php?a=yes&c=system.site.config&t=edit" method="post" data-toggle="validate">
    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun;?>Table">
      <thead><tr><th colspan="4" style="text-align:left;">
      <b style="color:red;font-size:14px;">站群配置【在编辑了站内(站外)站点后请到此点击保存，更新一下配置信息才能生效。】</b>
      </th></tr></thead>

      <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun;?>Table">
		<thead>
		      <tr>
		        <td width="150">使用站群状态</td>
		        <td>
					<?php echo $manager->GetForm('site' , 'site_open');?>
		         </td>
		      </tr>
		</thead>
	</table>
	</form>
</div>
     

<div class="bjui-pageFooter">
    <ul>
        <li><button type="button" class="btn-close" data-icon="close">取消</button></li>
        <li><button type="submit" class="btn-green" data-icon="save">保存</button></li>
    </ul>
</div>


<script>
$(document).ready(function(){
	//站群模式选择
	$("#site_type").change(function(){
		if( $(this).val() == 2){
			$(this).alertmsg('error', '对不起，数据独立站群模式暂未开通!');
			return false;
		}
	});
});
</script>
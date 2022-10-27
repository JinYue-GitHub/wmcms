<style>
.<?php echo $cFun?>Table{margin-bottom:20px}
</style>
	
<div class="bjui-pageContent">
	<form name="form1" action="index.php?a=yes&c=link.config&t=edit" method="post" data-toggle="validate">
    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
      <thead><tr><th colspan="4" style="text-align:left;"><b>模块基本设置</b></th></tr></thead>
      <tr>
        <td width="200">友链页检查参数：</td>
        <td><?php echo $manager->GetForm('link' , 'par_link');?></td>
        <td>开启友链申请：</td>
        <td><?php echo $manager->GetForm('link' , 'join');?></td>
      </tr>
      <tr>
        <td>公共链接出站模式：</td>
        <td><?php echo $manager->GetForm('link' , 'link_url_outtype');?></td>
        <td>友链点击模式：</td>
        <td><?php echo $manager->GetForm('link' , 'click_type');?></td>
      </tr>
      <tr>
        <td>UA检查：</td>
        <td colspan="3"><?php echo $manager->GetForm('link' , 'check_ua');?></td>
      </tr>
      <tr>
        <td>同IP每日只记录一次：</td>
        <td><?php echo $manager->GetForm('link' , 'check_oneip');?></td>
        <td>点击日志记录：</td>
        <td><?php echo $manager->GetForm('link' , 'click_log');?></td>
      </tr>
      <tr>
        <td>IP统计：</td>
        <td><?php echo $manager->GetForm('link' , 'getip_open');?></td>
        <td>位置统计：</td>
        <td><?php echo $manager->GetForm('link' , 'getadress_open');?></td>
      </tr>
      <tr>
        <td>前台申请默认状态：</td>
        <td><?php echo $manager->GetForm('link' , 'join_status');?></td>
        <td>后台添加默认状态：</td>
        <td><?php echo $manager->GetForm('link' , 'admin_status');?></td>
      </tr>
      <tr>
        <td>防刷时间间隔(单位：秒)：</td>
        <td colspan="3"><?php echo $manager->GetForm('link' , 'ref_time');?></td>
      </tr>
      <tr>
        <td>友链点入跳转页(留空则为首页)：</td>
        <td colspan="3"><?php echo $manager->GetForm('link' , 'in_jump');?> 格式:<?php echo WMURL?></td>
      </tr>
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
	$("#in_jump").css('width','400');
});
</script>
<style>
.<?php echo $cFun?>Table{margin-bottom:20px}
</style>

<div class="bjui-pageContent">
	<form name="form1" action="index.php?a=yes&c=operate.zt.config&t=config_edit" method="post" data-toggle="validate">
	
    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
      <thead><tr><th colspan="4" style="text-align:left;"><b>模块基本设置</b></th></tr></thead>
      <tr>
        <td width="200">专题页检查参数：</td>
        <td colspan="3"><?php echo $manager->GetForm('zt' , 'par_zt');?></td>
      </tr>
      <tr>
        <td width="200">评论页检查参数：</td>
        <td colspan="3"><?php echo $manager->GetForm('zt' , 'par_replay');?></td>
      </tr>
    </table>
    

    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
      <thead><tr><th colspan="6" style="text-align:left;"><b>模块互动设置</b></th></tr></thead>
       <tr>
        <td width="200">专题顶踩功能：</td>
        <td><?php echo $manager->GetForm('zt' , 'dingcai_open');?></td>
        <td width="200">顶踩是需要否登录：</td>
        <td><?php echo $manager->GetForm('zt' , 'dingcai_login');?></td>
        <td width="200">每日每篇专题顶踩次数：</td>
        <td><?php echo $manager->GetForm('zt' , 'dingcai_count');?></td>
      </tr>
      <tr>
        <td>专题评分功能：</td>
        <td><?php echo $manager->GetForm('zt' , 'score_open');?></td>
        <td>评分是否需要登录：</td>
        <td><?php echo $manager->GetForm('zt' , 'score_login');?></td>
        <td>每日每篇专题评分次数：</td>
        <td><?php echo $manager->GetForm('zt' , 'score_count');?></td>
      </tr>
      <tr>
        <td>专题评论功能：</td>
        <td><?php echo $manager->GetForm('zt' , 'replay_open');?></td>
        <td>评论是否需要登录：</td>
        <td colspan="3"><?php echo $manager->GetForm('zt' , 'replay_login');?></td>
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
	$('#default_simg').css("width",'300px');
	$('#dingcai_count').css("width",'50px');
	$('#score_count').css("width",'50px');
});
</script>

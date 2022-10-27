<style>
.<?php echo $cFun?>Table{margin-bottom:20px}
</style>

<div class="bjui-pageContent">
	<form name="form1" action="index.php?a=yes&c=picture.config&t=edit" method="post" data-toggle="validate">
    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
      <thead><tr><th colspan="4" style="text-align:left;"><b>模块基本设置</b></th></tr></thead>
      <tr>
        <td width="200">图集页检查参数：</td>
        <td><?php echo $manager->GetForm('picture' , 'par_picture');?></td>
        <td>评论页检查参数：</td>
        <td><?php echo $manager->GetForm('picture' , 'par_replay');?></td>
      </tr>
      <tr>
        <td>后台更新需要审核：</td>
        <td><?php echo $manager->GetForm('picture' , 'admin_add');?></td>
        <td>用户投稿需要审核：</td>
        <td><?php echo $manager->GetForm('picture' , 'user_add');?></td>
      </tr>
    </table>
    
    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
      <thead><tr><th colspan="4" style="text-align:left;"><b>模块互动设置</b></th></tr></thead>
      <tr>
        <td width="200">顶踩功能：</td>
        <td><?php echo $manager->GetForm('picture' , 'dingcai_open');?></td>
        <td>每日每图集顶踩次数：</td>
        <td><?php echo $manager->GetForm('picture' , 'dingcai_count');?></td>
      </tr>
      <tr>
        <td>评分功能：</td>
        <td><?php echo $manager->GetForm('picture' , 'score_open');?></td>
        <td>每日每图集评分次数：</td>
        <td><?php echo $manager->GetForm('picture' , 'score_count');?></td>
      </tr>
      <tr>
        <td>评分是否登录：</td>
        <td colspan="3"><?php echo $manager->GetForm('picture' , 'score_login');?></td>
      </tr>
      <tr>
        <td>评论功能：</td>
        <td><?php echo $manager->GetForm('picture' , 'replay_open');?></td>
        <td>评论是否登录：</td>
        <td><?php echo $manager->GetForm('picture' , 'replay_login');?></td>
      </tr>
    </table>

    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
      <thead><tr><th colspan="4" style="text-align:left;"><b>模块搜索设置</b></th></tr></thead>
      <tr>
        <td width="200">搜索功能：</td>
        <td><?php echo $manager->GetForm('picture' , 'search_open');?></td>
        <td>搜索间隔：</td>
        <td><?php echo $manager->GetForm('picture' , 'search_time');?> 时间(单位：秒)</td>
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
	$('#dingcai_count').css("width",'50px');
	$('#score_count').css("width",'50px');
	$('#search_time').css("width",'50px');
});
</script>

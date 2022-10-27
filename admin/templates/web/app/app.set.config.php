<style>
.<?php echo $cFun?>Table{margin-bottom:20px}
</style>

<div class="bjui-pageContent">
	<form name="form1" action="index.php?a=yes&c=app.config&t=edit" method="post" data-toggle="validate">
    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
      <thead><tr><th colspan="4" style="text-align:left;"><b>模块基本设置</b></th></tr></thead>
      <tr>
        <td>应用页检查参数：</td>
        <td><?php echo $manager->GetForm('app' , 'par_app');?></td>
        <td>评论页检查参数：</td>
        <td><?php echo $manager->GetForm('app' , 'par_replay');?></td>
      </tr>
      <tr>
        <td width="200">应用默认图标：</td>
        <td width="400"><?php echo $manager->GetForm('app' , 'default_ico');?></td>
        <td width="200">应用默认封面：</td>
        <td><?php echo $manager->GetForm('app' , 'default_simg');?></td>
      </tr>
      <tr>
        <td>应用发布默认状态：</td>
        <td><?php echo $manager->GetForm('app' , 'admin_add');?></td>
        <td>应用开启下载：</td>
        <td><?php echo $manager->GetForm('app' , 'down_open');?></td>
      </tr>
      <tr>
        <td>自动生成应用HTML：</td>
        <td colspan="3"><?php echo $manager->GetForm('app' , 'auto_create_html');?></td>
      </tr>
    </table>
    
    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
      <thead><tr><th colspan="4" style="text-align:left;"><b>模块互动设置</b></th></tr></thead>
      <tr>
        <td width="200">顶踩功能：</td>
        <td width="400"><?php echo $manager->GetForm('app' , 'dingcai_open');?></td>
        <td width="200">每日应用顶踩次数：</td>
        <td><?php echo $manager->GetForm('app' , 'dingcai_count');?></td>
      </tr>
      <tr>
        <td>评分功能：</td>
        <td><?php echo $manager->GetForm('app' , 'score_open');?></td>
        <td>每日应用评分次数：</td>
        <td><?php echo $manager->GetForm('app' , 'score_count');?></td>
      </tr>
      <tr>
        <td>评分是否登录：</td>
        <td colspan="2"><?php echo $manager->GetForm('app' , 'score_login');?></td>
      </tr>
      <tr>
        <td>评论功能：</td>
        <td><?php echo $manager->GetForm('app' , 'replay_open');?></td>
        <td>评论是否登录：</td>
        <td><?php echo $manager->GetForm('app' , 'replay_login');?></td>
      </tr>
    </table>

    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
      <thead><tr><th colspan="4" style="text-align:left;"><b>模块搜索设置</b></th></tr></thead>
      <tr>
        <td width="200">搜索功能：</td>
        <td width="400"><?php echo $manager->GetForm('app' , 'search_open');?></td>
        <td width="200">每次搜索的间隔时间(单位：秒)：</td>
        <td><?php echo $manager->GetForm('app' , 'search_time');?></td>
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

<style>
.<?php echo $cFun?>Table{margin-bottom:20px}
</style>

<div class="bjui-pageContent">
	<form name="form1" action="index.php?a=yes&c=bbs.config&t=edit" method="post" data-toggle="validate">
    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
      <thead><tr><th colspan="6" style="text-align:left;"><b>模块基本设置</b></th></tr></thead>
      <tr>
        <td width="200">主题页检查参数：</td>
        <td><?php echo $manager->GetForm('bbs' , 'par_bbs');?></td>
        <td>回帖页检查参数：</td>
        <td colspan="3"><?php echo $manager->GetForm('bbs' , 'par_relist');?></td>
      </tr>
      <tr>
        <td>开启发帖：</td>
        <td><?php echo $manager->GetForm('bbs' , 'post_open');?></td>
        <td>开启回帖：</td>
        <td colspan="3"><?php echo $manager->GetForm('bbs' , 'replay_open');?></td>
      </tr>
      <tr>
        <td>默认版块图标：</td>
        <td colspan="5"><?php echo $manager->GetForm('bbs' , 'default_ico');?></td>
      </tr>
      <tr>
        <td>作者是否可以查看：</td>
        <td><?php echo $manager->GetForm('bbs' , 'author_look');?></td>
        <td>作者是否可以修改：</td>
        <td><?php echo $manager->GetForm('bbs' , 'author_up');?></td>
        <td>作者是否可以删除：</td>
        <td><?php echo $manager->GetForm('bbs' , 'author_del');?></td>
      </tr>
      <tr>
        <td>用户发帖默认状态：</td>
        <td><?php echo $manager->GetForm('bbs' , 'user_post');?></td>
        <td>用户回帖默认状态：</td>
        <td><?php echo $manager->GetForm('bbs' , 'user_replay');?></td>
        <td>修改后是否需要审核：</td>
        <td><?php echo $manager->GetForm('bbs' , 'up_status');?></td>
      </tr>
	
    </table>
    

    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
      <thead><tr><th colspan="6" style="text-align:left;"><b>模块奖励设置</b></th></tr></thead>
      <tr>
        <td width="200">每日发帖数量：</td>
        <td><?php echo $manager->GetForm('bbs' , 'post_num');?> 0为不限制</td>
        <td>每天前几次有奖励：</td>
        <td colspan="3"><?php echo $manager->GetForm('bbs' , 'post_top');?> 0为关闭</td>
      </tr>
      <tr>
        <td>发帖奖励金币1：</td>
        <td><?php echo $manager->GetForm('bbs' , 'post_gold1');?></td>
        <td>发帖奖励金币2：</td>
        <td><?php echo $manager->GetForm('bbs' , 'post_gold2');?></td>
        <td>发帖奖励经验：</td>
        <td><?php echo $manager->GetForm('bbs' , 'post_exp');?></td>
      </tr>
      <tr>
        <td width="200">每日回帖数量：</td>
        <td><?php echo $manager->GetForm('bbs' , 'everyday_count');?> 0为不限制</td>
        <td>每天前几次有奖励：</td>
        <td colspan="3"><?php echo $manager->GetForm('bbs' , 'reward_count');?> 0为关闭</td>
      </tr>
      <tr>
        <td>回帖奖励金币1：</td>
        <td><?php echo $manager->GetForm('bbs' , 'replay_gold1');?></td>
        <td>回帖奖励金币2：</td>
        <td><?php echo $manager->GetForm('bbs' , 'replay_gold2');?></td>
        <td>回帖奖励经验：</td>
        <td><?php echo $manager->GetForm('bbs' , 'replay_exp');?></td>
      </tr>
    </table>

    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
      <thead><tr><th colspan="4" style="text-align:left;"><b>模块搜索设置</b></th></tr></thead>
      <tr>
        <td width="200">搜索功能：</td>
        <td width="400"><?php echo $manager->GetForm('bbs' , 'search_open');?></td>
        <td width="200">每次搜索的间隔时间(单位：秒)：</td>
        <td><?php echo $manager->GetForm('bbs' , 'search_time');?></td>
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
	$('#post_num').css("width",'50px');
	$('#post_top').css("width",'50px');
	$('#post_gold1').css("width",'50px');
	$('#post_gold2').css("width",'50px');
	$('#post_exp').css("width",'50px');
	$('#everyday_count').css("width",'50px');
	$('#reward_count').css("width",'50px');
	$('#replay_gold1').css("width",'50px');
	$('#replay_gold2').css("width",'50px');
	$('#replay_exp').css("width",'50px');
});
</script>

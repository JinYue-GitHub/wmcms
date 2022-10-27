<style>
.<?php echo $cFun?>Table{margin-bottom:20px}
</style>


<div class="bjui-pageContent">
	<form name="form1" action="index.php?a=yes&c=operate.replay.config&t=edit" method="post" data-toggle="validate">
    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
      <thead><tr><th colspan="6" style="text-align:left;"><b>模块基本设置</b></th></tr></thead>
      <tr>
        <td width="200">开启评论：</td>
        <td><?php echo $manager->GetForm('replay' , 'replay_open');?></td>
        <td>评论总模块设置：</td>
        <td><?php echo $manager->GetForm('replay' , 'unify');?></td>
        <td>评论是否需要登录：</td>
        <td><?php echo $manager->GetForm('replay' , 'replay_login');?></td>
      </tr>
      <tr>
        <td>默认评论状态：</td>
        <td><?php echo $manager->GetForm('replay' , 'status');?></td>
        <td>两次评论的间隔：</td>
        <td><?php echo $manager->GetForm('replay' , 'top_time');?></td>
        <td>每天限制评论条数：</td>
        <td><?php echo $manager->GetForm('replay' , 'everyday_count');?></td>
      </tr>
      <tr>
        <td>评论顶踩：</td>
        <td><?php echo $manager->GetForm('replay' , 'dingcai_open');?></td>
        <td>顶踩是否需要登录：</td>
        <td><?php echo $manager->GetForm('replay' , 'dingcai_login');?></td>
        <td>同一评论每日顶踩次数：</td>
        <td><?php echo $manager->GetForm('replay' , 'dingcai_count');?></td>
      </tr>
      <tr>
        <td width="200">默认用户昵称：</td>
        <td colspan="5"><?php echo $manager->GetForm('replay' , 'nickname');?></td>
      </tr>
      <tr>
        <td width="200">楼层名：</td>
        <td><?php echo $manager->GetForm('replay' , 'replay_floor_name');?></td>
        <td width="200">楼层昵称：</td>
        <td><?php echo $manager->GetForm('replay' , 'replay_floor_nickname');?></td>
        <td colspan="2">默认楼层的昵称，每行一个，不限楼层数量，超出的以楼层名显示。</td>
      </tr>
    </table>

    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table" >
      <thead><tr><th colspan="6" style="text-align:left;"><b>评论奖励设置</b></th></tr></thead>
      <tr>
        <td width="200">每天前几次有奖励：</td>
        <td colspan="5"><?php echo $manager->GetForm('replay' , 'reward_count');?></td>
      </tr>
      <tr>
        <td>奖励<?php echo $configArr['gold1_name'];?>：</td>
        <td><?php echo $manager->GetForm('replay' , 'replay_gold1');?></td>
        <td>奖励<?php echo $configArr['gold2_name'];?>：</td>
        <td><?php echo $manager->GetForm('replay' , 'replay_gold2');?></td>
        <td>奖励<?php echo $configArr['exp_name'];?>：</td>
        <td><?php echo $manager->GetForm('replay' , 'replay_exp');?></td>
      </tr>
    </table>
	
    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
      <thead><tr><th colspan="6" style="text-align:left;"><b>JS/普通评论设置</b></th></tr></thead>
      <tr>
        <td width="200">评论框标题：</td>
        <td><?php echo $manager->GetForm('replay' , 'boxname');?></td>
        <td>无数据提示：</td>
        <td><?php echo $manager->GetForm('replay' , 'no_data');?></td>
        <td>输入框提示：</td>
        <td><?php echo $manager->GetForm('replay' , 'input');?></td>
      </tr>
      <tr>
		<td>评论列表显示模式：</td>
        <td><?php echo $manager->GetForm('replay' , 'replay_type');?></td>
        <td>是否显示热门评论：</td>
        <td><?php echo $manager->GetForm('replay' , 'hot');?></td>
        <td>多少顶成为热门评论：</td>
        <td><?php echo $manager->GetForm('replay' , 'hot_display');?></td>
      </tr>
      <tr>
        <td>显示多少条热门：</td>
        <td><?php echo $manager->GetForm('replay' , 'hot_count');?></td>
        <td>每页显示多少条：</td>
        <td><?php echo $manager->GetForm('replay' , 'list_limit');?></td>
        <td>显示数字分页个数：</td>
        <td><?php echo $manager->GetForm('replay' , 'page_count');?></td>
      </tr>
    </table>
	
    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
      <thead><tr><th colspan="6" style="text-align:left;"><b>JS/普通评论回复设置[题主模式生效]</b></th></tr></thead>
      <tr>
        <td width="200">每条评论的回复显示几条：</td>
        <td><?php echo $manager->GetForm('replay' , 'replay_replay_number');?></td>
        <td>每页显示多少条回复：</td>
        <td><?php echo $manager->GetForm('replay' , 'replay_replay_page');?></td>
        <td>评论回复排序方式：</td>
        <td><?php echo $manager->GetForm('replay' , 'replay_replay_order');?></td>
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
	$('#reg_sign').css("width",'500px');
	$('#reg_sign').css("height",'100px');
	$('#replay_floor_nickname').css("height",'100px');
	$('.bjui-pageContent').find('table').find('input').css("width",'80px');
});
</script>

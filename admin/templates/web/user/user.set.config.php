<style>
.<?php echo $cFun?>Table{margin-bottom:20px}
</style>

<div class="bjui-pageContent">
	<form name="form1" action="index.php?a=yes&c=user.config&t=edit" method="post" data-toggle="validate">
    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
      <thead><tr><th colspan="6" style="text-align:left;"><b>模块基本设置</b></th></tr></thead>
      <tr>
        <td width="200">登陆功能：</td>
        <td><?php echo $manager->GetForm('user' , 'login_open');?></td>
        <td>登录成功后页面跳转：</td>
        <td><?php echo $manager->GetForm('user' , 'ajax_login');?></td>
        <td>登录日志：</td>
        <td><?php echo $manager->GetForm('user' , 'login_log');?></td>
      </tr>
      <tr>
        <td>注册功能：</td>
        <td><?php echo $manager->GetForm('user' , 'reg_open');?></td>
        <td width="200">默认注册的状态：</td>
        <td><?php echo $manager->GetForm('user' , 'reg_status');?></td>
        <td width="200">第三方登录账号绑定：</td>
        <td><?php echo $manager->GetForm('user' , 'api_login_bind');?></td>
      </tr>
      <tr>
        <td>默认注册赠送的<?php echo $configArr['gold1_name'];?>：</td>
        <td><?php echo $manager->GetForm('user' , 'reg_gold1');?></td>
        <td>默认注册赠送的<?php echo $configArr['gold2_name'];?>：</td>
        <td><?php echo $manager->GetForm('user' , 'reg_gold2');?></td>
        <td>默认注册赠送的经验：</td>
        <td><?php echo $manager->GetForm('user' , 'reg_exp');?></td>
      </tr>
      <tr>
        <td>默认注册赠送的<?php echo $configArr['ticket_rec'];?>：</td>
        <td><?php echo $manager->GetForm('user' , 'reg_rec');?></td>
        <td>默认注册赠送的<?php echo $configArr['ticket_month'];?>：</td>
        <td><?php echo $manager->GetForm('user' , 'reg_month');?></td>
      </tr>
      <tr>
      	<td width="200">默认头像宽高：</td>
        <td><?php echo $manager->GetForm('user' , 'head_width');?>*<?php echo $manager->GetForm('user' , 'head_height');?></td>
        <td>默认头像类型：</td>
        <td><?php echo $manager->GetForm('user' , 'user_head');?></td>
        <td>默认头像：<a target="_blank" href="<?php echo $configArr['default_head']?>">点击查看</a></td>
        <td>
        	<?php echo $manager->GetForm('user' , 'default_head');?>
			<span class="upload" data-id="default_head" data-uploader="index.php?a=yes&c=upload&t=img" data-on-upload-success="<?php echo $cFun;?>upload_success" data-file-type-exts="*.jpg;*.png;*.gif;*.mpg" data-toggle="upload" data-icon="cloud-upload"></span>
		</td>
      </tr>
      <tr>
        <td>系统头像：<a target="_blank" href="<?php echo $configArr['msg_head']?>">点击查看</a></td>
        <td colspan="3">
        	<?php echo $manager->GetForm('user' , 'msg_head');?>
			<span class="upload" data-id="msg_head" data-uploader="index.php?a=yes&c=upload&t=img" data-on-upload-success="<?php echo $cFun;?>upload_success" data-file-type-exts="*.jpg;*.png;*.gif;*.mpg" data-toggle="upload" data-icon="cloud-upload"></span>
		</td>
      </tr>
      <tr>
        <td>默认用户签名：</td>
        <td colspan="5"><?php echo $manager->GetForm('user' , 'reg_sign');?></td>
      </tr>
    </table>

    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table" id="<?php echo $cFun?>bscicTable">
      <thead><tr><th colspan="6" style="text-align:left;"><b>基本属性设置</b></th></tr></thead>
      <tr>
        <td width="200">经验名字：</td>
        <td><?php echo $manager->GetForm('user' , 'exp_name');?></td>
        <td width="200">余额名字：</td>
        <td colspan="3"><?php echo $manager->GetForm('user' , 'money_name');?></td>
      </tr>
      <tr>
        <td>金币1名字：</td>
        <td><?php echo $manager->GetForm('user' , 'gold1_name');?></td>
        <td>金币1单位：</td>
        <td colspan="3"><?php echo $manager->GetForm('user' , 'gold1_unit');?></td>
      </tr>
      <tr>
        <td>金币2名字：</td>
        <td><?php echo $manager->GetForm('user' , 'gold2_name');?></td>
        <td>金币2单位：</td>
        <td colspan="3"><?php echo $manager->GetForm('user' , 'gold2_unit');?></td>
      </tr>
      <tr>
        <td>推荐票名字：</td>
        <td><?php echo $manager->GetForm('user' , 'ticket_rec');?></td>
        <td>月票名字：</td>
        <td colspan="3"><?php echo $manager->GetForm('user' , 'ticket_month');?></td>
      </tr>
      <tr>
        <td>每日登录赠送<?php echo $configArr['gold1_name'];?>：</td>
        <td><?php echo $manager->GetForm('user' , 'login_gold1');?></td>
        <td>每日登录赠送<?php echo $configArr['gold2_name'];?>：</td>
        <td><?php echo $manager->GetForm('user' , 'login_gold2');?></td>
        <td>每日登录赠送经验：</td>
        <td><?php echo $manager->GetForm('user' , 'login_exp');?></td>
      </tr>
      <tr>
        <td>每日登录清空推荐票：</td>
        <td><?php echo $manager->GetForm('user' , 'login_clear_ticket');?></td>
        <td>每日登录赠送<?php echo $configArr['ticket_rec'];?>：</td>
        <td><?php echo $manager->GetForm('user' , 'login_rec');?></td>
        <td>每日登录赠送<?php echo $configArr['ticket_month'];?>：</td>
        <td><?php echo $manager->GetForm('user' , 'login_month');?></td>
      </tr>
    </table>
	
    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table" id="<?php echo $cFun?>operateTable">
      <thead><tr><th colspan="6" style="text-align:left;"><b>签到功能设置</b></th></tr></thead>
      <tr>
        <td width="200">签到功能：</td>
        <td width="200"><?php echo $manager->GetForm('user' , 'sign_open');?></td>
        <td width="200">签到奖励功能：</td>
        <td colspan="3"><?php echo $manager->GetForm('user' , 'sign_reward');?></td>
      </tr>
      <tr>
        <td>签到赠送<?php echo $configArr['gold1_name'];?>：</td>
        <td><?php echo $manager->GetForm('user' , 'sign_gold1');?></td>
        <td>签到赠送<?php echo $configArr['gold2_name'];?>：</td>
        <td width="200"><?php echo $manager->GetForm('user' , 'sign_gold2');?></td>
        <td width="200">签到赠送经验：</td>
        <td><?php echo $manager->GetForm('user' , 'sign_exp');?></td>
      </tr>
      <tr>
        <td>签到赠送<?php echo $configArr['ticket_rec'];?>：</td>
        <td><?php echo $manager->GetForm('user' , 'sign_rec');?></td>
        <td>签到赠送<?php echo $configArr['ticket_month'];?>：</td>
        <td colspan="3"><?php echo $manager->GetForm('user' , 'sign_month');?></td>
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
//上传图片
function <?php echo $cFun;?>upload_success(file,json,$element){
	var json = $.parseJSON(json);
	if ( json.statusCode == 300){
		$($element).bjuiajax("ajaxDone",json);// 显示处理结果
	}else{
		var id = $($element).data('id');
		var val = json.data.path.replace('../',"/");
		$("#"+id).val(val);
	}
}

$(document).ready(function(){
	$('#reg_sign').css("width",'500px');
	$('#reg_sign').css("height",'100px');
	$('#head_width').css("width",'50px');
	$('#head_height').css("width",'50px');
	$('#reg_gold1').css("width",'50px');
	$('#reg_gold2').css("width",'50px');
	$('#reg_exp').css("width",'50px');
	$('#reg_rec').css("width",'50px');
	$('#reg_month').css("width",'50px');
	$('#<?php echo $cFun?>bscicTable input').css("width",'80px');
	$('#<?php echo $cFun?>operateTable input').css("width",'80px');
	$('#<?php echo $cFun?>Finance input').css("width",'80px');
	$('#card_buy_url').css("width",'500px');
});
</script>

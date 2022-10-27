<style>
.<?php echo $cFun?>Table{margin-bottom:20px}
</style>

<div class="bjui-pageContent">
	<form name="form1" action="index.php?a=yes&c=finance.config&t=edit" method="post" data-toggle="validate">
    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table" id="<?php echo $cFun?>Finance">
      <thead><tr><th colspan="6" style="text-align:left;"><b>资金财务设置</b></th></tr></thead>
      <tr>
        <td width="200">是否开启充值：</td>
        <td width="150"><?php echo $manager->GetForm('finance' , 'recharge_open');?></td>
        <td width="150">充值比例设置：</td>
        <td width="250">1元人民币等于 <?php echo $manager->GetForm('finance' , 'rmb_to_gold2');?> <?php echo $userConfig['gold2_name'];?></td>
      	<td width="120">首充值奖励：</td>
        <td><?php echo $manager->GetForm('finance' , 'recharge_reward_gold2');?> <?php echo $userConfig['gold2_name'];?>，0为不奖励！</td>
      </tr>
      <tr>
        <td width="200">是否开启余额兑换<?php echo $userConfig['gold2_name'];?>：</td>
        <td><?php echo $manager->GetForm('finance' , 'money_to_gold2_open');?></td>
        <td>兑换比例设置：</td>
        <td colspan="3">1余额等于 <?php echo $manager->GetForm('finance' , 'money_to_gold2');?> <?php echo $userConfig['gold2_name'];?></td>
      </tr>
      <tr>
        <td width="200">是否<?php echo $userConfig['gold2_name'];?>兑换<?php echo $userConfig['gold1_name'];?>：</td>
        <td><?php echo $manager->GetForm('finance' , 'gold2_to_gold1_open');?></td>
        <td>兑换比例设置：</td>
        <td colspan="3">1<?php echo $userConfig['gold2_name'];?>等于 <?php echo $manager->GetForm('finance' , 'gold2_to_gold1');?> <?php echo $userConfig['gold1_name'];?></td>
      </tr>
      <tr>
        <td width="200">是否<?php echo $userConfig['gold1_name'];?>兑换<?php echo $userConfig['gold2_name'];?>：</td>
        <td><?php echo $manager->GetForm('finance' , 'gold1_to_gold2_open');?></td>
        <td>兑换比例设置：</td>
        <td colspan="3">1<?php echo $userConfig['gold1_name'];?>等于 <?php echo $manager->GetForm('finance' , 'gold1_to_gold2');?> <?php echo $userConfig['gold2_name'];?></td>
      </tr>
      <tr>
        <td width="200">是否开启提现：</td>
        <td><?php echo $manager->GetForm('finance' , 'cash_open');?></td>
        <td width="200">提现手续费：</td>
        <td><?php echo $manager->GetForm('finance' , 'cash_cost');?> %</td>
        <td width="200">最低提现金额：</td>
        <td><?php echo $manager->GetForm('finance' , 'cash_lowest');?> 元</td>
      </tr>
      <tr>
        <td width="200">是否<?php echo $userConfig['gold2_name'];?>兑换余额：</td>
        <td><?php echo $manager->GetForm('finance' , 'gold2_to_money_open');?></td>
        <td>兑换比例设置：</td>
        <td colspan="3">1<?php echo $userConfig['gold2_name'];?>等于 <?php echo $manager->GetForm('finance' , 'gold2_to_money');?> 余额 【<span style="color:red">开启后可使用<?php echo $userConfig['gold2_name'];?>兑换余额提现，否则只能提现余额！</span>】</td>
      </tr>
      <tr>
        <td width="200">卡密购买地址(如淘宝店铺)：</td>
        <td colspan="5"><?php echo $manager->GetForm('finance' , 'card_buy_url');?></td>
      </tr>
      <tr>
        <td width="200">充值活动：</td>
        <td colspan="5"><?php echo $manager->GetForm('finance' , 'activity_open');?> 活动的赠送比例请到充值等级菜单里面设置</td>
      </tr>
      <tr class="<?php echo $cFun.$type;?>activityBox" <?php if( $financeConfig['activity_open'] != 1){?>style="display:none"<?php }?>>
        <td width="200">充值活动时间：</td>
        <td colspan="5">
        	<input type="text" id="activity_starttime" name="finance[activity_starttime]" value="<?php echo date('Y-m-d H:i:s',$financeConfig['activity_starttime']);?>" data-toggle="datepicker" data-pattern="yyyy-MM-dd H:m:s" size="19">
			到 <input type="text" id="activity_endtime" name="finance[activity_endtime]" value="<?php echo date('Y-m-d H:i:s',$financeConfig['activity_endtime']);?>" data-toggle="datepicker" data-pattern="yyyy-MM-dd H:m:s" size="19">
		</td>
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
	$('#<?php echo $cFun?>Finance input').css("width",'80px');
	$('#card_buy_url').css("width",'500px');

	$('input[name="339"]').on('ifChanged', function(e) {
		 var checked = $(this).is(':checked'), val = $(this).val();
		 if (val == 1){
			 $('.<?php echo $cFun.$type;?>activityBox').show();
		 }else{
			 $('.<?php echo $cFun.$type;?>activityBox').hide();
		}
	});
});
</script>

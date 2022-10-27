<div class="bjui-pageContent">	                       
    <form action="index.php?a=yes&c=operate.weixin.account&t=<?php echo $type;?>" data-reload="false" data-toggle="validate" method="post"  <?php if($d) { echo 'data-callback="'.$cFun.'"';}?>>
	<input name="id[account_id]" type="hidden" class="input-text" value="<?php echo $id;?>">
		<fieldset>
			<legend>公众号编辑</legend>
            	<div class="tab-pane fade active in" id="<?php echo $cFun.$type;?>base">
					<table class="table table-border table-bordered table-bg table-sort">
		                <tr>
					      <td valign="top" width="170"><b>公众号名字：</b></td>
					      <td valign="top"><input type="text" size="15" name="account[account_name]" value="<?php echo C('account_name',null,'data');?>" data-rule="required"></td>
						  <td valign="top" width="170"><b>公众号账号：</b></td>
					      <td valign="top"><input type="text" size="15" name="account[account_account]" value="<?php echo C('account_account',null,'data');?>" data-rule="required"></td>
						</tr>
		                <tr>
		                    <td>
		                        <b>公众号原始id：</b>
		                    </td>
		                    <td>
		                        <input type="text" size="15" name="account[account_gid]" value="<?php echo C('account_gid',null,'data');?>" data-rule="required">
		                    </td>
		                    <td>
		                        <b>审 核 状 态：</b>
		                    </td>
		                    <td>
		                        <select data-toggle="selectpicker" name="account[account_status]">
		                       	<?php 
		                       	foreach ($accountMod->status as $k=>$v)
		                       	{
		                       		$select = str::CheckElse($k, C('account_status',null,'data') , 'selected=""');
		                       		echo '<option value="'.$k.'" '.$select.'>'.$v.'</option>';
		                       	}
		                       	?>
		                        </select>
		                    </td>
		                </tr>
		                <tr>
		                    <td>
		                        <b>账 号 类 型：</b>
		                    </td>
		                    <td>
		                        <select data-toggle="selectpicker" name="account[account_type]">
		                       	<?php 
		                       	foreach ($accountMod->type as $k=>$v)
		                       	{
		                       		$select = str::CheckElse($k, C('account_type',null,'data') , 'selected=""');
		                       		echo '<option value="'.$k.'" '.$select.'>'.$v.'</option>';
		                       	}
		                       	?>
		                        </select>
		                    </td>
		                    <td>
		                        <b>是 否 认 证：</b>
		                    </td>
		                    <td>
		                        <select data-toggle="selectpicker" name="account[account_auth]">
		                       	<?php 
		                       	foreach ($accountMod->auth as $k=>$v)
		                       	{
		                       		$select = str::CheckElse($k, C('account_auth',null,'data') , 'selected=""');
		                       		echo '<option value="'.$k.'" '.$select.'>'.$v.'</option>';
		                       	}
		                       	?>
		                        </select>
		                    </td>
		                </tr>
		                <tr>
		                    <td>
		                        <b>主公众号：</b>
		                    </td>
		                    <td>
		                        <select data-toggle="selectpicker" name="account[account_main]">
		                       	<?php 
		                       	foreach ($accountMod->main as $k=>$v)
		                       	{
		                       		$select = str::CheckElse($k, C('account_main',null,'data') , 'selected=""');
		                       		echo '<option value="'.$k.'" '.$select.'>'.$v.'</option>';
		                       	}
		                       	?>
		                        </select>
		                    </td>
		                    <td>
		                        <b>关注后访问：</b><br/><span style="color:red">强制关注有封号风险</span>
		                    </td>
		                    <td>
		                        <select data-toggle="selectpicker" name="account[account_follow]">
		                       	<?php 
		                       	foreach ($accountMod->follow as $k=>$v)
		                       	{
		                       		$select = str::CheckElse($k, C('account_follow',null,'data') , 'selected=""');
		                       		echo '<option value="'.$k.'" '.$select.'>'.$v.'</option>';
		                       	}
		                       	?>
		                        </select>
		                    </td>
		                </tr>
			             <tr>
		                    <td>
		                        <b>公众号APPID：</b>
		                    </td>
		                    <td>
		                        <input type="text" name="account[account_appid]" value="<?php echo C('account_appid',null,'data');?>" data-rule="required" size="30">
		                    </td>
		                    <td>
		                        <b>公众号SECRET：</b>
		                    </td>
		                    <td>
		                        <input type="text" name="account[account_secret]" value="<?php echo C('account_secret',null,'data');?>" data-rule="required" size="30">
		                    </td>
		                </tr>
			             <tr>
		                    <td><b>关注自动回复：</b><br/>可在自动回复里面设置</td>
		                    <td><textarea cols="30" rows="3" name="account[account_welcome]"><?php echo C('account_welcome',null,'data');?></textarea></td>
		                    <td><b>默认回复：</b><br/>可在自动回复里面设置</td>
		                    <td><textarea cols="30" rows="3" name="account[account_default]"><?php echo C('account_default',null,'data');?></textarea></td>
		                </tr>
		                <tr>
		                    <td>
		                        <b>是否接入：</b><br/>自定义菜单无需接入即可
		                    </td>
		                    <td>
		                       	<?php echo C('account_access',null,'data');?>
		                    </td>
		                </tr>
		                <tr>
		                    <td>
		                        <b>token：</b>
		                    </td>
		                    <td>
		                       	<?php echo C('account_token',null,'data');?>
		                    </td>
		                    <td>
		                        <b>消息加密key：</b>
		                    </td>
		                    <td>
		                       	<?php echo C('account_aeskey',null,'data');?>
		                    </td>
		                </tr>
		            </tbody>
		        </table>
			</div>
		</fieldset>
	</form>
</div>

<div class="bjui-pageFooter">
    <ul>
        <li><button type="button" class="btn-close" data-icon="close">取消</button></li>
        <li><button type="submit" class="btn-green" data-icon="save">保存</button></li>
    </ul>
</div>


<script>
//页面唯一回调函数
function <?php echo $cFun;?>(json){
	var op = operateWeixinAccountListGetOp();
	var tabid = 'weixin-account_list';
	op['id'] = tabid;
	
	$(this).bjuiajax("ajaxDone",json);// 显示处理结果
    $(this).navtab("reload",op);	// 刷新Tab页面
    $(this).navtab("switchTab",tabid);	// 切换Tab页面
}
</script>
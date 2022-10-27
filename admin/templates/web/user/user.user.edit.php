<div class="bjui-pageContent">	                       
    <form action="index.php?a=yes&c=user.user&t=<?php echo $type;?>" data-reload="false" data-toggle="validate" method="post" <?php if($d) { echo 'data-callback="'.$cFun.'"';}?>>
	<input name="user_id" type="hidden" class="input-text" value="<?php echo $id;?>">
		<fieldset>
			<legend>用户信息编辑</legend>
            <ul class="nav nav-tabs" role="tablist">
            	<li class="active"><a href="#<?php echo $cFun.$type;?>base" role="tab" data-toggle="tab">基本信息</a></li>
            	<li><a href="#<?php echo $cFun.$type;?>finance" id="<?php echo $cFun.$type;?>finance_tab" role="tab" data-toggle="tab">财务信息</a></li>
            </ul>
            <div class="tab-content">
            	<div class="tab-pane fade active in" id="<?php echo $cFun.$type;?>base">
		    		<table class="table table-border table-bordered table-bg table-sort">
			            <tbody>
			                <tr>
			                    <td colspan="5">
			                        <b>用户账号：</b>
			                        <input type="text" name="user[user_name]" value="<?php echo C('user_name',null,'data');?>" data-rule="required">
			                    </td>
			                </tr>
			                <tr>
			                    <td colspan="5">
			                        <b>用户密码：</b>
			                        <input type="text" name="user[user_psw]">
			                    </td>
			                </tr>
			                <tr>
				                <td>
				                	<b>账号审核状态：</b>
				                    <select data-toggle="selectpicker" name="user[user_status]">
		                            	<option value="1" <?php if(C('user_status',null,'data') == '1'){ echo 'selected=""';}?>>审核通过</option>
		                                <option value="0" <?php if(C('user_status',null,'data') == '0'){ echo 'selected=""';}?>>待审核</option>
		                            </select>
				                </td>
			                   <td>
			                        <b>账号封禁状态：</b>
			                        <select data-toggle="selectpicker" name="user[user_display]">
			                        <?php 
			                        foreach ($displayArr as $k=>$v)
			                        {
			                        	$select = str::CheckElse( C('user_display',null,'data') , $k , 'selected=""');
			                        	echo '<option value="'.$k.'" '.$select.'>'.$v.'</option>';
			                        }
			                        ?>
		                            </select>
			                   </td>
			                   <td>
			                        <b>封禁时间：</b>
			                        <input type="text" name="user[user_displaytime]" value="<?php echo date('Y-m-d H:i:s',C('user_displaytime',null,'data'));?>" data-toggle="datepicker" data-pattern="yyyy-MM-dd H:m:s" size="19">
			                   </td>
			                </tr>
			                <tr>
			                   <td colspan="5">
			                        <b>用户头像：</b>
			                        <div style="line-height: 25px;">
				                        <img width="100" id="user_head_img" height="100" src="<?php echo C('user_head',null,'data')?>" /><br/>
				                        <a target="_blank" href="<?php echo C('user_head',null,'data');?>">点击查看原图</a><br/>
				                        
				                        <input type="text" id="user_head" name="user[user_head]" value="<?php echo C('user_head',null,'data');?>" size="40">
										<span class="upload" data-uploader="index.php?a=yes&c=upload&t=img" data-on-upload-success="<?php echo $cFun;?>upload_success" data-file-type-exts="*.jpg;*.png;*.gif;*.mpg" data-toggle="upload" data-icon="cloud-upload"></span>
									</div>
			                    </td>
			                </tr>
			                <tr>
			                   <td>
			                        <b>用户昵称：</b>
			                        <input type="text" name="user[user_nickname]" data-rule="required" value="<?php echo C('user_nickname',null,'data');?>">
			                    </td>
			                   <td>
			                        <b>用户邮箱：</b>
			                        <input type="text" name="user[user_email]" data-rule="email" value="<?php echo C('user_email',null,'data');?>">
			                    </td>
			                   <td>
			                        <b>邮箱验证：</b>
			                        <input name="user[user_emailtrue]" type="radio" data-toggle="icheck" data-label="已验证" value="1" <?php if(C('user_emailtrue',null,'data') == '1'){ echo 'checked="1"';}?> />
			                        <input name="user[user_emailtrue]" type="radio" data-toggle="icheck" data-label="未验证" value="0" <?php if(C('user_emailtrue',null,'data') == '0'){ echo 'checked="0"';}?> />
			                    </td>
			                </tr>
			                <tr>
			                   <td>
			                        <b>用户出生日期：</b>
			                        <input type="text" data-toggle="datepicker" data-pattern="yyyy-MM-dd" name="user[user_birthday]"  value="<?php echo C('user_birthday',null,'data');?>" size="13">
			                    </td>
			                   <td>
			                        <b>用户手机号：</b>
			                        <input type="text" size="13" data-rule="number" name="user[user_tel]"  value="<?php echo C('user_tel',null,'data');?>">
			                    </td>
			                   <td>
			                        <b>手机号验证：</b>
			                        <input name="user[user_teltrue]" type="radio" data-toggle="icheck" data-label="已验证" value="1" <?php if(C('user_teltrue',null,'data') == '1'){ echo 'checked="1"';}?> />
			                        <input name="user[user_teltrue]" type="radio" data-toggle="icheck" data-label="未验证" value="0" <?php if(C('user_teltrue',null,'data') == '0'){ echo 'checked="0"';}?> />
			                    </td>
			                </tr>
			                <tr>
			                   <td>
			                        <b>用户性别：</b>
			                        <input name="user[user_sex]" type="radio" data-toggle="icheck" data-label="男" value="1" <?php if(C('user_sex',null,'data') == '1'){ echo 'checked="1"';}?> />
			                        <input name="user[user_sex]" type="radio" data-toggle="icheck" data-label="女" value="2" <?php if(C('user_sex',null,'data') == '2'){ echo 'checked="2"';}?> />
			                    </td>
			                   <td>
			                        <b>空间浏览量：</b>
			                        <input type="text" data-rule="number" size="10" name="user[user_browse]"  value="<?php echo C('user_browse',null,'data');?>">
			                    </td>
			                </tr>
			                <tr>
			                   <td>
			                        <b>用户经验值：</b>
			                        <input type="text" size="10" data-rule="number" name="user[user_exp]"  value="<?php echo C('user_exp',null,'data');?>">
			                    </td>
			                   <td>
			                        <b>用户qq：</b>
			                        <input type="text" size="13" data-rule="number" name="user[user_qq]"  value="<?php echo C('user_qq',null,'data');?>">
			                    </td>
			                </tr>
			                <tr>
			                   <td>
			                        <b>用户余额：</b>
			                        <input type="text" size="10" data-rule="number" readonly value="<?php echo C('user_money',null,'data');?>">
			                    </td>
			                   <td>
			                        <b>金币1数量：</b>
			                        <input type="text" size="10" data-rule="number" readonly value="<?php echo C('user_gold1',null,'data');?>">
			                    </td>
			                   <td>
			                        <b>金币2数量：</b>
			                        <input type="text" size="10" data-rule="number" readonly value="<?php echo C('user_gold2',null,'data');?>">
			                    </td>
			                </tr>
			                <tr>
			                   <td>
			                        <b>用户主题量：</b>
			                        <input type="text" size="10" data-rule="number" name="user[user_topic]"  value="<?php echo C('user_topic',null,'data');?>">
			                    </td>
			                   <td>
			                        <b>用户回帖量：</b>
			                        <input type="text" size="10" data-rule="number" name="user[user_retopic]"  value="<?php echo C('user_retopic',null,'data');?>">
			                    </td>
			                   <td>
			                        <b>用户评论量：</b>
			                        <input type="text" size="10" data-rule="number" name="user[user_replay]"  value="<?php echo C('user_replay',null,'data');?>">
			                    </td>
			                </tr>
			                <tr>
			                   <td colspan="3">
			                        <b>用户签名：</b>
			                        <textarea cols="70" rows="1" name="user[user_sign]" data-toggle="autoheight"><?php echo C('user_sign',null,'data');?></textarea>
			                    </td>
			                </tr>
			                <tr>
			                   <td>
			                        <b>上次登录时间：</b>
			                        <input type="text" name="user[user_logintime]" value="<?php echo date('Y-m-d H:i:s',C('user_logintime',null,'data'));?>" data-toggle="datepicker" data-pattern="yyyy-MM-dd H:m:s" size="19">
			                   </td>
			                   <td>
			                        <b>注册时间：</b>
			                        <input type="text" name="user[user_regtime]" value="<?php echo date('Y-m-d H:i:s',C('user_regtime',null,'data'));?>" data-toggle="datepicker" data-pattern="yyyy-MM-dd H:m:s" size="19">
			                   </td>
			                </tr>
			            </tbody>
			        </table>
			   </div>

		       <div class="tab-pane fade" id="<?php echo $cFun.$type;?>finance">
		    		<table class="table table-border table-bordered table-bg table-sort">
		            <tbody>
		                <tr>
		                   <td>
		                        <b>真实姓名：</b>
		                        <input size="15" name="finance[finance_realname]" value="<?php echo C('finance_realname',null,'data');?>">
		                    </td>
		                   <td>
		                        <b>&nbsp;&nbsp;&nbsp;&nbsp;身 份 证：</b>
		                        <input size="35" name="finance[finance_cardid]" value="<?php echo C('finance_cardid',null,'data');?>">
		                    </td>
		                </tr>
		                <tr>
		                   <td>
		                        <b>邮政编码：</b>
		                        <input size="15" name="finance[finance_zipcode]" value="<?php echo C('finance_zipcode',null,'data');?>">
		                    </td>
		                   <td>
		                        <b> 居 住 地 址：</b>
		                        <input size="35" name="finance[finance_address]" value="<?php echo C('finance_address',null,'data');?>">
		                    </td>
		                </tr>
		                <tr>
		                   <td>
		                        <b> 开 户 行 ：</b>
		                        <input size="15" name="finance[finance_bank]" value="<?php echo C('finance_bank',null,'data');?>">
		                    </td>
		                   <td>
		                        <b>开户行地址：</b>
		                        <input size="35" name="finance[finance_bankaddress]" value="<?php echo C('finance_bankaddress',null,'data');?>">
		                    </td>
		                </tr>
		                <tr>
		                   <td>
		                        <b> 持 卡 人 ：</b>
		                        <input size="15" name="finance[finance_bankmaster]" value="<?php echo C('bankmaster',null,'data');?>">
		                    </td>
		                   <td>
		                        <b> 银 行 卡 号：</b>
		                        <input size="35" name="finance[finance_bankcard]" value="<?php echo C('finance_bankcard',null,'data');?>">
		                    </td>
		                </tr>
		                <tr>
		                   <td colspan="3">
		                        <b> 支 付 宝 ：</b>
		                        <input size="25" name="finance[finance_alipay]" value="<?php echo C('finance_alipay',null,'data');?>">
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
//页面唯一op获取函数
function <?php echo $cFun;?>GetOp(){
	var op=new Array();
	op['type'] = 'POST';
	op['data'] = $("[name=<?php echo $cFun;?>Form]").serializeArray();
	return op;
}
//页面唯一回调函数
function <?php echo $cFun;?>(json){
	var op = userUserListGetOp();
	var tabid = 'user-list';
	op['id'] = tabid;
	
	$(this).bjuiajax("ajaxDone",json);// 显示处理结果
    $(this).navtab("reload",op);	// 刷新Tab页面
    $(this).navtab("switchTab",tabid);	// 切换Tab页面
}
//上传头像
function <?php echo $cFun;?>upload_success(file,json,$element){
	json = $.parseJSON(json);
	if ( json.statusCode == 300){
		$(this).bjuiajax("ajaxDone",json);// 显示处理结果
	}else{
		val = json.data.path.replace('../',"/");
		$("#user_head").val(val);
		$("#user_head_img").attr('src',val);
		
	}
}


$(document).ready(function(){
	$("#<?php echo $cFun;?>senior_tab").click(function(){
		$('.bjui-lookup').css("line-height",'22px');
		$('.bjui-lookup').css("height",'22px');
	});
});
</script>
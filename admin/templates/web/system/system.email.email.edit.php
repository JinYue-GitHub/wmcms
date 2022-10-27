<style>
.form-horizontal .row {display: table;width: 100%;}
.form .row {margin-top: 15px;}
.form-horizontal .form-label {margin-top: 8px;cursor: text;text-align: right;padding-right: 10px; font-size: 14px;font-weight: normal;}
</style>

<div class="bjui-pageContent">
	<form action="index.php?a=yes&c=system.email.email&t=<?php echo $type;?>" name="<?php echo $cFun.$type;?>Form" class="form form-horizontal" data-reload="false" data-toggle="ajaxform" method="post" data-callback="<?php echo $cFun.$type;?>">
    	<input type="hidden" name="email_id" value="<?php echo $id;?>"/>
	    <div class="row cl">
			<label class="form-label col-md-4">是否启用：</label>
			<div class="formControls col-md-8">
		      	<select data-toggle="selectpicker" name="data[email_status]">
		        <option value="1" <?php if(C('email_status',null,'data')=='1'){ echo 'selected=""';}?>>正常</option>
		        <option value="0" <?php if(C('email_status',null,'data')=='0'){ echo 'selected=""';}?>>禁用</option>
				</select>
			</div>
		</div>
	    <div class="row cl">
			<label class="form-label col-md-4">发信类型：</label>
	    	<div class="formControls col-md-8">
				<?php
				$input['type'] = 'select';
				$input['id'] = $cFun.$type.'email_type';
				$input['name'] = 'data[email_type]';
				$input['value'] = C('email_type',null,'data');
				$input['option'] = $emailMod->sendType;
				echo $manager->GetForm($input,null,false);
				?>
	    	</div>
	    </div>
	    <div class="row cl smtp">
			<label class="form-label col-md-4">SMTP服务器：</label>
	    	<div class="formControls col-md-8">
	    		<input type="text" class="form-control" placeholder="如：smtp.163.com" name="data[email_smtp]" size="24" value="<?php echo C('email_smtp',null,'data');?>">
	    	</div>
	    </div>
	    <div class="row cl smtp">
			<label class="form-label col-md-4">SMTP端口：</label>
	    	<div class="formControls col-md-8">
	    		<input type="text" class="form-control" placeholder="如：25" name="data[email_port]" size="24" value="<?php echo C('email_port',null,'data');?>">
	    	</div>
	    </div>
	    <div class="row cl smtp">
			<label class="form-label col-md-4">邮箱登录账号：</label>
	    	<div class="formControls col-md-8">
	    		<input type="text" class="form-control" placeholder="邮箱的登录账号" name="data[email_name]" size="24" value="<?php echo C('email_name',null,'data');?>">
	    	</div>
	    </div>
	    <div class="row cl smtp">
			<label class="form-label col-md-4">
				<a href="<?php echo WMHELP.'/html/admin/article/135.html';?>" target="_blank" style="color: #1e16df;text-decoration: underline;font-size: 16px;">授权码/密码</a>：
			</label>
	    	<div class="formControls col-md-8">
	        	<input type="text" class="form-control" value="******" name="data[email_psw]" size="24" value="<?php echo C('email_psw',null,'data');?>">
	    	</div>
	    </div>
	    <div class="row cl smtp">
			<label class="form-label col-md-4">邮件发送账户：</label>
	    	<div class="formControls col-md-8">
	        	<input type="text" class="form-control" placeholder="发送邮件的账号" name="data[email_send]" size="24" value="<?php echo C('email_send',null,'data');?>">
	    	</div>
	    </div>
	</div>
	</form>
	<div class="bjui-pageFooter">
	    <ul>
	        <li><button type="button" class="btn-close btn btn-danger size-MINI radius"><i class="fa fa-times"></i> 关闭</button></li>
	        <li><button type="submit" class="btn btn-success size-MINI radius"><i class="fa fa-floppy-o"></i> 保存</button></li>
	    </ul>
	</div>
</div>
<script type="text/javascript">
function <?php echo $cFun.$type;?>(json){
	$(this).bjuiajax("ajaxDone",json);// 显示处理结果
	if(json.statusCode==200){
    	$(this).navtab("reload",systemSetEmailGetOp('email'));	// 刷新当前Tab页面
	}
}
$(document).ready(function(){
	//邮件类型
	$("#<?php echo $cFun.$type;?>email_type").change(function(){
		if( $(this).val() == '1'){
			$('.smtp').show();
		}else{
			$('.smtp').hide();
		}
	});
});
</script>
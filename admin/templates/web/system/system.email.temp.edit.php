<style>
.form-horizontal .row {display: table;width: 100%;}
.form .row {margin-top: 15px;}
.form-horizontal .form-label {margin-top: 8px;cursor: text;text-align: right;padding-right: 10px; font-size: 14px;font-weight: normal;}
</style>

<div class="bjui-pageContent">
	<form action="index.php?a=yes&c=system.email.temp&t=<?php echo $type;?>" name="<?php echo $cFun.$type;?>Form" class="form form-horizontal" data-reload="false" data-toggle="ajaxform" method="post" data-callback="<?php echo $cFun.$type;?>">
	    <div class="row cl">
			<label class="form-label col-md-2">是否启用：</label>
			<div class="formControls col-md-8">
		      	<select data-toggle="selectpicker" name="data[temp_status]">
		        <option value="1" <?php if(C('temp_status',null,'data')=='1'){ echo 'selected=""';}?>>正常</option>
		        <option value="0" <?php if(C('temp_status',null,'data')=='0'){ echo 'selected=""';}?>>禁用</option>
				</select>
			</div>
		</div>
	    <div class="row cl smtp">
			<label class="form-label col-md-2">模版ID：</label>
	    	<div class="formControls col-md-8">
				<input type="text" class="form-control" placeholder="模版ID" name="data[temp_id]" size="24" value="<?php echo C('temp_id',null,'data');?>">
			</div>
	    </div>
	    <div class="row cl smtp">
			<label class="form-label col-md-2">发信模版名字：</label>
	    	<div class="formControls col-md-8">
				<input type="text" class="form-control" placeholder="发信模版名字" name="data[temp_name]" size="24" value="<?php echo C('temp_name',null,'data');?>">
			</div>
	    </div>
	    <div class="row cl smtp">
			<label class="form-label col-md-2">发信模版描述：</label>
	    	<div class="formControls col-md-8">
				<input type="text" class="form-control" placeholder="发信模版描述" name="data[temp_desc]" size="24" value="<?php echo C('temp_desc',null,'data');?>">
			</div>
	    </div>
	    <div class="row cl smtp">
			<label class="form-label col-md-2">邮件标题：</label>
	    	<div class="formControls col-md-8">
	    		<input type="text" class="form-control" placeholder="邮件标题" name="data[temp_title]" size="24" value="<?php echo C('temp_title',null,'data');?>">
	    	</div>
	    </div>
	    <div class="row cl smtp">
			<label class="form-label col-md-2">邮件内容：</label>
	    	<div class="formControls col-md-8">
	        	<?php echo Ueditor('height:200px;width:600px' , 'data[temp_content]',C('temp_content',null,'data'), 'editor.email');?>
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
    	$(this).navtab("reload",systemSetEmailGetOp('temp'));	// 刷新当前Tab页面
	}
}
$(document).ready(function(){
	$("#<?php echo $cFun.$type;?>temp_type").change(function(){
		if( $(this).val() == '1'){
			$('.smtp').show();
		}else{
			$('.smtp').hide();
		}
	});
});
</script>
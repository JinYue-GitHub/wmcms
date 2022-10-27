<style>
.form-horizontal .row {display: table;width: 100%;}
.form .row {margin-top: 15px;}
.form-horizontal .form-label {margin-top: 8px;cursor: text;text-align: right;padding-right: 10px; font-size: 14px;font-weight: normal;}
</style>

<div class="bjui-pageContent">
	<form action="index.php?a=yes&c=system.sms&t=<?php echo $type;?>" name="<?php echo $cFun.$type;?>Form" class="form form-horizontal" data-reload="false" data-toggle="ajaxform" method="post" data-callback="<?php echo $cFun.$type;?>">
    	<input type="hidden" name="sms_id" value="<?php echo $id;?>"/>
	    <div class="row cl">
			<label class="form-label col-md-2">是否启用：</label>
			<div class="formControls col-md-8">
		      	<select data-toggle="selectpicker" name="data[sms_status]">
		        <option value="1" <?php if(C('sms_status',null,'data')=='1'){ echo 'selected=""';}?>>正常</option>
		        <option value="0" <?php if(C('sms_status',null,'data')=='0'){ echo 'selected=""';}?>>禁用</option>
				</select>
			</div>
		</div>
	    <div class="row cl">
			<label class="form-label col-md-2">短信接口：</label>
			<div class="formControls col-md-8">
		      	<select data-toggle="selectpicker" name="data[sms_api_name]">
				<?php
				foreach($apiArr as $v){
					$selected = '';
					if($v['api_name']==C('sms_api_name',null,'data')){ $selected = 'selected=""';}
					echo '<option value="'.$v['api_name'].'" '.$selected.'>'.$v['api_title'].'</option>';
				}
				?>
				</select>
			</div>
		</div>
	    <div class="row cl">
			<label class="form-label col-md-2">模版类型：</label>
	    	<div class="formControls col-md-8">
				<input type="text" class="form-control" placeholder="模版类型" name="data[sms_type]" size="24" value="<?php echo C('sms_type',null,'data');?>">
			</div>
	    </div>
	    <div class="row cl">
			<label class="form-label col-md-2">模版签名：</label>
	    	<div class="formControls col-md-8">
				<input type="text" class="form-control" placeholder="第三方模版签名" name="data[sms_sign]" size="24" value="<?php echo C('sms_sign',null,'data');?>">
			</div>
	    </div>
	    <div class="row cl">
			<label class="form-label col-md-2">模版代码：</label>
	    	<div class="formControls col-md-8">
				<input type="text" class="form-control" placeholder="第三方模版代码" name="data[sms_tempcode]" size="24" value="<?php echo C('sms_tempcode',null,'data');?>">
			</div>
	    </div>
	    <div class="row cl">
			<label class="form-label col-md-2">替换参数：</label>
	    	<div class="formControls col-md-8">
	        	<textarea name="data[sms_params]" cols="25" rows="3"><?php if($type=='add'){echo 'code:{code}';}else{echo $data['sms_params'];}?></textarea>
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
    	$(this).navtab("reload",systemSetSmsGetOp('temp'));	// 刷新当前Tab页面
	}
}
</script>
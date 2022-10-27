<style>
.form-horizontal .row {display: table;width: 100%;}
.form .row {margin-top: 15px;}
.form-horizontal .form-label {margin-top: 8px;cursor: text;text-align: right;padding-right: 10px; font-size: 14px;font-weight: normal;}
</style>


<div class="bjui-pageContent">
	<form action="index.php?a=yes&c=system.config.msg&t=<?php echo $type;?>" class="form form-horizontal" data-reload="false" data-toggle="ajaxform" method="post" <?php if($type=='edit') { echo 'data-callback="'.$cFun.'"';}?>>
    	<input type="hidden" name="id[temp_id]" value="<?php echo $id;?>"/>
	    <div class="row cl">
			<label class="form-label col-md-3">模版名字：</label>
	    	<div class="formControls col-md-8">
	        	<input type="text" class="form-control"  name="data[temp_name]" data-rule="required" size="30" value="<?php echo C('temp_name',null,'data');?>">
	    	</div>
	    </div>
	    <div class="row cl">
			<label class="form-label col-md-3">所属模块：</label>
			<div class="formControls col-md-8">
		      	<select data-toggle="selectpicker" name="data[temp_module]" data-rule="required" >
		        <?php
				foreach ($moduleList as $k=>$v)
				{
					$checked = str::CheckElse( $k , C('temp_module',null,'data') , 'selected=""' );
	            	echo '<option value="'.$k.'" '.$checked.'>'.$v.'</option>';
	            }
	            ?>
				</select>
			</div>
		</div>
	    <div class="row cl">
			<label class="form-label col-md-3">模版标识：</label>
	    	<div class="formControls col-md-8">
	    		<input type="text" class="form-control" name="data[temp_key]" data-rule="required" size="30" value="<?php echo C('temp_key',null,'data');?>" <?php if($type=='edit' && !DEVELOPER){echo 'readonly';}?>>
	    	</div>
	    </div>
	    <div class="row cl smtp">
			<label class="form-label col-md-3">模版消息内容：</label>
	    	<div class="formControls col-md-8">
	        <?php echo Ueditor('height:150px;width:500px' , 'data[temp_content]',C('temp_content',null,'data'), 'editor.msg');?>
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
function <?php echo $cFun;?>(json){
	$(this).bjuiajax("ajaxDone",json);// 显示处理结果
    $(this).dialog("closeCurrent");//关闭
    $(this).navtab("reload",systemConfigMsgListGetOp());	// 刷新当前Tab页面
}
</script>
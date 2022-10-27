<style>
.form-horizontal .row {display: table;width: 100%;}
.form .row {margin-top: 15px;}
.form-horizontal .form-label {margin-top: 8px;cursor: text;text-align: right;padding-right: 10px; font-size: 14px;font-weight: normal;}
</style>


<div class="bjui-pageContent">
	<form action="index.php?a=yes&c=system.config.label&t=<?php echo $type;?>" class="form form-horizontal" data-reload="false" data-toggle="ajaxform" method="post" <?php if($d) { echo 'data-callback="'.$cFun.'"';}?>>
    	<input type="hidden" name="id[label_id]" value="<?php echo $id;?>"/>
	    <div class="row cl">
			<label class="form-label col-md-3">标签标题：</label>
	    	<div class="formControls col-md-8">
	        	<input type="text" class="form-control"  name="label[label_title]" value="<?php echo C('label_title',null,'data');?>">
	    	</div>
	    </div>
	    <div class="row cl">
			<label class="form-label col-md-3">标签名：</label>
	    	<div class="formControls col-md-8">
	    		<input type="text" class="form-control" name="label[label_name]" value="<?php echo C('label_name',null,'data');?>">
	    	</div>
	    </div>
	    <div class="row cl">
			<label class="form-label col-md-3">标签值：</label>
	    	<div class="formControls col-md-8">
	    		<textarea name="label[label_value]" cols="30" rows="3" data-toggle="autoheight"><?php echo str::Escape(C('label_value',null,'data'));?></textarea>
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
    $(this).navtab("reload",systemConfigLabelListGetOp());	// 刷新当前Tab页面
}
</script>
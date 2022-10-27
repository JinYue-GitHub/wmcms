<style>
.form-horizontal .row {display: table;width: 100%;}
.form .row {margin-top: 15px;}
.form-horizontal .form-label {margin-top: 8px;cursor: text;text-align: right;padding-right: 10px; font-size: 14px;font-weight: normal;}
</style>

<div class="bjui-pageContent">
	<form action="index.php?a=yes&c=system.tags.type&t=<?php echo $module.'_'.$type;?>" name="<?php echo $cFun.$type;?>Form" class="form form-horizontal" data-reload="false" data-toggle="ajaxform" method="post" data-callback="<?php echo $cFun.$type;?>">
    	<input type="hidden" name="type_id" value="<?php echo $id;?>"/>
	    <div class="row cl">
			<label class="form-label col-md-4">分类名字：</label>
	    	<div class="formControls col-md-8">
	        	<input type="text" class="form-control" placeholder="分类名字" name="data[type_name]" size="24" value="<?php echo C('type_name',null,'data');?>">
	    	</div>
	    </div>
	    <div class="row cl">
			<label class="form-label col-md-4">分类描述：</label>
	    	<div class="formControls col-md-8">
	        	<input type="text" class="form-control" placeholder="分类描述" name="data[type_desc]" size="24" value="<?php echo C('type_desc',null,'data');?>">
	    	</div>
	    </div>
	    <div class="row cl">
			<label class="form-label col-md-4">显示顺序：</label>
	    	<div class="formControls col-md-8">
	        	<input type="text" class="form-control" placeholder="数字，越小越靠前" name="data[type_order]" size="24" value="<?php echo C('type_order',null,'data');?>">
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
    	$(this).navtab("reload",systemTagsTypeListGetOp());	// 刷新当前Tab页面
	}
}
</script>
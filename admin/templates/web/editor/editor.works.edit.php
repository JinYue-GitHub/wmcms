<style>
.form-horizontal .row {display: table;width: 100%;}
.form .row {margin-top: 15px;}
.form-horizontal .form-label {margin-top: 8px;cursor: text;text-align: right;padding-right: 10px; font-size: 14px;font-weight: normal;}
</style>

<div class="bjui-pageContent">
	<form action="index.php?a=yes&c=editor.works&t=<?php echo $type;?>" name="<?php echo $cFun.$type;?>Form" class="form form-horizontal" data-reload="false" data-toggle="ajaxform" method="post" data-callback="<?php echo $cFun.$type;?>">
    	<input type="hidden" name="works_id" value="<?php echo $id;?>"/>
	    <div class="row cl">
			<label class="form-label col-md-4">作品ID：</label>
	    	<div class="formControls col-md-8">
	        	<input type="text" class="form-control" placeholder="作品的ID" name="data[works_cid]" size="24" value="<?php echo C('works_cid',null,'data');?>">
	    	</div>
	    </div>
	    <div class="row cl">
			<label class="form-label col-md-4">分组成员ID：</label>
	    	<div class="formControls col-md-8">
	        	<input type="text" class="form-control" placeholder="分组成员的ID" name="data[works_bind_id]" size="24" value="<?php echo C('works_bind_id',null,'data');?>">
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
        $(this).dialog("closeCurrent");	//关闭当前dialog
    	$(this).navtab("reload",editorWorksListGetOp());	// 刷新当前Tab页面
	}
}
</script>
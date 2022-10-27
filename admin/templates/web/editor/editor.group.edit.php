<style>
.form-horizontal .row {display: table;width: 100%;}
.form .row {margin-top: 15px;}
.form-horizontal .form-label {margin-top: 8px;cursor: text;text-align: right;padding-right: 10px; font-size: 14px;font-weight: normal;}
</style>

<div class="bjui-pageContent">
	<form action="index.php?a=yes&c=editor.group&t=<?php echo $type;?>" name="<?php echo $cFun.$type;?>Form" class="form form-horizontal" data-reload="false" data-toggle="ajaxform" method="post" data-callback="<?php echo $cFun.$type;?>">
    	<input type="hidden" name="group_id" value="<?php echo $id;?>"/>
	    <div class="row cl" style="display:none">
			<label class="form-label col-md-4">所属模块：</label>
	    	<div class="formControls col-md-8">
	        	<select data-toggle="selectpicker" name="data[group_module]">
		      	    <?php
		      	    if( $moduleArr )
		      	    {
    		      	    foreach ($moduleArr as $k=>$v)
                    	{
                    		$checked = str::CheckElse( $k , C('group_module','novel','data') , 'selected=""' );
                    		echo '<option value="'.$k.'" '.$checked.'>'.$v.'</option>';
                    	}
		      	    }
		      	    ?>
				</select>
	    	</div>
	    </div>
	    <div class="row cl">
			<label class="form-label col-md-4">分组名字：</label>
	    	<div class="formControls col-md-8">
	        	<input type="text" class="form-control" placeholder="编辑分组的名字" name="data[group_name]" size="24" value="<?php echo C('group_name',null,'data');?>">
	    	</div>
	    </div>
	    <div class="row cl">
			<label class="form-label col-md-4">分组描述：</label>
	    	<div class="formControls col-md-8">
	    	    <textarea class="form-control" 	cols="24" rows="5" placeholder="编辑分组的描述" name="data[group_desc]"><?php echo C('group_desc',null,'data');?></textarea>
	    	</div>
	    </div>
	    <div class="row cl">
			<label class="form-label col-md-4">排序：</label>
	    	<div class="formControls col-md-8">
	        	<input type="text" class="form-control" placeholder="数字，越大越靠前" name="data[group_order]" size="24" value="<?php echo C('group_order',null,'data');?>">
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
    	$(this).navtab("reload",editorGroupListGetOp());	// 刷新当前Tab页面
	}
}
</script>
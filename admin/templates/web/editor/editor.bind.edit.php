<style>
.form-horizontal .row {display: table;width: 100%;}
.form .row {margin-top: 15px;}
.form-horizontal .form-label {margin-top: 8px;cursor: text;text-align: right;padding-right: 10px; font-size: 14px;font-weight: normal;}
</style>

<div class="bjui-pageContent">
	<form action="index.php?a=yes&c=editor.bind&t=<?php echo $type;?>" name="<?php echo $cFun.$type;?>Form" class="form form-horizontal" data-reload="false" data-toggle="ajaxform" method="post" data-callback="<?php echo $cFun.$type;?>">
    	<input type="hidden" name="bind_id" value="<?php echo $id;?>"/>
	    <div class="row cl">
			<label class="form-label col-md-4">所属编辑组：</label>
	    	<div class="formControls col-md-8">
	        	<select data-toggle="selectpicker" name="data[bind_group_id]">
		      	    <?php
		      	    if( $groupArr )
		      	    {
    		      	    foreach ($groupArr as $k=>$v)
                    	{
                    		$checked = str::CheckElse( $v['group_id'] , C('bind_group_id',null,'data') , 'selected=""' );
                    		echo '<option value="'.$v['group_id'].'" '.$checked.'>ID：'.$v['group_id'].'，'.$v['group_name'].'</option>';
                    	}
		      	    }
		      	    ?>
				</select>
	    	</div>
	    </div>
	    <div class="row cl">
			<label class="form-label col-md-4">编辑类型：</label>
	    	<div class="formControls col-md-8">
	        	<select data-toggle="selectpicker" name="data[bind_type]">
		      	    <?php
		      	    foreach ($bindMod->GetType() as $k=>$v)
                	{
                		$checked = str::CheckElse( $k , C('bind_type',null,'data') , 'selected=""' );
                		echo '<option value="'.$k.'" '.$checked.'>'.$v.'</option>';
                	}
		      	    ?>
				</select>
	    	</div>
	    </div>
	    <div class="row cl">
			<label class="form-label col-md-4">编辑ID：</label>
	    	<div class="formControls col-md-8">
	        	<input type="text" class="form-control" placeholder="编辑的ID" name="data[bind_editor_id]" size="24" value="<?php echo C('bind_editor_id',null,'data');?>">
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
    	$(this).navtab("reload",editorBindListGetOp());	// 刷新当前Tab页面
	}
}
</script>
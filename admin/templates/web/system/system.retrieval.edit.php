<style>
.form-horizontal .row {display: table;width: 100%;}
.form .row {margin-top: 15px;}
.form-horizontal .form-label {margin-top: 8px;cursor: text;text-align: right;padding-right: 10px; font-size: 14px;font-weight: normal;}
</style>

<div class="bjui-pageContent">
	<form action="index.php?a=yes&c=system.retrieval&t=<?php echo $module.'_'.$type;?>" name="<?php echo $cFun.$type;?>Form" class="form form-horizontal" data-reload="false" data-toggle="ajaxform" method="post" data-callback="<?php echo $cFun.$type;?>">
    	<input type="hidden" name="retrieval_id" value="<?php echo $id;?>"/>
    	<input type="hidden" name="retrieval_module" value="<?php echo $module;?>"/>
	    <div class="row cl">
			<label class="form-label col-md-4">是否可用：</label>
			<div class="formControls col-md-8">
				<?php
				$input['type'] = 'select';
				$input['id'] = $cFun.$type.'retrieval_status';
				$input['name'] = 'data[retrieval_status]';
				$input['value'] = C('retrieval_status',null,'data');
				$input['option'] = $reMod->GetStatus();
				echo $manager->GetForm($input,null,false);
				?>
			</div>
		</div>
	    <div class="row cl">
			<label class="form-label col-md-4">条件名字：</label>
	    	<div class="formControls col-md-8">
	        	<input type="text" class="form-control" placeholder="条件的名字" name="data[retrieval_title]" size="24" value="<?php echo C('retrieval_title',null,'data');?>">
	    	</div>
	    </div>
	    <div class="row cl">
			<label class="form-label col-md-4">条件类型：</label>
	    	<div class="formControls col-md-8">
				<?php
				$input['type'] = 'select';
				$input['id'] = $cFun.$type.'retrieval_type_id';
				$input['name'] = 'data[retrieval_type_id]';
				$input['value'] = C('retrieval_type_id',null,'data');
				$input['option'] = $typeArr;
				echo $manager->GetForm($input,null,false);
				?>
	    	</div>
	    </div>
	    <div class="row cl">
			<label class="form-label col-md-4">条件字段：</label>
	    	<div class="formControls col-md-8">
	        	<input type="text" class="form-control" placeholder="条件的字段名字" name="data[retrieval_field]" size="24" value="<?php echo C('retrieval_field',null,'data');?>">
	    	</div>
	    </div>
	    <div class="row cl">
			<label class="form-label col-md-4">查询类型：</label>
	    	<div class="formControls col-md-8">
				<?php
				$input['type'] = 'select';
				$input['id'] = $cFun.$type.'retrieval_type';
				$input['name'] = 'data[retrieval_type]';
				$input['value'] = C('retrieval_type',null,'data');
				$input['option'] = $whereArr;
				echo $manager->GetForm($input,null,false);
				?>
	    	</div>
	    </div>
	    <div class="row cl">
			<label class="form-label col-md-4">条件值：</label>
	    	<div class="formControls col-md-8">
	        	<input type="text" class="form-control" placeholder="条件的字段值" name="data[retrieval_value]" size="24" value="<?php echo C('retrieval_value',null,'data');?>">
	    	</div>
	    </div>
	    <div class="row cl">
			<label class="form-label col-md-4">显示顺序：</label>
	    	<div class="formControls col-md-8">
	        	<input type="text" class="form-control" placeholder="条件显示的顺序" name="data[retrieval_order]" size="24" value="<?php echo C('retrieval_order',null,'data');?>">
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
    	$(this).navtab("reload",systemRetrievalListGetOp());	// 刷新当前Tab页面
	}
}
</script>
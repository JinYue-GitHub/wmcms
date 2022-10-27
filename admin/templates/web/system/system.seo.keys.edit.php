<style>
.form-horizontal .row {display: table;width: 100%;}
.form .row {margin-top: 15px;}
.form-horizontal .form-label {margin-top: 8px;cursor: text;text-align: right;padding-right: 10px; font-size: 14px;font-weight: normal;}
</style>

<div class="bjui-pageContent">
	<form action="index.php?a=yes&c=system.seo.keys&t=<?php echo $type;?>" class="form form-horizontal" data-reload="false" data-toggle="ajaxform" method="post" <?php if($d) { echo 'data-callback="'.$cFun.'"';}?>>
    	<input type="hidden" name="keys_id" value="<?php echo $id;?>"/>
	    <div class="row cl">
			<label class="form-label col-md-3">所属模块：</label>
			<div class="formControls col-md-8">
		      	<select data-toggle="selectpicker" readonly disabled>
		        <?php
				foreach ($moduleArr as $k=>$v)
				{
					$checked = str::CheckElse( $k , C('keys_module',null,'data') , 'selected=""' );
	            	echo '<option value="'.$k.'" '.$checked.'>'.$v.'</option>';
	            }
	            ?>
				</select>
			</div>
		</div>
	    <div class="row cl">
			<label class="form-label col-md-3">页面作用：</label>
	    	<div class="formControls col-md-8">
	    		<input type="text" class="form-control" readonly disabled size="25" value="<?php echo C('keys_pagename',null,'data');?>">
	    	</div>
	    </div>
	    <div class="row cl">
			<label class="form-label col-md-3">页面标题：</label>
	    	<div class="formControls col-md-8">
	        	<input type="text" class="form-control" name="keys_title" size="40" value="<?php echo C('keys_title',null,'data');?>">
	    	</div>
	    </div>
	    <div class="row cl">
			<label class="form-label col-md-3">关键词：</label>
	    	<div class="formControls col-md-8">
	        	<input type="text" class="form-control" name="keys_key" size="40" value="<?php echo C('keys_key',null,'data');?>">
	    	</div>
	    </div>
	    <div class="row cl">
			<label class="form-label col-md-3">页面描述：</label>
	    	<div class="formControls col-md-8">
	        	<input type="text" class="form-control" name="keys_desc" size="40" value="<?php echo C('keys_desc',null,'data');?>">
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
    $(this).navtab("reload",systemSeoKeysGetOp());	// 刷新当前Tab页面
}
</script>
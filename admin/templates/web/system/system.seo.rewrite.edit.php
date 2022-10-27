<style>
.form-horizontal .row {display: table;width: 100%;}
.form .row {margin-top: 15px;}
.form-horizontal .form-label {margin-top: 8px;cursor: text;text-align: right;padding-right: 10px; font-size: 14px;font-weight: normal;}
</style>

<div class="bjui-pageContent">
	<form action="index.php?a=yes&c=system.seo.rewrite&t=<?php echo $type;?>" class="form form-horizontal" data-reload="false" data-toggle="ajaxform" method="post" <?php if($d) { echo 'data-callback="'.$cFun.'"';}?>>
    <input type="hidden" name="urls_id" value="<?php echo $id;?>"/>
	    <div class="row cl">
			<label class="form-label col-md-3">所属模块：</label>
			<div class="formControls col-md-8">
		      	<select data-toggle="selectpicker" readonly disabled>
		        <?php
				foreach ($moduleArr as $k=>$v)
				{
					$checked = str::CheckElse( $k , C('urls_module',null,'data') , 'selected=""' );
	            	echo '<option value="'.$k.'" '.$checked.'>'.$v.'</option>';
	            }
	            ?>
				</select>
			</div>
		</div>
	    <div class="row cl">
			<label class="form-label col-md-3">页面作用：</label>
	    	<div class="formControls col-md-8">
	    		<input type="text" class="form-control" readonly disabled size="25" value="<?php echo C('urls_pagename',null,'data');?>">
	    	</div>
	    </div>
	    <div class="row cl">
			<label class="form-label col-md-3">传统动态地址：</label>
	    	<div class="formControls col-md-8">
	    		<input type="text" class="form-control" readonly disabled size="40" value="<?php echo C('urls_url1',null,'data');?>">
	    	</div>
	    </div>
	    <div class="row cl">
			<label class="form-label col-md-3">传统伪静态地址：</label>
	    	<div class="formControls col-md-8">
	        	<input type="text" class="form-control" name="urls_url2" size="40" value="<?php echo C('urls_url2',null,'data');?>">
	    	</div>
	    </div>
	    <div class="row cl">
			<label class="form-label col-md-3">普通模式地址：</label>
	    	<div class="formControls col-md-8">
	        	<input type="text" class="form-control" name="urls_url3" size="40" value="<?php echo C('urls_url3',null,'data');?>">
	    	</div>
	    </div>
	    <div class="row cl">
			<label class="form-label col-md-3">兼容模式地址：</label>
	    	<div class="formControls col-md-8">
	        	<input type="text" class="form-control" name="urls_url4" size="40" value="<?php echo C('urls_url4',null,'data');?>">
	    	</div>
	    </div>
	    <div class="row cl">
			<label class="form-label col-md-3">PATHINFO模式：</label>
	    	<div class="formControls col-md-8">
	        	<input type="text" class="form-control" name="urls_url5" size="40" value="<?php echo C('urls_url5',null,'data');?>">
	    	</div>
	    </div>
	    <div class="row cl">
			<label class="form-label col-md-3">REWRITE模式：</label>
	    	<div class="formControls col-md-8">
	        	<input type="text" class="form-control" name="urls_url6" size="40" value="<?php echo C('urls_url6',null,'data');?>">
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

	var op = systemSeoRewriteGetOp();
	op['id'] = 'seo-rewrite';
    $(this).navtab("reload",op);	// 刷新当前Tab页面
}
</script>
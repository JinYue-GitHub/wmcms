<style>
.form-horizontal .row {display: table;width: 100%;}
.form .row {margin-top: 15px;}
.form-horizontal .form-label {margin-top: 8px;cursor: text;text-align: right;padding-right: 10px; font-size: 14px;font-weight: normal;}
</style>


<div class="bjui-pageContent">
	<form action="index.php?a=yes&c=author.level&t=add" class="form form-horizontal" data-reload="false" data-toggle="validate" method="post" data-callback="<?php echo $cFun;?>">
	    <div class="row cl">
			<label class="form-label col-md-4">等级模块：</label>
			<div class="formControls col-md-8">
		      	<select data-toggle="selectpicker" name="level[level_module]">
		        <?php
				foreach ($lvModule as $k=>$v)
				{
	            	echo '<option value="'.$k.'">'.$v.'</option>';
	            }
	            ?>
				</select>
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-md-4">等级名字：</label>
			<div class="formControls col-md-8">
		      	<input type="text" value="" name="level[level_name]" class="form-control" data-rule="required">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-md-4">等级开始经验：</label>
			<div class="formControls col-md-8">
		      	<input type="text" value="" name="level[level_start]" class="form-control" data-rule="required;digits">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-md-4">等级结束经验：</label>
			<div class="formControls col-md-8">
		      	<input type="text" value="" name="level[level_end]" class="form-control" data-rule="required;digits">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-md-4">等级排序：</label>
			<div class="formControls col-md-8">
		      	<input type="text" value="0" name="level[level_order]" class="form-control" data-rule="required;digits">
			</div>
		</div>
	</div>
	<div class="bjui-pageFooter">
	    <ul>
	        <li><button type="button" class="btn-close btn btn-danger size-MINI radius"><i class="fa fa-times"></i> 关闭</button></li>
	        <li><button type="submit" class="btn btn-success size-MINI radius"><i class="fa fa-floppy-o"></i> 提交</button></li>
	    </ul>
	</div>
	</form>
</div>
<script type="text/javascript">
function <?php echo $cFun;?>(json){
	$(this).bjuiajax("ajaxDone",json);// 显示处理结果
    $(this).dialog("closeCurrent");//关闭
    $(this).navtab("reload");	// 刷新当前Tab页面
}
</script>
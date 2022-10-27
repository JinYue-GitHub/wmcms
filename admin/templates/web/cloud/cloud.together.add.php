<style>
.form-horizontal .row {display: table;width: 100%;}
.form .row {margin-top: 15px;}
.form-horizontal .form-label {margin-top: 8px;cursor: text;text-align: right;padding-right: 10px; font-size: 14px;font-weight: normal;}
</style>

<div class="bjui-pageContent">
	<form action="index.php?a=yes&c=cloud.together&t=add" class="form form-horizontal" data-reload="false" data-toggle="validate" method="post" data-callback="<?php echo $cFun;?>">
	    <div class="row cl">
			<label class="form-label col-md-3">公开域名：</label>
			<div class="formControls col-md-8">
		      	<select data-toggle="selectpicker" name="domainshow">
	            	<option value="1">公开</option>
	            	<option value="0">不公开</option>
				</select>
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-md-3">需求标题：</label>
			<div class="formControls col-md-8">
		      	 <input type="text" name="title" placeholder="请输入您的需求标题！" data-rule="required" size="30">
			</div>
		</div>
	    <div class="row cl">
			<label class="form-label col-md-3">需求详情：</label>
	    	<div class="formControls col-md-8">
	        	<textarea class="form-control" placeholder="请输入您的需求功能详细介绍！" name="desc" cols="30" rows="8" data-rule="required"></textarea>
	    	</div>
	    </div>
	</div>
	</form>
	<div class="bjui-pageFooter">
	    <ul>
	        <li><button type="button" class="btn-close btn btn-danger size-MINI radius"><i class="fa fa-times"></i> 关闭</button></li>
	        <li><button type="submit" class="btn btn-success size-MINI radius"><i class="fa fa-floppy-o"></i> 提交</button></li>
	    </ul>
	</div>
</div>
<script type="text/javascript">
function <?php echo $cFun;?>(json){
	$(this).bjuiajax("ajaxDone",json);// 显示处理结果
    $(this).dialog("closeCurrent");//关闭
    $(this).navtab("reload");	// 刷新当前Tab页面
}
</script>
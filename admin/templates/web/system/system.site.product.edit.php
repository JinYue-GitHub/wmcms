<style>
.form-horizontal .row {display: table;width: 100%;}
.form .row {margin-top: 15px;}
.form-horizontal .form-label {margin-top: 8px;cursor: text;text-align: right;padding-right: 10px; font-size: 14px;font-weight: normal;}
</style>

<div class="bjui-pageContent">
	<form action="index.php?a=yes&c=system.site.product&t=<?php echo $type;?>" name="<?php echo $cFun.$type;?>Form" class="form form-horizontal" data-reload="false" data-toggle="ajaxform" method="post" data-callback="<?php echo $cFun.$type;?>">
    	<input type="hidden" name="product_id" value="<?php echo $id;?>"/>
	    <div class="row cl">
			<label class="form-label col-md-4">是否可用：</label>
			<div class="formControls col-md-8">
		      	<select data-toggle="selectpicker" name="data[product_status]">
		        <option value="1" <?php if(C('product_status',null,'data')=='1'){ echo 'selected=""';}?>>正常</option>
		        <option value="0" <?php if(C('product_status',null,'data')=='0'){ echo 'selected=""';}?>>禁用</option>
				</select>
			</div>
		</div>
	    <div class="row cl">
			<label class="form-label col-md-4">站点名字：</label>
	    	<div class="formControls col-md-8">
	        	<input type="text" class="form-control" placeholder="站点的名字" name="data[product_title]" size="24" value="<?php echo C('product_title',null,'data');?>">
	    	</div>
	    </div>
	    <div class="row cl">
			<label class="form-label col-md-4">站点域名：</label>
	    	<div class="formControls col-md-8">
	    		<input type="text" class="form-control" placeholder="网站的域名" name="data[product_domain]" size="24" value="<?php echo C('product_domain',null,'data');?>">
	    	</div>
	    </div>
	    <div class="row cl">
			<label class="form-label col-md-4">后台文件夹：</label>
	    	<div class="formControls col-md-8">
	    		<input type="text" class="form-control" placeholder="后台管理的文件夹名字" name="data[product_admin]" size="24" value="<?php echo C('product_admin',null,'data');?>">
	    	</div>
	    </div>
	    <div class="row cl">
			<label class="form-label col-md-4">登录账号：</label>
	    	<div class="formControls col-md-8">
	    		<input type="text" class="form-control" placeholder="登录管理员的账号" name="data[product_name]" size="24" value="<?php echo C('product_name',null,'data');?>">
	    	</div>
	    </div>
	    <div class="row cl">
			<label class="form-label col-md-4">登录密码：</label>
	    	<div class="formControls col-md-8">
	    		<input type="text" class="form-control" placeholder="登录管理员的密码" name="data[product_psw]" size="24" value="<?php echo C('product_psw',null,'data');?>">
	    	</div>
	    </div>
	    <div class="row cl">
			<label class="form-label col-md-4">显示顺序：</label>
	    	<div class="formControls col-md-8">
	        	<input type="text" class="form-control" placeholder="站点显示的顺序" name="data[product_order]" size="24" value="<?php echo C('product_order',null,'data');?>">
	    	</div>
	    </div>
	</div>
	</form>
	<div class="bjui-pageFooter">
	    <ul>
	        <li><button type="button" class="btn-close btn btn-danger size-MINI radius"><i class="fa fa-times"></i> 关闭</button></li>
	        <li><button type="submit" class="btn btn-success size-MINI radius"><i class="fa fa-floppy-o"></i> 保存</button></li>
	        <li><button type="button" id="<?php echo $cFun.$type;?>test" class="btn btn-primary size-MINI radius"><i class="fa fa-exchange"></i> 测试通讯</button></li>
	    </ul>
	</div>
</div>
<script type="text/javascript">
function <?php echo $cFun.$type;?>(json){
	$(this).bjuiajax("ajaxDone",json);// 显示处理结果
	if(json.statusCode==200){
    	$(this).navtab("reload",systemSiteProductGetOp());	// 刷新当前Tab页面
	}
}

$(document).ready(function(){
	$('#<?php echo $cFun.$type;?>test').click(function() {
		var ajaxOptions=new Array();
		var ajaxData=new Object();
		ajaxData = $("[name=<?php echo $cFun.$type;?>Form]").serializeArray();
		
		ajaxOptions['type'] = 'POST';
		ajaxOptions['data'] = ajaxData;
		ajaxOptions['url'] = "index.php?a=yes&c=system.site.product&t=test";
		ajaxOptions['loadingmask'] = 'true';
		ajaxOptions['reloadNavtab'] = 'false';
		$(this).bjuiajax('doAjax', ajaxOptions);
	});
});
</script>
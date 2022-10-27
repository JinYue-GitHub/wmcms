<style>
.form-horizontal .row {display: table;width: 100%;}
.form .row {margin-top: 15px;}
.form-horizontal .form-label {margin-top: 8px;cursor: text;text-align: right;padding-right: 10px; font-size: 14px;font-weight: normal;}
</style>

<div class="bjui-pageContent">
	<form action="index.php?a=yes&c=system.site.site&t=<?php echo $type;?>" name="<?php echo $cFun.$type;?>Form" class="form form-horizontal" data-reload="false" data-toggle="ajaxform" method="post" data-callback="<?php echo $cFun.$type;?>">
    	<input type="hidden" name="site_id" value="<?php echo $id;?>"/>
	    <div class="row cl">
			<label class="form-label col-md-4">是否可用：</label>
			<div class="formControls col-md-8">
		      	<select data-toggle="selectpicker" name="data[site_status]">
		        <option value="1" <?php if(C('site_status',null,'data')=='1'){ echo 'selected=""';}?>>正常</option>
		        <option value="0" <?php if(C('site_status',null,'data')=='0'){ echo 'selected=""';}?>>禁用</option>
				</select>
			</div>
		</div>
	    <div class="row cl">
			<label class="form-label col-md-4">站点名字：</label>
	    	<div class="formControls col-md-8">
	        	<input type="text" class="form-control" placeholder="站点的名字" name="data[site_title]" size="24" value="<?php echo C('site_title',null,'data');?>">
	    	</div>
	    </div>
	    <div class="row cl">
			<label class="form-label col-md-4">域名类型：</label>
	    	<div class="formControls col-md-8">
			<?php
			$input['type'] = 'select';
			$input['id'] = $cFun.$type.'domain_type';
			$input['name'] = 'data[site_domain_type]';
			$input['value'] = C('site_domain_type',null,'data');
			$input['option'] = $siteSer->domainType;
			echo $manager->GetForm($input,null,false);
			?>
	    	</div>
	    </div>
	    <div class="row cl">
			<label class="form-label col-md-4">站点域名：</label>
	    	<div class="formControls col-md-8">
	    		<input type="text" id="<?php echo $cFun.$type.'domain';?>" class="form-control" placeholder="网站的域名" name="data[site_domain]" size="24" value="<?php echo C('site_domain',null,'data');?>">
	    	</div>
			<div class="form-label" style="font-size: 12px;clear:both;color: #a2a0a0;">泛解析域名直接填写顶级域名即可，如：http://weimengcms.com</div>
	    </div>
	    <div class="row cl">
			<label class="form-label col-md-4">数据类型：</label>
	    	<div class="formControls col-md-8">
			<?php
			$input['type'] = 'select';
			$input['id'] = $cFun.$type.'type';
			$input['name'] = 'data[site_type]';
			$input['value'] = C('site_type',null,'data');
			$input['option'] = $siteSer->type;
			echo $manager->GetForm($input,null,false);
			?>
	    	</div>
	    </div>
	    <div class="row cl">
			<label class="form-label col-md-4">模版文件夹：</label>
	    	<div class="formControls col-md-8">
	    		<input type="text" class="form-control" id="<?php echo $cFun.$type.'template';?>" placeholder="模版的文件夹名字" name="data[site_template]" size="24" value="<?php echo C('site_template',null,'data');?>">
	    	</div>
	    	<div class="form-label" style="font-size: 12px;clear:both;color: #a2a0a0;">如果是泛解析，模版文件夹以 *.weimengcms.com命名</div>
	    </div>
	    <div class="row cl">
			<label class="form-label col-md-4">显示顺序：</label>
	    	<div class="formControls col-md-8">
	        	<input type="text" class="form-control" placeholder="站点显示的顺序" name="data[site_order]" size="24" value="<?php echo C('site_order',null,'data');?>">
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
    	$(this).navtab("reload",systemSiteSiteGetOp());	// 刷新当前Tab页面
	}
}
$(document).ready(function(){
	//域名类型选择
	/*$("#<?php echo $cFun.$type;?>domain_type").change(function(){
		if($(this).val()==2){
			$("#<?php echo $cFun.$type;?>type").selectpicker('val',2);
		}
	});
	//类型选择
	$("#<?php echo $cFun.$type;?>type").change(function(){
		if($(this).val()!=2 && $("#<?php echo $cFun.$type;?>domain_type").val()==2)
		{
			$("#<?php echo $cFun.$type;?>domain_type").selectpicker('val',1);
		}
	});*/
	//域名输入
	$("#<?php echo $cFun.$type;?>domain").keyup(function(){
		var thisDomain = $(this).val();
		thisDomain = thisDomain.replace(/(http|ftp|https):\/\//i, '');
		$("#<?php echo $cFun.$type;?>template").val(thisDomain);
	});
});
</script>
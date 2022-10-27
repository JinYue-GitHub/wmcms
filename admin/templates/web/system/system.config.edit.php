<style>
.form-horizontal .row {display: table;width: 100%;}
.form .row {margin-top: 15px;}
.form-horizontal .form-label {margin-top: 8px;cursor: text;text-align: right;padding-right: 10px; font-size: 14px;font-weight: normal;}
</style>


<div class="bjui-pageContent">
	<form action="index.php?a=yes&c=system.config.config&t=<?php echo $type;?>" class="form form-horizontal" data-reload="false" data-toggle="ajaxform" method="post" <?php if($type=='edit') { echo 'data-callback="'.$cFun.'"';}?>>
    	<input type="hidden" name="id[config_id]" value="<?php echo $id;?>"/>
	    <div class="row cl">
			<label class="form-label col-md-3">显示状态：</label>
			<div class="formControls col-md-8">
		      	<select data-toggle="selectpicker" name="data[config_status]" <?php if($type=='edit' && !DEVELOPER){echo 'readonly disabled';}?>>
		        <option value="1" <?php if(C('config_status',null,'data')=='1'){ echo 'selected=""';}?>>显示</option>
		        <option value="0" <?php if(C('config_status',null,'data')=='0'){ echo 'selected=""';}?>>隐藏</option>
				</select>
			</div>
		</div>
	    <div class="row cl">
			<label class="form-label col-md-3">所属分组：</label>
			<div class="formControls col-md-8">
		      	<select data-toggle="selectpicker" name="data[group_id]" <?php if($type=='edit' && !DEVELOPER){echo 'readonly disabled';}?>>
		        <?php
				foreach ($groupArr as $k=>$v)
				{
					$checked = str::CheckElse( $v['group_id'] , C('config_group',null,'data') , 'selected=""' );
	            	echo '<option value="'.$v['group_id'].'" '.$checked.'>'.$v['group_remark'].'</option>';
	            }
	            ?>
				</select>
			</div>
		</div>
	    <div class="row cl">
			<label class="form-label col-md-3">所属模块：</label>
			<div class="formControls col-md-8">
		      	<select data-toggle="selectpicker" name="data[config_module]" <?php if($type=='edit' && !DEVELOPER){echo 'readonly disabled';}?>>
		        <?php
				foreach ($moduleArr as $k=>$v)
				{
					$checked = str::CheckElse( $k , C('config_module',null,'data') , 'selected=""' );
	            	echo '<option value="'.$k.'" '.$checked.'>'.$v.'</option>';
	            }
	            ?>
				</select>
			</div>
		</div>
	    <div class="row cl">
			<label class="form-label col-md-3">参数名：</label>
	    	<div class="formControls col-md-8">
	        	<input type="text" class="form-control"  name="data[config_name]" <?php if($type=='edit' && !DEVELOPER){echo 'readonly disabled';}?> size="30" value="<?php echo C('config_name',null,'data');?>">
	    	</div>
	    </div>
	    <div class="row cl">
			<label class="form-label col-md-3">参数标题：</label>
	    	<div class="formControls col-md-8">
	    		<input type="text" class="form-control" name="data[config_title]" size="30" value="<?php echo C('config_title',null,'data');?>">
	    	</div>
	    </div>
	    <div class="row cl">
			<label class="form-label col-md-3">参数值：</label>
	    	<div class="formControls col-md-8">
	    		<textarea name="data[config_value]" style="width: 300px;" data-toggle="autoheight"><?php echo str::Escape(C('config_value',null,'data'));?></textarea>
	    	</div>
	    </div>
	    <div class="row cl">
			<label class="form-label col-md-3">表单类型：</label>
			<div class="formControls col-md-8">
		      	<select data-toggle="selectpicker" name="data[config_formtype]" <?php if($type=='edit' && !DEVELOPER){echo 'readonly disabled';}?>>
		        <?php
				foreach ($FromArr as $k=>$v)
				{
					$checked = str::CheckElse( $k , C('config_formtype',null,'data') , 'selected=""' );
	            	echo '<option value="'.$k.'" '.$checked.'>'.$v.'</option>';
	            }
	            ?>
				</select>
			</div>
		</div>
	    <div class="row cl">
			<label class="form-label col-md-3">备注：</label>
	    	<div class="formControls col-md-8">
	        	<input type="text" class="form-control" name="data[config_remark]" size="30" value="<?php echo C('config_remark',null,'data');?>">
	    	</div>
	    </div>
	    <div class="row cl">
			<label class="form-label col-md-3">显示顺序：</label>
	    	<div class="formControls col-md-8">
	        	<input type="text" class="form-control"  name="data[config_order]" size="30" value="<?php echo C('config_order',null,'data');?>">
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
    $(this).navtab("reload",systemConfigListGetOp());	// 刷新当前Tab页面
}
</script>
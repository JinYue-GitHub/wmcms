<div class="bjui-pageContent">
<form action="index.php?a=yes&c=system.templates.templates&t=<?php echo $type;?>" data-reload="false" data-toggle="validate" method="post" data-callback="<?php echo $cFun;?>">
<input name="id[temp_id]" type="hidden" class="input-text" value="<?php echo $id;?>">

<table class="table table-border table-bordered table-bg table-sort" id="<?php echo $cFun;?>Table">
    <tr>
      <td valign="top" width="150"><b>所属模块：</b></td>
      <td valign="top">
        <select data-toggle="selectpicker" id="<?php echo $cFun.$module;?>temp_module" name="temp[temp_module]" data-rule="required" data-msg-required="请选择" data-nextselect="#<?php echo $cFun.$module;?>temp_type" data-refurl="index.php?a=yes&c=system.templates.templates&t=gettype&val={value}">
        <option value="">请选择模块</option>
        <?php
			foreach ($moduleArr as $k=>$v)
			{
				$checked = str::CheckElse( $k , $module , 'selected=""' );
            	echo '<option value="'.$k.'" '.$checked.'>'.$v.'</option>';
            }
         ?>
		</select>
      </td>
	</tr>
    <tr>
      <td valign="top"><b>模版页面：</b></td>
      <td valign="top">
       <select data-toggle="selectpicker" name="temp[temp_type]" id="<?php echo $cFun.$module;?>temp_type" data-rule="required" data-msg-required="请选择">
        <option value="">请选择页面</option>
        <?php
        if( $module != '' || $type == "edit" )
        {
			foreach ($tempTypeArr[$module] as $k=>$v)
			{
				$checked = str::CheckElse( $k , $page , 'selected=""' );
            	echo '<option value="'.$k.'" '.$checked.'>'.$v.'</option>';
            }
		}
        ?>
       </select>
      </td>
	</tr>
    <tr>
      <td valign="top"><b>模版位置：</b></td>
      <td valign="top">
       <select data-toggle="selectpicker" name="temp[temp_address]" id="<?php echo $cFun.$module;?>temp_address" data-rule="required" data-msg-required="请选择">
        <option value="">请选择</option>
        <?php
		foreach ($tempAddressArr as $k=>$v)
		{
			$checked = str::CheckElse( $k , C('temp_address',null,'data') , 'selected=""' );
           	echo '<option value="'.$k.'" '.$checked.'>'.$v.'</option>';
        }
        ?>
       </select>
      </td>
	</tr>
    <tr>
      <td valign="top"><b>模版名字：</b></td>
      <td valign="top"><input name="temp[temp_name]" data-rule="required" type="text" class="input-text" value="<?php echo C('temp_name',null,'data');?>"></td>
	</tr>
    <tr>
      <td valign="top"><b>电脑版路径：</b></td>
      <td valign="top">
      	<input name="temp[temp_temp4]" type="text" class="input-text address" value="<?php echo C('temp_temp4',null,'data');?>">
      	<span class="upload" data-uploader="index.php?a=yes&c=upload&t=templates" data-on-upload-success="<?php echo $cFun;?>upload_success" data-file-type-exts="*.html" data-toggle="upload" data-icon="cloud-upload"></span>
      </td>
	</tr>
    <tr>
      <td valign="top"><b>触屏版路径：</b></td>
      <td valign="top">
      	<input name="temp[temp_temp3]" type="text" class="input-text address" value="<?php echo C('temp_temp3',null,'data');?>">
        <span class="upload" data-uploader="index.php?a=yes&c=upload&t=templates" data-on-upload-success="<?php echo $cFun;?>upload_success" data-file-type-exts="*.html" data-toggle="upload" data-icon="cloud-upload"></span>
      </td>
	</tr>
    <tr>
      <td valign="top"><b>3G版路径：</b></td>
      <td valign="top">
      	<input name="temp[temp_temp2]" type="text" class="input-text address" value="<?php echo C('temp_temp2',null,'data');?>">
      	<span class="upload" data-uploader="index.php?a=yes&c=upload&t=templates" data-on-upload-success="<?php echo $cFun;?>upload_success" data-file-type-exts="*.html" data-toggle="upload" data-icon="cloud-upload"></span>
      </td>
	</tr>
    <tr>
      <td valign="top"><b>简洁版路径：</b></td>
      <td valign="top">
      	<input name="temp[temp_temp1]" type="text" class="input-text address" value="<?php echo C('temp_temp1',null,'data');?>">
      	<span class="upload" data-uploader="index.php?a=yes&c=upload&t=templates" data-on-upload-success="<?php echo $cFun;?>upload_success" data-file-type-exts="*.html" data-toggle="upload" data-icon="cloud-upload"></span>
      </td>
	</tr>
    <tr>
      <td valign="top" colspan="2"><button type="submit" class="btn-green" data-icon="save">提交</button></td>
	</tr>
</table>
</form>
</div>


<div class="bjui-pageFooter">
    <ul>
        <li><button type="button" class="btn-close" data-icon="close">取消</button></li>
        <li><button type="submit" class="btn-green" data-icon="save">保存</button></li>
    </ul>
</div>


<script type="text/javascript">
//上传模版成功后
function <?php echo $cFun;?>upload_success(file,json,$element){
	json = $.parseJSON(json);
	if ( json.statusCode == 300){
		$(this).bjuiajax("ajaxDone",json);// 显示处理结果
	}else{
		val = json.data.path.replace('../',"/");
		$element.siblings('input').val(val);
	}
}


//保存模版成功带回到当前的tab
function <?php echo $cFun;?>(json){
	if ( json.statusCode == 300){
		$(this).bjuiajax("ajaxDone",json);// 显示处理结果
	}else{
		<?php
		if($module != '' && $name != '') {
			$filedName = str::CheckElse($reName, '', 'type', $reName);
		?>
			$('#<?php echo $filedName.'_'.$tid;?>').val(json.data.id);
			$('#temp_<?php echo $name;?>').val(json.data.name);
		    $(this).dialog("closeCurrent");//关闭
		<?php
		}
		else if( $d )
		{
		?>
			$(this).bjuiajax("ajaxDone",json);// 显示处理结果
		    $(this).dialog("closeCurrent");//关闭
		    $(this).navtab("reload",systemTemplatesListGetOp());	// 刷新当前Tab页面
		<?php
		}
		?>
	}
}


//选择的是当前就禁止上传
$(document).ready(function(){
	$("#<?php echo $cFun.$module;?>temp_address").change(function(){
		if( $(this).val() == '0' ){
			$("#<?php echo $cFun;?>Table .bjui-upload-select").attr('disabled','true');
		}else{
			$("#<?php echo $cFun;?>Table .bjui-upload-select").removeAttr('disabled');
		}
	});	
	$("#<?php echo $cFun;?>Table .address").keyup(function(){
		if( $("#<?php echo $cFun.$module;?>temp_address").val() == '0'){
			$("#<?php echo $cFun;?>Table .address").val($(this).val());
		}
	});
});
</script>
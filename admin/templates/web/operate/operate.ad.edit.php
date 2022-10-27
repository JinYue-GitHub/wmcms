<div class="bjui-pageContent">
	<form action="index.php?a=yes&c=operate.ad&t=<?php echo $type;?>" data-reload="false" data-toggle="validate" method="post" <?php if($d) { echo 'data-callback="'.$cFun.'"';}?>>
	<input name="id[ad_id]" type="hidden" class="input-text" value="<?php echo $id;?>">
		<fieldset>
			<legend>广告编辑</legend>
        	<table class="table table-condensed table-hover" width="100%">
            <tbody>
                <tr>
                    <td>
                        <b>广告分类：</b>
                        <select data-toggle="selectpicker" name="ad[ad_type_id]">
                        <option value="0">请选择分类</option>
                       	<?php 
                       	if($typeIdArr)
                       	{
	                       	foreach ($typeIdArr as $k=>$v)
	                       	{
	                       		$select = str::CheckElse($v['type_id'], C('ad_type_id',null,'data') , 'selected=""');
	                       		echo '<option value="'.$v['type_id'].'" '.$select.'>'.$v['type_name'].'</option>';
	                       	}
                       	}
                       	?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>广告位名：</b>
                        <input type="text" name="ad[ad_name]" value="<?php echo C('ad_name',null,'data');?>" data-rule="required">
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>投放时间：</b>
                       	<input type="radio" name="ad[ad_time_type]" value="0" data-toggle="icheck" data-label="不限时间" <?php echo str::CheckElse('0', C('ad_time_type',null,'data') , 'checked');?>>
                       	<input type="radio" name="ad[ad_time_type]" value="1" data-toggle="icheck" data-label="限时投放" <?php echo str::CheckElse('1', C('ad_time_type',null,'data') , 'checked');?>>
                       	<span id="<?php echo $type.$cFun;?>adTime" <?php if(C('ad_time_type',null,'data') != '1'){ echo 'style="display:none"';}?>>
                       	开始时间：<input type="text" name="ad[ad_start_time]" value="<?php echo date('Y-m-d H:i:s',C('ad_start_time',null,'data'));?>" data-toggle="datepicker" data-pattern="yyyy-MM-dd H:m:s" size="19">
                       	结束时间：<input type="text" name="ad[ad_end_time]" value="<?php echo date('Y-m-d H:i:s',C('ad_end_time',null,'data'));?>" data-toggle="datepicker" data-pattern="yyyy-MM-dd H:m:s" size="19">
                       	</span>
		             </td>
                </tr>
                <tr>
                    <td>
                        <b>广告状态：</b>
                        <select data-toggle="selectpicker" name="ad[ad_status]">
                       	<?php 
                       	foreach ($statusArr as $k=>$v)
                       	{
                       		$select = str::CheckElse($k, C('ad_status',null,'data') , 'selected=""');
                       		echo '<option value="'.$k.'" '.$select.'>'.$v.'</option>';
                       	}
                       	?>
                        </select>
		             </td>
                </tr>
                <tr>
                    <td>
                        <b>广告平台：</b>
                       	<?php 
                       	foreach ($ptArr as $k=>$v)
                       	{
                       		$select = str::CheckElse($k, C('ad_pt',null,'data') , 'checked');
                       		echo '<input type="radio" name="ad[ad_pt]" value="'.$k.'" data-toggle="icheck" data-label="'.$v.'" '.$select.'>';
                       	}
                       	?>
		             </td>
                </tr>
                <tr>
                    <td>
                        <b>广告单价：</b>
                        <input type="text" name="ad[ad_price]" value="<?php echo C('ad_price',null,'data');?>" size="10">(单位:元)
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>广告类型：</b>
                        <select data-toggle="selectpicker" id="<?php echo $type?>ad_type" name="ad[ad_type]">
                       	<?php 
                       	foreach ($adArr as $k=>$v)
                       	{
                       		$select = str::CheckElse($k, C('ad_type',null,'data') , 'selected=""');
                       		echo '<option value="'.$k.'" '.$select.'>'.$v.'</option>';
                       	}
                       	?>
                        </select>
		             </td>
                </tr>
                
                <tr class="<?php echo $type;?>ad_txt_tr" <?php if(C('ad_type',null,'data') != '1'){ echo 'style="display:none"';}?>>
                    <td>
                        <b>广告标题：</b>
                        <input type="text" name="ad[ad_title]" value="<?php echo C('ad_title',null,'data');?>" size="40">
                    </td>
                </tr>
                <tr class="<?php echo $type;?>ad_txt_img_url" <?php if(C('ad_type',null,'data') == '3'){ echo 'style="display:none"';}?>>
                    <td>
                        <b>广告链接：</b>
                        <input type="text" name="ad[ad_url]" value="<?php echo C('ad_url',null,'data');?>" size="40">
                    </td>
                </tr>
                <tr class="<?php echo $type;?>ad_img_tr" <?php if(C('ad_type',null,'data') != '2'){ echo 'style="display:none"';}?>>
                   <td>
                        <b>广告图片：</b>
                        <input type="text" name="ad[ad_img]" value="<?php echo C('ad_img',null,'data');?>" id="<?php echo $type?>ad_simg" size="40">
						<span class="upload" data-uploader="index.php?a=yes&c=upload&t=img" data-on-upload-success="<?php echo $cFun;?>upload_success" data-file-type-exts="*.jpg;*.png;*.gif;*.mpg" data-toggle="upload" data-icon="cloud-upload"></span>
						<?php if( C('ad_img',null,'data') != '' ){ echo '<a target="_blank" href="'.C('ad_img',null,'data').'">点击查看图片</a>';}?>
                    </td>
                </tr>
                <tr class="<?php echo $type;?>ad_img_tr" <?php if(C('ad_type',null,'data') != '2'){ echo 'style="display:none"';}?>>
                    <td>
                        <b>图片尺寸：</b>
                        <input type="text" name="ad[ad_img_width]" value="<?php echo C('ad_img_width',null,'data');?>" size="10"> *
                        <input type="text" name="ad[ad_img_height]" value="<?php echo C('ad_img_height',null,'data');?>" size="10">
                    </td>
                </tr>
                
	            <tr class="<?php echo $type;?>ad_js_tr" <?php if(C('ad_type',null,'data') != '3'){ echo 'style="display:none"';}?>>
                    <td>
                        <b>J S代码：</b>
                        <textarea name="ad[ad_js]" id="<?php echo $type;?>ad_js"><?php echo C('ad_js',null,'data');?></textarea>
                    </td>
                </tr>
            </tbody>
        </table>
		</fieldset>
	</form>
</div>

<div class="bjui-pageFooter">
    <ul>
        <li><button type="button" class="btn-close" data-icon="close">取消</button></li>
        <li><button type="submit" class="btn-green" data-icon="save">保存</button></li>
    </ul>
</div>


<script>
//页面唯一回调函数
function <?php echo $cFun;?>(json){
	var op = operateAdListGetOp();
	var tabid = 'ad-list';
	op['id'] = tabid;
	
	$(this).bjuiajax("ajaxDone",json);// 显示处理结果
    $(this).navtab("reload",op);	// 刷新Tab页面
    $(this).navtab("switchTab",tabid);	// 切换Tab页面
}
//上传封面
function <?php echo $cFun;?>upload_success(file,json,$element){
	json = $.parseJSON(json);
	if ( json.statusCode == 300){
		$(this).bjuiajax("ajaxDone",json);// 显示处理结果
	}else{
		val = json.data.path.replace('../',"/");
		$("#<?php echo $type?>ad_simg").val(val);
	}
}


$(document).ready(function(){
	$('#<?php echo $type;?>ad_js').css("width",'500px');
	$('#<?php echo $type;?>ad_js').css("height",'200px');
	
	//广告类型选择
	$("#<?php echo $type?>ad_type").change(function(){
		var type = $(this).val();
		if( type == '1' ){
			$('.<?php echo $type;?>ad_txt_tr').css("display",'');
			$('.<?php echo $type;?>ad_txt_img_url').css("display",'');
			$('.<?php echo $type;?>ad_js_tr').css("display",'none');
			$('.<?php echo $type;?>ad_img_tr').css("display",'none');
		}else if( type == '2' ){
			$('.<?php echo $type;?>ad_img_tr').css("display",'');
			$('.<?php echo $type;?>ad_txt_img_url').css("display",'');
			$('.<?php echo $type;?>ad_js_tr').css("display",'none');
			$('.<?php echo $type;?>ad_txt_tr').css("display",'none');
		}else if( type == '3' ){
			$('.<?php echo $type;?>ad_js_tr').css("display",'');
			$('.<?php echo $type;?>ad_txt_tr').css("display",'none');
			$('.<?php echo $type;?>ad_txt_img_url').css("display",'none');
			$('.<?php echo $type;?>ad_img_tr').css("display",'none');
		}
	});


	//限时投放
    $('input[name="ad\[ad_time_type\]"]').on('ifChanged', function(e) {
    	
        var val = $(this).val();
        if( val == '1' ){
        	$('.bjui-lookup').css("line-height",'22px');
        	$('.bjui-lookup').css("height",'22px');
        	
			$('#<?php echo $type.$cFun;?>adTime').css("display",'');
        }else{
			$('#<?php echo $type.$cFun;?>adTime').css("display",'none');
        }
    })
});
</script>
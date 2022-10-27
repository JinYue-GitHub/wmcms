<?php
if( $zid == '' )
{
	echo '<script type="text/javascript">$(document).ready(function(){$(this).alertmsg("info", "对不起，请先选择专题!")});</script>';
}
?>
<div class="bjui-pageContent">	                       
    <form action="index.php?a=yes&c=operate.zt.node&t=<?php echo $type;?>" data-reload="false" data-toggle="validate" method="post" <?php if($d) { echo 'data-callback="'.$cFun.'"';}?>>
	<input name="id[node_id]" type="hidden" value="<?php echo $id;?>">
	<input name="node[node_zt_id]" type="hidden" value="<?php echo $zid;?>">
		<fieldset>
			<legend>专题编辑</legend>
        	<table class="table table-condensed table-hover" width="100%">
            <tbody>
		    <tr>
		      <td>
			      <b>节 点 名 字：</b>
			      <input name="node[node_name]" data-rule="required" type="text" class="input-text" value="<?php echo C('node_name',null,'data');?>">
			  </td>
			</tr>
		    <tr>
		      <td>
			      <b>节 点 标 识：</b>
			      <input name="node[node_pinyin]" type="text" class="input-text" value="<?php echo C('node_pinyin',null,'data');?>" data-rule="required" >
			  </td>
			</tr>
			<tr>
				<td>
					<b>节 点 类 型：</b>
					<select data-toggle="selectpicker" id="<?php echo $type?>node_type"  name="node[node_type]">
					<?php 
					foreach ($typeArr as $k=>$v)
					{
						$select = str::CheckElse($k, C('node_type',null,'data') , 'selected=""');
						echo '<option value="'.$k.'" '.$select.'>'.$v.'</option>';
					}
					?>
					</select>
				 </td>
			</tr>
		    <tr class="<?php echo $type;?>node_img_tr" <?php if(C('node_type',null,'data') != '1'){ echo 'style="display:none"';}?>>
		      <td>
		      	<b>节 点 图 片：</b>
		      	<input size="40" name="node[node_img]" type="text" class="input-text" value="<?php echo C('node_img',null,'data');?>">
		      	<span class="upload" data-uploader="index.php?a=yes&c=upload&t=img" data-on-upload-success="<?php echo $cFun;?>upload_success" data-file-type-exts="*.jpg,*.jpeg,*.png" data-toggle="upload" data-icon="cloud-upload"></span>
		      </td>
			</tr>
		    <tr class="<?php echo $type;?>node_label_tr" <?php if(C('node_type',null,'data') != '3'){ echo 'style="display:none"';}?>>
		      <td>
			      <b>节 点 标 签：</b>
			      <textarea name="node[node_label]" cols="40" rows="10" data-toggle="autoheight"><?php echo C('node_label',null,'data');?></textarea>
			  </td>
			</tr>
			<tr class="<?php echo $type;?>node_txt_tr" <?php if(C('node_type',null,'data') != '2'){ echo 'style="display:none"';}?>>
		      <td><b>节 点 内 容：</b>
				<?php echo Ueditor('width: 98%;height:250px' , 'node[node_content]' , C('node_content',null,'data'), 'editor.operate_ztnode');?>
		    </tr>
		    <tr>
		      <td><button type="submit" class="btn-green" data-icon="save">提交</button></td>
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


<script type="text/javascript">
//页面唯一回调函数
function <?php echo $cFun;?>(json){
	var op = operateZtNodeListGetOp();
	var tabid = 'zt-node-list';
	op['id'] = tabid;

	$(this).bjuiajax("ajaxDone",json);// 显示处理结果
    $(this).navtab("reload",op);	// 刷新Tab页面
    $(this).navtab("switchTab",tabid);	// 切换Tab页面
}

//上传图片成功后
function <?php echo $cFun;?>upload_success(file,json,$element){
	json = $.parseJSON(json);
	if ( json.statusCode == 300){
		$(this).bjuiajax("ajaxDone",json);// 显示处理结果
	}else{
		val = json.data.path.replace('../',"/");
		$element.siblings('input').val(val);
	}
}

$(document).ready(function(){
	//节点类型选择
	$("#<?php echo $type?>node_type").change(function(){
		var type = $(this).val();
		if( type == '1' ){
			$('.<?php echo $type;?>node_img_tr').css("display",'');
			$('.<?php echo $type;?>node_txt_tr').css("display",'none');
			$('.<?php echo $type;?>node_label_tr').css("display",'none');
		}else if( type == '2' ){
			$('.<?php echo $type;?>node_txt_tr').css("display",'');
			$('.<?php echo $type;?>node_img_tr').css("display",'none');
			$('.<?php echo $type;?>node_label_tr').css("display",'none');
		}else if( type == '3' ){
			$('.<?php echo $type;?>node_label_tr').css("display",'');
			$('.<?php echo $type;?>node_img_tr').css("display",'none');
			$('.<?php echo $type;?>node_txt_tr').css("display",'none');
		}
	});
});
</script>
</script>
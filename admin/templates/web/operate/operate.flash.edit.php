<div class="bjui-pageContent">	                       
    <form action="index.php?a=yes&c=operate.flash&t=<?php echo $type;?>" data-reload="false" data-toggle="validate" method="post" <?php if($d) { echo 'data-callback="'.$cFun.'"';}?>>
	<input name="id[flash_id]" type="hidden" class="input-text" value="<?php echo $id;?>">
		<fieldset>
			<legend>幻灯片编辑</legend>
        	<table class="table table-condensed table-hover" width="100%">
            <tbody>
            	<tr>
				  <td colspan="2">
				  	<b>专 题 分 类：</b>
					<input id="<?php echo $cFun.$type;?>type_id" name="flash[type_id]" type="hidden" value="<?php echo C('type_id',null,'data');?>">
			      	<input type="text" id="<?php echo $cFun.$type;?>_ztree_input" data-toggle="selectztree" data-tree="#<?php echo $cFun.$type;?>_ztree_select" readonly value="<?php echo C('type_name',null,'data');?>">
                    <ul id="<?php echo $cFun.$type;?>_ztree_select" class="ztree hide" data-toggle="ztree" data-expand-all="true" data-check-enable="true" data-chk-style="radio" data-radio-type="all" data-on-check="<?php echo $cFun.$type;?>S_NodeCheck" data-on-click="<?php echo $cFun.$type;?>S_NodeClick">
                    <?php 
                    	if( $typeArr )
                    	{
						    foreach ($typeArr as $k=>$v)
						    {
						    	$checked = str::CheckElse( $v['type_id'], C('type_id',null,'data') , 'true');
						      echo '<li data-checked="'.$checked.'" data-id="'.$v['type_id'].'" data-pid="'.$v['type_topid'].'">'.$v['type_name'].'</li>';
						    }
                    	}
					    ?>
                      </ul>
					</td>
				</tr>
                <tr>
                    <td>
                        <b>幻灯片标题：</b>
                        <input type="text" name="flash[flash_title]" value="<?php echo C('flash_title',null,'data');?>" data-rule="required">
                    </td>
                    <td>
                        <b>幻灯片简介：</b>
                        <input type="text" name="flash[flash_info]" value="<?php echo C('flash_info',null,'data');?>">
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>幻灯片状态：</b>
                        <select data-toggle="selectpicker" name="flash[flash_status]">
                       	<?php 
                       	foreach ($statusArr as $k=>$v)
                       	{
                       		$select = str::CheckElse($k, C('flash_status',null,'data') , 'selected=""');
                       		echo '<option value="'.$k.'" '.$select.'>'.$v.'</option>';
                       	}
                       	?>
                        </select>
		             </td>
                    <td>
                        <b>跳 转 地 址：</b>
                        <input type="text" name="flash[flash_url]" value="<?php echo C('flash_url',null,'data');?>" data-rule="文章标题:required" size="40">
                    </td>
                </tr>
                <tr>
                   <td colspan="3">
                        <b>幻灯片图片：</b>
                        <input type="text" id="<?php echo $cFun.$type;?>flash_img" name="flash[flash_img]" value="<?php echo C('flash_img',null,'data');?>" size="40">
						<span class="upload" data-uploader="index.php?a=yes&c=upload&t=img" data-on-upload-success="<?php echo $cFun;?>upload_success" data-file-type-exts="*.jpg;*.png;*.gif;*.mpg" data-toggle="upload" data-icon="cloud-upload"></span>
						<?php if( C('flash_img',null,'data') != '' ){ echo '<a target="_blank" href="'.C('flash_img',null,'data').'">点击查看图片</a>';}?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <b>所 属 模 块：</b>
                        <select data-toggle="selectpicker" name="flash[flash_module]">
                       	<?php 
                       	foreach ($moduleArr as $k=>$v)
                       	{
                       		$select = str::CheckElse($k, C('flash_module',null,'data') , 'selected=""');
                       		echo '<option value="'.$k.'" '.$select.'>'.$v.'</option>';
                       	}
                       	?>
                        </select>
		             </td>
		             <td>
                        <b>分 类 I D：</b>
                        <input type="text" name="flash[flash_pid]" value="<?php echo C('flash_pid',null,'data');?>" data-rule="digits" size="10">
                    </td>
		          </tr>
	              <tr>
                    <td colspan="3">
                        <b>显 示 顺 序：</b>
                        <input type="text" name="flash[flash_order]" value="<?php echo C('flash_order',null,'data');?>" data-rule="digits" size="10">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <b>幻灯片描述：</b>
                        <textarea name="flash[flash_desc]" cols="40" rows="6"><?php echo C('flash_desc',null,'data');?></textarea>
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
	var op = operateFlashListGetOp();
	var tabid = 'falsh-flash';
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
		$("#<?php echo $cFun.$type;?>flash_img").val(val);
	}
}


//选择事件
function <?php echo $cFun.$type;?>S_NodeCheck(e, treeId, treeNode) {
	var zTree = $.fn.zTree.getZTreeObj(treeId),
	    nodes = zTree.getCheckedNodes(true)
	var ids = '', names = ''
	
	for (var i = 0; i < nodes.length; i++) {
	    ids   += ','+ nodes[i].id
	    names += ','+ nodes[i].name
	}
	if (ids.length > 0) {
	    ids = ids.substr(1), names = names.substr(1)
	}
	
	var $from = $('#'+ treeId).data('fromObj')
	
	$("#<?php echo $cFun.$type;?>type_id").val(ids);
	console.log("#<?php echo $cFun;?>type_id");
	if ($from && $from.length) $from.val(names)
}
//单击事件
function <?php echo $cFun.$type;?>S_NodeClick(event, treeId, treeNode) {
	var zTree = $.fn.zTree.getZTreeObj(treeId)
	zTree.checkNode(treeNode, !treeNode.checked, true, true)
	event.preventDefault()
}

$(document).ready(function(){
	$("#<?php echo $cFun;?>senior_tab").click(function(){
		$('.bjui-lookup').css("line-height",'22px');
		$('.bjui-lookup').css("height",'22px');
	});
});
</script>
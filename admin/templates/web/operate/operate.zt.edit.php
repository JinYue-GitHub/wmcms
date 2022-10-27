<div class="bjui-pageContent">	                       
    <form action="index.php?a=yes&c=operate.zt&t=<?php echo $type;?>" data-reload="false" data-toggle="validate" method="post" <?php if($d) { echo 'data-callback="'.$cFun.'"';}?>>
	<input name="id[zt_id]" type="hidden" class="input-text" value="<?php echo $id;?>">
		<fieldset>
			<legend>专题编辑</legend>
            <ul class="nav nav-tabs" role="tablist">
            	<li class="active"><a href="#<?php echo $cFun.$type;?>base" role="tab" data-toggle="tab">基本设置</a></li>
            </ul>
            <div class="tab-content">
            	<div class="tab-pane fade active in" id="<?php echo $cFun.$type;?>base">
			        	<table class="table table-condensed table-hover" width="100%">
			            <tbody>
		            	<tr>
						  <td colspan="2">
						  	<b>专 题 分 类：</b>
							<input id="<?php echo $cFun.$type;?>type_id" name="zt[type_id]" type="hidden" value="<?php echo C('type_id',null,'data');?>">
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
					      <td width="40%">
						      <b>专 题 名 字：</b>
						      <input name="zt[zt_name]" data-rule="required" type="text" class="input-text" value="<?php echo C('zt_name',null,'data');?>">
						  </td>
					      <td>
						      <b>专 题 拼 音：</b>
						      <input name="zt[zt_pinyin]" type="text" class="input-text" value="<?php echo C('zt_pinyin',null,'data');?>">
						  </td>
						</tr>
						<tr>
							<td>
								<b>专 题 状 态：</b>
								<select data-toggle="selectpicker" name="zt[zt_status]">
								<?php 
								foreach ($statusArr as $k=>$v)
								{
									$select = str::CheckElse($k, C('zt_status',null,'data') , 'selected=""');
									echo '<option value="'.$k.'" '.$select.'>'.$v.'</option>';
								}
								?>
								</select>
							 </td>
							<td>
								<b>专题浏览量：</b><input size="10" name="zt[zt_read]" type="text" class="input-text" value="<?php echo C('zt_read',null,'data');?>">
							</td>
						</tr>
					    <tr>
					      <td colspan="2">
					      	<b>专 题 封 面：</b>
					      	<input size="40" name="zt[zt_simg]" type="text" class="input-text" value="<?php echo C('zt_simg',null,'data');?>">
					      	<span class="upload" data-uploader="index.php?a=yes&c=upload&t=img" data-on-upload-success="<?php echo $cFun.$type;?>upload_success" data-file-type-exts="*.jpg;*.png;*.gif;*.mpg" data-toggle="upload" data-icon="cloud-upload"></span>
					      </td>
						</tr>
					    <tr>
					      <td colspan="2">
					      	<b>专 题 横 幅：</b>
					      	<input size="40" name="zt[zt_banner]" type="text" class="input-text" value="<?php echo C('zt_banner',null,'data');?>">
					      	<span class="upload" data-uploader="index.php?a=yes&c=upload&t=img" data-on-upload-success="<?php echo $cFun.$type;?>upload_success" data-file-type-exts="*.jpg;*.png;*.gif;*.mpg" data-toggle="upload" data-icon="cloud-upload"></span>
					      </td>
						</tr>
					    <tr>
					      <td colspan="2">
					      	<b>专 题 介 绍：</b>
					      	<textarea name="zt[zt_info]" cols="70" rows="6"><?php echo C('zt_info',null,'data');?></textarea>
					      </td>
						</tr>
						<tr>
					      <td colspan="2"><b>专 题 内 容：</b>
							<?php echo Ueditor('width: 98%;height:250px' , 'zt[zt_content]' , C('zt_content',null,'data'), 'editor.operate_zt');?>
					    </tr>
			            </tbody>
			        </table>
	        	</div>
	        </div>
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
	var op = operateZtListGetOp();
	var tabid = 'zt-list';
	op['id'] = tabid;

	$(this).bjuiajax("ajaxDone",json);// 显示处理结果
    $(this).navtab("reload",op);	// 刷新Tab页面
    $(this).navtab("switchTab",tabid);	// 切换Tab页面
}

//上传封面
function <?php echo $cFun.$type;?>upload_success(file,json,$element){
	json = $.parseJSON(json);
	if ( json.statusCode == 300){
		$(this).bjuiajax("ajaxDone",json);// 显示处理结果
	}else{
		val = json.data.path.replace('../',"/");
		$element.siblings('input').val(val);
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
	$("#<?php echo $cFun.$type;?>templates_tab").click(function(){
		$(".bjui-lookup").css("line-height",'23px');
		$(".bjui-lookup").css("height",'23px');
	});
});
</script>
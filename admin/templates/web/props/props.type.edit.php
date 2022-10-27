<div class="bjui-pageContent">               
    <form action="index.php?a=yes&c=props.type&t=<?php echo $type;?>" data-reload="false" data-toggle="validate" method="post" <?php if($d) { echo 'data-callback="'.$cFun.$type.'"';}?>>
	<input name="type_id" id="<?php echo $cFun.$type;?>_id" type="hidden" class="input-text" value="<?php echo $id;?>">
		<fieldset>
			<legend>打赏道具分类编辑</legend>
			<ul class="nav nav-tabs" role="tablist">
            	<li class="active"><a href="#<?php echo $cFun.$type;?>base" role="tab" data-toggle="tab">基本信息</a></li>
            </ul>
            <div class="tab-content">
            	<div class="tab-pane fade active in" id="<?php echo $cFun.$type;?>base">
					<table class="table table-border table-bordered table-bg table-sort">
		            <tbody>
		            	<tr>
						  <td valign="top" width="150"><b>所属分类：</b></td>
					      <td valign="top">
							<input id="<?php echo $cFun.$type;?>type_pid" name="type[type_topid]" type="hidden" value="<?php echo C('type_topid',null,'data');?>">
					      	<input type="text" id="<?php echo $cFun.$type;?>_ztree_input" data-toggle="selectztree" data-tree="#<?php echo $cFun.$type;?>_ztree_select" readonly value="<?php echo C('name',null,'top');?>">
                            <ul id="<?php echo $cFun.$type;?>_ztree_select" class="ztree hide" data-toggle="ztree" data-expand-all="true" data-check-enable="true" data-chk-style="radio" data-radio-type="all" data-on-check="<?php echo $cFun.$type;?>S_NodeCheck" data-on-click="<?php echo $cFun.$type;?>S_NodeClick">
                                <li data-id="0">顶级分类</li>
                                <?php 
								if( $typeArr )
								{
									foreach ($typeArr as $k=>$v)
									{
										$checked = str::CheckElse( $v['type_id'], C('type_topid',null,'data') , 'true');
										echo '<li data-checked="'.$checked.'" data-id="'.$v['type_id'].'" data-pid="'.$v['type_topid'].'">'.$v['type_name'].'</li>';
									}
								}
							    ?>
                            </ul>
					      </td>
						</tr>
		                <tr>
		                    <td><b>所属模块：</td>
		                    <td><select data-toggle="selectpicker" name="type[type_module]">
	                            	<?php
				                	foreach ($moduleArr as $k=>$v)
				                	{
				                		$checked = str::CheckElse( $k , C('field_module',null,'data') , 'selected=""' );
				                		echo '<option value="'.$k.'" '.$checked.'>'.$v.'</option>';
				                	}
				                	?>
	                            </select>
		                    </td>
		                </tr>
		                <tr>
		                    <td><b>分类名字：</b></td>
		                    <td>
		                        <input type="text" name="type[type_name]" value="<?php echo C('type_name',null,'data');?>" data-rule="required">
		                    </td>
		                </tr>
		                <tr>
		                    <td><b>分类简称：</b></td>
		                    <td>
		                        <input type="text" name="type[type_cname]" value="<?php echo C('type_cname',null,'data');?>">
		                    </td>
		                </tr>
		                <tr>
		                    <td><b>分类排序：</b></td>
		                    <td>
		                        <input type="text" name="type[type_order]" value="<?php echo C('type_order',null,'data');?>" data-rule="required;digits" size="10">
		                    </td>
		                </tr>
		            </tbody>
		        </table>
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


<script>
//页面唯一回调函数
function <?php echo $cFun.$type;?>(json){
	var op = propsTypeListGetOp();
	var tabid = 'author-props-typelist';
	op['id'] = tabid;

	$(this).bjuiajax("ajaxDone",json);// 显示处理结果
    $(this).navtab("reload",op);	// 刷新Tab页面
    $(this).navtab("switchTab",tabid);	// 切换Tab页面
    
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
    
    $("#<?php echo $cFun.$type;?>type_pid").val(ids);
    if ($from && $from.length) $from.val(names)
}
//单击事件
function <?php echo $cFun.$type;?>S_NodeClick(event, treeId, treeNode) {
    var zTree = $.fn.zTree.getZTreeObj(treeId)
    
    zTree.checkNode(treeNode, !treeNode.checked, true, true)
    
    event.preventDefault()
}
//自定义字段回调
function <?php echo $cFun.$type;?>getfield(json){
	var html='';
	if( json.data ){
		var option = json.data;
		for(var i=0;i<option.length;i++){
			html += '<tr><td valign="top" width="150"><b>'+option[i]['title']+'：</b></td>'+
			      '<td valign="top">'+option[i]['form']+'</td></tr>';
		}
	}else{
		html ='<tr><td valign="top"><b>请先添加自定义字段</b></td></tr>';
	}
	$("#<?php echo $cFun.$type;?>field table").html(html);
	$("#<?php echo $cFun.$type;?>field table").trigger('bjui.initUI');
}
</script>
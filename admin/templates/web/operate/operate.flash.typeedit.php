<div class="bjui-pageContent">
    <form action="index.php?a=yes&c=operate.flash.type&t=<?php echo $type;?>" data-reload="false" data-toggle="validate" method="post" <?php if($d) { echo 'data-callback="'.$cFun.'"';}?>>
	<input name="type_id" id="<?php echo $cFun.$type;?>type_id" type="hidden" class="input-text" value="<?php echo $id;?>">
		<fieldset>
			<legend>专题分类编辑</legend>
            <ul class="nav nav-tabs" role="tablist">
            	<li class="active"><a href="#<?php echo $cFun.$type;?>base" role="tab" data-toggle="tab">基本设置</a></li>
            </ul>
            <div class="tab-content">
            	<div class="tab-pane fade active in" id="<?php echo $cFun.$type;?>base">
					<table class="table table-border table-bordered table-bg table-sort">
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
					      <td valign="top"><b>分类名字：</b></td>
					      <td valign="top"><input data-rule="分类名字:required;" name="type[type_name]" type="text" class="input-text" value="<?php echo C('type_name',null,'data');?>"></td>
						</tr>
					    <tr>
					      <td valign="top"><b>分类拼音：</b></td>
					      <td valign="top"><input name="type[type_pinyin]" type="text" class="input-text" value="<?php echo C('type_pinyin',null,'data');?>"></td>
						</tr>
					    <tr>
					      <td valign="top"><b>分类排序：</b></td>
					      <td valign="top"><input data-rule="required;number;" name="type[type_order]" type="text" class="input-text" value="<?php echo C('type_order',null,'data');?>"></td>
						</tr>
					    <tr>
					      <td valign="top"><b>分类简介：</b></td>
					      <td valign="top"><textarea name="type[type_info]" class="form-control" cols="40" rows="6"><?php echo C('type_info',null,'data');?></textarea></td>
						</tr>
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


<script>
function <?php echo $cFun;?>(json){
	var op = operateFlashTypelistGetOp();
	var tabid = 'flash-type_list';
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
</script>

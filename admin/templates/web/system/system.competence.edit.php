<div class="bjui-pageContent">
<form action="index.php?a=yes&c=system.competence.competence&t=<?php echo $type;?>" data-reload="false" data-toggle="ajaxform" method="post" <?php if($d) { echo 'data-callback="'.$cFun.'"';}?>>
<input name="id" type="hidden" class="input-text" value="<?php echo C('comp_id',null,'data');?>">
<table class="table table-border table-bordered table-bg table-sort">
	<th colspan="2" style="padding-left:10px;"><b>管理员可用权限选择</b></th></tr>
    <tr>
      <td valign="top" width="200">权限名字：</td>
      <td valign="top">
      	&nbsp;<input name="name" type="text" class="input-text" value="<?php echo C('comp_name',null,'data');?>">
      </td>
	</tr>
	<tr>
    <td valign="top">管理站点：</td>
    <td valign="top">
    <input id="<?php echo $cFun.$type;?>site" name="site" type="hidden" value="<?php echo C('comp_site',null,'data');?>">
    <input type="text" id="<?php echo $cFun.$type;?>site_ztree_input" data-toggle="selectztree" data-tree="#<?php echo $cFun.$type;?>site_ztree_select" readonly value="<?php echo C('comp_site_name',null,'data');?>">
    <ul id="<?php echo $cFun.$type;?>site_ztree_select" data-iptid="<?php echo $cFun.$type;?>site" class="ztree hide" data-toggle="ztree" data-expand-all="false" data-check-enable="true" data-chk-style="checkbox" data-radio-type="all" data-on-check="<?php echo $cFun.$type;?>S_NodeCheck" data-on-click="<?php echo $cFun.$type;?>S_NodeClick">
     <?php 
     	$siteComp = explode(',', C('comp_site',null,'data'));
	    foreach ($siteList as $k=>$v)
	    {
	    	$checked = 'false';
	    	if( in_array($v['id'], $siteComp) && $type=='edit')
	    	{
	    		$checked = 'true';
	    	}
	    	echo '<li data-checked="'.$checked.'" data-id="'.$v['id'].'">'.$v['name'].'【'.$v['domain'].'】</li>';
	    }
	    ?>
   	</ul>
	</tr>
	<tr>
    <td valign="top">可用权限选择：</td>
    <td valign="top">
    <input id="<?php echo $cFun.$type;?>comp" name="comp" type="hidden" value="<?php echo C('comp_content',null,'data');?>">
    <input type="text" id="<?php echo $cFun.$type;?>_ztree_input" data-toggle="selectztree" data-tree="#<?php echo $cFun.$type;?>_ztree_select" readonly value="<?php echo C('comp_content_name',null,'data');?>">
    <ul id="<?php echo $cFun.$type;?>_ztree_select" data-iptid="<?php echo $cFun.$type;?>comp" class="ztree hide" data-toggle="ztree" data-expand-all="false" data-check-enable="true" data-chk-style="checkbox" data-radio-type="all" data-on-check="<?php echo $cFun.$type;?>S_NodeCheck" data-on-click="<?php echo $cFun.$type;?>S_NodeClick">
     <?php 
     	$checkedComp = explode(',', C('comp_content',null,'data'));
	    foreach ($menuArr as $k=>$v)
	    {
	    	$checked = 'false';
	    	if( in_array($v['menu_id'], $checkedComp) )
	    	{
	    		$checked = 'true';
	    	}
	    	echo '<li data-checked="'.$checked.'" data-id="'.$v['menu_id'].'" data-pid="'.$v['menu_pid'].'">'.$v['menu_title'].'</li>';
	    }
	    ?>
   	</ul>
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
function <?php echo $cFun;?>(json){
	$(this).bjuiajax("ajaxDone",json);// 显示处理结果
    $(this).dialog("closeCurrent");//关闭
    $(this).navtab("reload",systemCompetenceListGetOp());	// 刷新当前Tab页面
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
  
  $("#"+$('#'+ treeId).data('iptid')).val(ids);
  if ($from && $from.length) $from.val(names)
}
//单击事件
function <?php echo $cFun.$type;?>S_NodeClick(event, treeId, treeNode) {
  var zTree = $.fn.zTree.getZTreeObj(treeId)
  zTree.checkNode(treeNode, !treeNode.checked, true, true)
  event.preventDefault()
}
</script>
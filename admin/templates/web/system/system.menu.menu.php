<script type="text/javascript">
//设置需要修改的数组
var options=new Array();
var data=new Object();

//单击事件
function ZtreeClick(event, treeId, treeNode) {
    event.preventDefault()
    var $detail = $('#ztree-detail')
    
    if ($detail.attr('tid') == treeNode.tId) return

 	//上级的标题
 	pname = $("#"+treeNode.parentTId).find("a").eq(0).text();
 	if(treeNode.pid == 0){
 	 	pname='顶级目录'
 	 }

	 if( typeof(treeNode.id) == 'undefined' ){
		$('#j_ztree_menus2').val('')
	 	$('#button').text('新增菜单')
	}else{
	 	$('#j_ztree_menus2').val(pname)
 	 	$('#button').text('更新菜单')
 	}

	 //如果为权限菜单的时候禁止修改参数
	 if(treeNode.group == '2')
	 {
		 $('#button').attr('disabled',true);
		 $('#menu_name').attr('disabled',true);
		 $('#menu_title').attr('disabled',true);
		 $('#menu_file').attr('disabled',true);
		 $('#menu_group').attr('disabled',true);
		 $('#menu_status').attr('disabled',true);
		 $('#j_ztree_menus2').attr('disabled',true);
		 $('#menu_order').attr('disabled',true);
	}else{
		 $('#button').attr('disabled',false);
		 $('#menu_name').attr('disabled',false);
		 $('#menu_title').attr('disabled',false);
		 $('#menu_file').attr('disabled',false);
		 $('#menu_group').attr('disabled',false);
		 $('#menu_status').attr('disabled',false);
		 $('#j_ztree_menus2').attr('disabled',false);
		 $('#menu_order').attr('disabled',false);
	}
	$('#menu_id').val(treeNode.id)
	$('#menu_name').val(treeNode.bname)
	$('#menu_title').val(treeNode.name)
	$('#menu_status').selectpicker('val',treeNode.status)
	$('#menu_pid').val(treeNode.pid)
	$('#menu_group').selectpicker('val',treeNode.group)
	$('#menu_order').val(treeNode.order)
	$('#menu_file').val(treeNode.file)

	$detail.attr('tid', treeNode.tId)
    $detail.show()
}
//保存属性
function M_Ts_Menu() {
	var zTree  = $.fn.zTree.getZTreeObj("<?php echo $cFun;?>_ztree_check")
	var id   = $('#menu_id').val()
	var status   = $('#menu_status').selectpicker('val')
	var title    = $('#menu_title').val()
	var name  = $('#menu_name').val()
	var pid = $('#menu_pid').val()
	var group = $('#menu_group').selectpicker('val')
	var order = $('#menu_order').val()
	var file = $('#menu_file').val()
	
 	//$('#j_ztree_menus2').val(pname)

	if ($.trim(name).length == 0) {
		$(this).alertmsg('error', '1菜单标识不能为空!')
		return;
	}
	if ($.trim(title).length == 0) {
		$(this).alertmsg('error', '2菜单标识不能为空!')
		return;
	}
	if ($.trim(order).length == 0) {
		$(this).alertmsg('error', '显示顺序不能为空!')
		return;
	}
	var upNode = zTree.getSelectedNodes()[0]
	
	if (upNode) {
		//$(this).alertmsg('error', '菜单标识不能为空!')
        //return
	
		upNode.name   = title
		upNode.status   = status
		upNode.bname   = name
		upNode.pid   = pid
		upNode.group   = group
		upNode.order   = order
		upNode.file   = file
		zTree.updateNode(upNode)
	}


	//检查pid是否相等，不相等就修改。
	data.id = id;
	data.name = name;
	data.status = status;
	data.title = title;
	data.pid = pid;
	data.group = group;
	data.order = order;
	data.file = file;
	var t = 'add';
	if(id != '' )
	{
		t = 'edit';
	}
	options['url'] = 'index.php?a=yes&c=system.menu.menu&t='+t;
	options['data'] = data;
	options['reload'] = false;
	options['loadingmask'] = true;
	
	$(this).bjuiajax('doAjax', options)
}
//监听拖拽事件
function M_BeforeNodeDrop(treeId, treeNodes, targetNode, moveType, isCopy) {
    /*禁止插入层级为2的节点*/
    if (moveType == 'inner' && targetNode.level == 2) {
        return false
    }
    /*禁止插入剩余层级不足的子节点*/
    if (moveType == 'inner' && treeNodes[0].isParent) {
        var molevel = 2 - targetNode.level //剩余层级
        var maxlevel = 1
        var zTree = $.fn.zTree.getZTreeObj("<?php echo $cFun;?>_ztree_check")
        var nodes = zTree.transformToArray(treeNodes)
        var level = nodes[0].level
        
        for (var i = 1; i < nodes.length; i++) {
            if (nodes[i].level == (level + 1)) {
                maxlevel++
                level++
            }
        }
        if (maxlevel > molevel) {
            return false
        }
    }
    return true
}
//拖拽开始事件
function M_BeforeNodeDrag(treeId, treeNodes) {
    return true
}
//拖拽结束事件
function M_NodeDrop(event, treeId, treeNodes, targetNode, moveType, isCopy) {
	//移动到哪一个对象的上面targetNode
	//移动的是哪一个对象treeNodes

	//检查pid是否相等，不相等就修改。
	if( targetNode.pid  != treeNodes[0].pid )
	{
		data.pid = targetNode.pid;
	}
	data.order = targetNode.order-1;
	data.id = treeNodes[0].id;
	options['url'] = 'index.php?a=yes&c=system.menu.menu&t=order';
	options['data'] = data;
	options['reload'] = false;
	options['loadingmask'] = true;
	
	$(this).bjuiajax('doAjax', options)
}

//删除前事件
function M_BeforeRemove(treeId, treeNode) {
	//如果是权限菜单就不进行删除
	if( treeNode.group == '2' )
	{
		$(this).alertmsg('error', '对不起权限菜单无法删除!')
		return false;
	}
    return true;
}
//删除结束事件
function M_NodeRemove(event, treeId, treeNode) {
	//如果id存在就删除
	if( typeof(treeNode.id) != 'undefined' ){
		data.id = treeNode.id;
		options['url'] = 'index.php?a=yes&c=system.menu.menu&t=del';
		options['data'] = data;
		options['reload'] = false;
		options['loadingmask'] = true;
		
		$(this).bjuiajax('doAjax', options)
	}
}


/*所属目录菜单选择和单击事件*/
//选择事件
function S_NodeCheck(e, treeId, treeNode) {
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

    $('#menu_pid').val(ids)
    if ($from && $from.length) $from.val(names)
}
//单击事件
function S_NodeClick(event, treeId, treeNode) {
    var zTree = $.fn.zTree.getZTreeObj(treeId)
    
    zTree.checkNode(treeNode, !treeNode.checked, true, true)
    
    event.preventDefault()
}

</script>


<div class="bjui-pageContent">
	<fieldset>
	<legend>后台首页菜单目录修改</legend>
    <div style="padding:20px;">
        <div class="clearfix">
            <div style="float:left; width:300px; height:500px; overflow:auto;    border:1px solid #C3CED5;">
            	<!-- 
            	第一行：拖拽属性
            	第二行：删除操作
            	 -->
                <ul id="<?php echo $cFun;?>_ztree_check" class="ztree" data-toggle="ztree" data-options="{expandAll: false,onClick: 'ZtreeClick' }"
                 data-edit-enable="true" data-before-drag="M_BeforeNodeDrag" data-before-drop="M_BeforeNodeDrop" data-on-drop="M_NodeDrop"
                 data-add-hover-dom="edit" data-remove-hover-dom="edit" data-before-remove="M_BeforeRemove" data-on-remove="M_NodeRemove">
                	<?php 
                	foreach ($menuArr as $k=>$v)
                	{
                		echo '<li id="'.$v['menu_id'].'" data-id="'.$v['menu_id'].'" data-status="'.$v['menu_status'].'" data-bname="'.$v['menu_name'].'" data-pid="'.$v['menu_pid'].'" data-group="'.$v['menu_group'].'" data-order="'.$v['menu_order'].'" data-file="'.$v['menu_file'].'" data-pid="'.$v['menu_pid'].'" data-faicon-close="cab">'.$v['menu_title'].'</li>';
                	}
                	?>
                </ul>
            </div>
            
            
            <div id="ztree-detail" style="margin-left:320px;">
            	<table class="table table-border table-bordered table-bg table-sort">
		      	<tr>
	        		<td width="220px"><b>菜单ID：</b></td>
	                <td>
	                	<input type="text" class="form-control" name="menu_title" id="menu_id" readonly placeholder="菜单ID"/>
	                </td>
	            </tr>
		      	<tr>
	        		<td><b>菜单名称：</b></td>
	                <td>
	                	<input type="text" class="form-control validate[required] required" name="menu_title" id="menu_title" placeholder="菜单名称，中文" />
	                </td>
	            </tr>
		      	<tr>
	        		<td><b>菜单标识：</b><br/>
	        		权限菜单的时候此项参数为方法选择器	        		
	        		</td>
	                <td>
	                	<input type="text" class="form-control validate[required] required" name="menu_name" id="menu_name" placeholder="菜单标识，英文" />
	                </td>
	            </tr>
		      	<tr>
	        		<td><b>菜单状态：</b></td>
	                <td>
	                	<select data-toggle="selectpicker" name="menu_status" id="menu_status">
	                        <option value="1">显示菜单</option>
	                        <option value="0">隐藏菜单</option>
	                    </select>
	                </td>
	            </tr>
		      	<tr>
	        		<td><b>菜单类型：</b></td>
	                <td>
	                	<select data-toggle="selectpicker" name="menu_group" id="menu_group">
	                        <option value="0">普通菜单</option>
	                        <option value="1">分组菜单</option>
	                        <option value="2">权限菜单</option>
	                    </select>
	                </td>
	            </tr>
		      	<tr>
	        		<td><b>所属菜单：</b></td>
	                <td>
	                	<input type="hidden" name="menu_pid" id="menu_pid" value="0">
	                	<input type="text" id="j_ztree_menus2" data-toggle="selectztree" size="18" data-tree="#j_select_tree2" readonly>
                        <ul id="j_select_tree2" class="ztree hide" data-toggle="ztree" data-check-enable="true" data-chk-style="radio" data-radio-type="all" data-on-check="S_NodeCheck" data-on-click="S_NodeClick">
                        <?php 
		                foreach ($menuArr as $k=>$v)
		                {
		                	echo '<li data-id="'.$v['menu_id'].'" data-pid="'.$v['menu_pid'].'" data-faicon-close="cab">'.$v['menu_title'].'</li>';
		                }
		                ?>
                    	</ul>
	                </td>
	            </tr>
		      	<tr>
	        		<td><b>显示顺序：</b></td>
	                <td><input type="text" class="form-control validate[required] required" name="menu_order" id="menu_order" placeholder="当前分组排序，越小越靠前" /></td>
	            </tr>
		      	<tr>
	        		<td><b>控制器名字：</b></td>
	                <td>
	                	<input type="text" class="form-control" name="menu_file" id="menu_file" placeholder="控制器的名字" />
	                </td>
	            </tr>
		      	<tr>
	        		<td colspan="2"><button id="button" class="btn btn-green" onclick="M_Ts_Menu();">增加菜单</button></td>
	            </tr>
	            </table>
            </div>
            <div style="margin-left:320px;border:1px solid #C3CED5;margin-top:10px;padding:5px;">
				注意：菜单类型为权限菜单的时候请勿修改任何数据。
            </div>
        </div>
    </div>
	</fieldset>
</div>
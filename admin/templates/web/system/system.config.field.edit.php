<style>
.form-horizontal .row {display: table;width: 100%;}
.<?php echo $cFun;?>label {width: 18%;}
.form .row {margin-top: 15px;}
.form-horizontal .form-label {margin-top: 8px;cursor: text;text-align: right;padding-right: 10px; font-size: 14px;font-weight: normal;}
</style>

<div class="bjui-pageContent">
	<form action="index.php?a=yes&c=system.config.field&t=<?php echo $type;?>" name="<?php echo $cFun;?>Form" class="form form-horizontal" data-reload="false" data-toggle="ajaxForm" method="post">
    	<input type="hidden" name="id[field_id]" value="<?php echo $id;?>"/>
	    <div class="row cl">
			<label class="form-label col-md-4  <?php echo $cFun;?>label">字段名字：</label>
	    	<div class="formControls">
	        	<input type="text" class="form-control" placeholder="字段标题，用于区分显示" name="field[field_name]" value="<?php echo C('field_name',null,'data');?>">
	    	</div>
	    </div>
	    <div class="row cl">
			<label class="form-label col-md-4  <?php echo $cFun;?>label">字段位置：</label>
	    	<div class="formControls">
	    		<select data-toggle="selectpicker" name="field[field_type]" <?php if($type=='edit' && !DEVELOPER){echo 'readonly';}?>>
			        <option value="1" <?php if(C('field_type',null,'data')=='1'){ echo 'selected=""';}?>>分类字段</option>
			        <option value="2" <?php if(C('field_type',null,'data')=='2'){ echo 'selected=""';}?>>内容字段</option>
				</select>
				<span style="margin-left:15px;color:#A7A7A7">一旦选择将无法修改。</span>
	    	</div>
	    </div>
	    <div class="row cl">
			<label class="form-label col-md-4  <?php echo $cFun;?>label">所属模块：</label>
	    	<div class="formControls">
	    		<select data-toggle="selectpicker" name="field[field_module]" id="<?php echo $cFun;?>module">
			        <option value="">---请选择模块---</option>
			        <?php
                	foreach ($moduleArr as $k=>$v)
                	{
                		$checked = str::CheckElse( $k , C('field_module',null,'data') , 'selected=""' );
                		echo '<option value="'.$k.'" '.$checked.'>'.$v.'</option>';
                	}
                	?>
				</select>
	    	</div>
	    </div>
	    <div class="row cl">
			<label class="form-label col-md-4  <?php echo $cFun;?>label">所属分类：</label>
	    	<div class="formControls" id="<?php echo $cFun.$type;?>_ztree_select_box">
		        <?php
			        if( $type=='add')
			        {
			        	echo '<div style="margin-top: 8px;font-size: 14px;color: #CAC8C8;">--请先选择模块--</div>';
			        }
			        else
			        {
			        	?>
			        	<input id="<?php echo $cFun.$type;?>type_id" name="field[field_type_id]" type="hidden" value="<?php echo C('field_type_id',null,'data');?>">
				      	<input type="text" id="<?php echo $cFun.$type;?>_ztree_input" data-toggle="selectztree" data-tree="#<?php echo $cFun.$type;?>_ztree_select" readonly value="<?php echo C('type_name',null,'data');?>">
	                   	<ul id="<?php echo $cFun.$type;?>_ztree_select" class="ztree hide" data-toggle="ztree" data-expand-all="true" data-check-enable="true" data-chk-style="radio" data-radio-type="all" data-on-check="<?php echo $cFun.$type;?>S_NodeCheck" data-on-click="<?php echo $cFun.$type;?>S_NodeClick">
	                        <li data-id="0"  data-checked="<?php echo str::CheckElse( 0, C('field_type_id',null,'data') , 'true')?>">全部分类</li>
	                    <?php 
	                	foreach ($data['type_list'] as $k=>$v)
	                	{
							$classChecked = str::CheckElse( $v['type_id'], C('field_type_id',null,'data') , 'true');
	                		echo '<li data-checked="'.$classChecked.'" data-id="'.$v['type_id'].'" data-pid="'.$v['type_topid'].'">'.$v['type_name'].'</li>';
	                	}
						
	                	//是否选中子分类
	                	$childChecked = str::CheckElse( 1 , C('field_type_child',null,'data') , 'checked');
	                	echo '</ul><span style="margin-left:20px"><input type="checkbox" '.$childChecked.' name="field[field_type_child]" value="1" data-toggle="icheck" data-label="对下级分类有效"></span>';
			        }
                ?>
	    	</div>
	    </div>
	    <div class="row cl">
			<label class="form-label col-md-4  <?php echo $cFun;?>label">字段选项：</label>
	    	<div class="formControls col-md-8" style="width:80%;margin-left:-15px">
	    		<ul id="<?php echo $cFun;?>formtype">
	    			<?php
			        if( $type=='add' || $data['field_option'] == '' )
			        {
			        	echo '<li style="margin-bottom: 7px;"><select data-toggle="selectpicker" name="option[formtype][]" data-width="80">';
						if( $fromArr )
						{
							foreach ($fromArr as $k=>$v)
							{
								$checked = str::CheckElse( $k , C('field_formtype',null,'data') , 'selected=""' );
				            	echo '<option value="'.$k.'" '.$checked.'>'.$v.'</option>';
				            }
						}
						echo '</select>
						<input type="text" class="form-control" name="option[title][]" placeholder="字段标题" style="width:80px">
						<input type="text" class="form-control" name="option[option][]" placeholder="字段选项，多个用,隔开" style="width:200px">
						<input type="text" class="form-control" name="option[default][]" placeholder="默认值" name="field[field_default]" style="width:80px">
						<a href="javascript:'.$cFun.'add()"><i class="fa fa-plus-square"></i></a></li>';
			        }
			        else
			        {
			        	$i=1;
			        	$optionArr = unserialize($data['field_option']);
			        	foreach ($optionArr as $key=>$val)
			        	{
			        		echo '<li style="margin-bottom: 7px;"><select data-toggle="selectpicker" name="option[formtype][]" data-width="80">';
			        		foreach ($fromArr as $k=>$v)
			        		{
			        			$checked = str::CheckElse( $k , $val['formtype'] , 'selected=""' );
			        			echo '<option value="'.$k.'" '.$checked.'>'.$v.'</option>';
			        		}
			        		echo '</select>
							<input type="text" class="form-control" name="option[title][]" value="'.$val['title'].'" style="width:80px">
							<input type="text" class="form-control" name="option[option][]" placeholder="字段选项，多个用,隔开" value="'.$val['option'].'" style="width:200px">
							<input type="text" class="form-control" name="option[default][]" placeholder="默认值" name="field[field_default]" value="'.$val['default'].'" style="width:80px">
							<a href="javascript:'.$cFun.'add()"><i class="fa fa-plus-square"></i></a>';
			        		if( $i > 1)
			        		{
			        			echo '<a href="javascript:void(0)" onClick="'.$cFun.'unadd(this)" style="margin-left: 4px;"><i class="fa fa-minus-square"></i></a>';
			        		}
                			echo '</li>';
                			$i++;
			        	}
			        }
                	?>
				</ul>
	    	</div>
	    </div>
	</div>
	</form>
	<div class="bjui-pageFooter">
	    <ul>
	        <li><button type="button" class="btn-close btn btn-danger size-MINI radius"><i class="fa fa-times"></i> 关闭</button></li>
	        <li><button type="button" class="btn btn-success size-MINI radius <?php echo $cFun;?>sub"><i class="fa fa-floppy-o"></i> 保存</button></li>
	    </ul>
	</div>
</div>
<script type="text/javascript">
function <?php echo $cFun;?>(json){
	var op = systemConfigFieldListGetOp();
	var tabid = 'option-field';
	op['id'] = tabid;

	$(this).bjuiajax("ajaxDone",json);// 显示处理结果
    //如果是编辑就要关闭
	<?php if($type == 'edit') { echo '$(this).dialog("closeCurrent");';}?>
    $(this).navtab("reload",op);	// 刷新Tab页面*/
}
//获得模块分类的回调函数
function <?php echo $cFun.$type?>gettype(json){
	if( json.data ){
		var html=htmls='';
		var data = json.data;
		for(var i=0;i<data.length;i++){
			html += '<li data-id="'+data[i]['type_id']+'" data-pid="'+data[i]['type_topid']+'">'+data[i]['type_name']+'</li>';
		}
		htmls = '<input id="<?php echo $cFun.$type;?>type_id" name="field[field_type_id]" type="hidden">'+
	      		'<input type="text" id="<?php echo $cFun.$type;?>_ztree_input" data-toggle="selectztree" data-tree="#<?php echo $cFun.$type;?>_ztree_select" readonly value="请选择分类">'+
        		'<ul id="<?php echo $cFun.$type;?>_ztree_select" class="ztree hide" data-toggle="ztree" data-expand-all="true" data-check-enable="true" data-chk-style="radio" data-radio-type="all" data-on-check="<?php echo $cFun.$type;?>S_NodeCheck" data-on-click="<?php echo $cFun.$type;?>S_NodeClick">'+
        		'<li data-id="0" data-pid="0">全部分类</li>'+html+
        		'</ul>'+
        		'<span style="margin-left:20px"><input type="checkbox" name="field[field_type_child]" value="1" data-toggle="icheck" data-label="对下级分类有效"></span>';
            	
		$("#<?php echo $cFun.$type;?>_ztree_select_select_box").remove();
		$("#<?php echo $cFun.$type;?>_ztree_select_box").html(htmls);
		$("#<?php echo $cFun.$type;?>_ztree_select_box").trigger('bjui.initUI');
	}else{
		$("#<?php echo $cFun.$type;?>field table").html('当前模块没有分类！');
	}
}//选择事件
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
    if ($from && $from.length) $from.val(names)
}
//单击事件
function <?php echo $cFun.$type;?>S_NodeClick(event, treeId, treeNode) {
    var zTree = $.fn.zTree.getZTreeObj(treeId)
    zTree.checkNode(treeNode, !treeNode.checked, true, true)
    event.preventDefault()
}


//提交方法
$(".<?php echo $cFun;?>sub").click(function(){
	var name = $("[name='field\[field_name\]']").val();
	var module = $("[name='field\[field_module\]']").val();
	var type_id = $("[name='field\[field_type_id\]']").val();
	if( name == '' ){
		$(this).alertmsg('error', '对不起，字段名字必须填写！');
	}else if( module == '' ){
		$(this).alertmsg('error', '对不起，请选择模块！');
	}else if( type_id == '' ){
		$(this).alertmsg('error', '对不起，请选择分类！');
	}else{
		var op=new Array();
		op['type'] = 'POST';
		op['data'] = $("[name=<?php echo $cFun;?>Form]").serializeArray();
		op['reload'] = 'false';
		<?php if($d) { echo "op['callback'] = '".$cFun."';";}?>
		$("[name='<?php echo $cFun;?>Form']").bjuiajax('ajaxForm',op);
	}
})


function <?php echo $cFun;?>add(){
	var html = '<li style="margin-bottom: 7px;"><select data-toggle="selectpicker" name="option[formtype][]" data-width="80" style="margin-left: 4px;">'+
		<?php
		foreach ($fromArr as $k=>$v)
		{
	    	echo '\'<option value="'.$k.'">'.$v.'</option>\'+';
	    }
		?>
		'</select>'+
		'<input type="text" class="form-control" name="option[title][]" placeholder="字段标题" style="width:80px;margin-left: 4px;">'+
		'<input type="text" class="form-control" name="option[option][]" placeholder="字段选项，多个用,隔开" style="width:200px;margin-left: 4px;">'+
		'<input type="text" class="form-control" name="option[default][]" placeholder="默认值" name="field[field_default]" style="width:80px;margin-left: 4px;">'+
		'<a href="javascript:<?php echo $cFun;?>add()" style="margin-left: 4px;"><i class="fa fa-plus-square"></i></a>'+
		'<a href="javascript:void(0)" onClick="<?php echo $cFun;?>unadd(this)" style="margin-left: 4px;"><i class="fa fa-minus-square"></i></a></li>';

	$("#<?php echo $cFun;?>formtype").append(html);
	$("#<?php echo $cFun;?>formtype select").selectpicker();
}
function <?php echo $cFun;?>unadd(obj){
	$(obj).parent().remove();
}



$(document).ready(function(){
	$('#<?php echo $cFun;?>module').change(function() {
		var op = new Array();
		op['type'] = 'GET';
		op['reload'] = 'false';
		op['url'] = "index.php?a=yes&c=system.config.field&t=gettype&module="+$(this).val();
		op['callback'] = '<?php echo $cFun.$type?>gettype';
		$(this).bjuiajax("doAjax",op);// 显示处理结果
	});
});
</script>
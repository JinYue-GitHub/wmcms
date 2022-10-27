<div class="bjui-pageContent">
    <form action="index.php?a=yes&c=operate.zt.type&t=<?php echo $type;?>" data-reload="false" data-toggle="validate" method="post" <?php if($d) { echo 'data-callback="'.$cFun.'"';}?>>
	<input name="type_id" id="<?php echo $cFun.$type;?>type_id" type="hidden" class="input-text" value="<?php echo $id;?>">
		<fieldset>
			<legend>专题分类编辑</legend>
            <ul class="nav nav-tabs" role="tablist">
            	<li class="active"><a href="#<?php echo $cFun.$type;?>base" role="tab" data-toggle="tab">基本设置</a></li>
            	<li><a href="#<?php echo $cFun.$type;?>seo" role="tab" data-toggle="tab">SEO设置</a></li>
            	<li><a href="#<?php echo $cFun.$type;?>templates" id="<?php echo $cFun.$type;?>templates_tab" role="tab" data-toggle="tab">模版设置</a></li>
            	<li><a href="#<?php echo $cFun.$type;?>html" role="tab" data-toggle="tab">HTML设置</a></li>
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
					      <td valign="top"><b>分类简称：</b></td>
					      <td valign="top"><input name="type[type_cname]" type="text" class="input-text" value="<?php echo C('type_cname',null,'data');?>"></td>
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
					      <td valign="top"><b>分类图标：</b></td>
					      <td valign="top" colspan="5"><input type="text" name="type[type_ico]" value="<?php echo C('type_ico',null,'data');?>" size="35">
								<span class="upload" data-uploader="index.php?a=yes&c=upload&t=img" data-on-upload-success="<?php echo $cFun;?>upload_success" data-file-type-exts="*.jpg;*.png;*.gif;*.mpg" data-toggle="upload" data-icon="cloud-upload"></span>
								<?php if( C('type_ico',null,'data') != '' ){ echo '<a target="_blank" href="'.C('type_ico',null,'data').'">点击查看图片</a>';}?>
						  </td>
						</tr>
					    <tr>
					      <td valign="top"><b>分类简介：</b></td>
					      <td valign="top"><textarea name="type[type_info]" class="form-control" cols="40" rows="6"><?php echo C('type_info',null,'data');?></textarea></td>
						</tr>
					</table>
				</div>

				<div class="tab-pane fade" id="<?php echo $cFun.$type;?>seo">
					<table class="table table-border table-bordered table-bg table-sort">
					    <tr>
					      <td valign="top" width="150"><b>自定义标题：</b></td>
					      <td valign="top"><input name="type[type_title]" type="text" class="input-text" size="40" value="<?php echo C('type_title',null,'data');?>"></td>
						</tr>
					    <tr>
					      <td valign="top"><b>自定义关键字：</b></td>
					      <td valign="top"><input name="type[type_key]" type="text" class="input-text" size="40" value="<?php echo C('type_key',null,'data');?>"></td>
						</tr>
					    <tr>
					      <td valign="top"><b>自定义描述：</b></td>
					      <td valign="top"><input name="type[type_desc]" type="text" class="input-text" size="40" value="<?php echo C('type_desc',null,'data');?>"></td>
						</tr>
					</table>
				</div>
				
				<div class="tab-pane fade" id="<?php echo $cFun.$type;?>templates">
					<table class="table table-border table-bordered table-bg table-sort">
					    <tr>
					      <td valign="top" width="150"><b>专题列表页模版：</b></td>
					      <td valign="top">
					      	<input id="type_tempid" name="type[type_tempid]" type="hidden" value="<?php echo C('type_tempid',null,'data');?>">
					      	<input id="temp_name" name="temp[temp_name]" type="text" value="<?php echo C('name',null,'temp');?>" data-toggle="lookup" data-url="index.php?c=system.templates.lookup&module=zt&page=list&name=name&tid=tempid" data-title="选择当前专题分类列表页模版" size="15" data-width="700" data-height="500">
					      	<a class="btn btn-default" href="index.php?c=system.templates.edit&t=add&module=zt&page=list&name=name&tid=tempid" data-toggle="dialog" data-title="上传新的专题列表页模版" data-width="540" data-height="450" ><i class="fa fa-cloud-upload">&nbsp;</i>上传模板</a>
					      </td>
						</tr>
					    <tr>
					      <td valign="top"><b>专题内容页模版：</b></td>
					      <td valign="top">
					      	<input id="type_ctempid" name="type[type_ctempid]" type="hidden" value="<?php echo C('type_ctempid',null,'data');?>">
					      	<input id="temp_cname" name="temp[temp_cname]" type="text" value="<?php echo C('cname',null,'temp');?>" data-toggle="lookup" data-url="index.php?c=system.templates.lookup&module=zt&page=content&name=cname&tid=ctempid" data-title="选择当前专题分类内容页模版" size="15" data-width="700" data-height="500">
					      	<a class="btn btn-default" href="index.php?c=system.templates.edit&t=add&module=zt&page=content&name=cname&tid=ctempid" data-toggle="dialog" data-title="上传新的专题内容页模版" data-width="540" data-height="450" ><i class="fa fa-cloud-upload">&nbsp;</i>上传模板</a>
					      </td>
						</tr>
					    <tr>
					      <td valign="top" colspan="2">不指定模版，将会使用当前应用的主题的自带模版！</td>
						</tr>
					</table>
				</div>
				
				<div class="tab-pane fade" id="<?php echo $cFun.$type;?>html">
					<table class="table table-border table-bordered table-bg table-sort">
					    <tr>
					      <td valign="top" width="150"><b>列表页静态路径：</b></td>
					      <td valign="top"><input name="html[list]" value="<?php echo C('list',null,'html');?>" type="text" class="input-text" size="40"></td>
						</tr>
					    <tr>
					      <td valign="top"><b>内容页静态路径：</b></td>
					      <td valign="top"><input name="html[content]" value="<?php echo C('content',null,'html');?>" type="text" class="input-text" size="40"></td>
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
	var op = operateZtTypelistGetOp();
	var tabid = 'zt-type_list';
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
    
    $("#<?php echo $cFun.$type;?>type_pid").val(ids);
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


	//用户清空模板文本框后设置模板id为空
	$('[data-toggle=lookup]').keyup(function(){
		if($(this).val()==''){
			$(this).parent().siblings('input').val('0');
		}
	});
});
</script>

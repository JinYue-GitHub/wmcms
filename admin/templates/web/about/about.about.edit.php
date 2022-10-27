<div class="bjui-pageContent">               
    <form action="index.php?a=yes&c=about.about&t=<?php echo $type;?>" data-reload="false" data-toggle="validate" method="post" <?php if($d) { echo 'data-callback="'.$cFun.'"';}?>>
	<input name="id[about_id]" id="<?php echo $cFun.$type;?>_cid" type="hidden" class="input-text" value="<?php echo $id;?>">
		<fieldset>
			<legend>信息内容编辑</legend>
			<ul class="nav nav-tabs" role="tablist">
            	<li class="active"><a href="#<?php echo $cFun.$type;?>base" role="tab" data-toggle="tab">基本信息</a></li>
            	<li><a href="#<?php echo $cFun.$type;?>field" id="<?php echo $cFun.$type;?>field_tab" role="tab" data-toggle="tab">自定义字段</a></li>
            </ul>
            <div class="tab-content">
            	<div class="tab-pane fade active in" id="<?php echo $cFun.$type;?>base">
		        	<table class="table table-condensed table-hover" width="100%">
		            <tbody>
		            	<tr>
						  <td colspan="3">
						  	<b>信 息 分 类：</b>
							<input id="<?php echo $cFun.$type;?>type_id" name="about[type_id]" type="hidden" value="<?php echo C('type_id',null,'data');?>">
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
		                    <td width="50%">
		                        <b>信 息 标 题：</b>
		                        <input type="text" name="about[about_name]" value="<?php echo C('about_name',null,'data');?>" data-rule="required">
		                    </td>
		                    <td>
		                        <b>信 息 拼 音：</b>
		                        <input type="text" name="about[about_pinyin]" value="<?php echo C('about_pinyin',null,'data');?>">
		                    </td>
		                </tr>
					    <tr>
					      <td colspan="2"><b>使 用 模 版：</b>
					      	<input id="about_ctempid" name="about[about_ctempid]" type="hidden" value="<?php echo C('about_ctempid',null,'data');?>">
					      	<input id="temp_cname" name="temp[temp_cname]" type="text" value="<?php echo C('cname',null,'temp');?>" data-toggle="lookup" data-url="index.php?c=system.templates.lookup&module=about&page=content&name=cname&tid=ctempid&rename=about" data-title="选择当前信息分类内容页模版" size="15" data-width="700" data-height="500">
					      	<a class="btn btn-default" href="index.php?c=system.templates.edit&t=add&module=about&page=content&name=cname&tid=ctempid&rename=about" data-toggle="dialog" data-mask="true" data-title="上传新的信息内容页模版" data-width="540" data-height="450" ><i class="fa fa-cloud-upload">&nbsp;</i>上传模板</a>
					      </td>
						</tr>
		                <tr>
		                    <td colspan="2">
		                        <b>页 面 标 题：</b>
		                        <input type="text" name="about[about_title]" value="<?php echo C('about_title',null,'data');?>" size="40">
		                    </td>
		                </tr>
		                <tr>
		                    <td colspan="2">
		                        <b>页面关键字：</b>
		                        <input type="text" name="about[about_key]" value="<?php echo C('about_key',null,'data');?>" size="40">
		                    </td>
		                </tr>
		                <tr>
		                    <td colspan="2">
		                        <b>页 面 描 述：</b>
		                        <input type="text" name="about[about_desc]" value="<?php echo C('about_desc',null,'data');?>" size="40">
		                    </td>
		                </tr>
		            	<tr>
						  <td colspan="4"><b>发 布 时 间：</b>
							<input type="text" name="about[about_time]" value="<?php echo date('Y-m-d H:i:s',C('about_time',null,'data'));?>" data-toggle="datepicker" data-pattern="yyyy-MM-dd H:m:s" size="19">
					      </td>
						</tr>
		                <tr>
		                   <td colspan="3">
		                        <b>信 息 内 容：</b>
		                        <div class="wm_form_content_box">
									<?php echo Ueditor('width: 90%;height:300px' , 'about[about_content]' , C('about_content',null,'data') , 'editor.about');?>
		                        </div>
		                    </td>
		                </tr>
		            </tbody>
		        </table>
		       </div>
		       
		       <div class="tab-pane fade" id="<?php echo $cFun.$type;?>field">
					<table class="table table-border table-bordered table-bg table-sort"></table>
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
//页面唯一op获取函数
function <?php echo $cFun;?>GetOp(){
	var op=new Array();
	op['type'] = 'POST';
	op['data'] = $("[name=<?php echo $cFun;?>Form]").serializeArray();
	return op;
}
//页面唯一回调函数
function <?php echo $cFun;?>(json){
	var op = aboutAboutListGetOp();
	var tabid = 'about-about-list';
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
    
    $("#<?php echo $cFun.$type;?>type_id").val(ids);
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


$(document).ready(function(){
	//自定义字段获取
	$("#<?php echo $cFun.$type;?>field_tab").click(function(){
		var cid = $("#<?php echo $cFun.$type;?>_cid").val();
		var tid = $("#<?php echo $cFun.$type;?>type_id").val();

		if( tid == '' ){
			$(this).alertmsg('error', '对不起，请先选择分类');
			return false;
		}else{
			var op = new Array();
			op['type'] = 'GET';
			op['url'] = 'index.php?a=yes&c=system.config.field&ft=2&t=getfield&module=<?php echo $curModule?>&tid='+tid+'&cid='+cid;
			op['callback'] = '<?php echo $cFun.$type;?>getfield';
			$(this).bjuiajax('doAjax', op);
		}
	});
});
</script>
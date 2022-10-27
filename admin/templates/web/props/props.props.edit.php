<div class="bjui-pageContent">               
    <form action="index.php?a=yes&c=props.props&t=<?php echo $type;?>" data-reload="false" data-toggle="validate" method="post" <?php if($d) { echo 'data-callback="'.$cFun.$type.'"';}?>>
	<input name="id[props_id]" id="<?php echo $cFun.$type;?>_id" type="hidden" class="input-text" value="<?php echo $id;?>">
		<fieldset>
			<legend>打赏道具信息编辑</legend>
			<ul class="nav nav-tabs" role="tablist">
            	<li class="active"><a href="#<?php echo $cFun.$type;?>base" role="tab" data-toggle="tab">基本信息</a></li>
            	<li><a href="#<?php echo $cFun.$type;?>option" role="tab" data-toggle="tab">道具属性</a></li>
            </ul>
            <div class="tab-content">
            	<div class="tab-pane fade active in" id="<?php echo $cFun.$type;?>base">
					<table class="table table-border table-bordered table-bg table-sort">
		            <tbody>
		            	<tr>
						  <td width="100"><b>道具分类：</b></td>
						  <td>
							<input id="<?php echo $cFun.$type;?>type_id" name="props[props_type_id]" type="hidden" value="<?php echo C('props_type_id',null,'data');?>">
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
		                    <td><b>道具名字：</b></td>
						  	<td>
		                        <input type="text" name="props[props_name]" value="<?php echo C('props_name',null,'data');?>" data-rule="required">
		                    </td>
		                </tr>
		                <tr>
		                   <td><b>道具封面：</b></td>
						  	<td>
		                        <input type="text" name="props[props_cover]" value="<?php echo C('props_cover',null,'data');?>" size="20">
								<span class="upload" data-uploader="index.php?a=yes&c=upload&t=img&module=author&utype=props&cid=<?php echo $id;?>" data-on-upload-success="<?php echo $cFun.$type;?>upload_success" data-file-type-exts="*.jpg;*.png;*.gif;*.mpg" data-toggle="upload" data-icon="cloud-upload"></span>
								<?php if( C('props_cover',null,'data') != '' ){ echo '<a target="_blank" href="'.C('props_cover',null,'data').'">点击查看图片</a>';}?>
		                    </td>
		                </tr>
		                <tr>
		                    <td><b>道具状态：</b></td>
						  	<td>
			                    <select data-toggle="selectpicker" name="props[props_status]">
	                            	<option value="1" <?php if(C('props_status',null,'data') == '1'){ echo 'selected=""';}?>>上架</option>
	                                <option value="0" <?php if(C('props_status',null,'data') == '0'){ echo 'selected=""';}?>>下架</option>
	                            </select>
		                    </td>
		                </tr>
		                <tr>
		                    <td><b>剩余库存：</b></td>
						  	<td>
		                        <input type="text" name="props[props_stock]" value="<?php echo C('props_stock',null,'data');?>" data-rule="required;digits" size="10">
		                    </td>
		                </tr>
		                <tr>
		                    <td><b>消费类型：</b></td>
						  	<td>
			                    <select data-toggle="selectpicker" name="props[props_cost]">
	                            	<option value="1" <?php if(C('props_cost',null,'data') == '1'){ echo 'selected=""';}?>><?php echo $propsSer->GetCost(1);?></option>
	                                <option value="2" <?php if(C('props_cost',null,'data') == '2'){ echo 'selected=""';}?>><?php echo $propsSer->GetCost(2);?></option>
	                            </select>
		                    </td>
		                </tr>
		                <tr id="<?php echo $cFun.$type;?>cost1">
		                    <td><b>站内货币：</b></td>
						  <td>
		                        <input type="text" name="props[props_gold1]" value="<?php echo C('props_gold1',null,'data');?>" data-rule="required;number" size="10"> <?php echo $userConfig['gold1_name']?>&nbsp;&nbsp;&nbsp;&nbsp;
		                        <input type="text" name="props[props_gold2]" value="<?php echo C('props_gold2',null,'data');?>" data-rule="required;number" size="10"> <?php echo $userConfig['gold2_name']?>
		                    </td>
		                </tr>
		                <tr id="<?php echo $cFun.$type;?>cost2">
		                    <td><b>现金价格：</b></td>
						  	<td>
		                        <input type="text" name="props[props_money]" value="<?php echo C('props_money',null,'data');?>" data-rule="required;number" size="10"> 元
		                    </td>
		                </tr>
		                <tr>
		                    <td><b>道具介绍：</b></td>
						  	<td>
		                        <textarea cols="35" rows="6" name="props[props_desc]"><?php echo C('props_desc',null,'data');?></textarea>
		                    </td>
		                </tr>
		                <tr>
		                    <td><b>显示顺序：</b></td>
						  	<td>
		                        <input type="text" name="props[props_order]" value="<?php echo C('props_order',null,'data');?>" data-rule="required;digits" size="10">
		                    </td>
		                </tr>
		            	<tr>
						  <td><b>入库时间：</b></td>
						  <td>
							<input type="text" name="props[props_time]" value="<?php echo date('Y-m-d H:i:s',C('props_time',null,'data'));?>" data-toggle="datepicker" data-pattern="yyyy-MM-dd H:m:s" size="19">
					      </td>
						</tr>
		            </tbody>
		        	</table>
				</div>
				
				<div class="tab-pane fade" id="<?php echo $cFun.$type;?>option">
					<table class="table table-border table-bordered table-bg table-sort">
		            <tbody>
		                <tr>
		                    <td colspan="2"><b style="color:red">使用道具后获得效果</b></td>
		                </tr>
		                <tr>
		                    <td width="150"><b><?php echo $userConfig['ticket_rec'];?>增加：</b></td>
		                    <td>
		                    	<input type="text" name="option[rec]" value="<?php echo C('rec',null,'option');?>" data-rule="required;digits" size="10">
		                    </td>
		                </tr>
		                <tr>
		                   <td><b><?php echo $userConfig['ticket_month'];?>增加：</b></td>
		                    <td>
		                    	<input type="text" name="option[month]" value="<?php echo C('month',null,'option');?>" data-rule="required;digits" size="10">
		                    </td>
		                </tr>
		                <tr>
		                    <td><b>作者经验增加：</b></td>
		                    <td>
		                    	<input type="text" name="option[author_exp]" value="<?php echo C('author_exp',null,'option');?>" data-rule="required;digits" size="10">
		                    </td>
		                </tr>
		                <tr>
		                    <td><b>粉丝经验增加：</b></td>
		                    <td>
		                    	<input type="text" name="option[fans_exp]" value="<?php echo C('fans_exp',null,'option');?>" data-rule="required;digits" size="10">
		                    </td>
		                </tr>
		                <tr>
		                    <td><b>用户经验增加：</b></td>
		                    <td>
		                    	<input type="text" name="option[user_exp]" value="<?php echo C('user_exp',null,'option');?>" data-rule="required;digits" size="10">
		                    </td>
		                </tr>
		            </tbody>
		        	</table>
				</div>
			</fieldset>
		</form>
	</div>
</div>

<div class="bjui-pageFooter">
    <ul>
        <li><button type="button" class="btn-close" data-icon="close">取消</button></li>
        <li><button type="submit" class="btn-green" data-icon="save">保存</button></li>
    </ul>
</div>


<script>
//页面唯一op获取函数
function <?php echo $cFun.$type;?>GetOp(){
	var op=new Array();
	op['type'] = 'POST';
	op['data'] = $("[name=<?php echo $cFun.$type;?>Form]").serializeArray();
	return op;
}
//页面唯一回调函数
function <?php echo $cFun.$type;?>(json){
	var op = propsPropsListGetOp();
	var tabid = 'author-props-propslist';
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
  if ($from && $from.length) $from.val(names)
}
//单击事件
function <?php echo $cFun.$type;?>S_NodeClick(event, treeId, treeNode) {
  var zTree = $.fn.zTree.getZTreeObj(treeId)
  zTree.checkNode(treeNode, !treeNode.checked, true, true)
  event.preventDefault()
}
</script>
<div class="bjui-pageContent">	                       
    <form action="index.php?a=yes&c=link.link&t=<?php echo $type;?>" data-reload="false" data-toggle="validate" method="post" <?php if($d) { echo 'data-callback="'.$cFun.'"';}?>>
	<input name="id[link_id]" id="<?php echo $cFun.$type;?>_cid" type="hidden" class="input-text" value="<?php echo $id;?>">
		<fieldset>
			<legend>友链内容编辑</legend>
            <ul class="nav nav-tabs" role="tablist">
            	<li class="active"><a href="#<?php echo $cFun.$type;?>base" role="tab" data-toggle="tab">基本信息</a></li>
            	<li><a href="#<?php echo $cFun.$type;?>senior" id="<?php echo $cFun.$type;?>senior_tab" role="tab" data-toggle="tab">高级信息</a></li>
            	<li><a href="#<?php echo $cFun.$type;?>field" id="<?php echo $cFun.$type;?>field_tab" role="tab" data-toggle="tab">自定义字段</a></li>
            </ul>
            <div class="tab-content">
            	<div class="tab-pane fade active in" id="<?php echo $cFun.$type;?>base">
		        	<table class="table table-condensed table-hover" width="100%">
		            <tbody>
		            	<tr>
						  <td colspan="2">
						  	<b>友链分类：</b>
							<input id="<?php echo $cFun.$type;?>type_id" name="link[type_id]" type="hidden" value="<?php echo C('type_id',null,'data');?>">
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
		                        <b>友链名字：</b>
		                        <input type="text" name="link[link_name]" value="<?php echo C('link_name',null,'data');?>" data-rule="required">
					                        　　　<b>友链拼音：</b>
		                        <input type="text" name="link[link_pinyin]" value="<?php echo C('link_pinyin',null,'data');?>" size="10">
		                    </td>
		                    <td>
		                        <b>友链简称：</b>
		                        <input type="text" name="link[link_cname]" value="<?php echo C('link_cname',null,'data');?>">
		                    </td>
		                </tr>
		                <tr>
		                    <td>
		                        <b>回链地址：</b>
		                        <input type="text" name="link[link_url]" value="<?php echo C('link_url',null,'data');?>" data-rule="required" size="40">
		                    </td>
		                    <td>
		                        <b>点入跳转：</b>
		                        <input type="text" name="link[link_in_jump]" value="<?php echo C('link_in_jump',null,'data');?>" size="40">
		                    </td>
		                </tr>
		                <tr>
		                    <td>
		                        <b>友链状态：</b>
		                        <select data-toggle="selectpicker" name="link[link_status]">
                                    <option value="1" <?php if(C('link_status',null,'data') == '1'){ echo 'selected=""';}?>>审核通过</option>
                                    <option value="0" <?php if(C('link_status',null,'data') == '0'){ echo 'selected=""';}?>>待审核</option>
                                </select>
		                    </td>
		                    <td>
		                        <b>友链状态：</b>
		                        <select data-toggle="selectpicker" name="link[link_show]">
                                    <option value="1" <?php if(C('link_show',null,'data') == '1'){ echo 'selected=""';}?>>显示链接</option>
                                    <option value="0" <?php if(C('link_show',null,'data') == '0'){ echo 'selected=""';}?>>显示直链</option>
                                </select>
		                    </td>
		                </tr>
		                <tr>
		                    <td>
		                        <b>是否固链：</b>
		                        <select data-toggle="selectpicker" name="link[link_fixed]">
                                    <option value="1" <?php if(C('link_fixed',null,'data') == '1'){ echo 'selected=""';}?>>已固链</option>
                                    <option value="0" <?php if(C('link_fixed',null,'data') == '0'){ echo 'selected=""';}?>>未固链</option>
                                </select>
		                    </td>
		                    <td>
		                        <b>是否推荐：</b>
		                        <select data-toggle="selectpicker" name="link[link_rec]">
                                    <option value="1" <?php if(C('link_rec',null,'data') == '1'){ echo 'selected=""';}?>>已推荐</option>
                                    <option value="0" <?php if(C('link_rec',null,'data') == '0'){ echo 'selected=""';}?>>未推荐</option>
                                </select>
		                    </td>
		                </tr>
		                <tr>
		                   <td colspan="3">
		                        <b>友链图标：</b>
		                        <input type="text" id="link_ico" name="link[link_ico]" value="<?php echo C('link_ico',null,'data');?>" size="40">
								<span class="upload" data-uploader="index.php?a=yes&c=upload&t=img" data-on-upload-success="<?php echo $cFun.$type;?>upload_success" data-file-type-exts="*.jpg;*.png;*.gif;*.mpg" data-toggle="upload" data-icon="cloud-upload"></span>
								<?php if( C('link_ico',null,'data') != '' ){ echo '<a target="_blank" href="'.C('link_ico',null,'data').'">点击查看图片</a>';}?>
		                    </td>
		                </tr>
		                <tr>
		                   <td>
		                        <b>友链排序：</b>
		                        <input type="text" name="link[link_order]" value="<?php echo C('link_order',null,'data');?>">
		                    </td>
		                   <td>
		                        <b>联系QQ：</b>
		                        <input type="text" name="link[link_qq]" value="<?php echo C('link_qq',null,'data');?>">
		                    </td>
		                </tr>
		                <tr>
		                   <td colspan="2">
		                        <b>友链简介：</b>
		                        <textarea cols="70" rows="1" name="link[link_info]" data-toggle="autoheight"><?php echo C('link_info',null,'data');?></textarea>
		                    </td>
		                </tr>
		            </tbody>
		        </table>
		       </div>
  
		       <div class="tab-pane fade" id="<?php echo $cFun.$type;?>senior">
		       		<table class="table table-condensed table-hover" width="100%">
		            <tbody>
		            	<tr>
						  <td colspan="2"><b> 热门度 ：</b>
							<input size="7" name="link[link_read]" value="<?php echo C('link_read',null,'data');?>">
					     </td>
						  <td colspan="2"><b> 顶 ：</b>
							<input size="7" name="link[link_ding]" value="<?php echo C('link_ding',null,'data');?>">
					      </td>
						  <td colspan="2"><b> 踩 ：</b>
							<input size="7" name="link[link_cai]" value="<?php echo C('link_cai',null,'data');?>">
					      </td>
						</tr>
		            	<tr>
						  <td><b> 日 点 入 ：</b>
							<input size="7" name="link[link_inday]" value="<?php echo C('link_inday',null,'data');?>">
					     </td>
						  <td><b> 周 点 入 ：</b>
							<input size="7" name="link[link_inweek]" value="<?php echo C('link_inweek',null,'data');?>">
					      </td>
						  <td><b> 月 点 入 ：</b>
							<input size="7" name="link[link_inmonth]" value="<?php echo C('link_inmonth',null,'data');?>">
					      </td>
						  <td><b> 年 点 入 ：</b>
							<input size="7" name="link[link_inyear]" value="<?php echo C('link_inyear',null,'data');?>">
					      </td>
						  <td><b> 总 点 入 ：</b>
							<input size="7" name="link[link_insum]" value="<?php echo C('link_insum',null,'data');?>">
					      </td>
						</tr>
		            	<tr>
						  <td><b> 日 点 出 ：</b>
							<input size="7" name="link[link_outday]" value="<?php echo C('link_outday',null,'data');?>">
					     </td>
						  <td><b> 周 点 出 ：</b>
							<input size="7" name="link[link_outweek]" value="<?php echo C('link_outweek',null,'data');?>">
					      </td>
						  <td><b> 月 点 出 ：</b>
							<input size="7" name="link[link_outmonth]" value="<?php echo C('link_outmonth',null,'data');?>">
					      </td>
						  <td><b> 年 点 出 ：</b>
							<input size="7" name="link[link_outyear]" value="<?php echo C('link_outyear',null,'data');?>">
					      </td>
						  <td><b> 总 点 出 ：</b>
							<input size="7" name="link[link_outsum]" value="<?php echo C('link_outsum',null,'data');?>">
					      </td>
						</tr>
		            	<tr>
						  <td colspan="2"><b>最后点入时间：</b>
							<input type="text" name="link[link_lastintime]" value="<?php echo date('Y-m-d H:i:s',C('link_lastintime',null,'data'));?>" data-toggle="datepicker" data-pattern="yyyy-MM-dd H:m:s" size="19">
					      </td>
						  <td colspan="2"><b>最后点出时间：</b>
							<input type="text" name="link[link_lastouttime]" value="<?php echo date('Y-m-d H:i:s',C('link_lastouttime',null,'data'));?>" data-toggle="datepicker" data-pattern="yyyy-MM-dd H:m:s" size="19">
					      </td>
						  <td colspan="2"><b>友链加入时间：</b>
							<input type="text" name="link[link_jointime]" value="<?php echo date('Y-m-d H:i:s',C('link_jointime',null,'data'));?>" data-toggle="datepicker" data-pattern="yyyy-MM-dd H:m:s" size="19">
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
	if ( json.statusCode == 300){
		$(this).bjuiajax("ajaxDone",json);// 显示处理结果
	}else{
		var op = linkLinkListGetOp();
		var tabid = 'link-link-list';
		op['id'] = tabid;
		
		$(this).bjuiajax("ajaxDone",json);// 显示处理结果
	    $(this).navtab("reload",op);	// 刷新Tab页面
	    $(this).navtab("switchTab",tabid);	// 切换Tab页面
	}
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
	$("#<?php echo $cFun.$type;?>senior_tab").click(function(){
		$('.bjui-lookup').css("line-height",'22px');
		$('.bjui-lookup').css("height",'22px');
	});


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
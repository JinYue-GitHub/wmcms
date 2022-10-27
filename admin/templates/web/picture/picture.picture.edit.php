<link href="/files/js/webupload/webuploader.css" rel="stylesheet" type="text/css" />
<style>
.uploader-list-container .filelist li .del{cursor: pointer;z-index:999;position: absolute;right:5px;top:5px;width:16px;height:16px;background: url(/files/js/webupload/images/icons.png) no-repeat;background-position: -49px 0px;}
</style>
<div class="bjui-pageContent">               
    <form action="index.php?a=yes&c=picture.picture&t=<?php echo $type;?>" data-reload="false" data-toggle="validate" method="post" <?php if($d) { echo 'data-callback="'.$cFun.'"';}?>>
	<input name="id[picture_id]" id="<?php echo $cFun.$type;?>_cid" type="hidden" class="input-text" value="<?php echo $id;?>">
		<fieldset>
			<legend>图集内容编辑</legend>
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
						  <td colspan="3">
						  	<b>图 集 分 类：</b>
							<input id="<?php echo $cFun.$type;?>type_id" name="picture[type_id]" type="hidden" value="<?php echo C('type_id',null,'data');?>">
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
		                        <b>图 集 标 题：</b>
		                        <input type="text" name="picture[picture_name]" value="<?php echo C('picture_name',null,'data');?>" data-rule="required">
		                    </td>
		                    <td>
		                        <b>图集短标题：</b>
		                        <input type="text" name="picture[picture_cname]" value="<?php echo C('picture_cname',null,'data');?>">
		                    </td>
		                </tr>
		                <tr>
		                    <td>
		                        <b>图 集 状 态：</b>
		                        <select data-toggle="selectpicker" name="picture[picture_status]">
                                    <option value="1" <?php if(C('picture_status',null,'data') == '1'){ echo 'selected=""';}?>>已审核</option>
                                    <option value="0" <?php if(C('picture_status',null,'data') == '0'){ echo 'selected=""';}?>>待审核</option>
                                </select>
		                    </td>
		                    <td>
		                        <b>是 否 推 荐：</b>
		                        <select data-toggle="selectpicker" name="picture[picture_rec]">
                                    <option value="1" <?php if(C('picture_rec',null,'data') == '1'){ echo 'selected=""';}?>>已推荐</option>
                                    <option value="0" <?php if(C('picture_rec',null,'data') == '0'){ echo 'selected=""';}?>>未推荐</option>
                                </select>
		                    </td>
		                </tr>
		                <tr>
		                   <td colspan="3">
		                        <b>图 集 封 面：</b>
		                        <input type="text" id="picture_simg" name="picture[picture_simg]" value="<?php echo C('picture_simg',null,'data');?>" size="40">
								<span class="upload" data-uploader="index.php?a=yes&c=upload&t=img" data-on-upload-success="<?php echo $cFun;?>upload_success" data-file-type-exts="*.jpg;*.png;*.gif;*.mpg" data-toggle="upload" data-icon="cloud-upload"></span>
								<?php if( C('picture_simg',null,'data') != '' ){ echo '<a target="_blank" href="'.C('picture_simg',null,'data').'">点击查看图片</a>';}?>
		                    </td>
		                </tr>
		                <tr>
		                   <td colspan="3">
		                        <b>图 集 标 签：</b>
		                        <input type="text" data-tags="546" data-tagsArr="123" id="picture_tags" name="picture[picture_tags]" size="40" value="<?php echo C('picture_tags',null,'data');?>">
		                    </td>
		                </tr>
		                <tr>
		                   <td colspan="3">
		                        <b>图 集 简 介：</b>
		                        <textarea cols="70" rows="1" name="picture[picture_info]" data-toggle="autoheight"><?php echo C('picture_info',null,'data');?></textarea>
		                    </td>
		                </tr>
		                <tr>
		                   <td colspan="3">
							<div class="formControls">
								<div class="uploader-list-container"> 
									<div class="queueList">
										<div id="<?php echo $cFun.$type;?>dndArea" class="placeholder <?php if($type == 'edit'){echo ' element-invisible';}?>">
											<div id="<?php echo $cFun.$type;?>filePicker-2"></div>
											<p>或将照片拖到这里，最多可上传30张</p>
										</div>
										<?php
										if($type == 'edit' && $picArr)
										{
											echo '<ul class="filelist">';
											$size = 0;
											foreach ($picArr as $k=>$v)
											{
												$size = $size+$v['upload_size'];
												echo '<li class="state-complete">
														<div class="del" onClick="DelWebUpload(this)" data-id='.$v['upload_id'].'></div>
														<p class="imgWrap"><img style="border-radius:5px" src="'.$v['upload_img'].'" width="160" height="160"></p>
														<p class="progress" style="display: none;"><span style="display: none; width: 0px;"></span></p>
														<p class="alt">
														<input type="hidden" value="'.$v['upload_img'].'" name="pic[src]['.$v['upload_id'].']">
														<input type="text" size="16" class="form-control" placeholder="请填写注释" value="'.$v['upload_alt'].'" name="pic[alt]['.$v['upload_id'].']"></p>
														<span class="success"></span>
		        										<div class="file-panel"><span class="cancel">删除</span><span class="rotateRight">向右旋转</span><span class="rotateLeft">向左旋转</span></div>
		        										</li>';
											}
											echo '</ul>';
										}
										?>
	
									</div>
									<div class="statusBar" <?php if($type == 'add'){echo 'style="display:none;"';}?> >
										<div class="progress l"> <span class="text">0%</span> <span class="percentage"></span> </div>
										<div class="info"></div>
										<div class="btns">
											<div id="<?php echo $cFun.$type;?>filePicker2"></div>
											<!-- <div class="uploadBtn">开始上传</div> -->
										</div>
									</div>
								</div>
							 </div>
		                    </td>
		                </tr>
		                <tr>
		                   <td colspan="3">
		                        <b>图 集 内 容：</b>
		                        <div class="wm_form_content_box">
									<?php echo Ueditor('width: 90%;height:300px' , 'picture[picture_content]' , C('picture_content',null,'data'), 'editor.picture');?>
		                        </div>
		                    </td>
		                </tr>
		            </tbody>
		        </table>
		       </div>
		       
		       <div class="tab-pane fade" id="<?php echo $cFun.$type;?>senior">
		       		<table class="table table-condensed table-hover" width="100%">
		            <tbody>
		            	<tr>
						  <td><b> 阅读量 ：</b>
							<input size="7" name="picture[picture_read]" value="<?php echo C('picture_read',null,'data');?>">
					     </td>
						  <td><b> 评论数 ：</b>
							<input size="7" name="picture[picture_replay]" value="<?php echo C('picture_replay',null,'data');?>">
					      </td>
						  <td><b> 顶 ：</b>
							<input size="7" name="picture[picture_ding]" value="<?php echo C('picture_ding',null,'data');?>">
					      </td>
						  <td><b> 踩 ：</b>
							<input size="7" name="picture[picture_cai]" value="<?php echo C('picture_cai',null,'data');?>">
					      </td>
						  <td><b> 星 级 ：</b>
							<input size="7" name="picture[picture_start]" value="<?php echo C('picture_start',null,'data');?>">
					      </td>
						  <td><b> 评 分 ：</b>
							<input size="7" name="picture[picture_score]" value="<?php echo C('picture_score',null,'data');?>">
					      </td>
						</tr>
		            	<tr>
						  <td colspan="6"><b>发 布 时 间：</b>
							<input type="text" name="picture[picture_time]" value="<?php echo date('Y-m-d H:i:s',C('picture_time',null,'data'));?>" data-toggle="datepicker" data-pattern="yyyy-MM-dd H:m:s" size="19">
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
pageCFun = '<?php echo $cFun.$type;?>';
pageModule = 'picture';
pageCid = '<?php echo $id;?>';
//页面唯一op获取函数
function <?php echo $cFun;?>GetOp(){
	var op=new Array();
	op['type'] = 'POST';
	op['data'] = $("[name=<?php echo $cFun;?>Form]").serializeArray();
	return op;
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
//页面唯一回调函数
function <?php echo $cFun;?>(json){
	var op = picturePictureListGetOp();
	var tabid = 'picture-picture-list';
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
<script type="text/javascript" src="/files/js/webupload/webuploader.min.js"></script> 
<script type="text/javascript" src="<?php echo $tempPath;?>/BJUI/js/webupload.js"></script> 
<script>
$(".info").html('共<?php echo count($picArr)?>张（<?php echo $size/1000;?>KB）');
</script>
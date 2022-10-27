<div class="bjui-pageContent">	                       
    <form action="index.php?a=yes&c=article.article&t=<?php echo $type;?>" data-reload="false" data-toggle="validate" method="post" <?php if($d) { echo 'data-callback="'.$cFun.'"';}?>>
	<input name="article_id" id="<?php echo $cFun.$type;?>_cid" type="hidden" class="input-text" value="<?php echo $id;?>">
		<fieldset>
			<legend>文章内容编辑</legend>
            <ul class="nav nav-tabs" role="tablist">
            	<li class="active"><a href="#<?php echo $cFun.$type;?>base" role="tab" data-toggle="tab">基本信息</a></li>
            	<li><a href="#<?php echo $cFun.$type;?>senior" id="<?php echo $cFun;?>senior_tab" role="tab" data-toggle="tab">高级信息</a></li>
            	<li><a href="#<?php echo $cFun.$type;?>field" id="<?php echo $cFun.$type;?>field_tab" role="tab" data-toggle="tab">自定义字段</a></li>
            </ul>
            <div class="tab-content">
            	<div class="tab-pane fade active in" id="<?php echo $cFun.$type;?>base">
		        	<table class="table table-condensed table-hover" width="100%">
		            <tbody>
		            	<tr>
						  <td colspan="3">
						  	<b>文章分类：</b>
							<input id="<?php echo $cFun.$type;?>type_id" name="article[type_id]" type="hidden" value="<?php echo C('type_id',null,'data');?>">
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
		                        <b>文章标题：</b>
		                        <input type="text" name="article[article_name]" value="<?php echo str::DelHtml(C('article_name',null,'data'));?>" data-rule="文章标题:required" size="40">
		                    </td>
		                    <td>
		                        <b>文章短标题：</b>
		                        <input type="text" name="article[article_cname]" value="<?php echo C('article_cname',null,'data');?>" size="30">
		                    </td>
		                </tr>
		                <tr>
		                   <td colspan="3">
		                        <b>标题颜色：</b>
		                        <input type="text" id="color" name="article[article_color]" value="<?php echo C('article_color',null,'data');?>" data-toggle="colorpicker" size="15" readonly>
		                        <a href="javascript:;" title="清除颜色" data-toggle="clearcolor" data-target="#color">清除颜色</a>
		                    </td>
		                </tr>
		                <tr>
		                    <td colspan="3">
		                        <b>文章状态：</b>
		                        <select data-toggle="selectpicker" name="article[article_status]">
                                    <option value="1" <?php if(C('article_status',null,'data') == '1'){ echo 'selected=""';}?>>审核通过</option>
                                    <option value="0" <?php if(C('article_status',null,'data') == '0'){ echo 'selected=""';}?>>待审核</option>
                                </select>
		                    </td>
		                </tr>
		                <tr>
		                    <td colspan="3">
		                        <b>文章属性：</b>
		                        <?php
		                        foreach ($attrArr as $k=>$v)
		                        {
		                        	$checked = str::CheckElse(C('article_'.$k,null,'data'), 1 , 'checked');
		                        	echo '<input '.$checked.' type="checkbox" name="article[article_'.$k.']" value="1" data-toggle="icheck" data-label="'.$v.'">';
		                        }
		                        ?>
		                    </td>
		                </tr>
		                <tr>
		                   <td colspan="3">
		                        <b>文章封面：</b>
		                        <input type="checkbox" name="down_simg" value="1" data-toggle="icheck" data-label="下载远程图片">
		                        <input type="text" id="article_simg" name="article[article_simg]" value="<?php echo C('article_simg',null,'data');?>" size="40">
								<span class="upload" data-uploader="index.php?a=yes&c=upload&t=img" data-on-upload-success="<?php echo $cFun;?>upload_success" data-file-type-exts="*.jpg;*.png;*.gif;*.mpg" data-toggle="upload" data-icon="cloud-upload"></span>
								<?php if( C('article_simg',null,'data') != '' ){ echo '<a target="_blank" href="'.C('article_simg',null,'data').'">点击查看图片</a>';}?>
		                    </td>
		                </tr>
		                <tr>
		                   <td>
		                        <b>文章标签：</b>
		                        <input type="text" data-tags="546" data-tagsArr="123" id="article_tags" name="article[article_tags]" size="40" value="<?php echo C('article_tags',null,'data');?>">
		                    </td>
		                   <td>
		                        <b>文章来源：</b>
		                        <input type="text" data-toggle="lookup" data-url="index.php?c=article.author.lookup&st=s" data-width="520" data-title="选择来源" name="article[article_source]" value="<?php echo C('article_source',null,'data');?>">
		                    </td>
		                </tr>
		                <tr>
		                   <td>
		                        <b>文章作者：</b>
		                        <input type="text" data-toggle="lookup" data-url="index.php?c=article.author.lookup&st=a" data-width="520" data-title="选择作者" name="article[article_author]" value="<?php echo C('article_author',null,'data');?>">
		                    </td>
		                   <td>
		                        <b>文章编辑：</b>
		                        <input type="text" data-toggle="lookup" data-url="index.php?c=article.author.lookup&st=e" data-width="520" data-title="选择编辑" name="article[article_editor]" value="<?php echo C('article_editor',null,'data');?>">
		                    </td>
		                </tr>
		                <tr>
		                   <td colspan="3">
		                        <b>文章预览：</b>
		                        <textarea cols="70" rows="1" name="article[article_info]" data-toggle="autoheight"><?php echo C('article_info',null,'data');?></textarea>
		                    </td>
		                </tr>
		                
		                <tr>
		                   <td colspan="4">
		                        <b>文章内容：</b>
		                    </td>
		                </tr>
		                <tr>
		                   <td colspan="4">
		                        <div class="wm_form_content_box">
		                        	<?php echo Ueditor('width: 100%;height:300px' , 'article[article_content]' , C('article_content',null,'data') , 'editor.article');?>
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
						  <td colspan="4"><b>跳转链接：</b>
							<input name="article[article_url]" size="40" value="<?php echo C('article_url',null,'data');?>" placeholder="不跳转请留空">
					      </td>
						</tr>
		                <tr>
		                    <td colspan="4">
		                        <b>是否显示：</b>
		                        <select data-toggle="selectpicker" name="article[article_display]">
                                    <option value="1" <?php if(C('article_display',null,'data') == '1'){ echo 'selected=""';}?>>显示、生成html</option>
                                    <option value="0" <?php if(C('article_display',null,'data') == '0'){ echo 'selected=""';}?>>不显示、不生成html</option>
                                </select>
		                    </td>
		                </tr>
		            	<tr>
						  <td><b>文章权重：</b>
							<input size="7" name="article[article_weight]" value="<?php echo C('article_weight',null,'data');?>">
					      </td>
						  <td colspan="3">越小越靠前 </td>
						</tr>
		            	<tr>
						  <td width="200px"><b>文章评分：</b>
							<input size="7" name="article[article_score]" value="<?php echo C('article_score',null,'data');?>">
							
					      </td>
						  <td colspan="3" >总分五分制 </td>
						</tr>
		            	<tr>
						  <td><b> 阅 读 量 ：</b>
							<input size="7" name="article[article_read]" value="<?php echo C('article_read',null,'data');?>">
					     </td>
						  <td><b> 评 论 量 ：</b>
							<input size="7" name="article[article_replay]" value="<?php echo C('article_replay',null,'data');?>">
					      </td>
						  <td><b> 文 章 顶 ：</b>
							<input size="7" name="article[article_ding]" value="<?php echo C('article_ding',null,'data');?>">
					      </td>
						  <td><b> 文 章 踩 ：</b>
							<input size="7" name="article[article_cai]" value="<?php echo C('article_cai',null,'data');?>">
					      </td>
						</tr>
		            	<tr>
						  <td colspan="4"><b>文章发布时间：</b>
							<input type="text" name="article[article_addtime]" value="<?php echo date('Y-m-d H:i:s',C('article_addtime',null,'data'));?>" data-toggle="datepicker" data-pattern="yyyy-MM-dd H:m:s" size="19">
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
	var op = articleArticleListGetOp();
	var tabid = 'article-article-list';
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
	$("#<?php echo $cFun;?>senior_tab").click(function(){
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
<div class="bjui-pageContent">
    <form action="index.php?a=yes&c=novel.novel&t=<?php echo $type;?>" data-reload="false" data-toggle="validate" method="post" <?php if($d) { echo 'data-callback="'.$cFun.'"';}?>>
	<input name="novel_id" id="<?php echo $cFun.$type;?>_cid" type="hidden" class="input-text" value="<?php echo $id;?>">
		<fieldset>
			<legend>小说内容编辑</legend>
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
						  	<b>小说分类：</b>
							<input id="<?php echo $cFun.$type;?>type_id" name="novel[type_id]" type="hidden" value="<?php echo C('type_id',null,'data');?>">
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
		                        <b>小说名字：</b>
		                        <input type="text" name="novel[novel_name]" value="<?php echo C('novel_name',null,'data');?>" data-rule="小说名字:required">
		                    </td>
		                    <td>
		                        <b>小说拼音：</b>
		                        <input type="text" name="novel[novel_pinyin]" value="<?php echo C('novel_pinyin',null,'data');?>" size="15">
		                    </td>
		                </tr>
		                <tr>
		                    <td colspan="2">
		                        <b>推荐标题：</b>
		                        <input type="text" name="rec[rec_rt]" value="<?php echo C('rec_rt',null,'data');?>" size="30">
		                    </td>
		                </tr>
		                <tr>
		                    <td colspan="2">
		                        <b>推荐属性：</b>
		                        <?php 
		                        foreach ($recArr as $k=>$v)
		                        {
		                        	$checked = str::CheckElse( C($k,null,'data') , 1 , 'checked');
		                        	echo '<input type="checkbox" name="rec['.$k.']" value="1" data-toggle="icheck" data-label="'.$v.'" '.$checked.'>';
		                        }
		                        ?>
		                    </td>
		                </tr>
		                <tr>
		                    <td>
		                        <b>小说状态：</b>
		                        <select data-toggle="selectpicker" name="novel[novel_status]">
                                    <option value="1" <?php if(C('novel_status',null,'data') == '1'){ echo 'selected=""';}?>>审核通过</option>
                                    <option value="0" <?php if(C('novel_status',null,'data') == '0'){ echo 'selected=""';}?>>待审核</option>
                                </select>
		                    </td>
		                    <td>
		                        <b>小说进程：</b>
		                        <select data-toggle="selectpicker" name="novel[novel_process]">
                                    <option value="1" <?php if(C('novel_process',null,'data') == '1'){ echo 'selected=""';}?>>连载中</option>
                                    <option value="2" <?php if(C('novel_process',null,'data') == '2'){ echo 'selected=""';}?>>已完结</option>
                                    <option value="3" <?php if(C('novel_process',null,'data') == '3'){ echo 'selected=""';}?>>已断更</option>
                                </select>
		                    </td>
		                </tr>
		                <tr>
		                    <td colspan="2">
		                        <b>小说类型：</b>
		                        <select data-toggle="selectpicker" name="novel[novel_type]">
                                    <option value="1" <?php if(C('novel_type',null,'data') == '1'){ echo 'selected=""';}?>>本站原创</option>
                                    <option value="2" <?php if(C('novel_type',null,'data') == '2'){ echo 'selected=""';}?>>他站首发</option>
                                </select>
		                    </td>
		                </tr>
		                <tr>
		                    <td>
		                        <b>小说作者：</b>
		                        <input type="text" name="novel[novel_author]" value="<?php echo C('novel_author',null,'data');?>" data-rule="小说作者:required" size="15">
		                    </td>
		                    <td>
		                        <b>前台管理：</b>
		                        <input type="text" name="novel[author_id]" value="<?php echo C('author_id',null,'data');?>" size="10" data-rule="digits">
								前台用户的id
		                    </td>
		                </tr>
		                <tr>
		                   <td colspan="2">
		                        <b>小说封面：</b>
		                        <input type="checkbox" name="down_cover" value="1" data-toggle="icheck" data-label="下载远程图片">
		                        <input type="text" id="novel_cover" name="novel[novel_cover]" value="<?php echo C('novel_cover',null,'data');?>" size="30">
								<span class="upload" data-uploader="index.php?a=yes&c=upload&t=img" data-on-upload-success="<?php echo $cFun.$type;?>upload_success" data-file-type-exts="*.jpg;*.png;*.gif;*.mpg" data-toggle="upload" data-icon="cloud-upload"></span>
								<?php if( C('novel_cover',null,'data') != '' ){ echo '<a target="_blank" href="'.C('novel_cover',null,'data').'">点击查看图片</a>';}?>
		                    </td>
		                </tr>
		                <tr>
		                   <td colspan="2">
		                        <b>小说标签：</b>
		                        <input type="text" name="novel[novel_tags]" size="30" value="<?php echo C('novel_tags',null,'data');?>">
		                    </td>
		                </tr>
		                <tr>
		                   <td>
		                        <b>最新章节：</b>
		                        <input type="text" data-rule="required" name="novel[novel_newcname]" value="<?php echo C('novel_newcname',null,'data');?>">
		                    </td>
		                   <td>
		                        <b>最新章节id：</b>
		                        <input type="text" data-rule="required;digits" name="novel[novel_newcid]" value="<?php echo C('novel_newcid',null,'data');?>" size="10">
		                    </td>
		                </tr>
		                <tr>
		                   <td colspan="2">
		                        <b>编辑点评：</b>
		                        <textarea cols="70" rows="3" name="novel[novel_comment]"><?php echo C('novel_comment',null,'data');?></textarea>
		                    </td>
		                </tr>
		                <tr>
		                   <td colspan="2">
		                        <b>小说简介：</b>
		                        <textarea cols="70" rows="6" name="novel[novel_info]"><?php echo C('novel_info',null,'data');?></textarea>
		                    </td>
		                </tr>
		            </tbody>
		        </table>
		       </div>
		       
		       <div class="tab-pane fade" id="<?php echo $cFun.$type;?>senior">
		       		<table class="table table-condensed table-hover" width="100%">
		            <tbody>
		            	<tr>
						  <td><b> 总 字 数 ：</b>
							<input data-rule="required;digits" size="7" name="novel[novel_wordnumber]" value="<?php echo C('novel_wordnumber',null,'data');?>">
					     </td>
						  <td><b>　评　分：</b>
							<input data-rule="required;number" size="7" name="novel[novel_score]" value="<?php echo C('novel_score',null,'data');?>">
					      </td>
						  <td><b>　　　顶：</b>
							<input data-rule="required;digits" size="7" name="novel[novel_ding]" value="<?php echo C('novel_ding',null,'data');?>">
					      </td>
						  <td><b>　　　踩：</b>
							<input data-rule="required;digits" size="7" name="novel[novel_cai]" value="<?php echo C('novel_cai',null,'data');?>">
					      </td>
						  <td><b>评论数：</b>
							<input data-rule="required;digits" size="7" name="novel[novel_replay]" value="<?php echo C('novel_replay',null,'data');?>">
					      </td>
						  <td><b>最后更新时间：</b>
							<input type="text" name="novel[novel_uptime]" value="<?php echo date('Y-m-d H:i:s',C('novel_uptime',null,'data'));?>" data-toggle="datepicker" data-pattern="yyyy-MM-dd H:m:s" size="19">
		            	  </td>
						</tr>
						<tr>
						  <td><b>本日点击：</b>
							<input data-rule="required;digits" size="7" name="novel[novel_todayclick]" value="<?php echo C('novel_todayclick',null,'data');?>">
					     </td>
						  <td><b>本周点击：</b>
							<input data-rule="required;digits" size="7" name="novel[novel_weekclick]" value="<?php echo C('novel_weekclick',null,'data');?>">
					      </td>
						  <td><b>本月点击：</b>
							<input data-rule="required;digits" size="7" name="novel[novel_monthclick]" value="<?php echo C('novel_monthclick',null,'data');?>">
					      </td>
						  <td><b>本年点击：</b>
							<input data-rule="required;digits" size="7" name="novel[novel_yearclick]" value="<?php echo C('novel_yearclick',null,'data');?>">
					      </td>
						  <td><b>总点击：</b>
							<input data-rule="required;digits" size="7" name="novel[novel_allclick]" value="<?php echo C('novel_allclick',null,'data');?>">
					      </td>
						  <td><b>最后点击时间：</b>
							<input type="text" name="novel[novel_clicktime]" value="<?php echo date('Y-m-d H:i:s',C('novel_clicktime',null,'data'));?>" data-toggle="datepicker" data-pattern="yyyy-MM-dd H:m:s" size="19">
					      </td>
						</tr>
		            	<tr>
						  <td><b>本日收藏：</b>
							<input data-rule="required;digits" size="7" name="novel[novel_todaycoll]" value="<?php echo C('novel_todaycoll',null,'data');?>">
					     </td>
						  <td><b>本周收藏：</b>
							<input data-rule="required;digits" size="7" name="novel[novel_weekcoll]" value="<?php echo C('novel_weekcoll',null,'data');?>">
					      </td>
						  <td><b>本月收藏：</b>
							<input data-rule="required;digits" size="7" name="novel[novel_monthcoll]" value="<?php echo C('novel_monthcoll',null,'data');?>">
					      </td>
						  <td><b>本年收藏：</b>
							<input data-rule="required;digits" size="7" name="novel[novel_yearcoll]" value="<?php echo C('novel_yearcoll',null,'data');?>">
					      </td>
						  <td><b>总收藏：</b>
							<input data-rule="required;digits" size="7" name="novel[novel_allcoll]" value="<?php echo C('novel_allcoll',null,'data');?>">
					      </td>
						  <td><b>最后收藏时间：</b>
							<input type="text" name="novel[novel_colltime]" value="<?php echo date('Y-m-d H:i:s',C('novel_colltime',null,'data'));?>" data-toggle="datepicker" data-pattern="yyyy-MM-dd H:m:s" size="19">
					      </td>
						</tr>
		            	<tr>
						  <td><b>本日推荐：</b>
							<input data-rule="required;digits" size="7" name="novel[novel_todayrec]" value="<?php echo C('novel_todayrec',null,'data');?>">
					     </td>
						  <td><b>本周推荐：</b>
							<input data-rule="required;digits" size="7" name="novel[novel_weekrec]" value="<?php echo C('novel_weekrec',null,'data');?>">
					      </td>
						  <td><b>本月推荐：</b>
							<input data-rule="required;digits" size="7" name="novel[novel_monthrec]" value="<?php echo C('novel_monthrec',null,'data');?>">
					      </td>
						  <td><b>本年推荐：</b>
							<input data-rule="required;digits" size="7" name="novel[novel_yearrec]" value="<?php echo C('novel_yearrec',null,'data');?>">
					      </td>
						  <td><b>总推荐：</b>
							<input data-rule="required;digits" size="7" name="novel[novel_allrec]" value="<?php echo C('novel_allrec',null,'data');?>">
					      </td>
						  <td><b>最后推荐时间：</b>
							<input type="text" name="novel[novel_rectime]" value="<?php echo date('Y-m-d H:i:s',C('novel_rectime',null,'data'));?>" data-toggle="datepicker" data-pattern="yyyy-MM-dd H:m:s" size="19">
					      </td>
						</tr>
		            	<tr>
						  <td colspan="6"><b>小说发布时间：</b>
							<input type="text" name="novel[novel_createtime]" value="<?php echo date('Y-m-d H:i:s',C('novel_createtime',null,'data'));?>" data-toggle="datepicker" data-pattern="yyyy-MM-dd H:m:s" size="19">
					      </td>
						</tr>
		            </tbody>
		        </table>
		       </div>
		       
		       <div class="tab-pane fade" id="<?php echo $cFun.$type;?>field">
					<table class="table table-border table-bordered table-bg table-sort"></table>
			   </div>
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
	var op = novelNovelListGetOp();
	var tabid = 'novel-novel-list';
	op['id'] = tabid;
	if( json.statusCode == 300 ){
		$(this).bjuiajax("ajaxDone",json);// 显示处理结果
	}else{
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
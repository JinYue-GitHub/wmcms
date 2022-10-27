<style>
.barline{float:left;margin-right:5px;width:200px;border:1px solid #4DBF7D;text-align:center;height:16px;font-size:8px;border-radius:8px;}
.barline_text_box{line-height:16px;background:#4DBF7D;border-radius:8px;width:0px;color: #3045E6;}
.barline_perc{line-height:16px;}
.barline_msg{margin-top: 11px;}
</style>
<div class="bjui-pageContent">
    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
      <thead><tr><th colspan="4" style="text-align:left;">
      <b style="color:red;font-size:14px;">提示:保存路径使用的是sitemap的伪静态路径！</b>
      </th></tr></thead>

      <thead>
	      <tr>
	      	<td width="150">RSS订阅首页生成</td>
	        <td colspan="2"><a class="btn btn-success radius size-MINI" onClick="createRss()">生成RSS地图</a></td>
	      </tr>
	      <tr>
	      	<td width="150" rowspan="6">RSS订阅列表生成</td>
	        <td width="150">模块选择</td>
	        <td>
	        	<select data-toggle="selectpicker" data-width="130" id="<?php echo $cFun?>module">
	            	<option value="">请选择模块</option>
	            	<?php 
	            	foreach ($moduleArr as $k=>$v)
	            	{
	            		echo '<option value="'.$k.'">'.$v.'</option>';
	            	}
	            	?>
	         	</select>
			</td>
	      </tr>
	      <tr>
	        <td>分类选择</td>
	        <td><input id="<?php echo $cFun;?>type" type="hidden">
			    <div class="formControls" id="<?php echo $cFun;?>_ztree_select_box">
	        		<div style="margin-top: 8px;font-size: 14px;">--请先选择模块--</div>
	        	</div>
	        </td>
	      </tr>
	      <tr>
			<td>下级栏目是否生成</td><td><input type="checkbox" value="1" id="<?php echo $cFun;?>child" data-toggle="icheck" data-label="是"></td>
		  </tr>
	      <tr>
	        <td colspan="2"><a class="btn btn-success radius size-MINI" onClick="createList()">生成RSS列表</a></td>
	      </tr>
	      <tr id="<?php echo $cFun?>barline_box" style="display:none"><td colspan="2">
      		<!-- 进度条 -->
			<div class="barline"><div class="barline_text_box" id="<?php echo $cFun?>text_box"><span id="<?php echo $cFun?>text">0</span>%</div></div>
			<div class="barline_perc">总进度<span id="<?php echo $cFun?>now">0</span>/<span id="<?php echo $cFun?>sum">1</span></div>
			<div class="barline_msg" id="<?php echo $cFun?>msg"></div>
		</td></tr>
		<tr id="<?php echo $cFun?>loading" style="display:none"><td colspan="2"><img src="/files/images/loading.gif">请稍候,初始化数据中....</td></tr>
	    
      <thead>
	 </table>
	</div>
</div>

<script>
$(document).ready(function(){
	$('#<?php echo $cFun;?>module').change(function() {
		var module = $(this).val();
		if(module == '' ){
			$(this).alertmsg('error', '请选择模块!');
			return false;
		}else{
			var op = new Array();
			op['type'] = 'GET';
			op['reload'] = 'false';
			op['url'] = "index.php?a=yes&c=system.seo.sitemap&t=gettype&module="+module;
			op['callback'] = '<?php echo $cFun?>gettype';
			$(this).bjuiajax("doAjax",op);// 显示处理结果
		}
	});
});

//生成RSS首页
function createRss(){
	var ajaxOptions=new Array();
	ajaxOptions['url'] = "index.php?a=yes&c=system.seo.sitemap&t=rss&page=rss";
	$(this).bjuiajax('doAjax', ajaxOptions);
}

//生成rss列表
function createList(){
	var module = $('#<?php echo $cFun;?>module').val();
	var tid = $("#<?php echo $cFun;?>type").val();
	var child = $("#<?php echo $cFun?>child").is(':checked');
	if(child){
		child = $("#<?php echo $cFun?>child").val();
	}
	if(tid == '' ){
		$(this).alertmsg('error', '请选择分类!');
		return false;
	}
	$("#<?php echo $cFun?>loading").show();
	
	var op = new Array();
	op['type'] = 'GET';
	op['reload'] = 'false';
	op['url'] = "index.php?a=yes&c=system.seo.sitemap&t=list&step=init&tid="+tid+"&child="+child+"&module="+module;
	op['callback'] = "<?php echo $cFun;?>listAjaxCallBack";
	$(this).bjuiajax("doAjax",op);// 显示处理结果
}
function <?php echo $cFun;?>listAjaxCallBack(json){
	if( json.statusCode == 200 ){
		var module = $('#<?php echo $cFun;?>module').val();
		$("#<?php echo $cFun?>loading").hide();
		$("#<?php echo $cFun?>barline_box").show();
		$("#<?php echo $cFun?>text_box").css('width','0%');
		$("#<?php echo $cFun?>text").text(0);
		$("#<?php echo $cFun?>sum").html(json.total);
		$("#<?php echo $cFun?>now").html(0);
		$("#<?php echo $cFun?>msg").html('生成日志：'+json.message);
		for(var i=1; i<=json.data.length; i++){
			var bar = parseInt((i/json.data.length)*100);
			$.ajax({
				type:"POST",
				url:"index.php?a=yes&c=system.seo.sitemap&t=list&step=create",
				data:{'module':module,'tid':json.data[i-1]['tid']},
				dataType:"json",
				success:function(rs){
					$("#<?php echo $cFun?>msg").html('生成日志：'+rs.message);
					$("#<?php echo $cFun?>text_box").css('width',bar+'%');
					$("#<?php echo $cFun?>text").text(bar);
				},
				async:true,
			});
			$("#<?php echo $cFun?>now").html(i);
		}
	}else{
		$("#<?php echo $cFun?>barline_box").hide();
		$(this).bjuiajax("ajaxDone",json);//显示处理结果
		$("#<?php echo $cFun?>loading").hide();
	}
}

//获得模块分类的回调函数
function <?php echo $cFun?>gettype(json){
	if( json.data ){
		var html=htmls='';
		var data = json.data;
		for(var i=0;i<data.length;i++){
			html += '<li data-id="'+data[i]['type_id']+'" data-pid="'+data[i]['type_topid']+'">'+data[i]['type_name']+'</li>';
		}
		htmls = '<input type="text" id="<?php echo $cFun;?>_ztree_input" data-toggle="selectztree" data-tree="#<?php echo $cFun;?>_ztree_select" readonly value="请选择分类">'+
      		'<ul id="<?php echo $cFun;?>_ztree_select" class="ztree hide" data-toggle="ztree" data-expand-all="true" data-check-enable="true" data-chk-style="radio" data-radio-type="all" data-on-check="<?php echo $cFun;?>S_NodeCheck" data-on-click="<?php echo $cFun;?>S_NodeClick">'+
      		'<li data-id="0" data-pid="0">全部分类</li>'+html+
      		'</ul>';
          	
		$("#<?php echo $cFun;?>_ztree_select_select_box").remove();
		$("#<?php echo $cFun;?>_ztree_select_box").html(htmls);
		$("#<?php echo $cFun;?>_ztree_select_box").trigger('bjui.initUI');
	}else{
		$(this).alertmsg('error', '当前模块没有分类，请先添加分类!');
	}
}
//选择事件
function <?php echo $cFun;?>S_NodeCheck(e, treeId, treeNode) {
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
  var $from = $('#'+ treeId).data('fromObj');
	$('#<?php echo $cFun;?>type').val(ids);
  if ($from && $from.length) $from.val(names)
}
//单击事件
function <?php echo $cFun;?>S_NodeClick(event, treeId, treeNode) {
  var zTree = $.fn.zTree.getZTreeObj(treeId)
  zTree.checkNode(treeNode, !treeNode.checked, true, true)
  event.preventDefault()
}
</script>
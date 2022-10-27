<style>
.<?php echo $cFun?>Table{margin-bottom:20px}
.barline{float:left;margin-right:5px;width:200px;border:1px solid #4DBF7D;text-align:center;height:16px;font-size:8px;border-radius:8px;}
.barline_text_box{line-height:16px;background:#4DBF7D;border-radius:8px;width:0px;color: #3045E6;}
.barline_perc{line-height:16px;}
.barline_msg{margin-top: 11px;}
</style>

<div class="bjui-pageContent">
    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
      <thead><tr><th colspan="4" style="text-align:left;">
      <b style="color:red;font-size:16px;">提示:如果您设置了分类的HTML保存路径，生成HTML的时候提示“当前分类没有设置HTML保存路径!”，请先在SEO设置里面重新生成缓存！</b><br/><br/>
      <b>提示:建议所有的html文件都放在统一的文件，这样方便对文件进行管理！</b>
      </th></tr></thead>
    </table>
    
    <fieldset>
		<legend>生成HTML文件</legend>
		<ul class="nav nav-tabs" role="tablist">
			<li class="active"><a href="#<?php echo $cFun;?>check" role="tab" data-toggle="tab">第一步：选择版本</a></li>
			<li><a href="#<?php echo $cFun;?>html_index" role="tab" data-toggle="tab" id="<?php echo $cFun;?>html_index_tab">第二步：① 生成首页/单页</a></li>
			<li><a href="#<?php echo $cFun;?>html_list" role="tab" data-toggle="tab" id="<?php echo $cFun;?>html_list_tab">第二步：② 生成分类列表</a></li>
			<li><a href="#<?php echo $cFun;?>html_content" role="tab" data-toggle="tab" id="<?php echo $cFun;?>html_content_tab">第二步：③ 生成内容</a></li>
			<li><a href="#<?php echo $cFun;?>html_tindex" role="tab" data-toggle="tab" id="<?php echo $cFun;?>html_tindex_tab">第二步：④ 生成分类首页</a></li>
			<li><a href="#<?php echo $cFun;?>html_menu" role="tab" data-toggle="tab" id="<?php echo $cFun;?>html_menu_tab">第二步：⑤ 生成目录页</a></li>
		</ul>
		<div class="tab-content">
			<!-- 生成版本选择 -->
			<div class="tab-pane fade active in" id="<?php echo $cFun;?>check">
				选择需要生成HTML的版本：
			   <select data-toggle="selectpicker" id="<?php echo $cFun;?>tp" name="tp" data-width="100">
	            	<option value="4">电脑版</option>
	            	<!-- <option value="3">触屏版</option>
	            	<option value="2">3G版</option>
	            	<option value="1">简洁版</option> -->
	         	</select>
		    </div>
		    
		    <!-- 首页/单页生成 -->
			<div class="tab-pane fade in" id="<?php echo $cFun;?>html_index">
				<table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
			      <thead>
			      <tr><th colspan="4" style="text-align:left;color: #F16B0A"><b>备注：如果不填写保存路径，首页/单页的html文件命名方式是根据伪静态的首页地址来保存命名</b></th></tr></thead>
			      </thead>
			    </table>
			    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
			      <thead>
			      <tr><th colspan="4" style="text-align:left;"><b>首页生成</b></th></tr></thead>
			      <tr>
			      	<td width="150">需要生成的模块</td>
			        <td>
			        	<select data-toggle="selectpicker" data-width="130" id="<?php echo $cFun?>index_module">
			            	<option value="index">全站首页</option>
			            	<?php 
			            	foreach ($moduleArr as $k=>$v)
			            	{
			            		echo '<option value="'.$k.'">'.$v.'首页</option>';
			            	}
			            	?>
			         	</select>
			      </td></tr>
			      <tr>
			      	<td width="150">保存路径</td>
			        <td><input type="text" id="<?php echo $cFun;?>index_path" class="form-control" value="/index.html"></td></tr>
			      <tr>
			        <td colspan="2"><a class="btn btn-success radius size-MINI" onClick="createIndex()">生成首页</a></td>
			      </tr>
			      <tr id="<?php echo $cFun?>index_barline_box" style="display:none"><td colspan="2">
			      		<!-- 进度条 -->
						<div class="barline"><div class="barline_text_box" id="<?php echo $cFun?>index_text_box"><span id="<?php echo $cFun?>index_text">0</span>%</div></div>
						<div class="barline_perc">进度<span id="<?php echo $cFun?>index_now">0</span>/<span id="<?php echo $cFun?>index_sum">1</span></div>
						<div class="barline_msg" id="<?php echo $cFun?>index_msg"></div>
					</td></tr>
			    </table>
		    </div>
		    
		    <!-- 分类列表生成 -->
			<div class="tab-pane fade in" id="<?php echo $cFun;?>html_list">
			    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
			      <thead><tr><th colspan="4" style="text-align:left;"><b>列表/目录页HTML生成</b></th></tr></thead>
			      <tr>
			      	<td width="150">需要生成的模块</td>
			        <td>
			        	<input class="nowtab" type="hidden" value="list">
			        	<select data-toggle="selectpicker" name="<?php echo $cFun;?>module" data-width="130">
			            	<option value="">请选择模块</option>
			            	<?php 
			            	foreach ($moduleArr as $k=>$v)
			            	{
			            		echo '<option value="'.$k.'">'.$v.'</option>';
			            	}
			            	?>
			         	</select>
			      </td></tr>
			      <tr>
			      	<td>需要生成的分类</td>
			      	<td>
			      	<input id="<?php echo $cFun;?>list_type" class="hidden_input" type="hidden">
			      	<div class="formControls" id="<?php echo $cFun;?>list_ztree_select_box">
	        			<div style="margin-top: 8px;font-size: 14px;">--请先选择模块--</div>
	        		</div>
		          </td></tr>
			      <tr>
			      	<td>下级栏目是否生成</td><td><input type="checkbox" value="1" id="<?php echo $cFun;?>list_child" data-toggle="icheck" data-label="是"></td></tr>
			      <tr>
			      	<td>聚合列表生成</td><td><input type="checkbox" value="1" id="<?php echo $cFun;?>list_all" data-toggle="icheck" data-label="是"></td></tr>
			      <tr>
			        <td>生成最大页数</td><td><input type="text" id="<?php echo $cFun;?>list_page" class="form-control" size="5" value="0">&nbsp;&nbsp;&nbsp;0为不限制页数</td>
			      </tr>
			      <tr><td colspan="2"><a class="btn btn-success radius size-MINI" onClick="createList()">生成分类列表</a></td></tr>
			     <tr id="<?php echo $cFun?>list_barline_box" style="display:none"><td colspan="2">
		      		<!-- 生成界面 -->
					<iframe style="width: 100%;height:80px;border:none;"></iframe>
				</td></tr>
				<tr id="<?php echo $cFun?>list_loading" style="display:none"><td colspan="2"><img src="/files/images/loading.gif">请稍候,初始化数据中....</td></tr>
			    </table>
		    </div>
		    
		    <!-- 内容页生成 -->
			<div class="tab-pane fade in" id="<?php echo $cFun;?>html_content">
			    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
			      <thead><tr><th colspan="4" style="text-align:left;"><b>内容页HTML生成</b></th></tr></thead>
			      <tr>
			      	<td width="150">需要生成的模块</td>
			        <td>
			        	<input class="nowtab" type="hidden" value="content">
			        	<select data-toggle="selectpicker" name="<?php echo $cFun;?>module" data-width="130">
			            	<option value="">请选择模块</option>
			            	<?php 
			            	foreach ($moduleArr as $k=>$v)
			            	{
			            		echo '<option value="'.$k.'">'.$v.'</option>';
			            	}
			            	?>
			         	</select>
			      </td></tr>
			      <tr>
			      	<td>需要生成的分类</td>
			      	<td>
			      	<input id="<?php echo $cFun;?>content_type" class="hidden_input" type="hidden">
			      	<div class="formControls" id="<?php echo $cFun;?>content_ztree_select_box">
	        			<div style="margin-top: 8px;font-size: 14px;">--请先选择模块--</div>
	        		</div>
		          </td></tr>
			      <tr>
			      	<td>下级栏目是否生成</td><td><input type="checkbox" value="1" id="<?php echo $cFun;?>content_child" data-toggle="icheck" data-label="是"></td></tr>
			      <tr>
			      	<td>查询的条件</td>
			        <td>
			        	<div style="float:left;margin-right:15px;">
			        		<select data-toggle="selectpicker" id="<?php echo $cFun;?>content_where" data-width="100">
				            	<option value="">请选择条件</option>
				            	<option value="all">全部</option>
				            	<option value="id">内容ID区间</option>
				            	<option value="time">时间选择</option>
				         	</select>
			         	</div>
			         	<div id="<?php echo $cFun;?>content_id_box" style="display:none">
	                		开始ID：<input type="text" id="<?php echo $cFun;?>content_startid" class="form-control" size="7" value="0">
	                		&nbsp;&nbsp;结束ID：<input type="text" id="<?php echo $cFun;?>content_endid" class="form-control" size="7" value="0">
			         	</div>
			         	
			         	<div id="<?php echo $cFun;?>content_time_box" style="display:none">
	                		开始时间：<input type="text" id="<?php echo $cFun;?>content_starttime" value="<?php echo $startTime;?>" data-toggle="datepicker" data-pattern="yyyy-MM-dd H:m:s" size="19">
	                		&nbsp;&nbsp;结束时间：<input type="text" id="<?php echo $cFun;?>content_endtime" value="<?php echo $endTime;?>" data-toggle="datepicker" data-pattern="yyyy-MM-dd H:m:s" size="19">
			         	</div>
			         </td>
			      </tr>
			       <tr>
			      	<td width="150">需要生成的页面类型</td>
			        <td>
			        	<select data-toggle="selectpicker" id="<?php echo $cFun;?>content_pagetype" data-width="130">
			            	<option value="content">内容页面</option>
			            	<option value="read">阅读页面</option>
			         	</select>
			      </td></tr>
			       <tr>
			      	<td width="150" style="color:red">提示</td>
			        <td style="color:red">如果一次性生成数据过多(5万-50万不等，看服务器配置)可能会造成服务器卡死，您可以设置查询条件为ID区间分批次生成（1万-10万不等，看服务器配置）</td></tr>
			      <tr><td colspan="2"><a class="btn btn-success radius size-MINI" onClick="createContent()">生成内容</a></td></tr>
			      <tr id="<?php echo $cFun?>content_barline_box" style="display:none"><td colspan="2">
			      		<!-- 进度条 -->
						<iframe style="width: 100%;height:80px;border:none;"></iframe>
					</td></tr>
					<tr id="<?php echo $cFun?>content_loading" style="display:none"><td colspan="2"><img src="/files/images/loading.gif">请稍候,初始化数据中....</td></tr>
			    </table>
			</div>
			
			<!-- 分类首页 -->
			<div class="tab-pane fade in" id="<?php echo $cFun;?>html_tindex">
			    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
			      <thead><tr><th colspan="4" style="text-align:left;"><b>分类首页HTML生成</b></th></tr></thead>
			      <tr>
			      	<td width="150">需要生成的模块</td>
			        <td>
			        	<input class="nowtab" type="hidden" value="tindex">
			        	<select data-toggle="selectpicker" name="<?php echo $cFun;?>module" data-width="130">
			            	<option value="">请选择模块</option>
			            	<?php 
			            	foreach ($moduleArr as $k=>$v)
			            	{
			            		echo '<option value="'.$k.'">'.$v.'</option>';
			            	}
			            	?>
			         	</select>
			      </td></tr>
			      <tr>
			      	<td>需要生成的分类</td>
			      	<td>
			      	<input id="<?php echo $cFun;?>tindex_type" class="hidden_input" type="hidden">
			      	<div class="formControls" id="<?php echo $cFun;?>tindex_ztree_select_box">
	        			<div style="margin-top: 8px;font-size: 14px;">--请先选择模块--</div>
	        		</div>
		          </td></tr>
			      <tr>
			      	<td>下级栏目是否生成</td><td><input type="checkbox" value="1" id="<?php echo $cFun;?>tindex_child" data-toggle="icheck" data-label="是"></td></tr>
			      <tr><td colspan="2"><a class="btn btn-success radius size-MINI" onClick="createTIndex()">生成分类首页</a></td></tr>
			     <tr id="<?php echo $cFun?>tindex_barline_box" style="display:none"><td colspan="2">
		      		<!-- 进度条 -->
					<div class="barline"><div class="barline_text_box" id="<?php echo $cFun?>tindex_text_box"><span id="<?php echo $cFun?>tindex_text">0</span>%</div></div>
					<div class="barline_perc">总进度<span id="<?php echo $cFun?>tindex_now">0</span>/<span id="<?php echo $cFun?>tindex_sum">1</span></div>
					<div class="barline_msg" id="<?php echo $cFun?>tindex_msg"></div>
				</td></tr>
				<tr id="<?php echo $cFun?>tindex_loading" style="display:none"><td colspan="2"><img src="/files/images/loading.gif">请稍候,初始化数据中....</td></tr>
			    </table>
		    </div>
		    
		    
		    <!-- 分类目录生成 -->
			<div class="tab-pane fade in" id="<?php echo $cFun;?>html_menu">
			    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
			      <thead><tr><th colspan="4" style="text-align:left;"><b>目录页HTML生成</b></th></tr></thead>
			      <tr>
			      	<td width="150">需要生成的模块</td>
			        <td>
			        	<input class="nowtab" type="hidden" value="menu">
			        	<select data-toggle="selectpicker" name="<?php echo $cFun;?>module" data-width="130">
			            	<option value="">请选择模块</option>
			            	<option value="novel">小说模块</option>
			         	</select>
			      </td></tr>
			      <tr>
			      	<td>需要生成的分类</td>
			      	<td>
			      	<input id="<?php echo $cFun;?>menu_type" class="hidden_input" type="hidden">
			      	<div class="formControls" id="<?php echo $cFun;?>menu_ztree_select_box">
	        			<div style="margin-top: 8px;font-size: 14px;">--请先选择模块--</div>
	        		</div>
		          </td></tr>
			      <tr>
			      	<td>下级栏目是否生成</td><td><input type="checkbox" value="1" id="<?php echo $cFun;?>menu_child" data-toggle="icheck" data-label="是"></td></tr>
			      <tr><td colspan="2"><a class="btn btn-success radius size-MINI" onClick="createMenu()">生成目录列表</a></td></tr>
			     <tr id="<?php echo $cFun?>menu_barline_box" style="display:none"><td colspan="2">
		      		<!-- 进度条 -->
					<iframe style="width: 100%;height:80px;border:none;"></iframe>
				</td></tr>
				<tr id="<?php echo $cFun?>list_loading" style="display:none"><td colspan="2"><img src="/files/images/loading.gif">请稍候,初始化数据中....</td></tr>
			    </table>
		    </div>
		    
		</div>
	</div>
</div>

<script>
var tabObj='';//当前点击的tab对象
var nowTab='';//当前选中的tab
var nowModule='';//当前选择的模块
var bar=i=0;
$(document).ready(function(){
	$("#<?php echo $cFun;?>html_content_tab").click(function(){
		$('.bjui-lookup').css("line-height",'22px');
		$('.bjui-lookup').css("height",'22px');
	});

	$("#<?php echo $cFun;?>content_where").change(function(){
		$("#<?php echo $cFun;?>content_id_box").hide();
		$("#<?php echo $cFun;?>content_time_box").hide();
		if( $(this).val() == 'id' ){
			$("#<?php echo $cFun;?>content_id_box").show();
		}else if($(this).val() == 'time'){
			$("#<?php echo $cFun;?>content_time_box").show();
		}
	});

	//根据模块获取分类数据
	$('[name=<?php echo $cFun;?>module]').change(function() {
		tabObj = $(this).parent().parent().next().find('.hidden_input');
		nowTab = $(this).parent().find('.nowtab').val();
		nowModule = $(this).val();
		var op = new Array();
		op['type'] = 'GET';
		op['reload'] = 'false';
		op['url'] = "index.php?a=yes&c=system.seo.html&t=gettype&module="+nowModule;
		op['callback'] = '<?php echo $cFun?>gettype';
		$(this).bjuiajax("doAjax",op);// 显示处理结果
	});
});

//生成首页
function createIndex(){
	$("#<?php echo $cFun?>index_barline_box").show();
	var ajaxOptions=new Array();
	var data=new Object();
	var id = $("#<?php echo $cFun?>tp").val();
	var module = $("#<?php echo $cFun?>index_module").val();
	data.path = $("#<?php echo $cFun?>index_path").val();
	ajaxOptions['data'] = data;
	ajaxOptions['url'] = "index.php?a=yes&c=system.seo.html&t=index&id="+id+"&module="+module;
	ajaxOptions['callback'] = "<?php echo $cFun;?>indexAjaxCallBack";
	$(this).bjuiajax('doAjax', ajaxOptions);
}
//生成首页回调方法
function <?php echo $cFun;?>indexAjaxCallBack(json){
	if( json.statusCode == 200 ){
		$("#<?php echo $cFun?>index_text_box").css('width','100%');
		$("#<?php echo $cFun?>index_text").text('100');
		$("#<?php echo $cFun?>index_now").html(1);
		$("#<?php echo $cFun?>index_msg").html('生成日志：'+json.message);
	}else{
		$("#<?php echo $cFun?>index_barline_box").hide();
		$(this).bjuiajax("ajaxDone",json);//显示处理结果
	}
}

//生成列表初始化数据操作
function createList(){
	var type = $("#<?php echo $cFun?>list_type").val();
	var child = $("#<?php echo $cFun?>list_child").is(':checked');
	if(child){
		child = $("#<?php echo $cFun?>list_child").val();
	}
	var all = $("#<?php echo $cFun?>list_all").is(':checked');
	if(all){
		all = $("#<?php echo $cFun?>list_all").val();
	}
	var page = $("#<?php echo $cFun?>list_page").val();
	if(nowModule == ''){
		$(this).alertmsg('error', '请选择模块!');
	}else if(type == ''){
		$(this).alertmsg('error', '请选择分类!');
	}else{
		$("#<?php echo $cFun?>list_loading").show();
		var ajaxOptions=new Array();
		var data=new Object();
		data.module = nowModule;
		data.tid = type;
		if(child){
			data.child = child;
		}
		if(all){
			data.all = all;
		}
		data.page = page;
		data.id = $("#<?php echo $cFun?>tp").val();
		ajaxOptions['data'] = data;
		ajaxOptions['url'] = "index.php?a=yes&c=system.seo.html&t=list&step=init";
		ajaxOptions['callback'] = "<?php echo $cFun;?>listAjaxCallBack";
		$(this).bjuiajax('doAjax', ajaxOptions);
	}
}
//生成列表初始化后的操作
function <?php echo $cFun;?>listAjaxCallBack(json){
	if( json.statusCode == 200 ){
		$("#<?php echo $cFun?>list_loading").hide();
		$("#<?php echo $cFun?>list_barline_box").show();
		$("#<?php echo $cFun?>list_barline_box iframe").attr('src','index.php?a=yes&c=system.seo.html&t=list&step=create&module='+nowModule);
	}else{
		$("#<?php echo $cFun?>list_barline_box").hide();
		$(this).bjuiajax("ajaxDone",json);//显示处理结果
		$("#<?php echo $cFun?>list_loading").hide();
	}
}

//生成内容初始化操作
function createContent(){
	var type = $("#<?php echo $cFun?>content_type").val();
	var child = $("#<?php echo $cFun?>content_child").is(':checked');
	if(child){
		child = $("#<?php echo $cFun?>content_child").val();
	}
	var pagetype = $("#<?php echo $cFun?>content_pagetype").val();
	var where = $("#<?php echo $cFun?>content_where").val();
	var startid = endid = starttime = endtime = '';
	
	if(nowModule == ''){
		$(this).alertmsg('error', '请选择模块!');
		return false;
	}else if(type == ''){
		$(this).alertmsg('error', '请选择分类!');
		return false;
	}else if(where == ''){
		$(this).alertmsg('error', '请选择生成条件!');
		return false;
	}else if(pagetype == 'read' && nowModule != 'novel'){
		$(this).alertmsg('error', '对不起，只有小说模块可以选择生成阅读html!');
		return false;
	}else{
		if(where == 'id'){
			startid = $("#<?php echo $cFun?>content_startid").val();
			endid = $("#<?php echo $cFun?>content_endid").val();
			if(startid == '' || endid == '' ){
				$(this).alertmsg('error', '请输入开始ID和结束ID');
			}
		}else if(where == 'time'){
			starttime = $("#<?php echo $cFun?>content_starttime").val();
			endtime = $("#<?php echo $cFun?>content_endtime").val();
			if(starttime == '' || endtime == '' ){
				$(this).alertmsg('error', '请输入文章的发布的开始和结束时间');
			}
		}
		
		$("#<?php echo $cFun?>content_loading").show();
		var ajaxOptions=new Array();
		var data=new Object();
		data.module = nowModule;
		data.tid = type;
		if(child){
			data.child = child;
		}
		data.where = where;
		data.startid = startid;
		data.endid = endid;
		data.starttime = starttime;
		data.endtime = endtime;
		data.pagetype = pagetype;
		data.id = $("#<?php echo $cFun?>tp").val();
		ajaxOptions['data'] = data;
		ajaxOptions['url'] = "index.php?a=yes&c=system.seo.html&t=content&step=init";
		ajaxOptions['callback'] = "<?php echo $cFun;?>contentAjaxCallBack";
		$(this).bjuiajax('doAjax', ajaxOptions);
	}
}
//生成内容初始化后的操作
function <?php echo $cFun;?>contentAjaxCallBack(json){
	if( json.statusCode == 200 ){
		$("#<?php echo $cFun?>content_loading").hide();
		$("#<?php echo $cFun?>content_barline_box").show();
		$("#<?php echo $cFun?>content_barline_box iframe").attr('src','index.php?a=yes&c=system.seo.html&t=content&step=create&module='+nowModule);
	}else{
		$("#<?php echo $cFun?>content_barline_box").hide();
		$(this).bjuiajax("ajaxDone",json);//显示处理结果
		$("#<?php echo $cFun?>content_loading").hide();
	}
}

//生成分类首页初始化操作
function createTIndex(){
	var type = $("#<?php echo $cFun?>tindex_type").val();
	var child = $("#<?php echo $cFun?>tindex_child").is(':checked');
	if(child){
		child = $("#<?php echo $cFun?>tindex_child").val();
	}
	if(nowModule == ''){
		$(this).alertmsg('error', '请选择模块!');
	}else if(type == ''){
		$(this).alertmsg('error', '请选择分类!');
	}else{
		$("#<?php echo $cFun?>tindex_loading").show();
		var ajaxOptions=new Array();
		var data=new Object();
		data.module = nowModule;
		data.tid = type;
		if(child){
			data.child = child;
		}
		data.id = $("#<?php echo $cFun?>tp").val();
		ajaxOptions['data'] = data;
		ajaxOptions['url'] = "index.php?a=yes&c=system.seo.html&t=tindex&step=init";
		ajaxOptions['callback'] = "<?php echo $cFun;?>tindexAjaxCallBack";
		$(this).bjuiajax('doAjax', ajaxOptions);
	}
}
//生成分类首页操作
function <?php echo $cFun;?>tindexAjaxCallBack(json){
	if( json.statusCode == 200 ){
		$("#<?php echo $cFun?>tindex_loading").hide();
		$("#<?php echo $cFun?>tindex_barline_box").show();
		$("#<?php echo $cFun?>tindex_text_box").css('width','0%');
		$("#<?php echo $cFun?>tindex_text").text(0);
		$("#<?php echo $cFun?>tindex_sum").html(json.total);
		$("#<?php echo $cFun?>tindex_now").html(0);
		$("#<?php echo $cFun?>tindex_msg").html('生成日志：'+json.message);
		for(var i=1; i<=json.data.length; i++){
			var bar = parseInt((i/json.total)*100);
			var now = i;
			$.ajax({
				type:"POST",
				url:"index.php?a=yes&c=system.seo.html&t=tindex&step=create",
				data:{'module':nowModule,'tid':json.data[i-1]['tid'],'tpinyin':json.data[i-1]['tpinyin']},
				dataType:"json",
				success:function(rs){
					$("#<?php echo $cFun?>tindex_msg").html('生成日志：'+rs.message);
					$("#<?php echo $cFun?>tindex_text_box").css('width',bar+'%');
					$("#<?php echo $cFun?>tindex_text").text(bar);
					$("#<?php echo $cFun?>tindex_now").html(now);
				},
				async:true,
			});
		}
	}else{
		$("#<?php echo $cFun?>tindex_barline_box").hide();
		$(this).bjuiajax("ajaxDone",json);//显示处理结果
		$("#<?php echo $cFun?>tindex_loading").hide();
	}
}

//生成目录列表初始化操作
function createMenu(){
	var type = $("#<?php echo $cFun?>menu_type").val();
	var child = $("#<?php echo $cFun?>menu_child").is(':checked');
	if(child){
		child = $("#<?php echo $cFun?>menu_child").val();
	}
	if(nowModule == ''){
		$(this).alertmsg('error', '请选择模块!');
	}else if(type == ''){
		$(this).alertmsg('error', '请选择分类!');
	}else{
		$("#<?php echo $cFun?>menu_loading").show();
		var ajaxOptions=new Array();
		var data=new Object();
		data.module = nowModule;
		data.tid = type;
		if(child){
			data.child = child;
		}
		data.id = $("#<?php echo $cFun?>tp").val();
		ajaxOptions['data'] = data;
		ajaxOptions['url'] = "index.php?a=yes&c=system.seo.html&t=menu&step=init";
		ajaxOptions['callback'] = "<?php echo $cFun;?>menuAjaxCallBack";
		$(this).bjuiajax('doAjax', ajaxOptions);
	}
}
//生成目录列表操作
function <?php echo $cFun;?>menuAjaxCallBack(json){
	if( json.statusCode == 200 ){
		$("#<?php echo $cFun?>menu_loading").hide();
		$("#<?php echo $cFun?>menu_barline_box").show();
		$("#<?php echo $cFun?>menu_barline_box iframe").attr('src','index.php?a=yes&c=system.seo.html&t=menu&step=create&module='+nowModule);
	}else{
		$("#<?php echo $cFun?>menu_barline_box").hide();
		$(this).bjuiajax("ajaxDone",json);//显示处理结果
		$("#<?php echo $cFun?>menu_loading").hide();
	}
}


//获得模块分类的回调函数
function <?php echo $cFun?>gettype(json){
	if( json.statusCode == 200 ){
		if( json.data ){
			var html=htmls='';
			var data = json.data;
			for(var i=0;i<data.length;i++){
				html += '<li data-id="'+data[i]['type_id']+'" data-pid="'+data[i]['type_topid']+'">'+data[i]['type_name']+'</li>';
			}
			htmls = '<input type="text" id="<?php echo $cFun;?>'+nowTab+'_ztree_input" data-toggle="selectztree" data-tree="#<?php echo $cFun;?>'+nowTab+'_ztree_select" readonly value="请选择分类">'+
	        		'<ul id="<?php echo $cFun;?>'+nowTab+'_ztree_select" class="ztree hide" data-toggle="ztree" data-expand-all="true" data-check-enable="true" data-chk-style="radio" data-radio-type="all" data-on-check="<?php echo $cFun;?>S_NodeCheck" data-on-click="<?php echo $cFun;?>S_NodeClick">'+
	        		'<li data-id="0" data-pid="0">全部分类</li>'+html+
	        		'</ul>';
	            	
			$("#<?php echo $cFun;?>"+nowTab+"_ztree_select_select_box").remove();
			$("#<?php echo $cFun;?>"+nowTab+"_ztree_select_box").html(htmls);
			$("#<?php echo $cFun;?>"+nowTab+"_ztree_select_box").trigger('bjui.initUI');
		}else{
			$(this).alertmsg('error', '当前模块没有分类，请先添加分类!');
		}
	}else{
		$(this).bjuiajax("ajaxDone",json);//显示处理结果
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
    var $from = $('#'+ treeId).data('fromObj')
	tabObj.val(ids);
    if ($from && $from.length) $from.val(names)
}
//单击事件
function <?php echo $cFun;?>S_NodeClick(event, treeId, treeNode) {
    var zTree = $.fn.zTree.getZTreeObj(treeId)
    zTree.checkNode(treeNode, !treeNode.checked, true, true)
    event.preventDefault()
}
</script>
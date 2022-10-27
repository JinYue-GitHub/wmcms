<style>
.barline{float:left;margin-right:5px;width:200px;border:1px solid #4DBF7D;text-align:center;height:16px;font-size:8px;border-radius:8px;}
.barline_text_box{line-height:16px;background:#4DBF7D;border-radius:8px;width:0px;color: #3045E6;}
.barline_perc{line-height:16px;}
.barline_msg{margin-top: 11px;}
.<?php echo $cFun;?>sptype_info{display:none}
</style>
<div class="bjui-pageContent">
    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
      <thead><tr><th colspan="4" style="text-align:left;">
      <b style="color:red;font-size:14px;">提示:请先到API管理-seo接口设置相关的api信息！</b>
      </th></tr></thead>

      <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
		<thead>
		      <tr>
		        <td width="150">搜索引擎</td>
		        <td>
		        	<select data-toggle="selectpicker" data-width="130" id="<?php echo $cFun;?>sptype">
		            	<option value="">请选择搜索引擎</option>
		            	<option value="baidu">百度</option>
		         	</select>
		         	<span class="<?php echo $cFun;?>sptype_info" id="<?php echo $cFun;?>sptype_info_baidu">提示：每次最多只能提交2000条链接，每天可以提交5000000条</span>
				</td>
		      </tr>
		      <tr>
		        <td colspan="2">
					<fieldset>
						<legend>输入url</legend>
						<ul class="nav nav-tabs" role="tablist">
							<li class="active"><a href="#<?php echo $cFun;?>auto" role="tab" data-toggle="tab">自动提交</a></li>
							<li><a href="#<?php echo $cFun;?>hand" role="tab" data-toggle="tab">手动提交</a></li>
						</ul>
						<div class="tab-content">
							<!-- 自动 -->
							<div class="tab-pane fade active in" id="<?php echo $cFun;?>auto">
								<table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
								<thead>
								      <tr>
								      	<td width="150">选择模块</td>
								        <td colspan="2">
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
								      	<td width="150">内容条件</td>
								        <td colspan="2">
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
						                		&nbsp;&nbsp;结束ID：<input type="text" id="<?php echo $cFun;?>content_endid" class="form-control" size="7" value="1">
								         	</div>
								         	
								         	<div id="<?php echo $cFun;?>content_time_box" style="display:none">
						                		开始时间：<input type="text" id="<?php echo $cFun;?>content_starttime" value="<?php echo $startTime;?>" data-toggle="datepicker" data-pattern="yyyy-MM-dd H:m:s" size="19">
						                		&nbsp;&nbsp;结束时间：<input type="text" id="<?php echo $cFun;?>content_endtime" value="<?php echo $endTime;?>" data-toggle="datepicker" data-pattern="yyyy-MM-dd H:m:s" size="19">
								         	</div>
			         	
								        </td>
								      </tr>
							    </thead>
								</table>
							</div>
							
							<!-- 手动 -->
							<div class="tab-pane fade in" id="<?php echo $cFun;?>hand">
								<table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
								<thead>
								      <tr>
								      	<td width="150">请输入URL，一行一个</td>
								      	<td><textarea rows="15" cols="50" id="<?php echo $cFun;?>urls"></textarea></td>
								      </tr>
							    </thead>
								</table>
							</div>
						</div>
					</fieldset>
		        </td>
		      </tr>
		      
		      <tr><td colspan="2"><a class="btn btn-success radius size-MINI" onClick="urlpost()">立即提交</a></td></tr>
		</thead>
	</table>
</div>
      

<script>
$(document).ready(function(){
	//模块选择
	$("#<?php echo $cFun;?>sptype").change(function(){
		$(".<?php echo $cFun;?>sptype_info").hide();
		$("#<?php echo $cFun;?>sptype_info_"+$(this).val()).show();
	});

	//条件选择
	$("#<?php echo $cFun;?>content_where").change(function(){
		$("#<?php echo $cFun;?>content_id_box").hide();
		$("#<?php echo $cFun;?>content_time_box").hide();
		if( $(this).val() == 'id' ){
			$("#<?php echo $cFun;?>content_id_box").show();
		}else if($(this).val() == 'time'){
			$("#<?php echo $cFun;?>content_time_box").show();
		}
	});
});

function urlpost(){
	var sptype = $("#<?php echo $cFun;?>sptype").val();
	var module = $("#<?php echo $cFun;?>module").val();
	var where = $("#<?php echo $cFun;?>content_where").val();
	var urls = $("#<?php echo $cFun;?>urls").val();
	var contentWhere = $("#<?php echo $cFun;?>content_where").val();
	if(sptype=='' ){
		$(this).alertmsg('error', '对不起，请选择搜索引擎类型!');
	}else if(module=='' && urls == '' ){
		$(this).alertmsg('error', '对不起，自动提交和手动提交必需选择一项!');
	}else if(contentWhere == ''  && urls == '' ){
		$(this).alertmsg('error', '对不起，请选择生成条件!');
	}else{
		var startid = $("#<?php echo $cFun;?>content_startid").val();
		var endid = $("#<?php echo $cFun;?>content_endid").val();
		var starttime = $("#<?php echo $cFun;?>content_starttime").val();
		var endtime = $("#<?php echo $cFun;?>content_endtime").val();
		var ajaxOptions=new Array();
		var data=new Object();
		data.sptype = sptype;
		data.module = module;
		data.contentWhere = contentWhere;
		data.startid = startid;
		data.endid = endid;
		data.starttime = starttime;
		data.endtime = endtime;
		data.urls = urls;
		ajaxOptions['data'] = data;
		ajaxOptions['loadingmask'] = true;
		ajaxOptions['reload'] = false;
		ajaxOptions['url'] = "index.php?a=yes&c=system.seo.urlpost&t=post";
		$(this).bjuiajax('doAjax', ajaxOptions);
	}
}
</script>
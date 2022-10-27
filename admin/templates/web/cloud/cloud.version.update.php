<div class="bjui-pageHeader">
	<div class="row cl pt-10 pb-10 pl-10">
		<div class="f-l" style="margin-left:10px">
			<div id="<?php echo $cFun;?>TextBox" class="alert alert-success search-inline"><i class="fa fa-info-circle"></i> <span id="<?php echo $cFun;?>newText">您已经是最新版本了！</span></div><br/><br/>
			<div id="<?php echo $cFun;?>VerBox" class="alert alert-success search-inline"><i class="fa fa-info-circle"></i> 当前版本<?php echo WMVER?> , 服务器最新版本<span id="<?php echo $cFun;?>newVer"><?php echo WMVER?></span>！</div>
        </div>
	</div>
</div>

<div class="bjui-pageContent">
	<div class="tab-pane fade active in">
		<table class="table table-border table-bordered table-hover table-bg table-sort">
			<thead>
			<tr>
				<th style="text-align: center;" width="7%">ID</th>
				<th style="text-align: center;" width="10%">版本号</th>
				<th style="text-align: center;" width="10%">版本类型</th>
				<th style="text-align: center;" width="10%">更新包大小</th>
				<th style="text-align: center;" width="15%">更新时间</th>
				<th style="text-align: center;" width="20%">版本主题</th>
	        </tr>
			</thead>
			<tbody id="<?php echo $cFun?>table">
				<tr class=""><td style="text-align: center;" colspan="6">加载中...</td></tr>
			</tbody>
		</table>
	</div>
</div>

<script>
var html = set = '';
$(document).ready(function(){
	//最新版本
	var newOp = new Array();
	newOp['type'] = 'GET';
	newOp['reload'] = 'false';
	newOp['url'] = "index.php?a=yes&c=cloud.version&t=getnew";
	newOp['callback'] = "<?php echo $cFun;?>ajaxCallBack";
	$(this).bjuiajax("doAjax",newOp);// 显示处理结果
});
function <?php echo $cFun;?>ajaxCallBack(json){
	var data = json.data.data;
	if(data && data['version_number'] != '<?php echo WMVER;?>'){
		$("#<?php echo $cFun;?>newVer").html(data['version_number']);
		$("#<?php echo $cFun;?>TextBox").addClass('alert-danger');
		$("#<?php echo $cFun;?>VerBox").addClass('alert-danger');
		$("#<?php echo $cFun;?>newText").html('当前服务器有最新版可以升级！');

		//下一级版本
		var op = new Array();
		op['type'] = 'GET';
		op['reload'] = 'false';
		op['loadingmask'] = 'true';
		op['url'] = "index.php?a=yes&c=cloud.version&t=getnext";
		op['callback'] = "<?php echo $cFun;?>NextAjaxCallBack";
		$(this).bjuiajax("doAjax",op);// 显示处理结果
	}else{
		$("#<?php echo $cFun?>table").html('<tr><td style="text-align: center;" colspan="6" id="<?php echo $cFun;?>Update">您已经是最新版本了！</td>');
	}
}
function <?php echo $cFun;?>NextAjaxCallBack(json){
	var data = json.data.data;
	if(data){
		$("#<?php echo $cFun;?>TextBox").removeClass('alert-success');
		$("#<?php echo $cFun;?>VerBox").removeClass('alert-success');
		$("#<?php echo $cFun;?>TextBox").addClass('alert-danger');
		$("#<?php echo $cFun;?>VerBox").addClass('alert-danger');
		$("#<?php echo $cFun;?>newText").html('当前服务器有最新版可以升级！');

		if(data.version_down == 1){
			set = '<a class="btn btn-secondary radius size-MINI" href="javascript:<?php echo $cFun;?>update()">升级</a>';
		}else{
			set = '对不起，该版本不支持在线升级，请到官网下载离线升级补丁！';
		}
		html += '<tr class=""><td style="text-align: center;">'+data['version_id']+'</td>'+
			'<td style="text-align: center;">'+data['version_number']+'</td>'+
			'<td style="text-align: center;">'+data['version_type']+'</td>'+
			'<td style="text-align: center;">'+Math.floor(data['version_size']/1024*100)/100+'KB</td>'+
			'<td style="text-align: center;">'+data['version_addtime']+'</td>'+
			'<td style="text-align: center;">'+data['version_title']+'</td>'+
			'</tr>';
		$("#<?php echo $cFun?>table").html(html+'<tr><td colspan="6" style="line-height:25px;">'+data['version_remark']+'</td></tr><tr class=""><td colspan="6" id="<?php echo $cFun;?>Update" style="text-align: center;">'+set+'</td></tr>');
	}else{
		$("#<?php echo $cFun?>table").html('<tr><td style="text-align: center;" colspan="6" id="<?php echo $cFun;?>Update">您已经是最新版本了！</td>');
	}
}

function <?php echo $cFun;?>update(){
	$('#<?php echo $cFun;?>Update').html('更新升级包中...');

	//开始升级操作
	$.ajax({
		type:"GET",
		timeout : 100000,
		url:"index.php?a=yes&c=cloud.version&t=update",
		dataType:"json",
		success:function(json){
			if(json.statusCode == 200 ){
				$("#<?php echo $cFun;?>Update").html(json.message);
			}else{
				$('#<?php echo $cFun;?>Update').html(set);
			}
			$(this).bjuiajax("ajaxDone",json);// 显示处理结果
		},
	});
}
</script>
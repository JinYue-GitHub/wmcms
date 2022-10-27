<div class="bjui-pageHeader">
	<div class="row cl pt-10 pb-10 pl-10">
		<div class="f-l" style="margin-left:10px">
			<div class="alert alert-success search-inline"><i class="fa fa-info-circle"></i> 可以开启自动上传错误，加快错误解决！</div>
        </div>
	</div>
</div>

<div class="bjui-pageContent">
	<div class="tab-pane fade active in">
		<table class="table table-border table-bordered table-hover table-bg table-sort">
			<thead>
			<tr>
				<th style="text-align: center;" width="7%">ID</th>
				<th style="text-align: center;" width="10%">是否解决</th>
				<th style="text-align: center;" width="10%">错误码</th>
				<th style="text-align: center;" width="15%">错误发生的时间</th>
				<th style="text-align: center;" width="15%">错误上传的时间</th>
				<th style="text-align: center;" width="15%">错误解决的时间</th>
				<th>错误信息</th>
	        </tr>
			</thead>
			<tbody id="<?php echo $cFun?>table">
				<tr class=""><td style="text-align: center;" colspan="7">加载中...</td></tr>
			</tbody>
		</table>
	</div>
</div>
<div class="bjui-pageFooter">
    <div class="pages">
        <span>第 <wm id="<?php echo $cFun?>page">0</wm>/<wm id="<?php echo $cFun?>sumpage">0</wm>页 ，共 <wm id="<?php echo $cFun?>dataCount">0</wm> 条</span>
    </div>
    <div class="pagination-box"><ul class="pagination" id="<?php echo $cFun?>pageList"></ul></div>
</div>


<script>
var html = '';
var pageCount = <?php echo $pageSize;?>;
var page = 1;
$(document).ready(function(){
	<?php echo $cFun;?>GetList(0,page,pageCount);
	//点击分页
	$("#<?php echo $cFun?>pageList li").live("click",function(){
		var nowPage = parseInt($(this).data('page'));
		if(nowPage != 0 && nowPage != page){
			page = nowPage;
			<?php echo $cFun;?>GetList(0,nowPage,pageCount);
		}
	});
});
function <?php echo $cFun;?>GetList(tid,page,pageCount){
	page = parseInt(page);
	var sumPage = parseInt($("#<?php echo $cFun?>sumpage").html());
	if(page<=1){
		page=1;
	}
	if(page>= sumPage){
		page = sumPage;
	}
	var op = new Array();
	op['type'] = 'GET';
	op['reload'] = 'false';
	op['loadingmask'] = 'true';
	op['url'] = "index.php?a=yes&c=cloud.err&t=getlist&page="+page+"&pagecount="+pageCount+"&tid="+tid;
	op['callback'] = "<?php echo $cFun;?>listAjaxCallBack";
	$(this).bjuiajax("doAjax",op);// 显示处理结果
}
function <?php echo $cFun;?>listAjaxCallBack(json){
	var data = json.data.data;
	if(data){
		html = '';
		for(var o=0;o<data.length;o++){
			var statusColor = 'color:red';
			if( data[o]['message_status'] == '1'){
				statusColor = 'color:green';
			}
			if( data[o]['errlog_remarktime'] == '0'){
				data[o]['errlog_remarktime'] = '------';
			}
			html += '<tr class=""><td style="text-align: center;">'+data[o]['errlog_id']+'</td>'+
				'<td style="text-align: center;'+statusColor+'">'+data[o]['errlog_status_text']+'</td>'+
				'<td style="text-align: center;">'+data[o]['errlog_state']+'</td>'+
				'<td style="text-align: center;">'+data[o]['errlog_uptime']+'</td>'+
				'<td style="text-align: center;">'+data[o]['errlog_addtime']+'</td>'+
				'<td style="text-align: center;">'+data[o]['errlog_remarktime']+'</td>'+
				'<td style="text-align: center;">'+data[o]['errlog_msg']+'</td>'+
				'</tr>';
		}
		$("#<?php echo $cFun?>table").html(html);
		$("#<?php echo $cFun?>page").html(page);
		$("#<?php echo $cFun?>sumpage").html(json.data.sumpage);
		$("#<?php echo $cFun?>dataCount").html(json.data.datacount);
		$("#<?php echo $cFun?>pageList").html(createPage(page,json.data.sumpage));
	}else{
		$("#<?php echo $cFun?>table").html('<tr><td style="text-align: center;" colspan="7">暂无数据</td>');
	}
}
</script>
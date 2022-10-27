<div class="bjui-pageHeader">
	<div class="row cl pt-10 pb-10 pl-10">
		<div class="f-l" style="margin-left:10px">
			<div class="alert alert-success search-inline"><i class="fa fa-info-circle"></i> 您的每一次反馈都是WMCMS的进步！</div>
        	<a class="btn btn-secondary radius size-MINI" data-mask="true" href="index.php?c=cloud.bug.add" data-toggle="dialog" data-title="提交新的反馈" data-width="530" data-height="450" ><i class="fa fa-plus"></i> 提交反馈</a>
        </div>
	</div>
</div>

<div class="bjui-pageContent">
	<div class="tab-pane fade active in">
		<table class="table table-border table-bordered table-hover table-bg table-sort">
			<thead>
			<tr>
				<th style="text-align: center;" width="7%">ID</th>
				<th style="text-align: center;" width="20%">提交域名</th>
				<th style="text-align: center;" width="20%">反馈类型</th>
				<th style="text-align: center;">BUG内容</th>
				<th style="text-align: center;" width="16%">提交时间</th>
				<th style="text-align: center;" width="10%">是否解决</th>
	            <th style="text-align: center;" width="8%">操作</th>
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
	op['url'] = "index.php?a=yes&c=cloud.bug&isuser=1&t=getlist&page="+page+"&pagecount="+pageCount+"&tid="+tid;
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
			html += '<tr class=""><td style="text-align: center;">'+data[o]['message_id']+'</td>'+
				'<td style="text-align: center;"><a href="'+data[o]['message_domain']+'" target="_blank">'+data[o]['message_domain']+'</td>'+
				'<td style="text-align: center;">'+data[o]['type_name']+'</td>'+
				'<td><div style="height:12px;overflow:hidden">'+data[o]['message_content']+'</div></td>'+
				'<td style="text-align: center;">'+data[o]['message_addtime']+'</td>'+
				'<td style="text-align: center;'+statusColor+'">'+data[o]['message_status_text']+'</td>'+
				'<td style="text-align: center;">'+
					'<a class="btn btn-secondary radius size-MINI" data-data="'+data[o]+'" data-mask="true" href="index.php?c=cloud.bug.detail&id='+data[o]['message_id']+'" data-toggle="dialog" data-title="查看反馈详细信息" data-width="520" data-height="450" >反馈详情</a>'+
	            '</td></tr>';
		}
		$("#<?php echo $cFun?>table").html(html);
		$("#<?php echo $cFun?>page").html(page);
		$("#<?php echo $cFun?>sumpage").html(json.data.sumpage);
		$("#<?php echo $cFun?>dataCount").html(json.data.datacount);
		$("#<?php echo $cFun?>pageList").html(createPage(page,json.data.sumpage));
	}else{
		$("#<?php echo $cFun?>table").html('<tr><td style="text-align: center;" colspan="6">暂无数据</td>');
	}
}
</script>
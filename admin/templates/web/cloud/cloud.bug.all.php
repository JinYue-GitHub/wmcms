<div class="bjui-pageHeader">
	<div class="row cl pt-10 pb-10 pl-10">
		<div class="f-l" style="margin-left:10px">
			<div class="alert alert-success search-inline"><i class="fa fa-info-circle"></i> 数据来源于所有用户主动提交,提交BUG请到我的反馈页面进行反馈！</div>
		</div>
	</div>
</div>

<div class="bjui-pageContent">
	<fieldset>
		<legend>全网反馈列表</legend>
        <ul class="nav nav-tabs" role="tablist" id="<?php echo $cFun?>tabs">
            <li class="active"><a href="#<?php echo $cFun.$type;?>0" data-id="0" role="tab" data-toggle="tab">全部反馈</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade active in">
				<table class="table table-border table-bordered table-hover table-bg table-sort">
					<thead>
						<tr>
						<th style="text-align: center;" width="7%">ID</th>
						<th style="text-align: center;" width="20%">提交域名</th>
						<th style="text-align: center;">反馈内容</th>
						<th style="text-align: center;" width="16%">提交时间</th>
						<th style="text-align: center;" width="10%">是否解决</th>
			            <th style="text-align: center;" width="8%">操作</th>
			            </tr>
					</thead>
					<tbody id="<?php echo $cFun?>table">
						<tr class=""><td style="text-align: center;" colspan="6">加载中...</td></tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="bjui-pageFooter">
	    <div class="pages">
	        <span>第 <wm id="<?php echo $cFun?>page">0</wm>/<wm id="<?php echo $cFun?>sumpage">0</wm>页 ，共 <wm id="<?php echo $cFun?>dataCount">0</wm> 条</span>
	    </div>
	    <div class="pagination-box"><ul class="pagination" id="<?php echo $cFun?>pageList"></ul></div>
	</div>
</div>


<script>
var html = '';
var nowTid = 0;
var pageCount = <?php echo $pageSize;?>;
var page = 1;
$(document).ready(function(){
	var op = new Array();
	op['type'] = 'GET';
	op['reload'] = 'false';
	op['loadingmask'] = 'true';
	//请求反馈的分类
	op['url'] = "index.php?a=yes&c=cloud.bug&t=gettype";
	op['callback'] = "<?php echo $cFun;?>typeListAjaxCallBack";
	$(this).bjuiajax("doAjax",op);// 显示处理结果

	//点击留言的分类
	$("#<?php echo $cFun?>tabs a").live("click",function(){
		var tid = $(this).data('id');
		page = 1;
		if( nowTid != tid ){
			//请求反馈的内容
			<?php echo $cFun;?>GetList(tid,page,pageCount);
			//请求新的留言数据
			nowTid = tid;
		}
	});	

	//点击分页
	$("#<?php echo $cFun?>pageList li").live("click",function(){
		var nowPage = parseInt($(this).data('page'));
		if(nowPage != 0 && nowPage != page){
			page = nowPage;
			<?php echo $cFun;?>GetList(nowTid,nowPage,pageCount);
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
	op['url'] = "index.php?a=yes&c=cloud.bug&t=getlist&page="+page+"&pagecount="+pageCount+"&tid="+tid;
	op['callback'] = "<?php echo $cFun;?>listAjaxCallBack";
	$(this).bjuiajax("doAjax",op);// 显示处理结果
}

function <?php echo $cFun;?>typeListAjaxCallBack(json){
	var data = json.data;
	if(data){
		for(var o=0;o<data.length;o++){
	    	html += '<li><a href="#<?php echo $cFun.$type;?>'+data[o]['type_id']+'" data-id="'+data[o]['type_id']+'" role="tab" data-toggle="tab">'+data[o]['type_name']+'</a></li>';
		}
		$("#<?php echo $cFun?>tabs").append(html);
	}
	//请求反馈的内容
	<?php echo $cFun;?>GetList(nowTid,page,pageCount);
}
function <?php echo $cFun;?>listAjaxCallBack(json){
	var data = json.data.data;
	if(data){
		html = '';
		for(var o=0;o<data.length;o++){
			var statusColor = 'color:red';
			var url = data[o]['message_domain'];
			if( data[o]['message_status'] == '1'){
				statusColor = 'color:green';
			}
			if( data[o]['message_domain_show'] == '0'){
				url = 'http://<?php echo WMDOMAIN;?>';
			}
			html += '<tr class=""><td style="text-align: center;">'+data[o]['message_id']+'</td>'+
				'<td style="text-align: center;"><a href="'+url+'" target="_blank">'+data[o]['message_domain']+'</td>'+
				'<td><div style="height:12px;overflow:hidden;word-break:break-all;">'+data[o]['message_content']+'</div></td>'+
				'<td style="text-align: center;">'+data[o]['message_addtime']+'</td>'+
				'<td style="text-align: center;'+statusColor+'">'+data[o]['message_status_text']+'</td>'+
				'<td style="text-align: center;">'+
					'<a class="btn btn-secondary radius size-MINI" data-mask="true" href="index.php?c=cloud.bug.detail&id='+data[o]['message_id']+'" data-toggle="dialog" data-title="查看反馈详细信息" data-width="520" data-height="450" >反馈详情</a>'+
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
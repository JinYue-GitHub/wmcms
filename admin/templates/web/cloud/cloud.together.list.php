<div class="bjui-pageHeader">
	<div class="row cl pt-10 pb-10 pl-10">
		<div class="f-l" style="margin-left:10px">
			<div class="alert alert-danger search-inline"><i class="fa fa-info-circle"></i> 
			需求众研是什么？所有站点可以在WMCMS后台提交需要的功能，然后由官方进行开发。</div>
			<div class="alert alert-success search-inline"><i class="fa fa-info-circle"></i> 众研如何进行？1.提交需求->2.官方评估->3.众评->4.开发->5.测试->6.上线</div>
			<a class="btn btn-secondary radius size-MINI" data-mask="true" href="index.php?c=cloud.together.add" data-toggle="dialog" data-title="提交新的需求" data-width="460" data-height="400" ><i class="fa fa-plus"></i> 提交需求</a>
		</div>
	</div>
</div>

<div class="bjui-pageContent">
	<fieldset>
		<legend>众研需求列表</legend>
        <ul class="nav nav-tabs" role="tablist" id="<?php echo $cFun?>tabs">
            <li class="active"><a href="#<?php echo $cFun.$type;?>0" data-id="0" role="tab" data-toggle="tab">全部需求</a></li>
            <li><a href="#<?php echo $cFun.$type;?>1" data-id="1" role="tab" data-toggle="tab">评估中</a></li>
            <li><a href="#<?php echo $cFun.$type;?>2" data-id="2" role="tab" data-toggle="tab">众评中</a></li>
            <li><a href="#<?php echo $cFun.$type;?>3" data-id="3" role="tab" data-toggle="tab">开发中</a></li>
            <li><a href="#<?php echo $cFun.$type;?>4" data-id="4" role="tab" data-toggle="tab">测试中</a></li>
            <li><a href="#<?php echo $cFun.$type;?>5" data-id="5" role="tab" data-toggle="tab">已上线</a></li>
            <li><a href="#<?php echo $cFun.$type;?>6" data-id="6" role="tab" data-toggle="tab">评估失败</a></li>
            <li><a href="#<?php echo $cFun.$type;?>7" data-id="7" role="tab" data-toggle="tab">众评失败</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade active in">
				<table class="table table-border table-bordered table-hover table-bg table-sort">
					<thead>
						<tr>
						<th style="text-align: center;" width="7%">ID</th>
						<th style="text-align: center;" width="8%">需求状态</th>
						<th style="text-align: center;" width="20%">提交站点</th>
						<th style="text-align: center;">需求标题</th>
						<th style="text-align: center;" width="8%">需求响应站点</th>
						<th style="text-align: center;" width="8%">需求(点击)</th>
						<th style="text-align: center;" width="8%">不需求(点击)</th>
						<th style="text-align: center;" width="15%">提交时间</th>
			            <th style="text-align: center;" width="8%">操作</th>
			            </tr>
					</thead>
					<tbody id="<?php echo $cFun?>table">
						<tr class=""><td style="text-align: center;" colspan="9">加载中...</td></tr>
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
//需求状态id
var nowTid = 0;
var pageCount = <?php echo $pageSize;?>;
var page = 1;
$(document).ready(function(){
	//请求需求列表
	<?php echo $cFun;?>GetList(nowTid,page,pageCount);

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

//获得需求列表
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
	op['url'] = "index.php?a=yes&c=cloud.together&t=getlist&page="+page+"&pagecount="+pageCount+"&tid="+tid;
	op['callback'] = "<?php echo $cFun;?>listAjaxCallBack";
	$(this).bjuiajax("doAjax",op);// 显示处理结果
}
//获得需求列表的回调操作
function <?php echo $cFun;?>listAjaxCallBack(json){
	var data = json.data.data;
	var needHtml = noNeedHtml = '';
	if(data){
		html = '';
		for(var o=0;o<data.length;o++){
			var url = data[o]['together_domain'];
			if( data[o]['together_domain_show'] == '0'){
				url = 'http://<?php echo WMDOMAIN;?>';
			}

			if( data[o]['together_status'] == '2'){
				needHtml = '<a class="btn btn-green radius size-MINI" data-mask="true" href="index.php?a=yes&c=cloud.together&t=operation&need=1&id='+data[o]['together_id']+'" data-toggle="doajax">'+data[o]['together_need']+'</a>';
				noNeedHtml = '<a class="btn btn-red radius size-MINI" data-mask="true" href="index.php?a=yes&c=cloud.together&t=operation&need=0&id='+data[o]['together_id']+'" data-toggle="doajax">'+data[o]['together_noneed']+'</a>';
			}else{
				needHtml = data[o]['together_need'];
				noNeedHtml = data[o]['together_noneed'];
			}

			html += '<tr class=""><td style="text-align: center;">'+data[o]['together_id']+'</td>'+
				'<td style="text-align: center;">'+data[o]['together_status_text']+'</td>'+
				'<td style="text-align: center;"><a href="'+url+'" target="_blank">'+data[o]['together_domain']+'</td>'+
				'<td><div style="height:12px;overflow:hidden;word-break:break-all;">'+data[o]['together_title']+'</div></td>'+
				'<td style="text-align: center;">'+data[o]['together_website']+'</td>'+
				'<td style="text-align: center;">'+needHtml+'</td>'+
				'<td style="text-align: center;">'+noNeedHtml+'</td>'+
				'<td style="text-align: center;">'+data[o]['together_addtime']+'</td>'+
				'<td style="text-align: center;">'+
					'<a class="btn btn-secondary radius size-MINI" data-mask="true" href="index.php?c=cloud.together.detail&id='+data[o]['together_id']+'" data-toggle="dialog" data-title="查看反馈详细信息" data-width="550" data-height="470" >需求详情</a>'+
	            '</td></tr>';
		}
		$("#<?php echo $cFun?>table").html(html);
		$("#<?php echo $cFun?>page").html(page);
		$("#<?php echo $cFun?>sumpage").html(json.data.sumpage);
		$("#<?php echo $cFun?>dataCount").html(json.data.datacount);
		$("#<?php echo $cFun?>pageList").html(createPage(page,json.data.sumpage));
	}else{
		$("#<?php echo $cFun?>table").html('<tr><td style="text-align: center;" colspan="9">暂无数据</td>');
	}
}
</script>
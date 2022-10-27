<div class="bjui-pageHeader">
	<div class="row cl pt-10 pb-10 pl-10">
		<div class="f-l">
			<form id="pagerForm" name="<?php echo $cFun;?>Form" data-toggle="ajaxform" action="<?php echo $url;?>" method="post">
				<input type="hidden" name="pageSize" value="<?php echo $pageSize;?>">
				<input type="hidden" name="pageCurrent" value="<?php echo $pageCurrent;?>">
				<input type="hidden" name="orderField" value="<?php echo $orderField;?>">
				<input type="hidden" name="orderDirection" value="<?php echo $orderDirection;?>">
				<input type="hidden" name="open" value="<?php echo $open;?>">
				<a href="index.php?c=operate.zt.typeedit&t=type_add" data-toggle="navtab" data-id="article-type-add" data-title="新增分类" data-width="350" data-height="330" class="btn btn-primary radius size-MINI"><i class="fa fa-plus"></i> 新增分类</a>
				<a onclick="<?php echo $cFun?>setOpen(1)" class="btn btn-warning  radius"> 全部展开 </a>
				<a onclick="<?php echo $cFun?>setOpen(0)" class="btn btn-secondary  radius"> 全部关闭 </a>
				<a id="<?php echo $cFun?>refresh" class="btn size-MINI btn-primary radius"><i class="fa fa-refresh fa-spin"></i> 刷新</a>
			</form>
		</div>
	</div>
</div>

<div class="bjui-pageContent">
	<div class="mt-10">
		<table class="table table-border table-bordered table-hover table-bg table-sort">
			<thead>
				<tr class="text-c">
					<th align="center" width="47">折叠</th>
					<th align="center" width="50">ID</th>
					<th align="center" width="50">排序</th>
					<th style="text-align:left">栏目名称 </th>
					<th align="center" width="120">操作</th>
				</tr>
			</thead>
			<tbody>
				<tr class="text-c">
					<td colspan="5" style="background-color: #fff;padding:0;">
					<div class="category">
						<?php $manager->GetTypeList($typeArr , $typeSer);?>
					</div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>


<script type="text/javascript">
//页面唯一op获取函数
function <?php echo $cFun;?>GetOp(){
	var op=new Array();
	op['type'] = 'POST';
	op['data'] = $("[name=<?php echo $cFun;?>Form]").serializeArray();
	return op;
}
//页面唯一展开关闭函数
function <?php echo $cFun?>setOpen(open){
	$('[name="open"]').val(open);
	$(this).navtab("reload",<?php echo $cFun;?>GetOp());	//刷新当前Tab页面 
}
//删除分类
function <?php echo $cFun;?>delAjax(id)
{
	var ajaxOptions=new Array();
	var ajaxData=new Object();
	ajaxOptions = <?php echo $cFun;?>GetOp();
	
	ajaxData.id = id;
	ajaxOptions['data'] = ajaxData;
	ajaxOptions['url'] = "index.php?a=yes&c=operate.zt.type&t=type_del";
	ajaxOptions['confirmMsg'] = "删除分类会删除分类下的子分类和友链，确定要删除分类吗？";
	ajaxOptions['callback'] = "<?php echo $cFun;?>ajaxCallBack";
	$(".btn-danger").bjuiajax('doAjax', ajaxOptions);
}
//页面唯一回调函数
function <?php echo $cFun;?>ajaxCallBack(json){
	$(this).bjuiajax("ajaxDone",json);//显示处理结果
	$(this).navtab("reload",<?php echo $cFun;?>GetOp());	//刷新当前Tab页面 
}


$(document).ready(function(){
	$('#<?php echo $cFun?>refresh').click(function() {
	    $(this).navtab("reload",<?php echo $cFun;?>GetOp());// 刷新当前Tab页面
	});
	<?php 
	if ($open == '1')
	{
		echo '$("dd").css("display","block");';
		echo '$(".fold i").removeClass("icon-fold");';
		echo '$(".fold i").addClass("icon-unfold");';
	}
	?>
	/* 分类展开收起 */
	$(".category dd").prev().find(".fold i").addClass("icon-fold")
		.click(function(){
			var self = $(this);
			if(self.hasClass("icon-unfold")){
				self.closest("dt").next().slideUp("fast", function(){
					self.removeClass("icon-unfold").addClass("icon-fold");
				});
			} else {
				self.closest("dt").next().slideDown("fast", function(){
					self.removeClass("icon-fold").addClass("icon-unfold");
				});
			}
		});
});
</script>
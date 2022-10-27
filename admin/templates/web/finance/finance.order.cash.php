<style>
.table tr {
    height: 35px;
}
thead th {
	text-align: center;
}
.list-tool{
	margin-bottom:5px;
}
.status0{color:red}
.status1{color:green}
.status2{color:#CACACA}
</style>
<div class="bjui-pageHeader">
	<div class="row cl pt-10 pb-10 pl-10">
		<div class="f-l" style="margin-left:10px">
            <span >快捷操作：</span>
			<a href="index.php?a=yes&c=finance.order.cash&t=del" data-toggle="doajaxchecked" data-idname="ids" data-group="ids" data-confirm-msg="确定要删除选中项吗？" class="btn btn-danger size-MINI radius" data-callback="<?php echo $cFun;?>ajaxCallBack"> <i class="fa fa-remove"></i> 批量删除</a>
			<a href="index.php?a=yes&c=finance.order.cash&t=clear" data-toggle="doajax" data-confirm-msg="确定清空记录要吗？" class="btn btn-danger size-MINI radius"><i class="fa fa-trash-o"></i> 清空记录</a>
		</div>
		<div class="list-tool">
			<form id="pagerForm" name="<?php echo $cFun;?>Form" data-toggle="ajaxsearch" data-loadingmask="true" action="<?php echo $url;?>" method="post">
				<input type="hidden" name="pageSize" value="<?php echo $pageSize;?>">
				<input type="hidden" name="pageCurrent" value="<?php echo $pageCurrent;?>">
				<input type="hidden" name="orderField" value="<?php echo $orderField;?>">
				<input type="hidden" name="orderDirection" value="<?php echo $orderDirection;?>">
				<a id="<?php echo $cFun;?>refresh" style="margin-left:10px;" class="btn size-MINI btn-primary radius"><i class="fa fa-refresh fa-spin"></i> 刷新</a>
             </form>
		</div>
	</div>
</div>


<div class="bjui-pageContent">
		<table class="table table-border table-bordered table-hover table-bg table-sort">
			<thead>
				<tr>
				<th style="text-align: center;" width="2%;"><input type="checkbox" class="checkboxCtrl" data-group="ids" data-toggle="icheck"></th>
				<th width="6%" data-order-field="cash_id">ID</th>
				<th width="5%" data-order-field="cash_status">订单状态</th>
				<th width="12%">申请用户</th>
				<th width="7%" data-order-field="cash_money">提现金额</th>
				<th width="8%" data-order-field="cash_real">实际到账</th>
	            <th width="8%" data-order-field="cash_cost">提现手续费</th>
				<th width="15%">备注信息</th>
	            <th width="15%" data-order-field="cash_time">申请时间时间</th>
	            <th width="10%">操作</th>
	            </tr>
			</thead>
			<tbody id="<?php echo $cFun?>List">
			<?php
			if( $dataArr )
			{
				$i = 1;
				foreach ($dataArr as $k=>$v)
				{
					$cur = str::CheckElse( $i%2 , 0 , '' , 'even_index_row');
					$set = '';
					if($v['cash_status'] == 0)
					{
						$set = '<button type="button" class="btn btn-warning radius" id="'.$cFun.'dropdownMenu_'.$v['cash_id'].'" data-toggle="dropdown">操作<span class="caret"></span></button>
								<ul class="dropdown-menu" aria-labelledby="'.$cFun.'dropdownMenu_'.$v['cash_id'].'" style="width:80px">
							      <li><a href="index.php?a=yes&c=finance.order.cash&t=status&status=1&id='.$v['cash_id'].'" data-mask="true" data-toggle="doajax">通过申请</a></li>
							      <li><a href="index.php?a=yes&c=finance.order.cash&t=status&status=2&id='.$v['cash_id'].'" data-mask="true" data-toggle="doajax">拒绝申请</a></li>
								</ul>';
					}
					echo '<tr class="'.$cur.'">
							<td style="text-align: center;"><input type="checkbox" name="ids" data-toggle="icheck" value="'.$v['cash_id'].'"></td>
							<td style="text-align: center;">'.$v['cash_id'].'</td>
							<td style="text-align: center;" class="status'.$v['cash_status'].'">'.$cashMod->GetStatus($v['cash_status']).'</td>
							<td style="text-align: center;">'.$v['user_nickname'].' ( id:'.$v['cash_user_id'].' )</td>
							<td style="text-align: center;">'.$v['cash_money'].'</td>
							<td style="text-align: center;">'.$v['cash_real'].'</td>
							<td style="text-align: center;">'.$v['cash_cost'].'</td>
							<td style="text-align: center;">'.$v['cash_remark'].'</td>
							<td style="text-align: center;">'.date( 'Y-m-d H:i:s' , $v['cash_time']).'</td>
							<td style="text-align: center;" data-noedit="true">
								'.$set.'
				            	<a class="btn btn-danger radius" onclick="'.$cFun.'delAjax('.$v['cash_id'].')">删除</a>
				            </td>
						</tr>';
					$i++;
				}
			}
			else
			{
				echo '<script type="text/javascript">$(document).ready(function(){$(this).alertmsg("info", "没有数据了!")});</script>';
			}
			?>
			</tbody>
		</table>
</div>

<div class="bjui-pageFooter">
    <div class="pages">
        <span>每页&nbsp;</span>
        <div class="selectPagesize">
            <select data-toggle="selectpicker" data-toggle-change="changepagesize">
                <option value="20">20</option>
                <option value="30">30</option>
                <option value="60">60</option>
                <option value="120">120</option>
            </select>
        </div>
        <span>&nbsp;条，共 <?php echo $total;?> 条</span>
    </div>
    <div class="pagination-box" data-toggle="pagination" data-total="<?php echo $total;?>" data-page-size="<?php echo $pageSize;?>" data-pageCurrent="<?php echo $pageCurrent?>">
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
//获得选中项
function getChecked() {
	var records = new Array();
	$('#<?php echo $cFun?>List').each(function() {
		if($(this).find('td:eq(0)>input:checked').length == 1){
			records[records.length] = gridObj.getRowRecord($(this));
		}
	});
	return records;
}
//删除记录
function <?php echo $cFun;?>delAjax(id)
{
	var ajaxOptions=new Array();
	var ajaxData=new Object();
	ajaxOptions = <?php echo $cFun;?>GetOp();
	
	ajaxData.id = id;
	ajaxOptions['data'] = ajaxData;
	ajaxOptions['url'] = "index.php?a=yes&c=finance.order.cash&t=del";
	ajaxOptions['confirmMsg'] = "确定要删除所选的记录吗？";
	ajaxOptions['callback'] = "<?php echo $cFun;?>ajaxCallBack";
	$(".btn-danger").bjuiajax('doAjax', ajaxOptions);
}
function <?php echo $cFun;?>ajaxCallBack(json){
	$(this).bjuiajax("ajaxDone",json);//显示处理结果
	$(this).dialog("closeCurrent");	//关闭当前dialog
	$(this).navtab("reload",<?php echo $cFun;?>GetOp());	//刷新当前Tab页面 
}


$(document).ready(function(){
	$('#<?php echo $cFun;?>refresh').click(function() {
	   $(this).navtab("reload",<?php echo $cFun;?>GetOp());// 刷新当前Tab页面
	});
});
</script>
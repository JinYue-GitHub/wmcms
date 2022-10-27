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
</style>
<div class="bjui-pageHeader">
	<div class="row cl pt-10 pb-10 pl-10">
		<div class="f-l">
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
				<th width="5%" data-order-field="order_id">ID</th>
				<th width="6%" data-order-field="order_gold1">结算<?php echo $config['gold1_name']?></th>
	            <th width="6%" data-order-field="order_gold2">结算<?php echo $config['gold2_name']?></th>
	            <th width="10%" data-order-field="order_money">结算金额</th>
	            <th width="10%">结算员</th>
	            <th width="10%" data-order-field="order_time">结算时间</th>
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
					echo '<tr class="'.$cur.'">
							<td style="text-align: center;">'.$v['order_id'].'</td>
							<td style="text-align: center;">'.$v['order_gold1'].'</td>
							<td style="text-align: center;">'.$v['order_gold2'].'</td>
							<td style="text-align: center;">'.$v['order_money'].'</td>
							<td style="text-align: center;">'.$manager->GetByMid($v['order_admin_id'],'manager_name').'( id:'.$v['order_admin_id'].')'.'</td>
							<td style="text-align: center;">'.date( 'Y-m-d H:i:s' , $v['order_time']).'</td>
							<td style="text-align: center;" data-noedit="true">
				            	<a class="btn btn-secondary radius size-MINI" data-toggle="navtab" data-id="finance-report-list" data-title="销售报表结算详情" href="index.php?d=yes&c=finance.report.list&ids='.$listMod->GetRidsByOid($v['order_id']).'">结算详情</a> 
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

<!-- 确认结算层 -->
<div id="<?php echo $cFun;?>Confirm" data-noinit="true" class="hide" align="center">
	<input id="<?php echo $cFun;?>ids" type="hidden">
	<p style="padding:10px;font-size: 14px;color:green">
		<?php echo $config['gold1_name']?>：<span id="<?php echo $cFun;?>Gold1">0</span>
	</p>
	<p style="padding:0px 10px;font-size: 14px;color:red">
		<?php echo $config['gold2_name']?>：<span id="<?php echo $cFun;?>Gold2">0</span> / 
		<span id="<?php echo $cFun;?>RmbToGold2">0</span> = 
		<span id="<?php echo $cFun;?>Rmb">0</span> 元
	</p>
    <div class="pt-10" style="margin-left: 80px">
		<button onClick="<?php echo $cFun;?>SettlementAjax()" type="button" class="btn-green" data-icon="location-arrow">确认结算</button>
		<button type="button" class="btn-close" data-icon="close">关闭</button>
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
//确认结算
function <?php echo $cFun;?>ConfirmAjax(id)
{
	$('#<?php echo $cFun;?>ids').html(0);
	$('#<?php echo $cFun;?>Gold1').html(0);
	$('#<?php echo $cFun;?>Gold2').html(0);
	$('#<?php echo $cFun;?>RmbToGold2').html(0);
	
	var ajaxOptions=new Array();
	var ajaxData=new Object();
	ajaxOptions = <?php echo $cFun;?>GetOp();
	ajaxData.id = id;
	ajaxOptions['data'] = ajaxData;
	ajaxOptions['url'] = "index.php?a=yes&c=finance.report&t=confirm";
	ajaxOptions['callback'] = "<?php echo $cFun;?>ConfirmCallBack";
	ajaxOptions['loadingmask'] = true;
	return $(this).bjuiajax('doAjax', ajaxOptions);
}
//确认结算回调
function <?php echo $cFun;?>ConfirmCallBack(json){
	if( json.statusCode != '200'){
		return $(this).bjuiajax("ajaxDone",json);//显示处理结果
	}
	$('#<?php echo $cFun;?>ids').val(json.data.ids);
	$('#<?php echo $cFun;?>Gold1').html(json.data.report_gold1);
	$('#<?php echo $cFun;?>Gold2').html(json.data.report_gold2);
	$('#<?php echo $cFun;?>RmbToGold2').html(json.data.rmb_to_gold2);
	$('#<?php echo $cFun;?>Rmb').html(json.data.report_gold2/json.data.rmb_to_gold2);

	//确认结算窗口打开
	var ajaxOptions=new Array();
	ajaxOptions['target'] = "#<?php echo $cFun;?>Confirm";
	ajaxOptions['title'] = "确认结算";
	ajaxOptions['width'] = "280";
	ajaxOptions['height'] = "150";
	ajaxOptions['loadingmask'] = true;
	ajaxOptions['mask'] = "true";
	return $(this).dialog(ajaxOptions);
}
//结算
function <?php echo $cFun;?>SettlementAjax()
{
	var ajaxOptions=new Array();
	var ajaxData=new Object();
	ajaxOptions = <?php echo $cFun;?>GetOp();
	ajaxData.ids = $('#<?php echo $cFun;?>ids').val();
	ajaxOptions['data'] = ajaxData;
	ajaxOptions['url'] = "index.php?a=yes&c=finance.report&t=settlement";
	ajaxOptions['confirmMsg'] = "确定要结算所选的记录吗？";
	ajaxOptions['callback'] = "<?php echo $cFun;?>ajaxCallBack";
	ajaxOptions['loadingmask'] = true;
	return $(this).bjuiajax('doAjax', ajaxOptions);
}
//结算后回调
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
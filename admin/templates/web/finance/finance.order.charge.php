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
		<div class="f-l" style="margin-left:10px">
            <span >快捷操作：</span>
			<a href="index.php?a=yes&c=finance.order.charge&t=del" data-toggle="doajaxchecked" data-idname="ids" data-group="ids" data-confirm-msg="确定要删除选中项吗？" class="btn btn-danger size-MINI radius" data-callback="<?php echo $cFun;?>ajaxCallBack"> <i class="fa fa-remove"></i> 批量删除</a>
			<a href="index.php?a=yes&c=finance.order.charge&t=clear" data-toggle="doajax" data-confirm-msg="确定清空记录要吗？" class="btn btn-danger size-MINI radius"><i class="fa fa-trash-o"></i> 清空记录</a>
		</div>
		<div class="list-tool">
			<form id="pagerForm" name="<?php echo $cFun;?>Form" data-toggle="ajaxsearch" data-loadingmask="true" action="<?php echo $url;?>" method="post">
				<input type="hidden" name="pageSize" value="<?php echo $pageSize;?>">
				<input type="hidden" name="pageCurrent" value="<?php echo $pageCurrent;?>">
				<input type="hidden" name="orderField" value="<?php echo $orderField;?>">
				<input type="hidden" name="orderDirection" value="<?php echo $orderDirection;?>">
				<span class="" style="float:left;margin:5px 0 0 15px;">快速查询：</span>
				
				<select data-toggle="selectpicker" name="status" data-width="100">
                	<option value="" <?php echo str::CheckElse( $status , '' , 'selected=""' );?>>全部</option>
                	<option value="0" <?php echo str::CheckElse( $status , '0' , 'selected=""' );?>>待付款</option>
                	<option value="1" <?php echo str::CheckElse( $status , '1' , 'selected=""' );?>>已支付</option>
                </select>
                
				<select data-toggle="selectpicker" name="type" data-width="100">
                	<option value="">全部</option>
					<?php 
					foreach ($typeArr as $key=>$val)
					{
						$select = str::CheckElse( $type , $key , 'selected=""' );
						echo '<option value="'.$key.'" '.$select.'>'.$val.'</option>';
					}
					?>
                </select>
                <input type="text" value="<?php echo $uid;?>" placeholder="用户ID" name="uid" size="15">
				<button type="submit" class="btn btn-warning radius" data-icon="search">查询</button>
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
				<th width="6%" data-order-field="charge_id">ID</th>
				<th>订单号</th>
				<th width="5%" data-order-field="charge_status">支付状态</th>
				<th width="9%" data-order-field="charge_type">充值方式</th>
				<th width="12%">充值用户</th>
				<th width="8%" data-order-field="charge_money">充值金额</th>
	            <th width="8%" data-order-field="charge_gold2">获得<?php echo $config['gold2_name']?></th>
				<th width="15%" data-order-field="log_time">下单时间</th>
	            <th width="15%" data-order-field="log_time">支付时间</th>
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
					$status = str::CheckElse( $v['charge_status'] , 1 , '<span style="color:green">已支付</span>' , '<span style="color:red">待支付</span>');
					$payTime = '------';
					if( $v['charge_status'] == 1)
					{
						$payTime = date( 'Y-m-d H:i:s' , $v['charge_paytime']);
					}
					echo '<tr class="'.$cur.'">
							<td style="text-align: center;"><input type="checkbox" name="ids" data-toggle="icheck" value="'.$v['charge_id'].'"></td>
							<td style="text-align: center;">'.$v['charge_id'].'</td>
							<td style="text-align: center;">'.$v['charge_sn'].'</td>
							<td style="text-align: center;">'.$status.'</td>
							<td style="text-align: center;">'.$chargeMod->GetType($v['charge_type']).'</td>
							<td style="text-align: center;">'.$v['user_nickname'].' ( id:'.$v['charge_user_id'].' )</td>
							<td style="text-align: center;">'.$v['charge_money'].'</td>
							<td style="text-align: center;">'.$v['charge_gold2'].'</td>
							<td style="text-align: center;">'.date( 'Y-m-d H:i:s' , $v['charge_addtime']).'</td>
							<td style="text-align: center;">'.$payTime.'</td>
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
	ajaxOptions['url'] = "index.php?a=yes&c=finance.order.charge&t=del";
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
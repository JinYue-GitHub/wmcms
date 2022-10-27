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
			<a href="index.php?a=yes&c=finance.report&t=confirm" data-toggle="doajaxchecked" data-idname="ids" data-group="ids" data-confirm-msg="确定要结算选中项吗？" class="btn btn-success size-MINI radius" data-callback="<?php echo $cFun;?>ConfirmCallBack"> <i class="fa fa-check-square"></i> 批量结算</a>
		</div>
		<div class="f-l">
			<form id="pagerForm" name="<?php echo $cFun;?>Form" data-toggle="ajaxsearch" data-loadingmask="true" action="<?php echo $url;?>" method="post">
				<input type="hidden" name="pageSize" value="<?php echo $pageSize;?>">
				<input type="hidden" name="pageCurrent" value="<?php echo $pageCurrent;?>">
				<input type="hidden" name="orderField" value="<?php echo $orderField;?>">
				<input type="hidden" name="orderDirection" value="<?php echo $orderDirection;?>">
				<span class="" style="float:left;margin:5px 0 0 15px;">快速查询：</span>
				
				<select data-toggle="selectpicker" name="settlement" data-width="100">
                	<option value="" <?php echo str::CheckElse( $settlement , '' , 'selected=""' );?>>全部</option>
                	<option value="0" <?php echo str::CheckElse( $settlement , '0' , 'selected=""' );?>>未结算</option>
                	<option value="1" <?php echo str::CheckElse( $settlement , '1' , 'selected=""' );?>>已结算</option>
                </select>
                
				<select data-toggle="selectpicker" name="module" data-width="100">
                	<option value="" <?php echo str::CheckElse( $module , '' , 'selected=""' );?>>全部模块</option>
					<?php 
					foreach ($moduleList as $key=>$val)
					{
						$select = str::CheckElse( $module , $key , 'selected=""' );
						echo '<option value="'.$key.'" '.$select.'>'.$val.'</option>';
					}
					?>
                </select>
                <input type="text" value="<?php echo $cid;?>" placeholder="请输入内容ID" name="cid" size="15">
				<button type="submit" class="btn btn-warning radius" data-icon="search">查询</button>
				<a id="<?php echo $cFun.$cid;?>refresh" style="margin-left:10px;" class="btn size-MINI btn-primary radius"><i class="fa fa-refresh fa-spin"></i> 刷新</a>
             </form>
		</div>
	</div>
</div>

<div class="bjui-pageContent">
		<table class="table table-border table-bordered table-hover table-bg table-sort">
			<thead>
				<tr>
				<th style="text-align: center;" width="2%;"><input type="checkbox" class="checkboxCtrl" data-group="ids" data-toggle="icheck"></th>
				<th width="5%" data-order-field="report_id">ID</th>
				<th width="5%" data-order-field="report_settlement">状态</th>
				<th width="6%">报表模块</th>
				<th width="6%">报表类型</th>
				<th width="6%" data-order-field="report_cid">内容ID</th>
				<th width="15%" data-order-field="report_user_id">用户</th>
				<th width="6%" data-order-field="report_gold1">结算<?php echo $config['gold1_name']?></th>
	            <th width="6%" data-order-field="report_gold2">结算<?php echo $config['gold2_name']?></th>
	            <th width="10%" data-order-field="report_time">入账时间</th>
	            <th width="10%" data-order-field="report_settlement_id">结算员</th>
	            <th width="10%" data-order-field="report_settlement_time">结算时间</th>
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
					$settlementId = '---';
					$settlementTime = '---';
					$disabled = '';
					$status = '<a href="javascript:;" onClick="'.$cFun.'ConfirmAjax('.$v['report_id'].')"><span style="color:red">未结算</span></a>';
					if( $v['report_settlement'] == '1' )
					{
						$status = '已结算';
						$settlementId = $manager->GetByMid($v['report_settlement_id'],'manager_name').'( id:'.$v['report_settlement_id'].')';
						$settlementTime = date( 'Y-m-d H:i:s' , $v['report_settlement_time']);
						$status = '<span style="color:green">已结算</span>';
						$disabled = 'disabled="disabled"';
					}
					echo '<tr class="'.$cur.'">
							<td style="text-align: center;"><input type="checkbox" name="ids" data-toggle="icheck" value="'.$v['report_id'].'" '.$disabled.'></td>
							<td style="text-align: center;">'.$v['report_id'].'</td>
							<td style="text-align: center;">'.$status.'</td>
							<td style="text-align: center;">'.GetModuleName($v['report_module']).'</td>
							<td style="text-align: center;">'.$logMod->GetTypeName($v['report_module'] , $v['report_type']).'</td>
							<td style="text-align: center;">'.$v['report_cid'].'</td>
							<td style="text-align: center;">'.$v['user_nickname'].' ( id:'.$v['report_user_id'].' )</td>
							<td style="text-align: center;">'.$v['report_gold1'].'</td>
							<td style="text-align: center;">'.$v['report_gold2'].'</td>
							<td style="text-align: center;">'.date( 'Y-m-d H:i:s' , $v['report_time']).'</td>
							<td style="text-align: center;">'.$settlementId.'</td>
							<td style="text-align: center;">'.$settlementTime.'</td>
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
	$('#<?php echo $cFun.$cid;?>refresh').click(function() {
	   $(this).navtab("reload",<?php echo $cFun;?>GetOp());// 刷新当前Tab页面
	});
});
</script>
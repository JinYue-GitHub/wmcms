<div class="bjui-pageHeader">
	<div class="row cl pt-10 pb-10 pl-10">
		<div class="f-l" style="margin-left:10px">
			<a href="index.php?a=yes&c=system.smslog&t=del" data-toggle="doajaxchecked" data-idname="ids" data-group="ids" data-confirm-msg="确定要删除选中项吗？" class="btn btn-danger size-MINI radius" data-callback="<?php echo $cFun;?>ajaxCallBack"> <i class="fa fa-remove"></i> 批量删除</a>
			<a href="index.php?a=yes&c=system.smslog&t=clear" data-toggle="doajax" data-confirm-msg="确定清空记录要吗？" class="btn btn-danger size-MINI radius"><i class="fa fa-trash-o"></i> 清空记录</a>
		</div>
		<div class="f-l">
			<form id="pagerForm" name="<?php echo $cFun;?>Form"  data-toggle="ajaxsearch" data-loadingmask="true" action="index.php?c=system.set.smslog" method="post">
				<input type="hidden" name="pageSize" value="<?php echo $pageSize;?>">
				<input type="hidden" name="pageCurrent" value="<?php echo $pageCurrent;?>">
				<input type="hidden" name="orderField" value="<?php echo $orderField;?>">
				<input type="hidden" name="orderDirection" value="<?php echo $orderDirection;?>">
				
				<span class="" style="float:left;margin:5px 0 0 15px;">快速查询：</span>
				<span class="" style="float:left;margin:5px 0 0 15px;">收件人：</span>
				<input type="text" class="input-text size-MINI radius form-control" name="log_receive" value="<?php echo $logReceive;?>" size="15">
                <!--
				<select id="adminid" class="select radius size-MINI" name="adminid" style="width:120px;float:left;margin-right:2px;height: 24px;">
					<option value="0">选择管理员</option>
					<option value="1">系统管理员</option>
				</select>-->

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
			<th width="5%">ID</th>
			<th width="5%">使用状态</th>
			<th width="5%">发送状态</th>
			<th width="5%">短信类型</th>
			<th width="10%">收件人</th>
			<th width="20%">短信内容</th>
			<th>备注</th>
			<th width="10%">发送时间</th>
			<th width="10%">过期时间</th>
			<th width="8%">操作</th>
			</tr>
		</thead>
		
		<tbody>
		<?php
		if( $logList )
		{
			$i = 1;
			foreach ($logList as $k=>$v)
			{
				$cur = str::CheckElse( $i%2 , 0 , '' , 'even_index_row');
				$status = str::CheckElse( $v['log_status'] , 1 , '<span style="color:green">'.$logMod->statusArr[$v['log_status']].'</span>' , '<span style="color:red">'.$logMod->statusArr[$v['log_status']].'</span>');
				$send = str::CheckElse( $v['log_send'] , 1 , '<span style="color:green">'.$logMod->sendArr[$v['log_send']].'</span>' , '<span style="color:red">'.$logMod->sendArr[$v['log_send']].'</span>');
				echo '<tr class="'.$cur.'">
						<td style="text-align: center;"><input type="checkbox" name="ids" data-toggle="icheck" value="'.$v['log_id'].'"></td>
						<td>'.$v['log_id'].'</td>
						<td>'.$status.'</td>
						<td>'.$send.'</td>
						<td>'.$v['log_type'].'</td>
						<td>'.$v['log_receive'].'</td>
						<td>'.$v['log_content'].'</td>
						<td>'.$v['log_remark'].'</td>
						<td>'.date('Y-m-d H:i:s',$v['log_time']).'</td>
						<td>'.date('Y-m-d H:i:s',$v['log_exptime']).'</td>
						<td style="text-align: center;">
							<a class="btn btn-danger radius" onclick="'.$cFun.'delAjax('.$v['log_id'].')">删除</a>
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

<script type="text/javascript">
var tab;
//页面唯一op获取函数
function <?php echo $cFun;?>GetOp(tab){
	var op=new Array();
	var ajaxData=new Object();
	ajaxData.tab = tab;
	op['type'] = 'POST';
	op['data'] = ajaxData;
	return op;
}
//删除数据
function <?php echo $cFun;?>delAjax(id)
{
	var ajaxOptions=new Array();
	var ajaxData=new Object();
	ajaxOptions = <?php echo $cFun;?>GetOp();
	
	ajaxData.id = id;
	ajaxOptions['data'] = ajaxData;
	ajaxOptions['url'] = "index.php?a=yes&c=system.smslog&t=del";
	ajaxOptions['confirmMsg'] = "确定要删除所选的数据吗？";
	ajaxOptions['callback'] = "<?php echo $cFun;?>ajaxCallBack";
	$(".btn-danger").bjuiajax('doAjax', ajaxOptions);
}
//页面唯一回调函数
function <?php echo $cFun;?>ajaxCallBack(json){
	$(this).bjuiajax("ajaxDone",json);//显示处理结果
	$(this).dialog("closeCurrent");	//关闭当前dialog
	if(json.statusCode==200){
		$(this).navtab("reload",<?php echo $cFun;?>GetOp(tab));	//刷新当前Tab页面 
	}
}
$(document).ready(function(){
	$('#<?php echo $cFun;?>refresh').click(function() {
	    $(this).navtab("reload",<?php echo $cFun;?>GetOp());// 刷新当前Tab页面
	});
});
</script>
<div class="bjui-pageHeader">
	<div class="row cl pt-10 pb-10 pl-10">
		<div class="f-l" style="margin-left:10px">
			<?php echo $manager->GetForm('system' , 'err_auto_upload');?>
			<div class="alert alert-success search-inline"><i class="fa fa-info-circle"></i> 开启自动上传错误到WMCMS官方云服务器，会加快错误修复速度！</div>
			<a href="index.php?a=yes&c=system.safe.errlog&t=del" data-toggle="doajaxchecked" data-idname="ids" data-group="ids" data-confirm-msg="确定要删除选中项吗？" class="btn btn-danger size-MINI radius" data-callback="<?php echo $cFun;?>ajaxCallBack"> <i class="fa fa-remove"></i> 批量删除</a>
			<a href="index.php?a=yes&c=system.safe.errlog&t=clear" data-toggle="doajax" data-confirm-msg="确定清空记录要吗？" class="btn btn-danger size-MINI radius"><i class="fa fa-trash-o"></i> 清空记录</a>
		</div>
		<div class="f-l">
			<form id="pagerForm" name="<?php echo $cFun;?>Form"  data-toggle="ajaxsearch" data-loadingmask="true" action="index.php?c=system.safe.errlog" method="post">
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
				<th width="5%" data-order-field="errlog_id">ID</th>
				<th width="20%">URL</th>
				<th width="20%">错误信息</th>
				<th width="6%">SQL码</th>
				<th width="6%">错误码</th>
				<th width="8%">访问IP</th>
				<th width="15%">错误时间</th>
	            <th width="18%">操作</th>
	            </tr>
			</thead>
			<tbody>
			<?php
			if( $errlogArr )
			{
				$i = 1;
				foreach ($errlogArr as $k=>$v)
				{
					$cur = str::CheckElse( $i%2 , 0 , '' , 'even_index_row');
					if( $v['errlog_status'] == '0' )
					{
						$upload = '<a class="btn btn-success radius size-MINI" href="index.php?a=yes&c=system.safe.errlog&t=upload&id='.$v['errlog_id'].'" data-mask="true" data-toggle="doajax">上报错误</a>';
					}
					else
					{
						$upload = '<a class="btn btn-warning radius size-MINI" href="javascript:void()">已经上报</a>';
					}
					echo '<tr class="'.$cur.'">
							<td style="text-align: center;"><input type="checkbox" name="ids" data-toggle="icheck" value="'.$v['errlog_id'].'"></td>
							<td style="text-align: center;">'.$v['errlog_id'].'</td>
							<td>'.substr(str::ToHtml(str::Escape($v['errlog_url'])),0,30).'</td>
							<td>'.substr(str::Escape($v['errlog_msg']),0,30).'</td>
							<td style="text-align: center;">'.$v['errlog_state'].'</td>
							<td style="text-align: center;">'.$v['errlog_code'].'</td>
							<td style="text-align: center;">'.$v['errlog_ip'].'</td>
							<td style="text-align: center;">'.date("Y-m-d H:i:s" , $v['errlog_time']).'</td>
							<td style="text-align: center;" data-noedit="true">
								<a class="btn btn-secondary radius size-MINI" href="index.php?c=system.safe.errlog.detail&id='.$v['errlog_id'].'" data-mask="true" data-toggle="dialog" data-title="查看错误日志的详细信息" data-width="600" data-height="450" >详情</a>
								'.$upload.'
								<a class="btn btn-danger radius" onclick="'.$cFun.'delAjax('.$v['errlog_id'].')">删除</a>
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
    <div class="pagination-box" data-toggle="pagination" data-total="<?php echo $total;?>" data-page-size="<?php echo $pageSize;?>" data-pageCurrent="<?php echo $pageCurrent?>"></div>
</div>



<script>
$(document).ready(function(){
	$('#err_auto_upload').change(function() {
		var op = new Array();
		op['type'] = 'GET';
		op['reload'] = 'false';
		op['url'] = "index.php?a=yes&c=system.safe.errlog&t=autoupload&val="+$(this).val();
		$(this).bjuiajax("doAjax",op);// 显示处理结果
	});
});
//页面唯一获取op函数
function <?php echo $cFun;?>GetOp(){
	var op=new Array();
	op['type'] = 'POST';
	op['data'] = $("[name=<?php echo $cFun;?>Form]").serializeArray();
	return op;
}
//删除账号
function <?php echo $cFun;?>delAjax(id)
{
	var ajaxOptions=new Array();
	var ajaxData=new Object();
	ajaxOptions = <?php echo $cFun;?>GetOp();
	ajaxData.id = id;
	ajaxOptions['data'] = ajaxData;
	ajaxOptions['url'] = "index.php?a=yes&c=system.safe.errlog&t=del";
	ajaxOptions['confirmMsg'] = "是否永久删除当前日志？";
	ajaxOptions['callback'] = "<?php echo $cFun;?>ajaxCallBack";
	$(".btn-danger").bjuiajax('doAjax', ajaxOptions);
}
//页面唯一回调函数
function <?php echo $cFun;?>ajaxCallBack(json){
	$(this).bjuiajax("ajaxDone",json);//显示处理结果
  $(this).navtab("reload",<?php echo $cFun;?>GetOp());	//刷新当前Tab页面 
}


$(document).ready(function(){
	$('#<?php echo $cFun;?>refresh').click(function() {
	    $(this).navtab("reload",<?php echo $cFun;?>GetOp());// 刷新当前Tab页面
	});
});
</script>
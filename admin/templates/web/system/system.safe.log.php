<div class="bjui-pageHeader">
	<div class="row cl pt-10 pb-10 pl-10">
		<div class="f-l" style="margin-left:10px">
			<a href="index.php?a=yes&c=system.safe.log&t=del" data-toggle="doajaxchecked" data-idname="ids" data-group="ids" data-confirm-msg="确定要删除选中项吗？" class="btn btn-danger size-MINI radius" data-callback="<?php echo $cFun;?>ajaxCallBack"> <i class="fa fa-remove"></i> 批量删除</a>
			<a href="index.php?a=yes&c=system.safe.log&t=clear" data-toggle="doajax" data-confirm-msg="确定清空记录要吗？" class="btn btn-danger size-MINI radius"><i class="fa fa-trash-o"></i> 清空记录</a>
		</div>
		<div class="f-l">
			<form id="pagerForm" name="<?php echo $cFun;?>Form"  data-toggle="ajaxsearch" data-loadingmask="true" action="index.php?c=system.safe.log" method="post">
				<input type="hidden" name="pageSize" value="<?php echo $pageSize;?>">
				<input type="hidden" name="pageCurrent" value="<?php echo $pageCurrent;?>">
				<input type="hidden" name="orderField" value="<?php echo $orderField;?>">
				<input type="hidden" name="orderDirection" value="<?php echo $orderDirection;?>">
                <!--<span class="" style="float:left;margin:5px 0 0 15px;">快速查询：</span>
				<select id="adminid" class="select radius size-MINI" name="adminid" style="width:120px;float:left;margin-right:2px;height: 24px;">
					<option value="0">选择管理员</option>
					<option value="1">系统管理员</option>
				</select>
				<select id="dtime" class="select size-MINI radius " name="dtime" style="width:100px;float:left;margin-right:2px;height: 24px;">
					<option value="0">时间</option>
					<option value="7">一周内</option>
					<option value="15">15天内</option>
					<option value="30">30天以内</option>
					<option value="60">60天以内</option>
				</select>
				<span class="" style="float:left;margin:5px 0 0 15px;">IP地址：</span>
				<input type="text" class="input-text size-MINI radius form-control" name="ip" size="15">

				<button type="submit" class="btn btn-warning radius" data-icon="search">查询</button>  -->
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
				<th width="5%" data-order-field="login_id">编号</th>
				<th width="10%">登录账号</th>
				<th width="10%">登录状态</th>
				<th width="14%">登录IP</th>
				<th width="10%">登录浏览器</th>
				<th width="10%">登录备注</th>
				<th width="14%">登录时间</th>
	            <th width="13%">操作</th>
	            </tr>
			</thead>
			<tbody>
			<?php
			if( $logArr )
			{
				$i = 1;
				foreach ($logArr as $k=>$v)
				{
					$cur = str::CheckElse( $i%2 , 0 , '' , 'even_index_row');
					$name = str::CheckElse($v['manager_name'], '','-----',$v['manager_name']);
					echo '<tr class="'.$cur.'">
							<td style="text-align: center;"><input type="checkbox" name="ids" data-toggle="icheck" value="'.$v['login_id'].'"></td>
							<td style="text-align: center;">'.$v['login_id'].'</td>
							<td>'.$name.'</td>
							<td>'.$v['login_status_text'].'</td>
							<td>'.$v['login_ip'].'</td>
							<td>'.$v['login_browser'].'</td>
							<td>'.mb_substr($v['login_remark'],0,12).'</td>
							<td>'.date("Y-m-d H:i:s" , $v['login_time']).'</td>
							<td style="text-align: center;" data-noedit="true">
								<a class="btn btn-secondary radius size-MINI" href="index.php?c=system.safe.log.detail&id='.$v['login_id'].'" data-mask="true" data-toggle="dialog" data-title="查看登录记录的详细信息" data-width="600" data-height="450" >详情</a>
								<a class="btn btn-danger radius" onclick="'.$cFun.'delAjax('.$v['login_id'].')">删除</a>
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
	ajaxOptions['url'] = "index.php?a=yes&c=system.safe.log&t=del";
	ajaxOptions['confirmMsg'] = "是否永久删除当前记录？";
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
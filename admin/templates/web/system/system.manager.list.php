<div class="bjui-pageHeader">
	<div class="row cl pt-10 pb-10 pl-10">
		<div class="f-l">
			<form id="pagerForm" name="<?php echo $cFun;?>Form" data-toggle="ajaxform" action="index.php?c=system.manager.list" method="post">
				<input type="hidden" name="pageSize" value="<?php echo $pageSize;?>">
				<input type="hidden" name="pageCurrent" value="<?php echo $pageCurrent;?>">
				<input type="hidden" name="orderField" value="<?php echo $orderField;?>">
				<input type="hidden" name="orderDirection" value="<?php echo $orderDirection;?>">
				<a id="<?php echo $cFun;?>refresh" class="btn size-MINI btn-primary radius"><i class="fa fa-refresh fa-spin"></i> 刷新</a>
			</form>
		</div>
	</div>
</div>

<div class="bjui-pageContent">
		<table class="table table-border table-bordered table-hover table-bg table-sort">
			<thead>
				<tr>
				<th width="5%" data-order-field="comp_id">编号</th>
				<th width="12%">登录账号</th>
				<th width="12%">账号状态</th>
				<th>管理权限</th>
				<th>最后登录IP</th>
				<th>最后登录时间</th>
	            <th width="18%">操作</th>
	            </tr>
			</thead>
			<tbody>
			<?php
			if( $managerArr )
			{
				$i = 1;
				foreach ($managerArr as $k=>$v)
				{
					if( $v['manager_status'] == '0' )
					{
						$status = '<a class="btn btn-success radius" onclick="'.$cFun.'statusAjax('.$v['manager_id'].',1)">使用</a>';
					}
					else
					{
						$status = '<a class="btn btn-warning radius" onclick="'.$cFun.'statusAjax('.$v['manager_id'].',0)">禁用</a>';
					}
					$cur = str::CheckElse( $i%2 , 0 , '' , 'even_index_row');
					echo '<tr class="'.$cur.'">
							<td style="text-align: center;">'.$v['manager_id'].'</td>
							<td>'.$v['manager_name'].'</td>
							<td>'.$v['manager_status_text'].'</td>
							<td>'.$v['comp_name'].'</td>
							<td>'.$v['manager_lastip'].'</td>
							<td>'.date("Y-m-d H:i:s" , $v['manager_lasttime']).'</td>
							<td style="text-align: center;" data-noedit="true">
								'.$status.'
				            	<a class="btn btn-secondary radius size-MINI" data-mask="true" data-on-close="onClose" href="index.php?d=yes&c=system.manager.edit&t=edit&id='.$v['manager_id'].'"  data-toggle="dialog" data-title="修改管理员账号信息" data-width="510" data-height="360" >修改</a>
								<a class="btn btn-danger radius" onclick="'.$cFun.'delAjax('.$v['manager_id'].')">删除</a>
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
//修改账号状态
function <?php echo $cFun;?>statusAjax(id,status)
{
	var ajaxOptions=new Array();
	var ajaxData=new Object();
	ajaxOptions = <?php echo $cFun;?>GetOp();
	
	if( status=='1'){
		ajaxOptions['confirmMsg'] = '是否恢复使用账号？';
	}else{
		ajaxOptions['confirmMsg'] = '是否禁用当前账号？';
	}
	ajaxOptions['url'] = 'index.php?a=yes&c=system.manager.manager&t=status';
	ajaxOptions['callback'] = '<?php echo $cFun;?>ajaxCallBack';
	
	ajaxData.id = id;
	ajaxData.status = status;
	ajaxOptions['data'] = ajaxData;
	$(this).bjuiajax('doAjax', ajaxOptions);
}
//删除账号
function <?php echo $cFun;?>delAjax(id)
{
	var ajaxOptions=new Array();
	var ajaxData=new Object();
	ajaxOptions = <?php echo $cFun;?>GetOp();
	
	ajaxData.id = id;
	ajaxOptions['data'] = ajaxData;
	ajaxOptions['url'] = "index.php?a=yes&c=system.manager.manager&t=del";
	ajaxOptions['confirmMsg'] = "是否永久删除当前账号？";
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
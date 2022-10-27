<style>
.table tr {
    height: 35px;
}
thead th {
	text-align: center;
}
</style>

<div class="bjui-pageHeader">
	<div class="row cl pt-10 pb-10 pl-10">
		<div class="f-l">
			<form id="pagerForm" name="<?php echo $cFun;?>Form" data-toggle="ajaxsearch" data-loadingmask="true" action="index.php?c=editor.editor.list&t=list" method="post">
				<input type="hidden" name="pageSize" value="<?php echo $pageSize;?>">
				<input type="hidden" name="pageCurrent" value="<?php echo $pageCurrent;?>">
				<input type="hidden" name="orderField" value="<?php echo $orderField;?>">
				<input type="hidden" name="orderDirection" value="<?php echo $orderDirection;?>">
                <span class="" style="float:left;margin:5px 0 0 15px;">快速查询：</span>
                <input type="text" placeholder="用户ID" name="uid" size="10" value="<?php echo $uid;?>">
				<button type="submit" class="btn btn-warning radius" data-icon="search">查询</button>
				<a id="<?php echo $cFun;?>refresh" class="btn size-MINI btn-primary radius"><i class="fa fa-refresh fa-spin"></i> 刷新</a>
				<a class="btn btn-success radius size-MINI" data-toggle="dialog" data-mask="true" data-title="添加编辑分组" href="index.php?d=yes&c=editor.editor.edit&t=add" data-width="490" data-height="540" ><i class="fa fa-plus"></i> 添加编辑</a> &nbsp;
			</form>
		</div>
	</div>
</div>

<div class="bjui-pageContent">
		<table class="table table-border table-bordered table-hover table-bg table-sort">
			<thead>
				<tr>
				<th width="4%" data-order-field="editor_id">ID</th>
				<th width="5%">状态</th>
	            <th width="10%">用户</th>
	            <th width="10%">编辑名字</th>
	            <th>编辑介绍</th>
	            <th width="8%">姓名</th>
	            <th width="10%">QQ号</th>
	            <th width="10%">微信号</th>
	            <th width="10%">手机号</th>
	            <th width="12%">添加时间</th>
	            <th width="6%">操作</th>
	            </tr>
			</thead>
			<tbody>
			<?php
			if( $editorList )
			{
				$i = 1;
				foreach ($editorList as $k=>$v)
				{
					$cur = str::CheckElse( $i%2 , 0 , '' , 'even_index_row');
					$status = str::CheckElse( $v['editor_status'] , 0 , '<a href="javascript:;" onClick="'.$cFun.'StatusAjax(1,'.$v['editor_id'].')"><span style="color:red">禁用</span></a>' , '<a href="javascript:;" onClick="'.$cFun.'StatusAjax(0,'.$v['editor_id'].')"><span style="color:green">启用</span></a>');
					echo '<tr class="'.$cur.'">
							<td style="text-align: center;">'.$v['editor_id'].'</td>
							<td style="text-align: center;">'.$status.'</td>
							<td style="text-align: center;">'.$v['user_nickname'].'(ID:'.$v['editor_uid'].')</td>
							<td style="text-align: center;">'.$v['editor_name'].'</td>
							<td style="text-align: center;">'.$v['editor_desc'].'</td>
							<td style="text-align: center;">'.$v['editor_realname'].'</td>
							<td style="text-align: center;">'.$v['editor_qq'].'</td>
							<td style="text-align: center;">'.$v['editor_weixin'].'</td>
							<td style="text-align: center;">'.$v['editor_tel'].'</td>
							<td style="text-align: center;">'.date('Y-m-d H:i:s',$v['editor_time']).'</td>
							<td style="text-align: center;">
				            	<a class="btn btn-secondary radius size-MINI" data-mask="true" data-on-close="onClose" href="index.php?d=yes&c=editor.editor.edit&t=edit&id='.$v['editor_id'].'"  data-toggle="dialog" data-title="修改编辑" data-width="490" data-height="540" >编辑</a> 
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
    <div id="asd" class="pagination-box" data-toggle="pagination" data-total="<?php echo $total;?>" data-page-size="<?php echo $pageSize;?>" data-pageCurrent="<?php echo $pageCurrent?>">
    </div>
</div>

<script type="text/javascript">
function <?php echo $cFun;?>GetOp(){
	var op=new Array();
	op['type'] = 'POST';
	op['data'] = $("[name=<?php echo $cFun;?>Form]").serializeArray();
	return op;
}
//页面唯一回调函数
function <?php echo $cFun;?>ajaxCallBack(json){
	$(this).bjuiajax("ajaxDone",json);//显示处理结果
	$(this).dialog("closeCurrent");	//关闭当前dialog
	$(this).navtab("reload",<?php echo $cFun;?>GetOp());	//刷新当前Tab页面 
}
//审核编辑
function <?php echo $cFun;?>StatusAjax(status,id)
{
	var type;
	var ajaxOptions=new Array();
	var ajaxData=new Object();
	ajaxOptions = <?php echo $cFun;?>GetOp();
	switch(status)
	{
		case 0:
			type = "禁用";
	  		break;
		default:
			type = "启用";
	  		break;
	}
	ajaxData.id = id;
	ajaxData.status = status;
	ajaxOptions['data'] = ajaxData;
	ajaxOptions['url'] = "index.php?a=yes&c=editor.editor&t=status";
	ajaxOptions['confirmMsg'] = "确定要"+type+"编辑吗？";
	ajaxOptions['callback'] = "<?php echo $cFun;?>ajaxCallBack";
	$(this).bjuiajax('doAjax', ajaxOptions);
}

$(document).ready(function(){
	$('#<?php echo $cFun;?>refresh').click(function() {
	    $(this).navtab("reload",<?php echo $cFun;?>GetOp());// 刷新当前Tab页面
	});
});
</script>
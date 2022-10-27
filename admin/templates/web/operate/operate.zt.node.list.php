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
<?php
if( $id == '' )
{
	echo '<script type="text/javascript">$(document).ready(function(){$(this).alertmsg("info", "对不起，请先选择专题!")});</script>';
}
?>
<div class="bjui-pageHeader">
	<div class="row cl pt-10 pl-10">
		<div class="list-tool pl-15">
            <span >快捷操作：</span>
			<a href="index.php?c=operate.zt.node.edit&zid=<?php echo $id;?>&t=add" data-toggle="navtab" data-id="zt-node-add" class="btn btn-success radius"> 新增节点</a>
			<a href="index.php?a=yes&c=operate.zt.node&t=del" data-toggle="doajaxchecked" data-idname="ids" data-group="ids" data-confirm-msg="确定要删除选中项吗？" class="btn btn-danger size-MINI radius" data-callback="<?php echo $type.$cFun;?>ajaxCallBack"> <i class="fa fa-remove"></i> 批量删除</a>
			<a href="index.php?a=yes&c=operate.zt.node&zid=<?php echo $id;?>&t=clear" data-toggle="doajax" data-confirm-msg="此操作不可撤回确定要清空当前专题节点吗？" class="btn btn-danger size-MINI radius"><i class="fa fa-trash-o"></i> 清空当前节点</a>
		</div>
		<div class="list-tool">
			<form id="pagerForm" name="<?php echo $cFun.$type;?>Form" data-toggle="ajaxsearch" data-loadingmask="true" action="<?php echo $url;?>" method="post">
				<input type="hidden" name="pageSize" value="<?php echo $pageSize;?>">
				<input type="hidden" name="pageCurrent" value="<?php echo $pageCurrent;?>">
				<input type="hidden" name="orderField" value="<?php echo $orderField;?>">
				<input type="hidden" name="orderDirection" value="<?php echo $orderDirection;?>">
                <span class="" style="float:left;margin:5px 0 0 15px;">快速查询：</span>
                
                <input type="text" placeholder="请输入关键字" value="<?php echo $name;?>" name="name" size="15">
                
				<button type="submit" class="btn btn-warning radius" data-icon="search">查询</button>
				<a id="<?php echo $type.$cFun;?>refresh" class="btn size-MINI btn-primary radius"><i class="fa fa-refresh fa-spin"></i> 刷新</a>
			</form>
		</div>
	</div>
</div>

<div class="bjui-pageContent">
	<table class="table table-border table-bordered table-hover table-bg table-sort">
		<thead>
			<tr>
			<th style="text-align: center;" width="2%;"><input type="checkbox" class="checkboxCtrl" data-group="ids" data-toggle="icheck"></th>
			<th width="5%" data-order-field="node_id">ID</th>
			<th width="10%" data-order-field="zt_name">专题名</th>
			<th width="10%" data-order-field="node_pinyin">节点标识</th>
			<th>节点名</th>
			<th width="10%" data-order-field="node_type">类型</th>
			<th width="15%" data-order-field="node_time">添加时间</th>
            <th width="18%">操作</th>
            </tr>
		</thead>
		<tbody>
			<?php
			if( $dataArr )
			{
				$i = 1;
				foreach ($dataArr as $k=>$v)
				{
					$cur = str::CheckElse( $i%2 , 0 , '' , 'even_index_row');
					$status = str::CheckElse( $v['zt_status'] , 0 , '<a href="javascript:;" onClick="'.$type.$cFun.'StatusAjax(1,'.$v['node_id'].')"><span style="color:red">待审核</span></a>' , '<a href="javascript:;" onClick="'.$type.$cFun.'StatusAjax(0,'.$v['node_id'].')"><span style="color:green">已审核</span></a>');
					echo '<tr class="'.$cur.'">
							<td style="text-align: center;"><input type="checkbox" name="ids" data-toggle="icheck" value="'.$v['node_id'].'"></td>
							<td style="text-align: center;">'.$v['node_id'].'</td>
							<td style="text-align: center;">'.$v['zt_name'].'</td>
							<td style="text-align: center;">'.$v['node_pinyin'].'</td>
							<td style="text-align: center;">'.$v['node_name'].'</td>
							<td style="text-align: center;">'.$nodeSer->GetType($v['node_type']).'节点</td>
							<td style="text-align: center;">'.date( 'Y-m-d H:i:s' , $v['node_time']).'</td>
							<td style="text-align: center;">
				            	<a class="btn btn-secondary radius size-MINI" data-toggle="navtab" data-id="zt-node-edit" data-title="编辑节点内容" href="index.php?d=yes&c=operate.zt.node.edit&zid='.$id.'&t=edit&id='.$v['node_id'].'">编辑</a> 
								<a class="btn btn-danger radius" onclick="'.$type.$cFun.'delAjax('.$v['node_id'].')">删除</a>
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


<script type="text/javascript">
//页面唯一op获取函数
function <?php echo $cFun;?>GetOp(){
	var op=new Array();
	op['type'] = 'POST';
	op['data'] = $("[name=<?php echo $type.$cFun;?>Form]").serializeArray();
	return op;
}
//删除数据
function <?php echo $type.$cFun;?>delAjax(id)
{
	var ajaxOptions=new Array();
	var ajaxData=new Object();
	ajaxOptions = <?php echo $type.$cFun;?>GetOp();
	
	ajaxData.id = id;
	ajaxOptions['data'] = ajaxData;
	ajaxOptions['url'] = "index.php?a=yes&c=operate.zt.node&t=del";
	ajaxOptions['confirmMsg'] = "确定要删除所选的专题节点吗？";
	ajaxOptions['callback'] = "<?php echo $type.$cFun;?>ajaxCallBack";
	$(".btn-danger").bjuiajax('doAjax', ajaxOptions);
}
//审核数据
function <?php echo $type.$cFun;?>StatusAjax(status,id)
{
	var type;
	var ajaxOptions=new Array();
	var ajaxData=new Object();
	ajaxOptions = <?php echo $type.$cFun;?>GetOp();

	//用户操作类型
	switch(status)
	{
		case 0:
			type = "取消审核";
	  		break;
	  		
		default:
			type = "通过审核";
	  		break;
	}
	
	ajaxData.id = id;
	ajaxData.status = status;
	ajaxOptions['data'] = ajaxData;
	ajaxOptions['url'] = "index.php?a=yes&c=operate.zt.node&t=status";
	ajaxOptions['confirmMsg'] = "确定要"+type+"专题节点吗？";
	ajaxOptions['callback'] = "<?php echo $type.$cFun;?>ajaxCallBack";
	$(".btn-danger").bjuiajax('doAjax', ajaxOptions);
}
//页面唯一回调函数
function <?php echo $type.$cFun;?>ajaxCallBack(json){
	$(this).bjuiajax("ajaxDone",json);//显示处理结果
	$(this).dialog("closeCurrent");	//关闭当前dialog
	$(this).navtab("reload",<?php echo $type.$cFun;?>GetOp());	//刷新当前Tab页面 
}


$(document).ready(function(){
	$('#<?php echo $type.$cFun;?>refresh').click(function() {
	   $(this).navtab("reload",<?php echo $type.$cFun;?>GetOp());// 刷新当前Tab页面
	});
});
</script>
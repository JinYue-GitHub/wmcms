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
			<form id="pagerForm" name="<?php echo $cFun;?>Form" data-toggle="ajaxsearch" data-loadingmask="true" action="index.php?c=system.retrieval.list&t=<?php echo $module?>_list" method="post">
				<input type="hidden" name="pageSize" value="<?php echo $pageSize;?>">
				<input type="hidden" name="pageCurrent" value="<?php echo $pageCurrent;?>">
				<input type="hidden" name="orderField" value="<?php echo $orderField;?>">
				<input type="hidden" name="orderDirection" value="<?php echo $orderDirection;?>">
                <span class="" style="float:left;margin:5px 0 0 15px;">快速查询：</span>
				<select data-toggle="selectpicker" id="<?php echo $cFun;?>type" name="tid" data-width="100">
                	<option value="">全部类型</option>
                	<?php
                	foreach ($typeArr as $k=>$v)
                	{
                		$checked = str::CheckElse( $k , $tid , 'selected=""' );
                		echo '<option value="'.$k.'" '.$checked.'>'.$v.'</option>';
                	}
                	?>
                	
                </select>
                <input type="text" placeholder="请输入关键词" value="<?php echo $name;?>" name="name" size="10">
				<button type="submit" class="btn btn-warning radius" data-icon="search">查询</button>
				<a id="<?php echo $cFun;?>refresh" class="btn size-MINI btn-primary radius"><i class="fa fa-refresh fa-spin"></i> 刷新</a>
				<a class="btn btn-success radius size-MINI" data-toggle="dialog" data-mask="true" data-title="添加条件" href="index.php?d=yes&c=system.retrieval.edit&t=<?php echo $module?>_add" data-width="450" data-height="420" ><i class="fa fa-plus"></i> 添加条件</a> &nbsp;
			</form>
		</div>
	</div>
</div>

<div class="bjui-pageContent">
		<table class="table table-border table-bordered table-hover table-bg table-sort">
			<thead>
				<tr>
				<th width="5%" data-order-field="retrieval_id">ID</th>
				<th width="6%" data-order-field="retrieval_status">使用状态</th>
				<th width="6%" data-order-field="retrieval_type_id">条件分类</th>
	            <th width="8%">条件名字</th>
	            <th width="13%">字段名</th>
	            <th width="5%">查询类型</th>
	            <th width="8%">字段值</th>
	            <th width="8%">显示顺序</th>
	            <th width="10%">操作</th>
	            </tr>
			</thead>
			<tbody>
			<?php
			if( $list )
			{
				$i = 1;
				foreach ($list as $k=>$v)
				{
					$cur = str::CheckElse( $i%2 , 0 , '' , 'even_index_row');
					$status = str::CheckElse( $v['retrieval_status'] , 0 , '<a href="javascript:;" onClick="'.$cFun.'StatusAjax(1,'.$v['retrieval_id'].')"><span style="color:red">'.$reMod->GetStatus('0').'</span></a>' , '<a href="javascript:;" onClick="'.$cFun.'StatusAjax(0,'.$v['retrieval_id'].')"><span style="color:green">'.$reMod->GetStatus('1').'</span></a>');
					echo '<tr class="'.$cur.'">
							<td style="text-align: center;">'.$v['retrieval_id'].'</td>
							<td style="text-align: center;">'.$status.'</td>
							<td style="text-align: center;">'.$typeArr[$v['retrieval_type_id']].'</td>
							<td style="text-align: center;">'.$v['retrieval_title'].'</td>
							<td style="text-align: center;">'.$v['retrieval_field'].'</td>
							<td style="text-align: center;">'.$reMod->GetWhereType($v['retrieval_type']).'</td>
							<td style="text-align: center;">'.$v['retrieval_value'].'</td>
							<td style="text-align: center;">'.$v['retrieval_order'].'</td>
							<td style="text-align: center;">
				            	<a class="btn btn-secondary radius size-MINI" data-mask="true" data-on-close="onClose" href="index.php?d=yes&c=system.retrieval.edit&t='.$module.'_edit&id='.$v['retrieval_id'].'"  data-toggle="dialog" data-title="修改检索条件" data-width="450" data-height="420" >编辑</a> 
				           		<a class="btn btn-danger radius" onclick="'.$cFun.'delAjax('.$v['retrieval_id'].')">删除</a>
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
//删除数据
function <?php echo $cFun;?>delAjax(id)
{
	var ajaxOptions=new Array();
	var ajaxData=new Object();
	ajaxOptions = <?php echo $cFun;?>GetOp();
	
	ajaxData.id = id;
	ajaxOptions['data'] = ajaxData;
	ajaxOptions['url'] = "index.php?a=yes&c=system.retrieval&t=<?php echo $module;?>_del";
	ajaxOptions['confirmMsg'] = "确定要删除所选的数据吗？";
	ajaxOptions['callback'] = "<?php echo $cFun;?>ajaxCallBack";
	$(".btn-danger").bjuiajax('doAjax', ajaxOptions);
}
//禁用使用条件
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
			type = "使用";
	  		break;
	}
	
	ajaxData.id = id;
	ajaxData.status = status;
	ajaxOptions['data'] = ajaxData;
	ajaxOptions['url'] = "index.php?a=yes&c=system.retrieval&t=<?php echo $module;?>_status";
	ajaxOptions['confirmMsg'] = "确定要"+type+"条件吗？";
	ajaxOptions['callback'] = "<?php echo $cFun;?>ajaxCallBack";
	$(".btn-danger").bjuiajax('doAjax', ajaxOptions);
}
//页面唯一回调函数
function <?php echo $cFun;?>ajaxCallBack(json){
	$(this).bjuiajax("ajaxDone",json);//显示处理结果
	$(this).dialog("closeCurrent");	//关闭当前dialog
	$(this).navtab("reload",<?php echo $cFun;?>GetOp());	//刷新当前Tab页面 
}

$(document).ready(function(){
	$('#<?php echo $cFun;?>type').change(function() {
		$("[name=<?php echo $cFun;?>Form]").submit();
	});
	$('#<?php echo $cFun;?>refresh').click(function() {
	    $(this).navtab("reload",<?php echo $cFun;?>GetOp());// 刷新当前Tab页面
	});
});
</script>
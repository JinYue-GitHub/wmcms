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
<form id="pagerForm" name="<?php echo $cFun;?>Form" data-toggle="ajaxsearch" data-loadingmask="true" action="<?php echo $url;?>" method="post">
	<input type="hidden" name="pageSize" value="<?php echo $pageSize;?>">
	<input type="hidden" name="pageCurrent" value="<?php echo $pageCurrent;?>">
	<input type="hidden" name="orderField" value="<?php echo $orderField;?>">
	<input type="hidden" name="orderDirection" value="<?php echo $orderDirection;?>">
</form>

<div class="bjui-pageContent">
		<table class="table table-border table-bordered table-hover table-bg table-sort">
			<thead>
				<tr>
				<th style="text-align: center;" width="2%;"><input type="checkbox" class="checkboxCtrl" data-group="ids" data-toggle="icheck"></th>
				<th width="5%" data-order-field="link_id">ID</th>
				<th width="7%" data-order-field="l.type_id">友链分类</th>
				<th data-order-field="link_name">友链名字</th>
				<th width="5%" data-order-field="link_insum">总点入</th>
				<th width="5%" data-order-field="link_outsum">总点出</th>
				<th width="7%">欠量率</th>
				<th width="15%" data-order-field="link_lastintime">最后点入时间</th>
				<th width="15%" data-order-field="link_lastouttime">最后点出时间</th>
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
							<td style="text-align: center;"><input type="checkbox" name="ids" data-toggle="icheck" value="'.$v['link_id'].'"></td>
							<td style="text-align: center;">'.$v['link_id'].'</td>
							<td style="text-align: center;">'.$v['type_name'].'</td>
							<td>'.$v['link_name'].'</td>
							<td style="text-align: center;">'.$v['link_insum'].'</td>
							<td style="text-align: center;">'.$v['link_outsum'].'</td>
							<td style="text-align: center;">'.$v['owed'].'%</td>
							<td style="text-align: center;">'.date( 'Y-m-d H:i:s' , $v['link_lastintime']).'</td>
							<td style="text-align: center;">'.date( 'Y-m-d H:i:s' , $v['link_lastouttime']).'</td>
							<td style="text-align: center;" data-noedit="true">
				            	<a class="btn btn-secondary radius size-MINI" data-toggle="navtab" data-id="link-link-edit" data-title="编辑友链内容" href="index.php?c=link.link.edit&t=edit&id='.$v['link_id'].'">编辑</a> 
								<a class="btn btn-danger radius" onclick="'.$cFun.'delAjax('.$v['link_id'].')">删除</a>
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



<script type="text/javascript">
var <?php echo $cFun;?>ZtreeInputName = 'tid';
//页面唯一op获取函数
function <?php echo $cFun;?>GetOp(){
	var op=new Array();
	op['type'] = 'POST';
	op['data'] = $("[name=<?php echo $cFun;?>Form]").serializeArray();
	return op;
}
//删除友链
function <?php echo $cFun;?>delAjax(id)
{
	var ajaxOptions=new Array();
	var ajaxData=new Object();
	ajaxOptions = <?php echo $cFun;?>GetOp();
	
	ajaxData.id = id;
	ajaxOptions['data'] = ajaxData;
	ajaxOptions['url'] = "index.php?a=yes&c=link.link&t=del";
	ajaxOptions['confirmMsg'] = "确定要删除所选的友链吗？";
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
	$('#<?php echo $cFun;?>refresh').click(function() {
	   $(this).navtab("reload",<?php echo $cFun;?>GetOp());// 刷新当前Tab页面
	});
});
</script>
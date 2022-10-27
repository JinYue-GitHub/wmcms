<div class="bjui-pageHeader">
	<div class="row cl pt-10 pb-10 pl-10">
		<div class="f-l">
			<form id="pagerForm" name="<?php echo $cFun;?>Form" data-toggle="ajaxform" action="index.php?c=system.competence.list" method="post">
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
				<th width="15%">权限名字</th>
				<th width="20%">管理站点</th>
				<th>管理菜单</th>
	            <th width="12%">操作</th>
	            </tr>
			</thead>
			<tbody>
			<?php
			if( $compArr )
			{
				$i = 1;
				foreach ($compArr as $k=>$v)
				{
					$cur = str::CheckElse( $i%2 , 0 , '' , 'even_index_row');
					echo '<tr class="'.$cur.'">
							<td style="text-align: center;">'.$v['comp_id'].'</td>
							<td>'.$v['comp_name'].'</td>
							<td>'.$v['comp_site'].'</td>
							<td>'.$v['comp_menu'].'</td>
							<td style="text-align: center;" data-noedit="true">
				            	<a class="btn btn-secondary radius size-MINI" data-mask="true" href="index.php?d=yes&c=system.competence.edit&t=edit&id='.$v['comp_id'].'"  data-toggle="dialog" data-title="修改权限信息" data-width="700" data-height="580" >修改</a> 
				        		<a class="btn btn-danger radius" onclick="'.$cFun.'delAjax('.$v['comp_id'].')">删除</a>    
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



<script>
//页面唯一获取op函数
function <?php echo $cFun;?>GetOp(){
	var op=new Array();
	op['type'] = 'POST';
	op['data'] = $("[name=<?php echo $cFun;?>Form]").serializeArray();
	return op;
}
//删除权限
function <?php echo $cFun;?>delAjax(id)
{
	var ajaxOptions=new Array();
	var ajaxData=new Object();
	ajaxOptions = <?php echo $cFun;?>GetOp();
	ajaxData.id = id;
	ajaxOptions['data'] = ajaxData;
	ajaxOptions['url'] = "index.php?a=yes&c=system.competence.competence&t=del";
	ajaxOptions['confirmMsg'] = "是否永久删除当前权限？";
	ajaxOptions['callback'] = "<?php echo $cFun;?>ajaxCallBack";
	$(".btn-danger").bjuiajax('doAjax', ajaxOptions);
}
//回调函数
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
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
		<div class="list-tool pl-15">
            <span >快捷操作：</span>
			<a href="index.php?a=yes&c=editor.works&t=del" data-toggle="doajaxchecked" data-mask="true" data-idname="ids" data-group="ids" data-confirm-msg="确定要删除选中项吗？" class="btn btn-danger size-MINI radius" data-callback="<?php echo $cFun;?>ajaxCallBack"> <i class="fa fa-remove"></i> 批量删除</a>
		</div>
		<div class="f-l">
			<form id="pagerForm" name="<?php echo $cFun;?>Form" data-toggle="ajaxsearch" data-loadingmask="true" action="index.php?c=editor.works.list&t=list" method="post">
				<input type="hidden" name="pageSize" value="<?php echo $pageSize;?>">
				<input type="hidden" name="pageCurrent" value="<?php echo $pageCurrent;?>">
				<input type="hidden" name="orderField" value="<?php echo $orderField;?>">
				<input type="hidden" name="orderDirection" value="<?php echo $orderDirection;?>">
                <span class="" style="float:left;margin:5px 0 0 15px;">快速查询：</span>
                <select data-toggle="selectpicker" id="group_id" name="group_id" data-width="100">
                	<option value="">全部分组</option>
                	<?php
                	if( $groupArr )
                	{
                    	foreach ($groupArr as $k=>$v)
                    	{
                    		$checked = str::CheckElse( $v['group_id'] , $groupId , 'selected=""' );
                    		echo '<option value="'.$v['group_id'].'" '.$checked.'>'.$v['group_name'].'</option>';
                    	}
                	}
                	?>
                </select>
                <input type="text" placeholder="作品ID" name="cid" size="10" value="<?php echo $cid;?>">
                <input type="text" placeholder="编辑ID" name="editor_id" size="10" value="<?php echo $editorId;?>">
				<button type="submit" class="btn btn-warning radius" data-icon="search">查询</button>
				<a id="<?php echo $cFun;?>refresh" class="btn size-MINI btn-primary radius"><i class="fa fa-refresh fa-spin"></i> 刷新</a>
				<a class="btn btn-success radius size-MINI" data-toggle="dialog" data-mask="true" data-title="添加关联" href="index.php?d=yes&c=editor.works.edit&t=add" data-width="490" data-height="210" ><i class="fa fa-plus"></i> 添加关联</a> &nbsp;
			</form>
		</div>
	</div>
</div>

<div class="bjui-pageContent">
		<table class="table table-border table-bordered table-hover table-bg table-sort">
			<thead>
				<tr>
				<th style="text-align: center;" width="2%;"><input type="checkbox" class="checkboxCtrl" data-group="ids" data-toggle="icheck"></th>
				<th width="8%" data-order-field="works_id">ID</th>
	            <th width="14%">编辑组</th>
	            <th width="18%">编辑</th>
	            <th>作品信息</th>
	            <th width="16%">添加时间</th>
	            <th width="14%">操作</th>
	            </tr>
			</thead>
			<tbody>
			<?php
			if( $worksList )
			{
				$i = 1;
				foreach ($worksList as $k=>$v)
				{
					$cur = str::CheckElse( $i%2 , 0 , '' , 'even_index_row');
					$editor = '<span style="color: #ababab;">暂未关联</span>';
					if( $v['editor_name']!= '' )
					{
					    $editor = $v['editor_name'].'(ID:'.$v['editor_id'].')';
					}
					echo '<tr class="'.$cur.'">
							<td style="text-align: center;"><input type="checkbox" name="ids" data-toggle="icheck" value="'.$v['works_id'].'"></td>
							<td style="text-align: center;">'.$v['works_id'].'</td>
							<td style="text-align: center;">'.$v['group_name'].'(ID:'.$v['bind_group_id'].')</td>
							<td style="text-align: center;">'.$editor.'</td>
							<td style="text-align: center;">'.$tableSer->GetContentName($v['works_module'],$v['works_cid']).'(ID:'.$v['works_cid'].')</td>
							<td style="text-align: center;">'.date('Y-m-d H:i:s',$v['works_time']).'</td>
							<td style="text-align: center;">
				            	<a class="btn btn-secondary radius size-MINI" data-mask="true" data-on-close="onClose" href="index.php?d=yes&c=editor.works.edit&t=edit&id='.$v['works_id'].'"  data-toggle="dialog" data-title="修改关联" data-width="490" data-height="210" >编辑</a> 
								<a class="btn btn-danger radius" onclick="'.$cFun.'delAjax('.$v['works_id'].')">删除</a>
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
//删除
function <?php echo $cFun;?>delAjax(id)
{
	var ajaxOptions=new Array();
	var ajaxData=new Object();
	ajaxOptions = <?php echo $cFun;?>GetOp();
	
	ajaxData.id = id;
	ajaxOptions['data'] = ajaxData;
	ajaxOptions['url'] = "index.php?a=yes&c=editor.works&t=del";
	ajaxOptions['loadingmask'] = true;
	ajaxOptions['confirmMsg'] = "确定要删除编辑作品关联吗？";
	ajaxOptions['callback'] = "<?php echo $cFun;?>ajaxCallBack";
	$(".btn-danger").bjuiajax('doAjax', ajaxOptions);
}

$(document).ready(function(){
	$('#<?php echo $cFun;?>refresh').click(function() {
	    $(this).navtab("reload",<?php echo $cFun;?>GetOp());// 刷新当前Tab页面
	});
});
</script>
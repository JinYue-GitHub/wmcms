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
	<div class="row cl pt-10 pl-10">
		<div class="list-tool pl-15">
            <span >快捷操作：</span>
			<a href="index.php?a=yes&c=operate.search&t=del" data-toggle="doajaxchecked" data-idname="ids" data-group="ids" data-confirm-msg="确定要删除选中项吗？" class="btn btn-warning size-MINI radius" data-callback="<?php echo $type.$cFun;?>ajaxCallBack"> <i class="fa fa-remove"></i> 批量删除</a>
			<?php if($type == 'all'){ echo '<a href="index.php?a=yes&c=operate.search&t=clear" data-toggle="doajax" data-confirm-msg="此操作不可撤回确定要清空吗？" class="btn btn-danger size-MINI radius"><i class="fa fa-trash-o"></i> 清空搜索</a>';}?>
		</div>
		<div class="list-tool">
			<form id="pagerForm" name="<?php echo $cFun.$type;?>Form" data-toggle="ajaxsearch" data-loadingmask="true" action="<?php echo $url;?>&t=<?php echo $type;?>" method="post">
				<input type="hidden" name="pageSize" value="<?php echo $pageSize;?>">
				<input type="hidden" name="pageCurrent" value="<?php echo $pageCurrent;?>">
				<input type="hidden" name="orderField" value="<?php echo $orderField;?>">
				<input type="hidden" name="orderDirection" value="<?php echo $orderDirection;?>">
                <span class="" style="float:left;margin:5px 0 0 15px;">快速查询：</span>
                
                <select data-toggle="selectpicker" id="module" name="module" data-width="100">
                	<option value="">全部模块</option>
                	<?php
                	foreach ($moduleArr as $k=>$v)
                	{
                		$checked = str::CheckElse( $k , $module , 'selected=""' );
                		echo '<option value="'.$k.'" '.$checked.'>'.$v.'</option>';
                	}
                	?>
                </select>
                <?php
                if($type == 'all'){
                ?>
                <select data-toggle="selectpicker" id="st" name="st" data-width="100">
                	<?php
                	foreach ($typeArr as $k=>$v)
                	{
                		$checked = str::CheckElse( $k , $st , 'selected=""' );
                		echo '<option value="'.$k.'" '.$checked.'>'.$v.'</option>';
                	}
                	?>
                </select>
	            <?php
                }else{
                	echo '<input type="hidden" name="st" value="'.$st.'">';
                }
	            ?>
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
				<th width="5%" data-order-field="search_id">ID</th>
				<th width="10%" data-order-field="search_module">搜索模块</th>
				<th width="7%">是否推荐</th>
				<th>搜索词</th>
				<th width="5%" data-order-field="search_type">搜索类型</th>
				<th width="5%" data-order-field="search_count">搜索次数</th>
				<th width="8%" data-order-field="search_data">搜索结果量</th>
	            <th width="15%" data-order-field="search_time">搜索时间</th>
	            <th width="5%">操作</th>
	            </tr>
			</thead>
			<tbody id="<?php echo $type.$cFun?>List">
			<?php
			if( $dataArr )
			{
				$i = 1;
				foreach ($dataArr as $k=>$v)
				{
					$cur = str::CheckElse( $i%2 , 0 , '' , 'even_index_row');
					$rec = str::CheckElse( $v['search_rec'] , 0 , '<a href="javascript:;" onClick="'.$type.$cFun.'RecAjax(1,'.$v['search_id'].')">推荐</a>' , '<a href="javascript:;" onClick="'.$type.$cFun.'RecAjax(0,'.$v['search_id'].')"><span style="color:red">取消</span></a>');
					echo '<tr class="'.$cur.'">
							<td style="text-align: center;"><input type="checkbox" name="ids" data-toggle="icheck" value="'.$v['search_id'].'"></td>
							<td style="text-align: center;">'.$v['search_id'].'</td>
							<td style="text-align: center;">'.GetModuleName($v['search_module']).'</td>
							<td style="text-align: center;">'.$rec.'</td>
							<td style="text-align: center;">'.$v['search_key'].'</td>
							<td style="text-align: center;">'.$searchSer->GetType($v['search_type']).'</td>
							<td style="text-align: center;">'.$v['search_count'].'</td>
							<td style="text-align: center;">'.$v['search_data'].'</td>
							<td style="text-align: center;">'.date( 'Y-m-d H:i:s' , $v['search_time']).'</td>
							<td style="text-align: center;" data-noedit="true">
								<a class="btn btn-danger radius" onclick="'.$type.$cFun.'delAjax('.$v['search_id'].')">删除</a>
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
//页面唯一op获取函数
function <?php echo $type.$cFun;?>GetOp(){
	var op=new Array();
	op['type'] = 'POST';
	op['data'] = $("[name=<?php echo $type.$cFun;?>Form]").serializeArray();
	return op;
}

//推荐搜索关键字
function <?php echo $type.$cFun;?>RecAjax(rec,id)
{
	var type;
	var ajaxOptions=new Array();
	var ajaxData=new Object();
	ajaxOptions = <?php echo $type.$cFun;?>GetOp();

	//推荐操作类型
	switch(rec)
	{
		case 0:
			type = "取消推荐";
	  		break;
	  		
		default:
			type = "设为推荐";
	  		break;
	}
	
	ajaxData.id = id;
	ajaxData.rec = rec;
	ajaxOptions['data'] = ajaxData;
	ajaxOptions['url'] = "index.php?a=yes&c=operate.search&t=rec";
	ajaxOptions['confirmMsg'] = "确定要"+type+"搜索关键词吗？";
	ajaxOptions['callback'] = "<?php echo $type.$cFun;?>ajaxCallBack";
	$(".btn-danger").bjuiajax('doAjax', ajaxOptions);
}
//删除数据
function <?php echo $type.$cFun;?>delAjax(id)
{
	var ajaxOptions=new Array();
	var ajaxData=new Object();
	ajaxOptions = <?php echo $type.$cFun;?>GetOp();
	
	ajaxData.id = id;
	ajaxOptions['data'] = ajaxData;
	ajaxOptions['url'] = "index.php?a=yes&c=operate.search&t=del";
	ajaxOptions['confirmMsg'] = "确定要删除所选的搜索结果吗？";
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
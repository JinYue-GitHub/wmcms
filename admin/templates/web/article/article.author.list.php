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
#<?php echo $cFun;?>table td{text-align: center;}
</style>
<div class="bjui-pageHeader">
	<div class="row cl pt-10 pl-10">
		<div class="list-tool">
			<form id="pagerForm" name="<?php echo $cFun;?>Form" data-toggle="ajaxsearch" data-loadingmask="true" action="<?php echo $url;?>" method="post">
				<input type="hidden" name="pageSize" value="<?php echo $pageSize;?>">
				<input type="hidden" name="pageCurrent" value="<?php echo $pageCurrent;?>">
				<input type="hidden" name="orderField" value="<?php echo $orderField;?>">
				<input type="hidden" name="orderDirection" value="<?php echo $orderDirection;?>">
                <span class="" style="float:left;margin:5px 0 0 15px;">快速查询：</span>
                
				<select data-toggle="selectpicker" name="author" data-width="100">
                	<option value="">全部数据</option>
                	<?php
                	foreach ($authorArr as $k=>$v)
                	{
                		$checked = str::CheckElse( $k , $author , 'selected=""' );
                		echo '<option value="'.$k.'" '.$checked.'>'.$v.'</option>';
                	}
                	?>
                </select>
                <input type="text" placeholder="<?php echo $name;?>" name="name" size="15">
				<button type="submit" class="btn btn-warning radius" data-icon="search">查询</button>
				<a id="<?php echo $cFun;?>refresh" class="btn size-MINI btn-primary radius"><i class="fa fa-refresh fa-spin"></i> 刷新</a> &nbsp;
            	<a class="btn btn-secondary radius size-MINI" data-toggle="dialog" data-mask="true" data-title="添加文章作者来源" href="index.php?d=yes&c=article.author.edit&t=add" data-width="420" data-height="300" ><i class="fa fa-plus"></i> 添加数据</a> &nbsp;
				<a href="index.php?a=yes&c=article.author&t=del" data-toggle="doajaxchecked" data-idname="ids" data-group="ids" data-confirm-msg="确定要删除选中项吗？" class="btn btn-danger size-MINI radius" data-callback="<?php echo $cFun;?>ajaxCallBack"> <i class="fa fa-remove"></i> 批量删除</a>
			</form>
		</div>
	</div>
</div>


<div class="bjui-pageContent">
		<table class="table table-border table-bordered table-hover table-bg table-sort">
			<thead>
				<tr>
				<th width="2%;"><input type="checkbox" class="checkboxCtrl" data-group="ids" data-toggle="icheck"></th>
				<th width="5%" data-order-field="author_id">ID</th>
				<th width="15%" data-order-field="author_type">类型</th>
	            <th width="15%" data-order-field="author_default">默认选中</th>
				<th width="30%" data-order-field="author_name">内容</th>
				<th width="15%" data-order-field="author_data">数据量</th>
	            <th width="12%">操作</th>
	            </tr>
			</thead>
			<tbody id="<?php echo $cFun;?>table">
			<?php
			if( $dataArr )
			{
				$i = 1;
				foreach ($dataArr as $k=>$v)
				{
					$cur = str::CheckElse( $i%2 , 0 , '' , 'even_index_row');
					$default = str::CheckElse( $v['author_default'] , 0 , '不是默认' , '默认数据');
					
					echo '<tr class="'.$cur.'">
							<td><input type="checkbox" name="ids" data-toggle="icheck" value="'.$v['author_id'].'"></td>
							<td>'.$v['author_id'].'</td>
							<td>'.$articleSer->GetAuthor($v['author_type']).'</td>
							<td>'.$default.'</td>
							<td>'.$v['author_name'].'</td>
							<td>'.$v['author_data'].'</td>
							<td>
				            	<a class="btn btn-secondary radius size-MINI" data-toggle="dialog" data-mask="true" data-title="编辑文章作者来源" href="index.php?d=yes&c=article.author.edit&t=edit&id='.$v['author_id'].'" data-width="420" data-height="300" >编辑</a> 
								<a class="btn btn-danger radius" onclick="'.$cFun.'delAjax('.$v['author_id'].')">删除</a>
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
	</form>
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
	ajaxOptions['url'] = "index.php?a=yes&c=article.author&t=del";
	ajaxOptions['confirmMsg'] = "确定要删除所选的数据吗？";
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
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
			<form id="pagerForm" name="<?php echo $cFun;?>Form" data-toggle="ajaxsearch" data-loadingmask="true" action="index.php?c=system.seo.keys" method="post">
				<input type="hidden" name="pageSize" value="<?php echo $pageSize;?>">
				<input type="hidden" name="pageCurrent" value="<?php echo $pageCurrent;?>">
				<input type="hidden" name="orderField" value="<?php echo $orderField;?>">
				<input type="hidden" name="orderDirection" value="<?php echo $orderDirection;?>">
                <span class="" style="float:left;margin:5px 0 0 15px;">快速查询：</span>
				<select data-toggle="selectpicker" id="<?php echo $cFun;?>module" name="module" data-width="100">
                	<option value="">全部模块</option>
                	<?php
                	foreach ($moduleArr as $k=>$v)
                	{
                		$checked = str::CheckElse( $k , $module , 'selected=""' );
                		echo '<option value="'.$k.'" '.$checked.'>'.$v.'</option>';
                	}
                	?>
                </select>
                <input type="text" placeholder="<?php echo $name;?>" name="name" size="10">
				<button type="submit" class="btn btn-warning radius" data-icon="search">查询</button>
				<a id="<?php echo $cFun;?>refresh" class="btn size-MINI btn-primary radius"><i class="fa fa-refresh fa-spin"></i> 刷新</a>
				<div class="alert alert-danger search-inline"><i class="fa fa-info-circle"></i> 修改完成后请点击生成缓存文件，否则不会生效</div>
				<a href="index.php?a=yes&c=system.seo.keys&t=config" data-reload="false" data-toggle="doajax" data-confirm-msg="确定要生成新的缓存吗？" class="btn btn-danger  radius"> 生成缓存</a>
			</form>
		</div>
	</div>
</div>

<div class="bjui-pageContent">
		<table class="table table-border table-bordered table-hover table-bg table-sort">
			<thead>
				<tr>
				<th width="6%" data-order-field="keys_module">所属模块</th>
				<th width="10%">页面作用</th>
	            <th width="20%">标题</th>
	            <th width="26%">关键词</th>
	            <th width="33%">描述</th>
	            <th width="5%">操作</th>
	            </tr>
			</thead>
			<tbody>
			<?php
			if( $keyArr )
			{
				$i = 1;
				foreach ($keyArr as $k=>$v)
				{
					$cur = str::CheckElse( $i%2 , 0 , '' , 'even_index_row');
					echo '<tr class="'.$cur.'">
							<td style="text-align: center;">'.GetModuleName($v['keys_module']).'</td>
							<td>'.$v['keys_pagename'].'</td>
							<td>'.$v['keys_title'].'</td>
							<td>'.$v['keys_key'].'</td>
							<td>'.$v['keys_desc'].'</td>
							<td style="text-align: center;">
				            	<a class="btn btn-secondary radius size-MINI" data-mask="true" data-on-close="onClose" href="index.php?d=yes&c=system.seo.keys.edit&t=edit&id='.$v['keys_id'].'"  data-toggle="dialog" data-title="修改页面seo信息" data-width="640" data-height="370" >编辑</a> 
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
//页面唯一op获取函数
function <?php echo $cFun;?>GetOp(){
	var op=new Array();
	op['type'] = 'POST';
	op['data'] = $("[name=<?php echo $cFun;?>Form]").serializeArray();
	return op;
}

$(document).ready(function(){
	$('#<?php echo $cFun;?>module').change(function() {
		$("[name=<?php echo $cFun;?>Form]").submit();
	});
	$('#<?php echo $cFun;?>refresh').click(function() {
	   $(this).navtab("reload",<?php echo $cFun;?>GetOp());// 刷新当前Tab页面
	});
});
</script>
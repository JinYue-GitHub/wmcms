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
		<div class="f-l" style="margin-left:10px">
			<a href="index.php?a=yes&c=system.tags&t=<?php echo $module?>_del" data-toggle="doajaxchecked" data-idname="ids" data-group="ids" data-confirm-msg="确定要删除选中项吗？" class="btn btn-danger size-MINI radius" data-callback="<?php echo $cFun;?>ajaxCallBack"> <i class="fa fa-remove"></i> 批量删除</a>
			<a href="index.php?a=yes&c=system.tags&t=<?php echo $module?>_clear" data-toggle="doajax" data-confirm-msg="确定清空记录要吗？" class="btn btn-danger size-MINI radius"><i class="fa fa-trash-o"></i> 清空记录</a>
		</div>
		<div class="f-l">
			<form id="pagerForm" name="<?php echo $cFun;?>Form" data-toggle="ajaxsearch" data-loadingmask="true" action="index.php?c=system.tags.list&t=<?php echo $module?>_list" method="post">
				<input type="hidden" name="pageSize" value="<?php echo $pageSize;?>">
				<input type="hidden" name="pageCurrent" value="<?php echo $pageCurrent;?>">
				<input type="hidden" name="orderField" value="<?php echo $orderField;?>">
				<input type="hidden" name="orderDirection" value="<?php echo $orderDirection;?>">
                <span class="" style="float:left;margin:5px 0 0 15px;">快速查询：</span>
                <select data-toggle="selectpicker" id="type_id" name="type_id" data-width="100">
                	<option value="">所有</option>
                	<?php
                	if( $typeData )
                	{
                    	foreach ($typeData as $k=>$v)
                    	{
                    		$checked = str::CheckElse( $v['type_id'] , $typeId , 'selected=""' );
                    		echo '<option value="'.$v['type_id'].'" '.$checked.'>'.$v['type_name'].'</option>';
                    	}
                	}
                	?>
                </select>
                <select data-toggle="selectpicker" id="author_rec" name="author_rec" data-width="100">
                	<option value="">所有</option>
                	<?php
                	foreach ($tagsMod->GetAuthorRec() as $k=>$v)
                	{
                		$checked = str::CheckElse( $k , $authorRec , 'selected=""' );
                		echo '<option value="'.$k.'" '.$checked.'>'.$v.'</option>';
                	}
                	?>
                </select>
                <input type="text" placeholder="请输入关键词" value="<?php echo $name;?>" name="name" size="10">
				<button type="submit" class="btn btn-warning radius" data-icon="search">查询</button>
				<a id="<?php echo $cFun;?>refresh" class="btn size-MINI btn-primary radius"><i class="fa fa-refresh fa-spin"></i> 刷新</a>
				<a class="btn btn-success radius size-MINI" data-toggle="dialog" data-mask="true" data-title="添加标签" href="index.php?d=yes&c=system.tags.edit&t=<?php echo $module?>_add" data-width="450" data-height="250" ><i class="fa fa-plus"></i> 添加标签</a> &nbsp;
			</form>
		</div>
	</div>
</div>

<div class="bjui-pageContent">
		<table class="table table-border table-bordered table-hover table-bg table-sort">
			<thead>
				<tr>
				<th style="text-align: center;" width="2%;"><input type="checkbox" class="checkboxCtrl" data-group="ids" data-toggle="icheck"></th>
				<th width="5%" data-order-field="tags_id">ID</th>
				<th width="6%">作者推荐</th>
	            <th width="10%">标签分类</th>
	            <th width="10%">标签名字</th>
	            <th width="15%">标签拼音</th>
	            <th width="5%">数据量</th>
	            <th width="8%">搜索次数</th>
	            <th width="8%">创建时间</th>
	            <th width="8%">操作</th>
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
					$authorRec = str::CheckElse( $v['tags_author_rec'] , 1 , '<a href="javascript:;" onClick="'.$cFun.'StatusAjax(0,'.$v['tags_id'].')"><span style="color:red">'.$tagsMod->GetAuthorRec('1').'</span></a>' , '<a href="javascript:;" onClick="'.$cFun.'StatusAjax(1,'.$v['tags_id'].')"><span style="color:green">'.$tagsMod->GetAuthorRec('0').'</span></a>');
					echo '<tr class="'.$cur.'">
							<td style="text-align: center;"><input type="checkbox" name="ids" data-toggle="icheck" value="'.$v['tags_id'].'"></td>
							<td style="text-align: center;">'.$v['tags_id'].'</td>
							<td style="text-align: center;">'.$authorRec.'</td>
							<td style="text-align: center;">'.$v['type_name'].'</td>
							<td style="text-align: center;">'.$v['tags_name'].'</td>
							<td style="text-align: center;">'.$v['tags_pinyin'].'</td>
							<td style="text-align: center;">'.$v['tags_data'].'</td>
							<td style="text-align: center;">'.$v['tags_search'].'</td>
							<td style="text-align: center;">'.date('Y-m-d H:i:s',$v['tags_time']).'</td>
							<td style="text-align: center;">
				            	<a class="btn btn-secondary radius size-MINI" data-mask="true" data-on-close="onClose" href="index.php?d=yes&c=system.tags.edit&t='.$module.'_edit&id='.$v['tags_id'].'"  data-toggle="dialog" data-title="修改标签" data-width="450" data-height="250" >编辑</a> 
				           		<a class="btn btn-danger radius" onclick="'.$cFun.'delAjax('.$v['tags_id'].')">删除</a>
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
	ajaxOptions['url'] = "index.php?a=yes&c=system.tags&t=<?php echo $module;?>_del";
	ajaxOptions['confirmMsg'] = "确定要删除所选的数据吗？";
	ajaxOptions['callback'] = "<?php echo $cFun;?>ajaxCallBack";
	$(".btn-danger").bjuiajax('doAjax', ajaxOptions);
}
//禁用使用条件
function <?php echo $cFun;?>StatusAjax(authorrec,id)
{
	var type;
	var ajaxOptions=new Array();
	var ajaxData=new Object();
	ajaxOptions = <?php echo $cFun;?>GetOp();
	switch(authorrec)
	{
		case 0:
			type = "取消推荐";
	  		break;
	  		
		default:
			type = "推荐";
	  		break;
	}
	
	ajaxData.id = id;
	ajaxData.authorrec = authorrec;
	ajaxOptions['data'] = ajaxData;
	ajaxOptions['url'] = "index.php?a=yes&c=system.tags&t=<?php echo $module;?>_authorrec";
	ajaxOptions['confirmMsg'] = "确定要"+type+"标签吗？";
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
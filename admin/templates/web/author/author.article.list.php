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
            <a href="index.php?a=yes&c=author.article.apply&t=status&status=1" data-toggle="doajaxchecked" data-idname="ids" data-group="ids" data-confirm-msg="确定要审核选中项吗？" data-callback="<?php echo $cFun;?>ajaxCallBack" class="btn btn-warning radius"> 批量审核</a>
			<a href="index.php?a=yes&c=author.article.apply&t=del" data-toggle="doajaxchecked" data-idname="ids" data-group="ids" data-confirm-msg="确定要删除选中项吗？" class="btn btn-danger size-MINI radius" data-callback="<?php echo $cFun;?>ajaxCallBack"> <i class="fa fa-remove"></i> 批量删除</a>
			<a href="index.php?a=yes&c=author.article.apply&t=clear" data-toggle="doajax" data-confirm-msg="确定清空申请记录吗？" class="btn btn-danger size-MINI radius"><i class="fa fa-trash-o"></i> 清空记录</a>
		</div>
		<div class="list-tool">
			<form id="pagerForm" name="<?php echo $cFun;?>Form" data-toggle="ajaxsearch" data-loadingmask="true" action="<?php echo $url;?>" method="post">
				<input type="hidden" name="pageSize" value="<?php echo $pageSize;?>">
				<input type="hidden" name="pageCurrent" value="<?php echo $pageCurrent;?>">
				<input type="hidden" name="orderField" value="<?php echo $orderField;?>">
				<input type="hidden" name="orderDirection" value="<?php echo $orderDirection;?>">
                <span class="" style="float:left;margin:5px 0 0 15px;">快速查询：</span>
               
				<select data-toggle="selectpicker" name="type" data-width="100">
                	<option value="1" <?php echo str::CheckElse( $st , 1 , 'selected=""' );?>>搜书名</option>
                	<option value="2" <?php echo str::CheckElse( $st , 2 , 'selected=""' );?>>搜作者</option>
                	<option value="3" <?php echo str::CheckElse( $st , 3 , 'selected=""' );?>>搜书号</option>
                </select>
                <input type="text" placeholder="<?php echo $name;?>" name="name" size="15">
				<button type="submit" class="btn btn-warning radius" data-icon="search">查询</button>
				<a id="<?php echo $cFun;?>refresh" class="btn size-MINI btn-primary radius"><i class="fa fa-refresh fa-spin"></i> 刷新</a>
			</form>
		</div>
	</div>
</div>

<div class="bjui-pageContent">
		<table class="table table-border table-bordered table-hover table-bg table-sort">
			<thead>
				<tr>
				<th style="text-align: center;" width="2%;"><input type="checkbox" class="checkboxCtrl" data-group="ids" data-toggle="icheck"></th>
				<th width="5%" data-order-field="apply_id">ID</th>
				<th width="5%" data-order-field="apply_status">是否审核</th>
				<th width="5%" data-order-field="article_id">文章id</th>
				<th>文章标题</th>
				<th width="7%">文章分类</th>
				<th width="10%" data-order-field="article_author">作者笔名</th>
				<th width="15%" data-order-field="apply_createtime">申请时间</th>
	            <th width="18%">操作</th>
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
					$set = '';
					switch ($v['apply_status'])
					{
						case '0':
							$status = '<a href="javascript:;" onClick="'.$cFun.'StatusAjax(1,'.$v['apply_id'].')"><span style="color:red">未审核</span></a>';
							$set = '<button type="button" class="btn btn-warning radius" data-toggle="dropdown">操作<span class="caret"></span></button><ul class="dropdown-menu" style="width:90px"><li><a href="javascript:;" onClick="'.$cFun.'StatusAjax(1,'.$v['apply_id'].')">通过审核</a></li>
								  <li><a href="index.php?d=yes&c=system.apply.refuse&t=refuse_author_article_edit&id='.$v['apply_id'].'&module=author&type=article_editarticle&uid='.$v['apply_uid'].'&cid='.$v['apply_cid'].'" data-mask="true" data-toggle="dialog" data-title="拒绝文章修改申请" data-width="630" data-height="510">拒绝通过</a></li></ul>';
							break;
						case '1':
							$status = '<span style="color:green">已审核</span>';
							break;
						default:
							$status = '<span style="color:#A09E9E">已拒绝</span>';
							break;
					}
					switch ($v['apply_type'])
					{
						case "chapter_edit":
							$chapterType='编辑文章';
							break;
						case "chapter_add":
							$chapterType='新增文章';
							break;
					}
					$option = unserialize($v['apply_option']);
					if( $option['article_name'] == '' )
					{
						$v['article_name'] = '<span style="color:#A09E9E">该数据已经处理无法查看！</span>';
					}
					echo '<tr class="'.$cur.'">
							<td style="text-align: center;"><input type="checkbox" name="ids" data-toggle="icheck" value="'.$v['apply_id'].'"></td>
							<td style="text-align: center;">'.$v['apply_id'].'</td>
							<td style="text-align: center;">'.$status.'</td>
							<td style="text-align: center;">'.$v['article_id'].'</td>
							<td style="text-align: center;">'.$v['article_name'].'</a></td>
							<td style="text-align: center;">'.$v['type_name'].'</a></td>
							<td style="text-align: center;">'.$v['article_author'].'</td>
							<td style="text-align: center;">'.date( 'Y-m-d H:i:s' , $v['apply_createtime']).'</td>
							<td style="text-align: center;" data-noedit="true">
							      '.$set.'
				            	<a class="btn btn-secondary  radius" href="index.php?c=system.apply.detail&t=refuse_author_article_edit&id='.$v['apply_id'].'&module=author&type='.$v['apply_type'].'" data-mask="true" data-toggle="dialog" data-title="查看修改的详细数据" data-width="600" data-height="450" > 查看数据 </a>
				            	<a class="btn btn-danger radius" onclick="'.$cFun.'delAjax('.$v['apply_id'].')">删除</a>
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
function <?php echo $cFun;?>GetOp(){
	var op=new Array();
	op['type'] = 'POST';
	op['data'] = $("[name=<?php echo $cFun;?>Form]").serializeArray();
	return op;
}
//删除文章修改申请
function <?php echo $cFun;?>delAjax(id)
{
	var ajaxOptions=new Array();
	var ajaxData=new Object();
	ajaxOptions = <?php echo $cFun;?>GetOp();
	
	ajaxData.id = id;
	ajaxOptions['data'] = ajaxData;
	ajaxOptions['url'] = "index.php?a=yes&c=author.article.apply&t=del";
	ajaxOptions['confirmMsg'] = "确定要删除所选的文章修改申请吗？";
	ajaxOptions['callback'] = "<?php echo $cFun;?>ajaxCallBack";
	$(".btn-danger").bjuiajax('doAjax', ajaxOptions);
}
//审核文章修改申请
function <?php echo $cFun;?>StatusAjax(status,id)
{
	var type;
	var ajaxOptions=new Array();
	var ajaxData=new Object();
	ajaxOptions = <?php echo $cFun;?>GetOp();

	//文章修改申请操作类型
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
	ajaxOptions['url'] = "index.php?a=yes&c=author.article.apply&t=status";
	ajaxOptions['confirmMsg'] = "确定要"+type+"文章修改申请吗？";
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
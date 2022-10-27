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
			<a data-mask="true" data-toggle="navtab" data-id="novel-chapter-add" data-title="添加章节" data-width="530" data-height="270" href="index.php?c=novel.chapter.edit&t=add&nid=<?php echo $nid;?>" class="btn btn-success size-MINI radius"> <i class="fa fa-plus-square"></i> 添加章节</a>
			<?php if( $nid > 0 ) { ?><a href="javascript:;" onClick="<?php echo $cFun?>BatchDiv()" class="btn btn-warning  radius"> 快速批量修改</a><?php }?>
			<a href="index.php?a=yes&c=novel.chapter&t=del" data-toggle="doajaxchecked" data-idname="ids" data-group="ids" data-confirm-msg="确定要删除选中项吗？" class="btn btn-danger size-MINI radius" data-callback="<?php echo $cFun;?>ajaxCallBack"> <i class="fa fa-remove"></i> 批量删除</a>
			<a href="index.php?a=yes&c=novel.chapter&t=clear&name=<?php echo $name;?>&nid=<?php echo $nid;?>" data-toggle="doajax" data-confirm-msg="确定要彻底清空当前小说的所有章节吗？" class="btn btn-danger size-MINI radius" data-callback="<?php echo $cFun;?>ajaxCallBack"> <i class="fa fa-remove"></i> 清空章节</a>
			<?php if($nid>0){ ?><a data-toggle="dialog" data-mask="true" data-id="novel-txt-upload" data-width="500" data-height="350" data-title="导入TXT" href="index.php?d=yes&c=novel.txt.upload&nid=<?php echo $nid;?>" class="btn btn-secondary size-MINI radius"> <i class="fa fa-exchange"></i> 导入TXT</a></li><?php }?>
		</div>
		<div class="list-tool">
			<form id="pagerForm" name="<?php echo $cFun;?>Form" data-toggle="ajaxsearch" data-loadingmask="true" action="<?php echo $url;?>" method="post">
				<input type="hidden" name="pageSize" value="<?php echo $pageSize;?>">
				<input type="hidden" name="pageCurrent" value="<?php echo $pageCurrent;?>">
				<input type="hidden" name="orderField" value="<?php echo $orderField;?>">
				<input type="hidden" name="orderDirection" value="<?php echo $orderDirection;?>">
                <span class="" style="float:left;margin:5px 0 0 15px;">快速查询：</span>
                
                <input type="hidden" name="nid" value="<?php echo $nid;?>">
				<select data-toggle="selectpicker" name="st" data-width="100">
                	<option value="1" <?php echo str::CheckElse( $st , 1 , 'selected=""' );?>>搜小说名</option>
                	<option value="2" <?php echo str::CheckElse( $st , 2 , 'selected=""' );?>>搜章节名</option>
                </select>
                <input type="text" placeholder="请输入关键字" value="<?php echo $name;?>" name="name" size="15">
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
				<th width="5%" data-order-field="chapter_id">ID</th>
				<th width="11%" data-order-field="novel_name">小说名字</th>
	            <th width="11%" data-order-field="novel_author">小说作者</th>
	            <th width="6%" data-order-field="chapter_status">章节状态</th>
				<th width="8%" data-order-field="volume_name">分卷名字</th>
	            <th data-order-field="chapter_name">章节名字</th>
	            <th width="6%" data-order-field="chapter_ispay">收费类型</th>
	            <th width="6%" data-order-field="chapter_islogin">是否登录</th>
				<th width="4%" data-order-field="chapter_order">章节排序</th>
	            <th width="10%" data-order-field="chapter_time">创建时间</th>
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
					$status = str::CheckElse( $v['chapter_status'] , 1 , '<span style="color:green">已审核</span>' , '<span style="color:red">未审核</span>');
					$isPay = str::CheckElse( $v['chapter_ispay'] , 1 , '<span style="color:red">收费章节</span>' , '<span style="color:green">公众章节</span>');
					$isLogin = str::CheckElse( $v['chapter_islogin'] , 1 , '<span style="color:red">需要登录</span>' , '<span style="color:green">无需登录</span>');
					
					echo '<tr class="'.$cur.'">
							<td style="text-align: center;"><input type="checkbox" name="ids" data-toggle="icheck" value="'.$v['chapter_id'].'"></td>
							<td style="text-align: center;">'.$v['chapter_id'].'</td>
							<td>'.$v['novel_name'].'</td>
							<td style="text-align: center;">'.$v['novel_author'].'</td>
							<td style="text-align: center;">'.$status.'</td>
							<td style="text-align: center;">'.$v['volume_name'].'</td>
							<td style="text-align: center;">'.$v['chapter_name'].'</td>
							<td style="text-align: center;">'.$isPay.'</td>
							<td style="text-align: center;">'.$isLogin.'</td>
							<td style="text-align: center;">'.$v['chapter_order'].'</td>
							<td style="text-align: center;">'.date( 'Y-m-d H:i:s' , $v['chapter_time']).'</td>
							<td style="text-align: center;" data-noedit="true">
								<button type="button" class="btn btn-warning radius" data-toggle="dropdown">操作<span class="caret"></span></button>
								<ul class="dropdown-menu" style="width:80px">
							    	<li><a data-toggle="navtab" data-id="novel-chapter-edit" data-title="编辑《'.$v['novel_name'].'》章节内容" href="index.php?c=novel.chapter.edit&t=edit&id='.$v['chapter_id'].'">编辑</a></li>
							    	<li class="divider"></li>
									<li><a data-toggle="dialog" data-mask="true" data-height="170" data-id="novel-chapter-edit" data-title="编辑《'.$v['novel_name'].'》章节顺序" href="index.php?d=yes&c=novel.chapter.order&nid='.$v['chapter_nid'].'&cid='.$v['chapter_id'].'">移动</a></li>
								</ul>
								<a class="btn btn-danger radius" onclick="'.$cFun.'delAjax('.$v['chapter_id'].')">删除</a>
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
                <option value="100">100</option>
                <option value="500">500</option>
                <option value="1000">1000</option>
                <option value="3000">3000</option>
                <option value="5000">5000</option>
                <option value="10000">10000</option>
            </select>
        </div>
        <span>&nbsp;条，共 <?php echo $total;?> 条</span>
    </div>
    <div class="pagination-box" data-toggle="pagination" data-total="<?php echo $total;?>" data-page-size="<?php echo $pageSize;?>" data-pageCurrent="<?php echo $pageCurrent?>">
    </div>
</div>

<!-- 批量操作层 -->
<div id="<?php echo $cFun;?>BatchDiv" data-noinit="true" class="hide" align="center">
    <table class="table table-condensed table-hover" width="100%">
	    <tbody>
		<tr>
		    <td colspan="2">
		        <b>章节分卷：</b>
		        <select data-toggle="selectpicker" id="chapter_vid">
		            <option value="">不修改</option>
		        	<?php
		        	foreach ($volume as $v)
		        	{
		        		echo '<option value="'.$v['volume_id'].'">'.$v['volume_name'].'</option>';
		        	}
		        	?>
		        </select>
		    </td>
		</tr>
		<tr>
		    <td>
		        <b>章节状态：</b>
		        <select data-toggle="selectpicker" id="chapter_status">
		            <option value="">不修改</option>
		            <option value="1">审核通过</option>
		            <option value="0">隐藏显示</option>
		        </select>
		    </td>
		    <td>
		        <b>收费类型：</b>
		        <select data-toggle="selectpicker" id="chapter_ispay">
		            <option value="">不修改</option>
		            <option value="0">公众章节</option>
		            <option value="1">收费章节</option>
		        </select>
		    </td>
		</tr>
		<tr>
		    <td colspan="2">
		        <b>阅读是否需要登录：</b>
		        <select data-toggle="selectpicker" id="chapter_islogin">
		            <option value="">不修改</option>
		            <option value="0">无需登录</option>
		            <option value="1">需要登录</option>
		        </select>
		    </td>
		</tr>
	    </tbody>
	</table>
    <div class="pt-10" style="text-align: center;">
		<button onClick="<?php echo $cFun;?>BatchAjax()" type="button" class="btn-green" data-icon="location-arrow">确认操作</button>
		<button type="button" class="btn-close" data-icon="close">关闭</button>
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
//删除章节
function <?php echo $cFun;?>delAjax(id)
{
	var ajaxOptions=new Array();
	var ajaxData=new Object();
	ajaxOptions = <?php echo $cFun;?>GetOp();
	
	ajaxData.id = id;
	ajaxOptions['data'] = ajaxData;
	ajaxOptions['url'] = "index.php?a=yes&c=novel.chapter&t=del";
	ajaxOptions['confirmMsg'] = "确定要删除所选的小说吗？";
	ajaxOptions['callback'] = "<?php echo $cFun;?>ajaxCallBack";
	$(".btn-danger").bjuiajax('doAjax', ajaxOptions);
}
//打开批量操作窗口
function <?php echo $cFun;?>BatchDiv()
{
	var ajaxOptions=new Array();
	ajaxOptions['target'] = "#<?php echo $cFun;?>BatchDiv";
	ajaxOptions['title'] = "批量修改";
	ajaxOptions['width'] = "400";
	ajaxOptions['height'] = "200";
	ajaxOptions['loadingmask'] = true;
	ajaxOptions['mask'] = "true";
	$(this).dialog(ajaxOptions);
}
//批量操作请求
function <?php echo $cFun;?>BatchAjax()
{
	var ajaxData=new Object();
	ajaxData.chapter_vid = $('#chapter_vid').val();
	ajaxData.chapter_ispay = $('#chapter_ispay').val();
	ajaxData.chapter_islogin = $('#chapter_islogin').val();
	ajaxData.chapter_status = $('#chapter_status').val();
	var ajaxOptions=new Array();
	ajaxOptions['url'] = "index.php?a=yes&c=novel.chapter&t=batch";
	ajaxOptions['idName'] = "ids";
	ajaxOptions['group'] = "ids";
	ajaxOptions['data'] = ajaxData;
	ajaxOptions['isNavtab'] = true;
	ajaxOptions['confirmMsg'] = "该操作无法撤回，确定要批量修改选中项吗？";
	ajaxOptions['callback'] = "<?php echo $cFun;?>ajaxCallBack";
	$(this).bjuiajax('doAjaxChecked', ajaxOptions);
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
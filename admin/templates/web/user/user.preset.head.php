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
		<form name="<?php echo $cFun;?>Form" action="index.php?a=yes&c=user.head&t=add" data-reload="false" data-toggle="validate" method="post" data-callback="<?php echo $cFun;?>ajaxCallBack">
		<div class="list-tool pl-15">
            <span >上传头像：</span>
			<input type="text" id="head_src" name="head_src" size="30">
			<span class="upload" data-uploader="index.php?a=yes&c=upload&t=img" data-on-upload-success="<?php echo $cFun;?>upload_success" data-file-type-exts="*.jpg;*.png;*.gif;*.mpg" data-toggle="upload" data-icon="cloud-upload"></span>
			<button type="submit" class="btn-green" data-icon="save">保存</button>
		</div>
		</form>
	</div>
</div>

<div class="bjui-pageContent">
	<table class="table table-border table-bordered table-hover table-bg table-sort">
		<tbody id="<?php echo $cFun?>List">
		<?php
		if( $headArr )
		{
			$i = 1;
			foreach ($headArr as $k=>$v)
			{
				echo '<a href="javascript:void(0)" onclick="'.$cFun.'delAjax('.$v['head_id'].')"><img src="'.$v['head_src'].'" height="100" width="100" style="border-radius:5px;margin-right: 10px;margin-bottom: 10px;" /></a>';
				$i++;
			}
		}
		?>
		</tbody>
	</table>
</div>

<script type="text/javascript">
//页面唯一op获取函数
function <?php echo $cFun;?>GetOp(){
	var op=new Array();
	op['type'] = 'POST';
	op['data'] = $("[name=<?php echo $cFun;?>Form]").serializeArray();
	return op;
}
//页面唯一回调函数
function <?php echo $cFun;?>ajaxCallBack(json){
	$(this).bjuiajax("ajaxDone",json);//显示处理结果
	$(this).navtab("reload");	//刷新当前Tab页面 
}
//上传头像
function <?php echo $cFun;?>upload_success(file,json,$element){
	json = $.parseJSON(json);
	if ( json.statusCode == 300){
		$(this).bjuiajax("ajaxDone",json);// 显示处理结果
	}else{
		val = json.data.path.replace('../',"/");
		$("#head_src").val(val);
	}
}
//删除头像
function <?php echo $cFun;?>delAjax(id)
{
	var ajaxOptions=new Array();
	var ajaxData=new Object();
	ajaxOptions = <?php echo $cFun;?>GetOp();

	ajaxData.id = id;
	ajaxOptions['data'] = ajaxData;
	ajaxOptions['url'] = "index.php?a=yes&c=user.head&t=del";
	ajaxOptions['confirmMsg'] = "确定要删除所选的头像吗？";
	ajaxOptions['callback'] = "<?php echo $cFun;?>ajaxCallBack";
	$(this).bjuiajax('doAjax', ajaxOptions);
}


$(document).ready(function(){
	$('#<?php echo $cFun;?>refresh').click(function() {
	   $(this).navtab("reload",<?php echo $cFun;?>GetOp());// 刷新当前Tab页面
	});
});
</script>
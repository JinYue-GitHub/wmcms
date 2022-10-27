<div class="bjui-pageContent">
<table class="table table-border table-bordered table-bg table-sort">
    <tr>
      <td width="150"><b>电脑版静态资源：</b></td>
      <td>
      	<?php
      	if($staticTemp4){echo '<span style="color:green">存在资源</span> <a class="btn btn-danger radius" onclick="'.$cFun.'delAjax(this,\'web\')">删除资源</a> ';}else{echo '<span style="color:red">暂无资源</span>';}
      	?>
      	<span class="upload" data-uploader="index.php?a=yes&c=upload&t=static&path=web&id=<?php echo $id;?>" data-on-upload-success="<?php echo $cFun;?>upload_success" data-file-type-exts="*.zip" data-toggle="upload" data-icon="cloud-upload"></span>
      </td>
	</tr>
    <tr>
      <td><b>触屏版静态资源：</b></td>
      <td>
      	<?php
      	if($staticTemp3){echo '<span style="color:green">存在资源</span> <a class="btn btn-danger radius" onclick="'.$cFun.'delAjax(this,\'m\')">删除资源</a> ';}else{echo '<span style="color:red">暂无资源</span>';}
      	?>
      	<span class="upload" data-uploader="index.php?a=yes&c=upload&t=static&path=m&id=<?php echo $id;?>" data-on-upload-success="<?php echo $cFun;?>upload_success" data-file-type-exts="*.zip" data-toggle="upload" data-icon="cloud-upload"></span>
      </td>
	</tr>
    <tr>
      <td><b>3G版静态资源：</b></td>
      <td>
      	<?php
      	if($staticTemp2){echo '<span style="color:green">存在资源</span> <a class="btn btn-danger radius" onclick="'.$cFun.'delAjax(this,\'3g\')">删除资源</a> ';}else{echo '<span style="color:red">暂无资源</span>';}
      	?>
      	<span class="upload" data-uploader="index.php?a=yes&c=upload&t=static&path=3g&id=<?php echo $id;?>" data-on-upload-success="<?php echo $cFun;?>upload_success" data-file-type-exts="*.zip" data-toggle="upload" data-icon="cloud-upload"></span>
      </td>
	</tr>
    <tr>
      <td><b>简洁版静态资源：</b></td>
      <td>
      	<?php
      	if($staticTemp1){echo '<span style="color:green">存在资源</span> <a class="btn btn-danger radius" onclick="'.$cFun.'delAjax(this,\'wap\')">删除资源</a> ';}else{echo '<span style="color:red">暂无资源</span>';}
      	?>
      	<span class="upload" data-uploader="index.php?a=yes&c=upload&t=static&path=wap&id=<?php echo $id;?>" data-on-upload-success="<?php echo $cFun;?>upload_success" data-file-type-exts="*.zip" data-toggle="upload" data-icon="cloud-upload"></span>
      </td>
	</tr>
</table>
</div>


<div class="bjui-pageFooter">
    <ul>
        <li><button type="button" class="btn-close" data-icon="close">关闭</button></li>
    </ul>
</div>


<script type="text/javascript">
//上传模版成功后
function <?php echo $cFun;?>upload_success(file,json,$element){
	json = $.parseJSON(json);
	if ( json.statusCode == 300){
		$(this).bjuiajax("ajaxDone",json);// 显示处理结果
	}else{
		$element.parent().find('span').eq(0).html('存在资源');
		$element.parent().find('span').eq(0).css('color','green');
	}
}

//删除数据
function <?php echo $cFun;?>delAjax($this,path)
{
	var ajaxOptions=new Array();
	var ajaxData=new Object();
	<?php echo $cFun;?>obj = $($this);
	ajaxData.id = <?php echo $id;?>;
	ajaxData.path = path;
	ajaxOptions['data'] = ajaxData;
	ajaxOptions['url'] = "index.php?a=yes&c=system.templates.templates&t=delstatic";
	ajaxOptions['confirmMsg'] = "确定要删除当前资源吗？";
	ajaxOptions['callback'] = "<?php echo $cFun;?>ajaxCallBack";
	$(this).bjuiajax('doAjax', ajaxOptions);
}
//页面唯一回调函数
function <?php echo $cFun;?>ajaxCallBack(json,$element){
	$(this).bjuiajax("ajaxDone",json);// 显示处理结果
	if ( json.statusCode == 200){
		<?php echo $cFun;?>obj.parent().find('span').eq(0).html('暂无资源');
		<?php echo $cFun;?>obj.parent().find('span').eq(0).css('color','red');
		<?php echo $cFun;?>obj.remove();
	}
}
</script>
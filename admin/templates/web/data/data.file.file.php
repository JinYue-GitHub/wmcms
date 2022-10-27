<style>
.<?php echo $cFun?>Table{margin-bottom:20px}
</style>

<div class="bjui-pageContent">
	<form name="<?php echo $cFun.$type;?>Form" action="index.php?a=yes&c=data.file&t=<?php echo $type;?>" method="post" data-toggle="validate" <?php if($d) { echo 'data-callback="'.$cFun.$type.'"';}?>>
    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
      <tr>
        <td width="100">工作目录：</td>
        <td>
        	<input type="text" class="input-text" name="path" <?php if($type == 'edit'){ echo 'readonly';}?> value="<?php echo $path;?>" size="30" placeholder="保存目录"/>
			<small>空白表示根目录 ，不允许用 “..” 形式的路径</small>
		</td>
	</tr>
	<tr>
        <td>文件名称：</td>
        <td>
        	<input type="text" class="input-text" name="filename" <?php if($type == 'edit'){ echo 'readonly';}?> value="<?php echo $file;?>" size="30" data-rule="required" placeholder="文件名称"/>
        	<small>不允许用 “..” 形式的路径</div>
		</td>
	</tr>
	<tr>
        <td>文件内容：</td>
        <td>
			<textarea id="<?php echo $cFun.$type;?>edit" name="content" style="height:400px;"><?php echo $fileContent;?></textarea>
		</td>
	</tr>
    </table>
  </form>
</div>

<div class="bjui-pageFooter">
    <ul>
        <li><button type="button" class="btn-close btn btn-danger"><i class="fa fa-times"></i> 关闭</button></li>
        <li><button id="<?php echo $cFun.$type;?>sub" type="button" class="btn btn-success"><i class="fa fa-floppy-o"></i> 提交</button></li>
    </ul>
</div>


<link rel="stylesheet" href="/files/js/codeedit/lib/codemirror.css">
<script src="/files/js/codeedit/lib/codemirror.js" type="text/javascript"></script>

<link rel="stylesheet" href="/files/js/codeedit/theme/mbo.css">
<script src="/files/js/codeedit/mode/htmlmixed/htmlmixed.js"></script>
<script src="/files/js/codeedit/mode/xml/xml.js"></script>
<script src="/files/js/codeedit/mode/javascript/javascript.js"></script>
<script src="/files/js/codeedit/mode/css/css.js"></script>
<script src="/files/js/codeedit/mode/clike/clike.js"></script>
<script src="/files/js/codeedit/addon/selection/active-line.js"></script>
<script src="/files/js/codeedit/mode/php/php.js" type="text/javascript"></script>
<script type="text/javascript">
var codeedit<?php echo time()?> = CodeMirror.fromTextArea(document.getElementById("<?php echo $cFun.$type;?>edit"), {
    lineNumbers: true,//行号
    styleActiveLine: true,
    matchBrackets: true,
	mode: "application/x-httpd-php",//相关联的mime
	indentUnit: 4,//缩进单位，值为空格数
	theme:'mbo'//皮肤
  });
codeedit<?php echo time()?>.setSize('auto', 'auto');//自动高度

//页面唯一op获取函数
function <?php echo $cFun.$type;?>GetOp(){
	var op=new Array();
	op['type'] = 'POST';
	op['data'] = $("[name=<?php echo $cFun.$type;?>Form]").serializeArray();
	return op;
}
function <?php echo $cFun.$type;?>(json){
	if ( json.statusCode == 300){
		$(this).bjuiajax("ajaxDone",json);// 显示处理结果
	}else{
		var op = dataFileListGetOp();
		var tabid = 'file-list';
		op['id'] = tabid;
		
		$(this).bjuiajax("ajaxDone",json);// 显示处理结果
	    $(this).navtab("switchTab",tabid);	// 切换Tab页面
	    console.log(op);
	    $(this).navtab("reload",op);	// 刷新Tab页面
	}
}

$("#<?php echo $cFun.$type;?>sub").click(function(event) {
	$("#<?php echo $cFun.$type;?>edit").val(codeedit<?php echo time()?>.getValue());
	$("[name=<?php echo $cFun.$type;?>Form]").submit();
});
</script>
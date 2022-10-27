<div class="bjui-pageContent">
<form id="pagerForm" name="<?php echo $cFun;?>Form" data-toggle="ajaxsearch" data-loadingmask="true" action="<?php echo $url;?>" method="post">
	<input type="hidden" name="path" value="<?php echo $nowPath;?>">
</form>
			
<table class="table table-border table-bordered table-hover table-bg table-sort">
	<thead>
	<tr>
	    <td colspan="4" height="26" bgcolor="#F9FCEF">
			<a href="<?php echo $url;?>" data-toggle="navtab" data-id="file-list" data-title="文件列表"><span class="btn btn-secondary radius size-MINI">根目录</span></a> 
			<a href="index.php?d=yes&c=data.file.file&t=create&path=/<?php echo $nowPath;?>" data-id="file-create" data-toggle="navtab" data-title="新建文件"><span class="btn btn-success radius size-MINI">新建文件</span></a> 
			<a href="index.php?d=yes&c=data.file.createfolder&path=<?php echo $nowPath;?>" data-toggle="dialog" data-title="新建目录"  data-width="380" data-height="130" data-mask="true"><span class="btn btn-primary radius size-MINI">新建目录</span></a>  
			<a href="index.php?a=yes&c=data.file&t=backup" data-toggle="doajax" data-loadingmask="true" data-callback="<?php echo $cFun;?>ajaxCallBack" data-title="新建文件"><span class="btn btn-danger radius size-MINI"> 程序备份</span></a> 
		</td>
	</tr>
	</thead>
</table>

<table class="table table-border table-bordered table-hover table-bg table-sort">
<thead>
  <tr>
    <th width="30%"><strong>文件名</strong></th>
    <th width="10%"><strong>文件大小</strong></th>
    <th width="17%"><strong>创建时间</strong></th>
    <th width="17%"><strong>最后修改时间</strong></th>
    <th><strong>操作</strong></th>
  </tr>
</thead>

<?php
if( $nowPath != '' )
{
	echo '<tr>
	  <td height="25"><img src="/files/images/back.png" border="0" width="25" height="25" align="absmiddle"> <a data-toggle="navtab" data-title="文件列表" href="'.$url.'&path='.$prePath.'">上级目录</a></td>
	  <td colspan="4" bgcolor="#ffffff">当前目录:/'.$nowPath.' &nbsp;</td>
	  </tr>';
}
  
if( $floderArr )
{
	foreach ($floderArr as $k=>$v)
	{
		echo '<tr>
			   <td><img src="/files/images/fileico/folder.png" border="0" width="30" height="30"> <a data-toggle="navtab" href="'.$url.'&path='.$nowPath.$v['file'].'" data-title="文件列表">'.$v['file'].'</a></td>
			   <td>　</td>
			   <td>'.$v['createtime'].'</td>
			   <td>'.$v['uptime'].'</td>
			   <td>
			   		<a href="index.php?d=yes&c=data.file.rename&path='.$nowPath.'&name='.$v['file'].'" data-id="file-rename" data-toggle="dialog" data-title="重命名文件夹" data-width="360" data-height="170" data-mask="true"><span class="btn btn-primary size-MINI radius">改名</span></a>
			   		&nbsp;
			   		<a href="index.php?a=yes&c=data.file&t=del&dt=folder&path='.$nowPath.$v['file'].'" data-toggle="doajax" data-confirm-msg="确定要删除吗？"><span class="btn btn-danger size-MINI radius" data-callback="'.$cFun.'Ref">删除</span></a>
			   </td>
			</tr>';
	}
}

if( $fileArr )
{
	foreach ($fileArr as $k=>$v)
	{
		$edit = '';
		//检查是否可以编辑
		if( $fileSer->IsEdit($v['filetype']) )
		{
			$edit = '<a href="index.php?d=yes&c=data.file.file&t=edit&file='.$v['file'].'&path='.$nowPath.'" data-toggle="navtab" data-id="file-edit" data-title="编辑文件"><span class="btn btn-success size-MINI radius">编辑</span></a>&nbsp;';
			$edit .= '&nbsp;<a href="index.php?a=yes&c=data.file&t=down&file='.$v['file'].'&path='.$nowPath.'" target="_blank"><span class="btn btn-secondary size-MINI radius">下载</span></a>&nbsp;';
		}
		echo '<tr>
		   <td><img src="'.$fileSer->GetFileIco($v['filetype']).'" border=0 width=30 height=30 align=absmiddle> <a href="javascript:;">'.$v['file'].'</a></td>
		   <td>'.$v['size'].' Kb</td>
		   <td>'.$v['createtime'].'</td>
		   <td>'.$v['uptime'].'</td>
		   <td>
			'.$edit.'
		   <a href="index.php?d=yes&c=data.file.rename&path='.$nowPath.'&name='.$v['file'].'" data-id="file-rename" data-toggle="dialog" data-title="重命名文件" data-width="360" data-height="170" data-mask="true"><span class="btn btn-primary size-MINI radius">改名</span></a>
		   &nbsp;
		   <a href="index.php?a=yes&c=data.file&t=del&dt=file&path='.$nowPath.$v['file'].'" data-toggle="doajax" data-confirm-msg="确定要删除吗？"><span class="btn btn-danger size-MINI radius" data-callback="'.$cFun.'Ref" data-confirm-msg="确定要删除文件吗？">删除</span></a>
		   &nbsp;
		   <a href="index.php?d=yes&c=data.file.movefile&path='.$nowPath.'&file='.$v['file'].'" data-toggle="dialog" data-id="file-move" data-mask="true" data-height="210" data-title="移动文件"><span class="btn btn-warning size-MINI radius">移动</span></a>
		   </td>
		   </tr>';
	}
}
?>
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
	if( json.statusCode == '200'){
		$(this).navtab("reload",<?php echo $cFun;?>GetOp());	//刷新当前Tab页面 
	}
}

function <?php echo $cFun;?>Ref(json){
	$(this).bjuiajax("ajaxDone",json);// 显示处理结果
    $(this).navtab("reload");	// 刷新当前Tab页面
}
</script>

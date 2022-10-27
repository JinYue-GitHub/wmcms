<div class="bjui-pageContent">	                       
    <form action="index.php?a=yes&c=system.apply&t=<?php echo 'refuse_'.$module.'_'.$mt;?>" data-reload="false" data-toggle="validate" method="post" <?php if($d) { echo 'data-callback="'.$cFun.'"';}?>>
		<input type="hidden" name="module" value="<?php echo $module;?>">
		<input type="hidden" name="type" value="<?php echo $mt;?>">
		<input type="hidden" name="uid" value="<?php echo $uid;?>">
		<input type="hidden" name="cid" value="<?php echo $cid;?>">
		<input type="hidden" name="id" value="<?php echo $id;?>">
		<input type="hidden" name="pid" value="<?php echo $pid;?>">
		
		<fieldset>
			<legend>拒绝原因</legend>
    		<table class="table table-border table-bordered table-bg table-sort">
	            <tbody>
	                <tr>
	                   <td>
							<?php echo Ueditor('height:200px' , 'remark' , $remark, 'editor.'.$module.'_'.$type.'_refuse');?>
	                   </td>
	                </tr>
	            </tbody>
	        </table>
		</fieldset>
	</form>
</div>

<div class="bjui-pageFooter">
    <ul>
        <li><button type="button" class="btn-close" data-icon="close">取消</button></li>
        <li><button type="submit" class="btn-green" data-icon="save">提交</button></li>
    </ul>
</div>

<script>
//页面唯一回调函数
function <?php echo $cFun;?>(json){
	$(this).bjuiajax("ajaxDone",json);//显示处理结果
	$(this).dialog("closeCurrent");	//关闭当前dialog
	$(this).navtab("reload");	//刷新当前Tab页面 
}
</script>
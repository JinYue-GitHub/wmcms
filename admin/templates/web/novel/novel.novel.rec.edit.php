<div class="bjui-pageContent">
    <form action="index.php?a=yes&c=novel.rec&t=<?php echo $type;?>" data-reload="false" data-toggle="validate" method="post" <?php if($d) { echo 'data-callback="'.$cFun.'"';}?>>
	<input name="rec_id" type="hidden" class="input-text" value="<?php echo $id;?>">
		<fieldset>
			<legend>小说推荐属性编辑</legend>
            <div class="tab-pane fade active in">
				<table class="table table-border table-bordered table-bg table-sort">
					<tr>
					    <td valign="top" width="30%"><b>小说分类：</b></td>
						<td valign="top"><?php echo C('type_name',null,'data');?></td>
					</tr>
					<tr>
					    <td valign="top"><b>所属小说：</b></td>
						<td valign="top"><?php echo C('novel_name',null,'data');?></td>
					</tr>
					<tr>
					    <td valign="top"><b>小说作者：</b></td>
						<td valign="top"><?php echo C('novel_author',null,'data');?></td>
					</tr>
					<tr>
					    <td valign="top"><b>推荐标题：</b></td>
						<td valign="top"><input name="rec[rec_rt]" type="text" class="input-text" value="<?php echo C('rec_rt',null,'data');?>"></td>
					</tr>
					<tr>
					    <td valign="top"><b>电脑推荐大图：</b></td>
						<td valign="top">
							<input type="text" name="rec[rec_img4]" value="<?php echo C('rec_img4',null,'data');?>" size="15">
							<span class="upload" data-uploader="index.php?a=yes&c=upload&t=img" data-on-upload-success="<?php echo $cFun;?>upload_success" data-file-type-exts="*.jpg;*.png;*.gif;*.mpg" data-toggle="upload" data-icon="cloud-upload"></span>
							<?php if( C('rec_img4',null,'data') != '' ){ echo '<a target="_blank" href="'.C('rec_img4',null,'data').'">点击查看图片</a>';}?>
						</td>
					</tr>
					<tr>
					    <td valign="top"><b>触屏推荐大图：</b></td>
						<td valign="top">
							<input type="text" name="rec[rec_img3]" value="<?php echo C('rec_img3',null,'data');?>" size="15">
							<span class="upload" data-uploader="index.php?a=yes&c=upload&t=img" data-on-upload-success="<?php echo $cFun;?>upload_success" data-file-type-exts="*.jpg;*.png;*.gif;*.mpg" data-toggle="upload" data-icon="cloud-upload"></span>
							<?php if( C('rec_img3',null,'data') != '' ){ echo '<a target="_blank" href="'.C('rec_img3',null,'data').'">点击查看图片</a>';}?>
						</td>
					</tr>
				    <tr>
				      <td valign="top"><b>推荐属性：</b></td>
				      <td valign="top">
                        <?php 
                        $i = '1';
                        foreach ($recArr as $k=>$v)
                        {
                        	$checked = str::CheckElse( C($k,null,'data') , 1 , 'checked');
                        	echo '<input type="checkbox" name="rec['.$k.']" value="1" data-toggle="icheck" data-label="'.$v.'" '.$checked.'>';
                        	if( $i == '3' )
                        	{
                        		echo '<br/>';
                        	}
                        	$i++;
                        }
                        ?>
                        </td>
					</tr>
					<tr>
					    <td valign="top"><b>显示顺序：</b></td>
						<td valign="top"><input size="10" name="rec[rec_order]" type="text" class="input-text" value="<?php echo C('rec_order',null,'data');?>"></td>
					</tr>
				</table>
			</div>
		</fieldset>
	</form>
</div>

<div class="bjui-pageFooter">
    <ul>
        <li><button type="button" class="btn-close" data-icon="close">取消</button></li>
        <li><button type="submit" class="btn-green" data-icon="save">保存</button></li>
    </ul>
</div>


<script>
//上传封面
function <?php echo $cFun;?>upload_success(file,json,$element){
	json = $.parseJSON(json);
	if ( json.statusCode == 300){
		$(this).bjuiajax("ajaxDone",json);// 显示处理结果
	}else{
		val = json.data.path.replace('../',"/");
		$element.siblings('input').val(val);
	}
}
function <?php echo $cFun;?>(json){
	$(this).bjuiajax("ajaxDone",json);// 显示处理结果
    $(this).dialog("closeCurrent");//关闭
    $(this).navtab("reload",novelNovelRecGetOp());	// 刷新当前Tab页面
}
</script>

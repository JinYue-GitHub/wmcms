<div class="bjui-pageContent">               
    <form action="index.php?a=yes&c=novel.sign&t=<?php echo $type;?>" data-reload="false" data-toggle="validate" method="post" <?php if($d) { echo 'data-callback="'.$cFun.$type.'"';}?>>
	<input name="nid" type="hidden" class="input-text" value="<?php echo $data['novel_id'];?>">
		<fieldset>
			<legend>《<?php echo $data['novel_name']?>》在线签约</legend>
			<ul class="nav nav-tabs" role="tablist">
            	<li class="active"><a href="#<?php echo $cFun.$type;?>base" role="tab" data-toggle="tab">基本信息</a></li>
            </ul>
            <div class="tab-content">
            	<div class="tab-pane fade active in" id="<?php echo $cFun.$type;?>base">
					<table class="table table-border table-bordered table-bg table-sort">
		            <tbody>
		                <tr>
		                    <td width="120"><b>小说名字：</b></td>
						  	<td>《<?php echo $data['novel_name']?>》（id:<?php echo $data['novel_id']?>）</td>
		                </tr>
		                <tr>
		                    <td width="120"><b>签约状态：</b></td>
						  	<td style="<?php if($data['novel_copyright'] >= 1 ){?>color:red<?php }?>">
						  	<?php if($data['novel_copyright'] >= 1 ){
						  		echo '该小说已签约';
						  	}else{
						  		echo '该小说暂未签约';
						  	}?></td>
		                </tr>
		                <tr>
		                    <td><b>签约类型：</b></td>
						  	<td>
			                    <input type="radio" name="copy" value="1" data-toggle="icheck" data-label="签约分成" <?php if($data['novel_copyright'] == 1 ){ echo 'checked';}?> <?php if($data['novel_copyright'] >= 1 ){ echo 'disabled';}?>>
			                    <input type="radio" name="copy" value="2" data-toggle="icheck" data-label="买断版权" <?php if($data['novel_copyright'] == 2 ){ echo 'checked';}?> <?php if($data['novel_copyright'] >= 1 ){ echo 'disabled';}?>>
		                    </td>
		                </tr>
		                <tr id="<?php echo $cFun.$type?>sid"<?php if($data['novel_copyright'] == 2 ){ echo 'style="display:none"';}?>>
		                    <td><b>签约等级：</b></td>
						  	<td>
			                    <select data-toggle="selectpicker" name="sid" <?php if($data['novel_copyright'] >= 1 ){ echo 'disabled';}?>>
			                    	<option value="0">请选择签约等级</option>
			                    	<?php foreach ($signArr as $k=>$v)
			                    	{
			                    		$selected = str::CheckElse($v['sign_id'], $data['novel_sign_id'],'selected=""');
			                    		echo '<option value="'.$v['sign_id'].'" '.$selected.'>'.$v['sign_name'].'</option>';
			                    	}
			                    	?>
	                            </select>
		                    </td>
		                </tr>
		            </tbody>
		        	</table>
				</div>
			</fieldset>
		</form>
	</div>
</div>

<div class="bjui-pageFooter">
    <ul>
        <li><button type="button" class="btn-close" data-icon="close">取消</button></li>
        <li><button type="submit" class="btn-green" data-icon="save"><?php if($data['novel_copyright'] >= 1 ){ echo '取消签约';}else{echo '立即签约';}?></button></li>
    </ul>
</div>


<script>
//页面唯一回调函数
function <?php echo $cFun.$type;?>(json){
	$(this).bjuiajax("ajaxDone",json);// 显示处理结果
	if(json.statusCode == '200'){
		$(this).dialog('refresh','novel-sign-add');
	}
}
$('input[name="copy"]').on('ifChanged', function(e) {
    var checked = $(this).is(':checked'), val = $(this).val();
    if (val == 1){
        $('#<?php echo $cFun.$type?>sid').show();
    }else{
        $('#<?php echo $cFun.$type?>sid').hide();
	}
})
</script>
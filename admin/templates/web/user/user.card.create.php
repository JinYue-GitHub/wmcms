<div class="bjui-pageContent">	                       
    <form action="index.php?a=yes&c=user.card&t=create" data-reload="false" data-toggle="validate" method="post">
		<fieldset>
			<legend>卡号信息编辑</legend>
            <ul class="nav nav-tabs" role="tablist">
            	<li class="active"><a href="#<?php echo $cFun;?>base" role="tab" data-toggle="tab">基本信息</a></li>
            </ul>
            <div class="tab-content">
            	<div class="tab-pane fade active in" id="<?php echo $cFun;?>base">
		    		<table class="table table-border table-bordered table-bg table-sort">
			            <tbody>
			                <tr>
				                <td colspan="2">
				                	<b>卡号类型：</b>
				                    <select data-toggle="selectpicker" id="<?php echo $cFun?>Type" name="type" data-rule="required;number">
		                            	<option value="">请选择卡号类型</option>
		                                <?php foreach ($cardArr as $k=>$v)
		                                {
		                                	echo '<option value="'.$k.'">'.$v.'</option>';
		                                }
		                                ?>
		                            </select>
				                </td>
			                </tr>
			                <tr id="<?php echo $cFun?>type_1" style="display: none">
			                   <td>
			                        <b>卡号金额：</b>
			                        <input type="text" name="money" size="15"  data-rule="required;number" value="100"> 元
			                   </td>
			                   <td>
			                        <b>赠送金额：</b>
			                        <input type="text" name="give" size="15"  data-rule="required;number" value="0"> <?php echo $userConfig['gold2_name'];?>
			                   </td>
			                </tr>
			                <tr>
			                    <td width="400">
			                        <b>生成条数：</b>
			                        <input type="text"  data-rule="required;digits" size="15" name="number" value="100">
			                    </td>
			                   <td>
			                        <b>发布渠道：</b>
			                        <input type="text" size="15" name="channel" placeholder="如：淘宝自动发号">
			                    </td>
			                </tr>
			                <tr>
			                   <td colspan="2" id="<?php echo $cFun;?>start">
			                        <b>开头字符：</b>
                            		<input type="radio" checked name="start" value="0" data-toggle="icheck" data-label="随机字符开头">
                            		<input type="radio" name="start" value="1" data-toggle="icheck" data-label="指定字符开头">
			                        <input type="text" size="10" name="start_str" readonly value="">
			                    </td>
			                </tr>
			                <tr>
			                   <td colspan="2">
			                        <b>生成位数：</b>
			                        <input type="radio" checked name="length" value="16" data-toggle="icheck" data-label="共16位字符">
                            		<input type="radio" name="length" value="32" data-toggle="icheck" data-label="共32位字符">
			                   </td>
			                </tr>
			            </tbody>
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
$(document).ready(function(){
	$("#<?php echo $cFun?>Type").change(function(){
		for(var i=1;i<=1;i++){
			$("#<?php echo $cFun?>type_"+i).hide();
			$('[name="money"]').val(0);
			$('[name="give"]').val(0);
		}
		$("#<?php echo $cFun?>type_"+$(this).val()).show();
	});

	$('#<?php echo $cFun;?>start input[type="radio"]').on('ifChanged', function(e) {
		 var checked = $(this).is(':checked'), val = $(this).val()
		if (checked && val == 1){
			$('#<?php echo $cFun;?>start [name="start_str"]').removeAttr("readonly");
		}else{
			$('#<?php echo $cFun;?>start [name="start_str"]').attr("readonly",'readonly');
		}
	});
});
</script>
<div class="bjui-pageContent">
	<form action="index.php?a=yes&c=system.set.logo&t=logo" method="post" data-toggle="validate">
	<fieldset>
		<legend>LOGO设置</legend>
        <ul class="nav nav-tabs" role="tablist">
			<li class="active"><a href="#<?php echo $cFun.$type;?>logo" role="tab" data-toggle="tab">LOGO设置</a></li>
			<li><a href="#<?php echo $cFun.$type;?>ewm" role="tab" data-toggle="tab">二维码设置</a></li>
        </ul>
        <div class="tab-content">
        	<div class="tab-pane fade active in" id="<?php echo $cFun.$type;?>logo">
		    	<table class="table table-border table-bordered table-bg table-sort">
			      	<tr>
		              	<td><b style="color: red">
		              	如果模版没有启用logo标签后台将无法更改logo图片<br/>
		              	LOGO位于/files/images下面
		              	</b>
		              	</td>
		            </tr>
			      	<tr>
		              	<td>
		             		<b>wap版LOGO：</b>
		            		<input type="text" name="239" value="<?php echo C('config.web.logo_1');?>" size="40">
							<span class="upload" data-uploader="index.php?a=yes&c=upload&t=logo&name=logo_1" data-on-upload-success="<?php echo $cFun;?>upload_success" data-file-type-exts="*.jpg;*.png;*.gif" data-toggle="upload" data-icon="cloud-upload"></span>
							<img src="<?php C('config.set.logo_1');?>" />
		                 </td>
		            </tr>
			      	<tr>
		              	<td>
		             		<b>3 g 版LOGO：</b>
		            		<input type="text" name="240" value="<?php echo C('config.web.logo_2');?>" size="40">
							<span class="upload" data-uploader="index.php?a=yes&c=upload&t=logo&name=logo_2" data-on-upload-success="<?php echo $cFun;?>upload_success" data-file-type-exts="*.jpg;*.png;*.gif" data-toggle="upload" data-icon="cloud-upload"></span>
							<img src="<?php C('config.set.logo_2');?>" />
		                 </td>
		            </tr>
			      	<tr>
		              	<td>
		             		<b>触屏版LOGO：</b>
		            		<input type="text" name="241" value="<?php echo C('config.web.logo_3');?>" size="40">
							<span class="upload" data-uploader="index.php?a=yes&c=upload&t=logo&name=logo_3" data-on-upload-success="<?php echo $cFun;?>upload_success" data-file-type-exts="*.jpg;*.png;*.gif" data-toggle="upload" data-icon="cloud-upload"></span>
							<img src="<?php C('config.set.logo_3');?>" />
		                 </td>
		            </tr>
			      	<tr>
		              	<td>
		             		<b>电脑版LOGO：</b>
		            		<input type="text" name="242" value="<?php echo C('config.web.logo_4');?>" size="40">
							<span class="upload" data-uploader="index.php?a=yes&c=upload&t=logo&name=logo_4" data-on-upload-success="<?php echo $cFun;?>upload_success" data-file-type-exts="*.jpg;*.png;*.gif" data-toggle="upload" data-icon="cloud-upload"></span>
							<img src="<?php C('config.set.logo_4');?>" />
		                 </td>
		            </tr>
		    	</table>
		    </div>
		    
		    
        	<div class="tab-pane fade" id="<?php echo $cFun.$type;?>ewm">
		    	<table class="table table-border table-bordered table-bg table-sort">
			      	<tr>
		              	<td><b style="color: red">
		              	上传的二维码位于/files/images下面
		              	</b>
		              	</td>
		            </tr>
			      	<tr>
		              	<td>
		             		<b>微信二维码：</b>
		            		<input type="text" name="246" value="<?php echo C('config.web.ewm_wx');?>" size="40">
							<span class="upload" data-uploader="index.php?a=yes&c=upload&t=logo&name=ewm_wx" data-on-upload-success="<?php echo $cFun;?>upload_success" data-file-type-exts="*.jpg;*.png;*.gif" data-toggle="upload" data-icon="cloud-upload"></span>
							<img src="<?php C('config.set.ewm_wx');?>" />
		                 </td>
		            </tr>
			      	<tr>
		              	<td>
		             		<b>支付宝二维码：</b>
		            		<input type="text" name="247" value="<?php echo C('config.web.ewm_alipay');?>" size="40">
							<span class="upload" data-uploader="index.php?a=yes&c=upload&t=logo&name=ewm_alipay" data-on-upload-success="<?php echo $cFun;?>upload_success" data-file-type-exts="*.jpg;*.png;*.gif" data-toggle="upload" data-icon="cloud-upload"></span>
							<img src="<?php C('config.set.ewm_alipay');?>" />
		                 </td>
		            </tr>
			      	<tr>
		              	<td>
		             		<b>Q群二维码：</b>
		            		<input type="text" name="249" value="<?php echo C('config.web.ewm_qun');?>" size="40">
							<span class="upload" data-uploader="index.php?a=yes&c=upload&t=logo&name=ewm_qun" data-on-upload-success="<?php echo $cFun;?>upload_success" data-file-type-exts="*.jpg;*.png;*.gif" data-toggle="upload" data-icon="cloud-upload"></span>
							<img src="<?php C('config.set.ewm_qun');?>" />
		                 </td>
		            </tr>
			      	<tr>
		              	<td>
		             		<b>微博二维码：</b>
		            		<input type="text" name="248" value="<?php echo C('config.web.ewm_weibo');?>" size="40">
							<span class="upload" data-uploader="index.php?a=yes&c=upload&t=logo&name=ewm_weibo" data-on-upload-success="<?php echo $cFun;?>upload_success" data-file-type-exts="*.jpg;*.png;*.gif" data-toggle="upload" data-icon="cloud-upload"></span>
							<img src="<?php C('config.set.ewm_weibo');?>" />
		                 </td>
		            </tr>
		        </table>
		   </div>
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
//上传图片成功后
function <?php echo $cFun;?>upload_success(file,json,$element){
	json = $.parseJSON(json);
	if ( json.statusCode == 300){
		$(this).bjuiajax("ajaxDone",json);// 显示处理结果
	}else{
		val = json.data.path.replace('../',"/");
		$element.siblings('input').val(val);
	}
}
</script>
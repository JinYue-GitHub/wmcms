<div class="bjui-pageContent">
	<form action="index.php?a=yes&c=system.set.water&t=config" method="post" data-toggle="validate">
	<fieldset>
		<legend>水印上传设置</legend>
    	<table class="table table-border table-bordered table-bg table-sort">
	      	<?php
            foreach ($configArr as $key=>$val)
            {
            	//是否输出表单
            	$EchoForm = true;
            	$form = $manager->GetForm( $val );
            	//上传类类型
            	if ( $val['config_name'] == 'upload_type' )
            	{
            		$form = '<input type="text" id="'.$val['config_name'].'" name="'.$val['config_id'].'" value="'.$val['config_value'].'" size="40">';
            	}
            	//上传大小
            	else if ( $val['config_name'] == 'upload_size' )
            	{
            		$form = '<input type="text" id="'.$val['config_name'].'" name="'.$val['config_id'].'" value="'.$val['config_value'].'" size="10"> KB';
            	}
            	//上传保存位置
            	else if ( $val['config_name'] == 'upload_savetype' )
            	{
            		$form = $manager->GetForm( $val ).' <span style="color:red">如果您选择的不是本地保存，就需要您先到API管理里面设置接口相关参数！</span>';
            	}
            	//上传保存位置
            	else if ( $val['config_name'] == 'upload_savepath' )
            	{
            		$form = $manager->GetForm( $val ).' <span style="color:red">默认为空，英文。不为空则表示远程保存路径或文件名由此开头！</span>';
            	}
            	//触发剪裁的尺寸
            	else if ( $val['config_name'] == 'upload_imgwidth' || $val['config_name'] == 'upload_imgheight' )
            	{
            		if ( $val['config_name'] == 'upload_imgwidth' )
            		{
            			$EchoForm = false;
            			$uploadImgWidth = $val['config_value'];
            			$uploadImgWidthId = $val['config_id'];
            		}
            		else
            		{
            			$val['config_title'] = '触发剪裁的图片尺寸';
            			$val['config_remark'] = '当图片尺寸大于设置的宽*高时就会自动进行剪裁';
            			$form = '<input type="text" name="'.$uploadImgWidthId.'" value="'.$uploadImgWidth.'" size="8"> * <input type="text" name="'.$val['config_id'].'" value="'.$val['config_value'].'" size="8">';
            		}
            	}
            	//剪裁后的尺寸
            	else if ( $val['config_name'] == 'upload_cutwidth' || $val['config_name'] == 'upload_cutheight' )
            	{
            		if ( $val['config_name'] == 'upload_cutwidth' )
            		{
            			$EchoForm = false;
            			$uploadImgWidth = $val['config_value'];
            			$uploadImgWidthId = $val['config_id'];
            		}
            		else
            		{
            			$val['config_title'] = '剪裁后的图片尺寸';
            			$val['config_remark'] = '图片剪裁的大小为此项设置的宽*高';
            			$form = '<input type="text" name="'.$uploadImgWidthId.'" value="'.$uploadImgWidth.'" size="8"> * <input type="text" name="'.$val['config_id'].'" value="'.$val['config_value'].'" size="8">';
            		}
            	}
            	//触发剪裁缩略图的原图宽高
            	else if ( $val['config_name'] == 'upload_simg_width' || $val['config_name'] == 'upload_simg_height' )
            	{
            		if ( $val['config_name'] == 'upload_simg_width' )
            		{
            			$EchoForm = false;
            			$uploadImgWidth = $val['config_value'];
            			$uploadImgWidthId = $val['config_id'];
            		}
            		else
            		{
            			//$val['config_title'] = '剪裁后的图片尺寸';
            			$val['config_remark'] = '触发生成缩略图的大小为此项设置的宽*高';
            			$form = '<input type="text" name="'.$uploadImgWidthId.'" value="'.$uploadImgWidth.'" size="8"> * <input type="text" name="'.$val['config_id'].'" value="'.$val['config_value'].'" size="8">';
            		}
            	}
            	//剪裁后的缩略图宽高
            	else if ( $val['config_name'] == 'upload_simgwidth' || $val['config_name'] == 'upload_simgheight' )
            	{
            		if ( $val['config_name'] == 'upload_simgwidth' )
            		{
            			$EchoForm = false;
            			$uploadImgWidth = $val['config_value'];
            			$uploadImgWidthId = $val['config_id'];
            		}
            		else
            		{
            			//$val['config_title'] = '剪裁后的图片尺寸';
            			$val['config_remark'] = '生成缩略图的大小为此项设置的宽*高';
            			$form = '<input type="text" name="'.$uploadImgWidthId.'" value="'.$uploadImgWidth.'" size="8"> * <input type="text" name="'.$val['config_id'].'" value="'.$val['config_value'].'" size="8">';
            		}
            	}
            	//触发水印的图片尺寸
            	else if ( $val['config_name'] == 'watermark_width' || $val['config_name'] == 'watermark_height' )
            	{
            		if ( $val['config_name'] == 'watermark_width' )
            		{
            			$EchoForm = false;
            			$uploadImgWidth = $val['config_value'];
            			$uploadImgWidthId = $val['config_value'];
            		}
            		else
            		{
            			$val['config_title'] = '触发水印的图片尺寸';
            			$val['config_remark'] = '当图片的宽*高大于此值的时候就加水印';
            			$form = '<input type="text" name="'.$uploadImgWidthId.'" value="'.$uploadImgWidth.'" size="8"> * <input type="text" name="'.$val['config_id'].'" value="'.$val['config_value'].'" size="8">';
            		}
            	}
            	//水印的位置
            	else if ( $val['config_name'] == 'watermark_location' )
            	{
            		$location = array(
            			'顶部居左','顶部居中','顶部居右',
            			'左边居中','图片中心','右边居中',
            			'底部居左','底部居中','底部居右',
            		);
            		$form = '<table class="table table-border table-bordered table-hover table-bg table-sort mt-10"><tr>';
            		for($i=1 ; $i<=9 ; $i++ )
            		{
            			$checked = '';
            			if( $val['config_value'] == $i )
            			{
            				$checked = 'checked';
            			}
            			$form .= '<td><input type="radio" name="'.$val['config_id'].'"  data-toggle="icheck" data-label="'.$location[$i-1].'" value="'.$i.'" '.$checked.'></td>';
            			if( $i == 6 || $i == 3)
            			{
            				$form .= '</tr><tr>';
            			}
            		}
            		$form .= '</tr></table>';
            	}
            	
            	if ( $EchoForm )
            	{
					echo '<tr>
	        		<td width="20%"><b>'.$val['config_title'].'</b><br />
					<span class="STYLE2">'.$val['config_remark'].'</span></td>
	                <td>'.$form.'</td>
	                </tr>';
            	}
            }
           ?>
           <tr>
        		<td width="20%">
        			<b>测试水印，需保存后生效</b><br/>
					<span class="STYLE2"><a class="btn btn-secondary radius" onclick="<?php echo $cFun;?>WaterTest()">立即生成</a></span>
				</td>
                <td><img id="<?php echo $cFun;?>WaterImg" style="display:none" src='' alt=" "></td>
           </tr>
    	</table>
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
//删除账号
function <?php echo $cFun;?>WaterTest()
{
	$("#<?php echo $cFun;?>WaterImg").hide();
	var ajaxOptions=new Array();
	ajaxOptions['type'] = 'GET';
	ajaxOptions['url'] = "index.php?a=yes&c=system.set.water&t=water_test";
	ajaxOptions['callback'] = "<?php echo $cFun;?>ajaxCallBack";
	$(".btn-secondary").bjuiajax('doAjax', ajaxOptions);
}
//页面唯一回调函数
function <?php echo $cFun;?>ajaxCallBack(json){
	if( json.statusCode == 200 ){
		var src = json.data.src;
		$("#<?php echo $cFun;?>WaterImg").show();
		$("#<?php echo $cFun;?>WaterImg").attr('src',src);
	}else{
		$(this).bjuiajax("ajaxDone",json);//显示处理结果
	}
}


$(function(){
    $("#watermark_color").colorpicker({
        fillcolor:true
    });

    <?php
    	if(C('config.web.watermark_type')=='img'){
    		echo '$("#watermark_text").attr("readonly","true");
			$("#watermark_color").attr("disabled","disabled");
			$("#watermark_size").attr("readonly","true"); ';
    	}
    ?>
    $('input[name="upload\\[watermark_type\\]"]').on('ifChanged', function(e) {
       var checked = $(this).is(':checked'), val = $(this).val()

       if (checked && val=='img'){
    	   $("#watermark_text").attr('readonly','true'); 
    	   $("#watermark_color").attr('disabled','disabled'); 
    	   $("#watermark_size").attr('readonly','true'); 
       }else if(checked && val=='text'){
    	   $("#watermark_text").removeAttr('readonly'); 
    	   $("#watermark_color").removeAttr('disabled'); 
    	   $("#watermark_size").removeAttr('readonly'); 
       }
    })
});
</script>
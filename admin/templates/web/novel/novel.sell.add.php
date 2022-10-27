<?php 
if( $data['novel_copyright'] < 1 )
{
	echo '<script type="text/javascript">$(document).ready(function(){$(this).alertmsg("info", "请先签约!")});</script>';
}
else
{
?>
<div class="bjui-pageContent">               
    <form action="index.php?a=yes&c=novel.sell&t=<?php echo $type;?>" data-reload="false" data-toggle="validate" method="post" <?php if($d) { echo 'data-callback="'.$cFun.$type.'"';}?>>
	<input name="data[sell_novel_id]" type="hidden" class="input-text" value="<?php echo $data['novel_id'];?>">
		<fieldset>
			<legend>《<?php echo $data['novel_name']?>》上架销售</legend>
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
		                    <td width="120"><b>上架状态：</b></td>
						  	<td style="<?php if($data['novel_sell'] == 1 ){?>color:red<?php }?>">
						  	<?php if($data['novel_sell'] == 1 ){
						  		echo '该小说已经上架销售';
						  	}else{
						  		echo '该小说暂未上架销售';
						  	}?></td>
		                </tr>
		                <tr>
		                    <td><b>收费方式：</b></td>
						  	<td>
			                    <input type="checkbox" name="data[sell_type][]" value="1" data-toggle="icheck" data-label="单章" <?php if( in_array('1',$sellType) ){ echo 'checked';}?>>
			                    <input type="checkbox" name="data[sell_type][]" value="2" data-toggle="icheck" data-label="全本" <?php if( in_array('2',$sellType) ){ echo 'checked';}?>>
			                    <input type="checkbox" name="data[sell_type][]" value="3" data-toggle="icheck" data-label="包月" <?php if( in_array('3',$sellType) ){ echo 'checked';}?>>
		                    </td>
		                </tr>
		                <tr>
		                    <td><b>单章价格：</b></td>
						  	<td>
			                    <input type="text" name="data[sell_number]" value="<?php echo C('sell_number',null,'sellData');?>" size="14" placeholder="请输入单章千字价格"> <?php echo $goldName?> / 每千字
			                </td>
		                </tr>
		                <tr>
		                    <td><b>全本价格：</b></td>
						  	<td>
			                    <input type="text" name="data[sell_all]" value="<?php echo C('sell_all',null,'sellData');?>" size="14" placeholder="请输入全本的价格"> <?php echo $goldName?> / 本
			                </td>
		                </tr>
		                <tr>
		                    <td><b>包月价格：</b></td>
						  	<td>
			                    <input type="text" name="data[sell_month]" value="<?php echo C('sell_month',null,'sellData');?>" size="14" placeholder="请输入包月的价格"> <?php echo $goldName?> / 月
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
        <li><button type="submit" class="btn-green" data-icon="save"><?php if($data['novel_sell'] == 1 ){ echo '立即下架';}else{echo '立即上架';}?></button></li>
    </ul>
</div>


<script>
//页面唯一回调函数
function <?php echo $cFun.$type;?>(json){
	$(this).bjuiajax("ajaxDone",json);// 显示处理结果
	if(json.statusCode == '200'){
		$(this).dialog('refresh','novel-sell-add');
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
<?php 
}
?>
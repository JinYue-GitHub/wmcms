<?php 
if( $novelData['novel_copyright'] < 1 || $novelData['novel_sell'] < 1 )
{
	echo '<script type="text/javascript">$(document).ready(function(){$(this).alertmsg("info", "请先签约且上架!")});</script>';
}
else
{
?>
<div class="bjui-pageContent">               
    <form action="index.php?a=yes&c=novel.sell.welfare&t=<?php echo $type;?>" data-reload="false" data-toggle="validate" method="post">
	<input name="id" type="hidden" class="input-text" value="<?php echo $data['welfare_id'];?>">
	<input name="data[welfare_nid]" type="hidden" class="input-text" value="<?php echo $data['welfare_nid'];?>">
		<fieldset>
			<legend>《<?php echo $novelData['novel_name']?>》福利设置</legend>
			<ul class="nav nav-tabs" role="tablist">
            	<li class="active"><a href="#<?php echo $cFun.$type;?>base" role="tab" data-toggle="tab">福利设置</a></li>
            </ul>
            <div class="tab-content">
            	<div class="tab-pane fade active in" id="<?php echo $cFun.$type;?>base">
					<table class="table table-border table-bordered table-bg table-sort">
		            <tbody>
		                <tr>
		                    <td width="120"><b>小说名字：</b></td>
						  	<td>《<?php echo $novelData['novel_name']?>》（id:<?php echo $novelData['novel_id']?>）</td>
		                </tr>
		                <tr>
		                    <td width="120"><b>字数奖励：</b></td>
						  	<td>
						  		<input type="text" name="data[welfare_number]" value="<?php echo C('welfare_number',null,'data');?>" size="14" placeholder="请输入价格"> <?php echo $goldName;?> / 每千字
						  	</td>
		                </tr>
		                <tr>
		                    <td width="120"><b>分成设置：</b></td>
						  	<td>
						  		<input type="checkbox" name="data[welfare_type][sub]" value="1" data-toggle="icheck" <?php if(C('welfare_type.sub',null,'data') == '1'){echo 'checked';}?> data-label="订阅分成">
						  		<input type="checkbox" name="data[welfare_type][prop]" value="1" data-toggle="icheck" <?php if(C('welfare_type.prop',null,'data') == '1'){echo 'checked';}?> data-label="礼物分成">
						  		<input type="checkbox" name="data[welfare_type][reward]" value="1" data-toggle="icheck" <?php if(C('welfare_type.reward',null,'data') == '1'){echo 'checked';}?> data-label="打赏分成">
						  	</td>
		                </tr>
		                <tr>
		                    <td width="120"><b>更新奖：</b></td>
						  	<td>
						  		<ul id="<?php echo $cFun;?>Update">
					    			<?php
							        if( intval(C('welfare_id',null,'data')) < 1 || !C('welfare_update',null,'data') )
							        {
							        	echo '<li style="height: 30px;"><input type="text" class="form-control" name="data[welfare_update][where][]" placeholder="字数" style="width:100px"> 万字
										&nbsp;&nbsp;<input type="text" class="form-control" name="data[welfare_update][val][]" placeholder="奖励" style="width:100px"> '.$goldName.'
										&nbsp;&nbsp;<a href="javascript:'.$cFun.'add(\''.$cFun.'Update\',\'data[welfare_update]\')"><i class="fa fa-plus-square"></i></a></li>';
							        }
							        else
							        {
							        	$i=1;
							        	$optionArr = $data['welfare_update'];
							        	foreach ($optionArr['where'] as $key=>$val)
							        	{
							        		echo '<li style="height: 30px;">';
							        		echo '<input type="text" class="form-control" name="data[welfare_update][where][]" placeholder="字数" value="'.$val.'" style="width:100px"> 万字
											&nbsp;&nbsp;<input type="text" class="form-control" name="data[welfare_update][val][]" placeholder="奖励" value="'.$optionArr['val'][$key].'" style="width:100px"> '.$goldName.'
											&nbsp;&nbsp;<a href="javascript:'.$cFun.'add(\''.$cFun.'Update\',\'data[welfare_update]\')"><i class="fa fa-plus-square"></i></a>';
							        		if( $i > 1)
							        		{
							        			echo '&nbsp;&nbsp;<a href="javascript:void(0)" onClick="'.$cFun.'unadd(this)"><i class="fa fa-minus-square"></i></a>';
							        		}
				                			echo '</li>';
				                			$i++;
							        	}
							        }
				                	?>
								</ul>
							</td>
		                </tr>
		                <tr>
		                    <td width="120"><b>全勤奖：</b></td>
						  	<td>
						  		<ul id="<?php echo $cFun;?>Full">
					    			<?php
							        if( intval(C('welfare_id',null,'data')) < 1 )
							        {
							        	echo '<li style="height: 30px;"><input type="text" class="form-control" name="data[welfare_full][where][]" placeholder="出勤天数" style="width:100px"> 天数
										&nbsp;&nbsp;<input type="text" class="form-control" name="data[welfare_full][val][]" placeholder="奖励" style="width:100px"> '.$goldName.'
										&nbsp;&nbsp;<a href="javascript:'.$cFun.'add(\''.$cFun.'Full\',\'data[welfare_full]\')"><i class="fa fa-plus-square"></i></a></li>';
							        }
							        else
							        {
							        	$i=1;
							        	$optionArr = $data['welfare_full'];
							        	foreach ($optionArr['where'] as $key=>$val)
							        	{
							        		echo '<li style="height: 30px;">';
							        		echo '<input type="text" class="form-control" name="data[welfare_full][where][]" placeholder="出勤天数" value="'.$val.'" style="width:100px"> 天数
											&nbsp;&nbsp;<input type="text" class="form-control" name="data[welfare_full][val][]" placeholder="奖励" value="'.$optionArr['val'][$key].'" style="width:100px"> '.$goldName.'
											&nbsp;&nbsp;<a href="javascript:'.$cFun.'add(\''.$cFun.'Full\',\'data[welfare_full]\')"><i class="fa fa-plus-square"></i></a>';
							        		if( $i > 1)
							        		{
							        			echo '&nbsp;&nbsp;<a href="javascript:void(0)" onClick="'.$cFun.'unadd(this)"><i class="fa fa-minus-square"></i></a>';
							        		}
				                			echo '</li>';
				                			$i++;
							        	}
							        }
				                	?>
								</ul>
							</td>
		                </tr>
		                <tr>
		                    <td width="120"><b>完本奖：</b></td>
						  	<td>
						  		<ul id="<?php echo $cFun;?>Finish">
					    			<?php
							        if( intval(C('welfare_id',null,'data')) < 1 )
							        {
							        	echo '<li style="height: 30px;"><input type="text" class="form-control" name="data[welfare_finish][where][]" placeholder="字数" style="width:100px"> 万字
										&nbsp;&nbsp;<input type="text" class="form-control" name="data[welfare_finish][val][]" placeholder="奖励" style="width:100px"> '.$goldName.'
										&nbsp;&nbsp;<a href="javascript:'.$cFun.'add(\''.$cFun.'Finish\',\'data[welfare_finish]\')"><i class="fa fa-plus-square"></i></a></li>';
							        }
							        else
							        {
							        	$i=1;
							        	$optionArr = $data['welfare_finish'];
							        	foreach ($optionArr['where'] as $key=>$val)
							        	{
							        		echo '<li style="height: 30px;">';
							        		echo '<input type="text" class="form-control" name="data[welfare_finish][where][]" placeholder="字数" value="'.$val.'" style="width:100px"> 万字
											&nbsp;&nbsp;<input type="text" class="form-control" name="data[welfare_finish][val][]" placeholder="奖励" value="'.$optionArr['val'][$key].'" style="width:100px"> '.$goldName.'
											&nbsp;&nbsp;<a href="javascript:'.$cFun.'add(\''.$cFun.'Finish\',\'data[welfare_finish]\')"><i class="fa fa-plus-square"></i></a>';
							        		if( $i > 1)
							        		{
							        			echo '&nbsp;&nbsp;<a href="javascript:void(0)" onClick="'.$cFun.'unadd(this)"><i class="fa fa-minus-square"></i></a>';
							        		}
				                			echo '</li>';
				                			$i++;
							        	}
							        }
				                	?>
								</ul>
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
        <li><button type="submit" class="btn-green" data-icon="save">提交</button></li>
    </ul>
</div>


<script>
function <?php echo $cFun;?>add(id,inputName){
	var input1place = '字数';
	var input1text = '万字';
	var input2place = '奖励';
	var input2text = '<?php echo $goldName;?>';
	switch(id){
		case '<?php echo $cFun;?>Full':
			input1place = '出勤天数';
			input1text = '天数';
			break;
	}
	var html = '<li style="height: 30px;">'+
		'<input type="text" class="form-control" name="'+inputName+'[where][]" placeholder="'+input1place+'" style="width:100px;"> '+input1text+
		"\t"+'&nbsp;&nbsp;<input type="text" class="form-control" name="'+inputName+'[val][]" placeholder="'+input2place+'" style="width:100px;"> '+input2text+
		'&nbsp;&nbsp;<a href="javascript:<?php echo $cFun;?>add(\''+id+'\')"><i class="fa fa-plus-square"></i></a>'+
		'&nbsp;&nbsp;<a href="javascript:void(0)" onClick="<?php echo $cFun;?>unadd(this)"><i class="fa fa-minus-square"></i></a></li>';

	$("#"+id).append(html);
}
function <?php echo $cFun;?>unadd(obj){
	$(obj).parent().remove();
}
</script>
<?php 
}
?>
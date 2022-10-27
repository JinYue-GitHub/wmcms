<?php 
if( $novelData['novel_copyright'] < 1 || $novelData['novel_sell'] < 1 )
{
	echo '<script type="text/javascript">$(document).ready(function(){$(this).alertmsg("info", "请先签约且上架!")});</script>';
}
else
{
?>
<style>
.<?php echo $cFun?>Table tbody tr td{border: 1px solid #f3f3f3;padding:auto}
.<?php echo $cFun?>Table .titleTd{font-size:14px}
</style>
<div class="bjui-pageContent">
	<form action="index.php?a=yes&c=novel.sell.settlement&t=apply" data-reload="false" data-toggle="validate" method="post" data-confirm-msg="确定要提交<?php echo $year.'年'.$month.'月';?>的结算申请吗？">
	<input name="nid" type="hidden" value="<?php echo $nid;?>">
	<input name="total" type="hidden" value="<?php echo $total;?>">
	<input name="real" type="hidden" value="<?php echo $real;?>">
	<input name="year" type="hidden" value="<?php echo $year;?>">
	<input name="month" type="hidden" value="<?php echo $month;?>">
    <div style="margin:15px auto 0; width:96%;">
        <div class="row" style="padding: 0 8px;">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                    	<h3 class="panel-title"><i class="fa fa-bar-chart-o fa-fw"></i>《<?php echo $novelData['novel_name'];?>》作者的结算统计</h3>
                    </div>
                    <div class="panel-body" style="font-size: 18px">
                    	 <table class="table table-border table-bordered table-bg table-sort <?php echo $cFun?>Table">
					     <tr>
					        <td width="150" class="titleTd">选择日期</td>
					        <td class="titleTd" colspan="3">
					        	<select data-toggle="selectpicker" id="<?php echo $cFun;?>year">
				        		<?php
				        		for ($i=$year;$i>=$year-5;$i--)
				        		{
				        			$selected = str::CheckElse( $i,$year, 'selected=""');
				        			echo '<option value="'.$i.'" '.$selected.'>'.$i.'年</option>';
				        		}
				        		?>
					        	</select>
					        	<select data-toggle="selectpicker" id="<?php echo $cFun;?>month">
				        		<?php
				        		for ($i=date('n');$i>=1;$i--)
				        		{
				        			$selected = str::CheckElse( $i,$month, 'selected=""');
				        			echo '<option value="'.$i.'" '.$selected.'>'.$i.'月</option>';
				        		}
				        		?>
					        	</select>
					        	<button type="button" class="btn-blue" data-icon="search" onclick="<?php echo $cFun;?>select()">查看</button>
					        </td>
					      </tr>
					      <tr>
					        <td width="150" class="titleTd">结算状态</td>
					        <td class="titleTd" colspan="3">
				        	<?php 
			        		if($applyData)
			        		{
			        			if( $applyData['apply_status'] == '0' )
			        			{
			        				echo '<b style="color:red">结算申请中</b>';
			        			}
			        			else if( $applyData['apply_status'] == '1' )
			        			{
			        				echo '<b style="color:#a3aba3">已结算</b>';
			        			}
			        			else if( $applyData['apply_status'] == '2' )
			        			{
			        				echo '<b style="color:red">未通过原因：'.$applyData['apply_handle_remark'].'</b>';
			        			}
			        		}
			        		else
			        		{
			        			echo '<b style="color:green">未结算</b>';
			        		}
				        	?>
					        </td>
					      </tr>
					      <tr>
					        <td width="150" class="titleTd">小说总字数</td>
					        <td class="titleTd" colspan="3"><?php echo $novelData['novel_wordnumber'];?> 字</td>
					      </tr>
					      <tr>
					        <td width="150" class="titleTd">小说状态</td>
					        <td width="250" class="titleTd">连载中</td>
					        <td width="150" class="titleTd">完本奖励</td>
					        <td class="titleTd" colspan="3">
					        	<?php 
				        		if( $novelData['novel_process'] == '2' )
				        		{
				        			if( $finishNowNumer == 0 && $finishNowGold2 == 0 )
				        			{
										if( isset($finishLowGold2) )
										{
											echo '没有满足最低字数 '.$finishLowNumer.' 字，无法得到最低完本奖励 '.$finishLowGold2.' '.$goldName;
										}
										else
										{
											echo '暂未设置小说更新福利';
										}
				        			}
				        			else
				        			{
				        				echo '满足字数 '.$finishNowNumer.' 字，可以得到完本奖励 '.$finishNowGold2.' '.$goldName;
				        			}
				        		}
				        		else
				        		{
				        			echo '未完本，无法满足完本奖励';
				        		}
					        	?>
					        </td>
					      </tr>
					      <tr>
					        <td width="150" class="titleTd">更新统计</td>
					        <td width="250" class="titleTd">
					        	本月更新章节：<?php echo $updateChapterCount;?> 章<br/>本月更新字数：<?php echo $updateChapterNumber/10000;?> 万字
								<br/>-----------------------<br/>
					        	本月更新Vip章节：<?php echo $updateChapterVipCount;?> 章<br/>本月更新Vip字数：<?php echo $updateChapterVipNumber/10000;?> 万字
					        </td>
					        <td width="150" class="titleTd">更新奖励</td>
					        <td class="titleTd">
					        	<?php 
					        		if( $updateNowNumber == 0 && $updateNowGold2 == 0 )
				        			{
										if( isset($updateLowGold2) )
										{
											echo '没有满足最低更新字数 '.$updateLowDay.' 字，无法得到最低更新奖励 '.$updateLowGold2.' '.$goldName;
										}
										else
										{
											echo '暂未设置小说更新福利';
										}
				        			}
				        			else
				        			{
				        				echo '满足更新字数 '.$updateNowNumber.' 字，可以得到更新奖励 '.$updateNowGold2.' '.$goldName;
				        			}
					        	?>
					        </td>
					      </tr>
					      <tr>
					        <td width="150" class="titleTd">出勤统计</td>
					        <td width="250" class="titleTd"><?php echo $fullCount;?> 天</td>
					        <td width="150" class="titleTd">出勤奖励</td>
					        <td class="titleTd">
					        	<?php 
					        		if( $fullNowDay == 0 && $fullNowGold2 == 0 )
				        			{
										if( isset($fullLowGold2) )
										{
											echo '没有满足最低考勤天数 '.$fullLowDay.' 天，无法得到最低考勤奖励 '.$fullLowGold2.' '.$goldName;
										}
										else
										{
											echo '暂未设置小说更新福利';
										}
				        			}
				        			else
				        			{
				        				echo '满足考勤天数 '.$fullNowDay.' 天，可以得到考勤奖励 '.$fullNowGold2.' '.$goldName;
				        			}
					        	?>
					        </td>
					      </tr>
					      <tr>
					        <td width="150" class="titleTd">共计收入</td>
					        <td class="titleTd" colspan="3" style="color:red"><?php echo '<b id="'.$cFun.'total">'.$total.'</b> '.$goldName;?></td>
					      </tr>
					      <tr>
					        <td width="150" class="titleTd">额外奖金</td>
					        <td width="250" class="titleTd">
					        	<input size="7" <?php if($applyData && $applyData['apply_status'] != '2'){echo 'disabled';}?> name="bonus_gold2" type="text" class="input-text" placeholder="不扣奖金请填0" value="0"> <?php echo $goldName;?>
					        </td>
					        <td width="150" class="titleTd">奖励原因</td>
					        <td class="titleTd"><textarea name="bonus_remark" <?php if($applyData && $applyData['apply_status'] != '2'){echo 'disabled';}?> data-toggle="autoheight" cols="50" rows="1"></textarea></td>
					      </tr>
					      <tr>
					        <td width="150" class="titleTd">扣除奖金</td>
					        <td width="250" class="titleTd"><input size="7" <?php if($applyData && $applyData['apply_status'] != '2'){echo 'disabled';}?> name="deduct_gold2" type="text" class="input-text" placeholder="不扣奖金请填0" value="0"> <?php echo $goldName;?></td>
					        <td width="150" class="titleTd">扣除原因</td>
					        <td class="titleTd"><textarea <?php if($applyData && $applyData['apply_status'] != '2'){echo 'disabled';}?> name="deduct_remark" data-toggle="autoheight" cols="50" rows="1"></textarea></td>
					      </tr>
					      <tr>
					        <td width="150" class="titleTd">实际收入</td>
					        <td class="titleTd" colspan="3" style="color:green">
					        	<?php echo '<b id="'.$cFun.'real">'.$real.'</b> '.$goldName;?>
					        	/
					        	<?php echo $financeConfig['rmb_to_gold2'];?>
					        	=
					        	<span id="<?php echo $cFun;?>real_money">0</span>元
					        </td>
					      </tr>
					    </table>
					</div>
                </div>
            </div>
        </div>
    </div>
	</form>
</div>


<div class="bjui-pageFooter">
    <ul>
        <li><button type="button" class="btn-close" data-icon="close">关闭</button></li>
        <?php 
        	if( $applyData )
        	{
	        	if( $applyData['apply_status'] == '0' )
	        	{
        			echo '<li><button type="button" class="btn-default">结算申请中</button></li>';
	        	}
	        	else if( $applyData['apply_status'] == '1' )
	        	{
        			echo '<li><button type="button" class="btn-default">已完成结算</button></li>';
	        	}
	        	else if( $applyData['apply_status'] == '2' )
	        	{
        			echo '<li><button type="submit" class="btn-green" data-icon="save">重新提交结算</button></li>';
	        	}
        	}
        	else
        	{
        		echo '<li><button type="submit" class="btn-green" data-icon="save">提交结算</button></li>';
        	}
        ?>
    </ul>
</div>



<script>
function <?php echo $cFun;?>select(){
	var year = $('#<?php echo $cFun;?>year').val();
	var month = $('#<?php echo $cFun;?>month').val();
	var obj = new Array();
	obj['type'] = 'GET';
	obj['url'] = 'index.php?d=yes&c=novel.sell.settlement&nid=<?php echo $nid;?>&year='+year+'&month='+month;
	$(this).navtab('reload', obj);
}

$(document).ready(function(){
	//奖励输入事件
	$("[name=bonus_gold2]").keyup(function(){
		var real = <?php echo $total;?>+parseInt($(this).val())-parseInt($("[name=deduct_gold2]").val());
		$('#<?php echo $cFun;?>real').html(real);
		$('#<?php echo $cFun;?>real_money').html(real/<?php echo $financeConfig['rmb_to_gold2'];?>);
		$('[name=real]').val(real);
	});
	//惩罚输入事件
	$("[name=deduct_gold2]").keyup(function(){
		var real = <?php echo $total;?>+parseInt($("[name=bonus_gold2]").val())-parseInt($(this).val());
		$('#<?php echo $cFun;?>real').html(real);
		$('#<?php echo $cFun;?>real_money').html(real/<?php echo $financeConfig['rmb_to_gold2'];?>);
		$('[name=real]').val(real);
	});
});
</script>
<?php 
}
?>
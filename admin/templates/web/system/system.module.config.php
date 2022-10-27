<style>
.<?php echo $cFun?>Table{margin-bottom:20px}
</style>

<div class="bjui-pageContent">
	<form name="form1" action="index.php?a=yes&c=system.module.config&t=edit&module=<?php echo $type?>" method="post" data-toggle="validate">
	
    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
      <thead><tr><th colspan="4" style="text-align:left;"><b>绑定模块功能</b></th></tr></thead>
      <tr>
        <td colspan="4">您当前模块只有绑定了相应的模块才能在模版里面使用绑定过的模块标签</td>
      </tr>
      <tr>
        <td width="200"><?php echo GetModuleName($type);?>绑定功能模块：</td>
       	<td colspan="3" style="line-height:30px">
        <?php
        	$i = 1;
       		foreach (GetModuleName('all.down') as $k=>$v)
       		{
       			$check = '';
       			if( in_array($k, $moduleArr) )
       			{
       				$check = 'checked';
       			}
       			echo '<input type="checkbox" name="module[]" value="'.$k.'" data-toggle="icheck" '.$check.' data-label="'.$v.'">';
       			if( $i % 7 == 0)
       			{
       				echo '<br/>';
       			}
       			$i++;
       		}
       	?>
       	</td>
      </tr>
     </table>
  </form>
</div>



<div class="bjui-pageFooter">
    <ul>
        <li><button type="button" class="btn-close" data-icon="close">取消</button></li>
        <li><button type="submit" class="btn-green" data-icon="save">保存</button></li>
    </ul>
</div>

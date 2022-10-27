<style>
.<?php echo $cFun?>Table{margin-bottom:20px}
</style>

<div class="bjui-pageContent">
	<form name="form1" action="index.php?a=yes&c=system.module.install&t=install" method="post" data-toggle="validate">
	
    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
      <thead><tr><th colspan="4" style="text-align:left;"><b>安装功能模块</b></th></tr></thead>
      <tr>
        <td width="200">安装功能模块：</td>
       	<td colspan="3" style="line-height:30px">
        <?php
        	$i = 1;
       		foreach ($moduleList as $k=>$v)
       		{
       			$check = '';
       			if( in_array($v['file'], $installModule) )
       			{
       				$check = 'checked';
       			}
       			echo '<input type="checkbox" name="module[]" value="'.$v['file'].'" data-toggle="icheck" '.$check.' data-label="'.$moduleMod->GetModuleName($v['file']).'">';
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

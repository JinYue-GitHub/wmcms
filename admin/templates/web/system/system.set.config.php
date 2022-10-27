<div class="bjui-pageContent">
	<form name="form1" action="index.php?a=yes&c=system.set.config&t=system" method="post" data-toggle="validate">
	<fieldset>
		<legend>基本设置[共<?php echo $configCount;?>项]</legend>
    	<table class="table table-border table-bordered table-bg table-sort">
	      	<?php
            foreach ($configArr as $key=>$val)
            {
            	if($val['config_id']!=396)
            	{
	            	$form = $manager->GetForm( $val );
	            	//如果等于域名设置
	            	if($val['config_id']==2)
	            	{
	            		$form = $manager->GetForm($configArr[396]).$form;
	            	}
					echo '<tr>
        			<td width="400"><b>'.$val['config_title'].'</b><br />
					<span class="STYLE2" id="help5">'.$val['config_remark'].'</span></td>
                    <td>'.$form.'</td>
                    </tr>';	
            	}
            }
           ?>
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
$(document).ready(function(){
	$("#closeinfo").css('height','80px');
	$("#closeinfo").css('width','300px');
});
</script>
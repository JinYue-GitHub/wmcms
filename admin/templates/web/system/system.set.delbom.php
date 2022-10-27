<div class="bjui-pageContent">
	<form name="form1" action="index.php?a=yes&c=system.set.delbom&t=delbom" method="post" data-reload="false" data-toggle="validate">
	<fieldset>
		<legend>去除文件BOM</legend>
    	<table class="table table-border table-bordered table-bg table-sort">
	      	<tr>
        		<td width="30%"><b>选择文件夹</b><br />
				<span class="STYLE2">需要删除BOM的文件夹(如果模版出现头部空出一行可使用)</span></td>
                <td>
                	<select data-toggle="selectpicker" name="path">
	                	<?php
	                	$floder = file::FloderList(WMTEMPLATE,array('ajax','site','system'));
	                	foreach ($floder as $k=>$v)
	                	{
	                		echo '<option value="'.$v['file'].'">'.$v['file'].'</option>';
	                	}
	                	?>
                	</select>
                </td>
            </tr>
	      	<tr>
                <td colspan="2">
               		<button type="submit" class="btn-secondary">确定删除</button>
                </td>
            </tr>
		</table>
	</fieldset>
	</form>
</div>
<div class="tableContent">
	  <form name="<?php echo $cFun;?>EditForm" action="index.php?a=yes&c=system.menu.menu&t=quickorder" data-toggle="validate" method="post">
	  <table class="table table-border table-bordered table-bg table-sort">
	    <tr>
	      <td colspan="9"><strong>快捷菜单设置</strong></td>
	    </tr>
	    <tr>
	      <td>菜单ID</td>
	      <td>菜单名字</td>
	      <td>菜单文件</td>
	      <td>菜单排序</td>
	    </tr>
	    
	    <?php 
	    if( $quickMenuArr )
	    {
		    foreach ($quickMenuArr as $k=>$v)
		    {
		    	echo '<tr>
			      <td>'.$v['quick_id'].'</td>
			      <td>'.$v['menu_title'].'</td>
			      <td>'.$v['menu_file'].'</td>
			      <td><input name="qucik['.$v['quick_id'].']" value="'.$v['quick_order'].'"  type="text" size="10"/></td>
			    </tr>';
		    }
		}
		else
		{
			echo '<tr><td colspan="9" style="text-align:center">暂无数据!</td></tr>';
		}
	    ?>
	    </table>
	  
		<div class="bjui-pageFooter">
		    <ul>
		        <li><button type="button" class="btn-close btn btn-danger size-MINI radius"><i class="fa fa-times"></i> 关闭</button></li>
		        <li><button type="submit" class="btn btn-success size-MINI radius"><i class="fa fa-floppy-o"></i> 提交更改</button></li>
		    </ul>
		</div>
	</form>
</div>
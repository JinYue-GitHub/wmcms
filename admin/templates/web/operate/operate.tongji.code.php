<div class="bjui-pageContent">
	<form name="form1" action="index.php?a=yes&c=operate.tongji&t=edit" method="post" data-toggle="validate">
    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
      <thead><tr><th colspan="4" style="text-align:left;"><b>模块基本设置</b></th></tr></thead>
      <tr>
        <td width="200">统计代码：</td>
        <td><?php echo $manager->GetForm('tongji' , 'tongji');?></td>
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




<script>
$(document).ready(function(){
	$('#tongji').css("width",'90%');
	$('#tongji').css("height",'400px');
});
</script>

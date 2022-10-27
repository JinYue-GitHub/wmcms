<style>
.<?php echo $cFun?>Table{margin-bottom:20px}
</style>

<div class="bjui-pageContent">
	<form name="form1" action="index.php?a=yes&c=system.safe.shield&t=config" method="post" data-toggle="validate">
    <table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
      <thead><tr><th colspan="4" style="text-align:left;"><b>检测启用设置</b></th></tr></thead>
      <tr>
        <td width="200">检查保留字符：</td>
        <td><?php echo $manager->GetForm('system' , 'check_shield');?></td>
      </tr>
      <tr>
        <td width="200">检查禁用字符：</td>
        <td><?php echo $manager->GetForm('system' , 'check_disable');?></td>
      </tr>
    </table>
	
	<table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
      <thead><tr><th colspan="4" style="text-align:left;"><b>敏感词库</b></th></tr></thead>
      <tr>
        <td width="250">全平台禁用的敏感词</td>
        <td><textarea name="disable" cols="70" rows="10"><?php echo $disable;?></textarea></td>
      </tr>
    </table>
	<table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
      <thead><tr><th colspan="4" style="text-align:left;"><b>保留词库</b></th></tr></thead>
      <tr>
        <td width="250">平台保留普通用户不能使用的关键词</td>
        <td><textarea name="shield" cols="70" rows="10"><?php echo $shield;?></textarea></td>
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
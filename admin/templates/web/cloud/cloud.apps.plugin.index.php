<?php
if( !$pluginData )
{
	die('<script type="text/javascript">$(document).ready(function(){$(this).alertmsg("info", "'.$errInfo.'")});$(this).navtab("closeCurrentTab");</script>');
}
?>
<style>
.<?php echo $cFun?>Table{margin-bottom:20px}
</style>

<div class="bjui-pageContent">
	<table class="table table-border table-bordered table-hover table-bg table-sort <?php echo $cFun?>Table">
      <thead><tr><th colspan="4" style="text-align:left;"><b>插件基本信息</b></th></tr></thead>
      <tr>
        <td width="200">插件名字</td>
        <td><?php echo $pluginData['plugin_name'];?></td>
      </tr>
      <tr>
        <td>插件路径</td>
        <td>/plugin/apps/<?php echo $pluginData['plugin_floder'];?>/</td>
      </tr>
      <tr>
        <td>插件作者</td>
        <td><?php echo $pluginData['plugin_author'];?></td>
      </tr>
      <tr>
        <td>插件版本</td>
        <td><?php echo $pluginData['plugin_version'];?></td>
      </tr>
      <tr>
        <td>插件安装时间</td>
        <td><?php echo date('Y-m-d H:i:s',$pluginData['plugin_time']);?></td>
      </tr>
      <tr>
        <td>插件首页网址</td>
        <td><?php echo $pluginIndexUrl;?></td>
      </tr>
      <tr>
        <td>插件首页二维码</td>
        <td><img src="<?php echo $qrcodePath;?>" alt='.' /></td>
      </tr>
    </table>
</div>
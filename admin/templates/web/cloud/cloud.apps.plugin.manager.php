<?php
if( !$pluginData )
{
	die('<script type="text/javascript">$(document).ready(function(){$(this).alertmsg("info", "'.$errInfo.'")});$(this).navtab("closeCurrentTab");</script>');
}
?>
<script type="text/javascript">
function do_open_layout(event, treeId, treeNode) {
    if (treeNode.isParent) {
        var zTree = $.fn.zTree.getZTreeObj(treeId)
        zTree.expandNode(treeNode)
        return
    }
    $('#<?php echo $cFun;?>Title').html(treeNode.name);
    $(event.target).bjuiajax('doLoad', {url:treeNode.url, target:treeNode.divid})
    event.preventDefault()
}
//进入页面加载插件首页
function <?php echo $cFun;?>Load(){
	$(this).bjuiajax('doLoad', {url:'index.php?d=yes&c=cloud.apps.plugin.index&t=index&id=<?php echo $id;?>', target:'#<?php echo $cFun;?>ManagerContent'})
}
<?php echo $cFun;?>Load();
</script>
<div class="bjui-pageContent">
    <div style="float:left; width:200px;">
        <ul id="layout-tree" class="ztree" data-toggle="ztree" data-expand-all="true" data-on-click="do_open_layout">
        	<?php 
        	foreach ($pluginMenu as $key=>$val)
        	{
        		echo '<li data-id="'.$key.'" data-pid="0">'.$val['name'].'</li>';
        		foreach ($val['menu'] as $k=>$v)
        		{
        			echo '<li data-id="'.$k.'" data-pid="'.$key.'" data-url="'.$v['action'].'" data-divid="#'.$cFun.'ManagerContent">'.$v['name'].'</li>';
        		}
        	}
        	?>
        </ul>
    </div>
    <div style="margin-left:210px; height:99.9%; overflow:hidden;">
        <div style="height:100%; overflow:hidden;">
            <fieldset style="height:100%;">
                <legend id="<?php echo $cFun;?>Title">插件首页</legend>
                <div id="<?php echo $cFun;?>ManagerContent" style="height:96%; overflow:hidden;">加载中...</div>
            </fieldset>
        </div>
    </div>
</div>
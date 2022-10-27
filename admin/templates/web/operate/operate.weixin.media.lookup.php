<div class="bjui-pageHeader">
    <form id="pagerForm" data-toggle="ajaxsearch" action="index.php?c=operate.weixin.media.lookup" method="post">
    <input type="hidden" name="pageSize" value="<?php echo $pageSize;?>">
	<input type="hidden" name="pageCurrent" value="<?php echo $pageCurrent;?>">
	<input type="hidden" name="orderField" value="<?php echo $orderField;?>">
	<input type="hidden" name="orderDirection" value="<?php echo $orderDirection;?>">
	<input type="hidden" name="st" value="<?php echo $st;?>">
        <div class="bjui-searchBar">
            <label>素材名字：</label><input type="text" placeholder="<?php echo $key;?>" name="key" size="15" />&nbsp;
            <button type="submit" class="btn-default" data-icon="search">查询</button>&nbsp;
            <a class="btn btn-orange" href="javascript:;" data-icon="undo">清空查询</a>&nbsp;
        </div>
    </form>
</div>

<div class="bjui-pageContent">
	<table class="table table-border table-bordered table-hover table-bg table-sort">
		<thead>
			<tr>
			<th width="8%" data-order-field="author_id">ID</th>
			<th width="15%">公众号</th>
			<th width="10%">类型</th>
			<th width="30%">素材名字</th>
            <th width="25%" style="text-align: center;">操作</th>
            </tr>
		</thead>
		<tbody>
			<?php
			if( $dataArr )
			{
				$i = 1;
				foreach ($dataArr as $k=>$v)
				{
					$cur = str::CheckElse( $i%2 , 0 , '' , 'even_index_row');
					
					echo '<tr class="'.$cur.'">
							<td style="text-align: center;">'.$v['media_id'].'</td>
							<td>'.$v['account_name'].'</td>
							<td>'.$mediaMod->type[$v['media_type']].'</td>
							<td>'.$v['media_filename'].'</td>
							<td style="text-align: center;">
            					<a href="'.$v['media_filepath'].'" target="_blank">查看文件</a>
								<a href="javascript:;" data-toggle="lookupback" data-args="{\'autoreply[autoreply_media_id]\':\''.$v['media_media_id'].'\'}" class="btn btn-blue" title="选择本项" data-icon="check">选择</a>
				            </td>
				          </tr>';
					$i++;
				}
			}
			else
			{
				echo '<script type="text/javascript">$(document).ready(function(){$(this).alertmsg("info", "没有数据了!")});</script>';
			}
			?>
		</tbody>
	</table>
</div>

<div class="bjui-pageFooter">
    <div class="pages">
        <span>每页&nbsp;</span>
        <div class="selectPagesize">
            <select data-toggle="selectpicker" data-toggle-change="changepagesize">
                <option value="20">20</option>
                <option value="30">30</option>
                <option value="60">60</option>
                <option value="120">120</option>
            </select>
        </div>
        <span>&nbsp;条，共 <?php echo $total;?> 条</span>
    </div>
    <div class="pagination-box" data-toggle="pagination" data-total="<?php echo $total;?>" data-page-size="<?php echo $pageSize;?>" data-pageCurrent="<?php echo $pageCurrent?>"></div>
</div>

<script>
$(document).ready(function(){
	$(".btn-orange").click(function(){
		$("#key").val("");
		$("#pagerForm").submit();
	});
});
</script>
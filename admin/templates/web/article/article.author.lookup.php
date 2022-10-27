<div class="bjui-pageHeader">
    <form id="pagerForm" data-toggle="ajaxsearch" action="index.php?c=article.author.lookup" method="post">
    <input type="hidden" name="pageSize" value="<?php echo $pageSize;?>">
	<input type="hidden" name="pageCurrent" value="<?php echo $pageCurrent;?>">
	<input type="hidden" name="orderField" value="<?php echo $orderField;?>">
	<input type="hidden" name="orderDirection" value="<?php echo $orderDirection;?>">
	<input type="hidden" name="st" value="<?php echo $st;?>">
        <div class="bjui-searchBar">
            <label>模版关键字：</label><input type="text" value="<?php echo $key;?>" name="key" size="10" />&nbsp;
            <button type="submit" class="btn-default" data-icon="search">查询</button>&nbsp;
            <a class="btn btn-orange" href="javascript:;" data-icon="undo">清空查询</a>&nbsp;
        </div>
    </form>
</div>

<div class="bjui-pageContent">
	<table class="table table-border table-bordered table-hover table-bg table-sort">
		<thead>
			<tr>
			<th width="15%" data-order-field="author_id">ID</th>
			<th width="25%" >类型</th>
			<th width="30%" >数据值</th>
			<th width="20%" data-order-field="author_data">数据量</th>
            <th width="10" style="text-align: center;">操作</th>
            </tr>
		</thead>
		<tbody>
			<?php
			if( $data )
			{
				$args = "article[article_author]";
				if( $st == 's' )
				{
					$args = "article[article_source]";
				}
				else if( $st == 'e')
				{
					$args = "article[article_editor]";
				}
				
				$i = 1;
				foreach ($data as $k=>$v)
				{
					$cur = str::CheckElse( $i%2 , 0 , '' , 'even_index_row');
					$author = '文章作者';
					if( $v['author_type'] == 's' )
					{
						$author = '文章来源';
					}
					else if( $v['author_type'] == 'e')
					{
						$author = '文章编辑';
					}
					
					echo '<tr class="'.$cur.'">
							<td style="text-align: center;">1</td>
							<td>'.$author.'</td>
							<td>'.$v['author_name'].'</td>
							<td>'.$v['author_data'].'</td>
							<td style="text-align: center;">
								<a href="javascript:;" data-toggle="lookupback" data-args="{\''.$args.'\':\''.$v['author_name'].'\'}" class="btn btn-blue" title="选择本项" data-icon="check">选择</a>
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
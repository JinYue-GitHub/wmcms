<form id="pagerForm" name="<?php echo $cFun;?>Form"  data-toggle="ajaxsearch" data-loadingmask="true" action="index.php?c=system.safe.errlog" method="post">
<input type="hidden" name="pageSize" value="<?php echo $pageSize;?>">
<input type="hidden" name="pageCurrent" value="<?php echo $pageCurrent;?>">
<input type="hidden" name="orderField" value="<?php echo $orderField;?>">
<input type="hidden" name="orderDirection" value="<?php echo $orderDirection;?>">
</form>

<div class="bjui-pageContent">
		<table class="table table-border table-bordered table-hover table-bg table-sort">
			<thead>
				<tr>
				<th style="text-align: center;" width="10%">ID</th>
				<th style="text-align: center;" width="35%">菜单位置</th>
				<th style="text-align: center;" width="20%">菜单标识</th>
				<th style="text-align: center;" width="20%">菜单文件</th>
				<th style="text-align: center;" width="15%">操作</th>
	            </tr>
			</thead>
			<tbody>
			<?php
			if( $menuArr )
			{
				$i = 1;
				foreach ($menuArr as $k=>$v)
				{
					$cur = str::CheckElse( $i%2 , 0 , '' , 'even_index_row');
					echo '<tr class="'.$cur.'">
							<td style="text-align: center;">'.$v['menu_id'].'</td>
							<td style="text-align: center;">'.$menuSer->GetParent($v['menu_pid']).str_replace($key, '<span style="color:red">'.$key.'</span>', $v['menu_title']).'</td>
							<td style="text-align: center;">'.$v['menu_name'].'</td>
							<td style="text-align: center;">'.$v['menu_file'].'</td>
							<td style="text-align: center;">
								<a class="btn btn-secondary radius size-MINI" href="index.php?c='.$v['menu_file'].'" data-id="serach-navtab" data-toggle="navtab" data-title="'.$v['menu_title'].'">打开页面</a>
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
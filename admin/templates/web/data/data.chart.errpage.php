<div class="bjui-pageContent">
    <div style="margin:15px auto 0; width:96%;">
        <div class="row" style="padding: 0 8px;">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                    	<h3 class="panel-title"><i class="fa fa-bar-chart-o fa-fw"></i>错误页面记录走势图</h3>
                    </div>
                    <div class="panel-body">
					    <div id="<?php echo $cFun;?>canvas"></div>
					</div>
                </div>
            </div>
        </div>
    </div>
</div>
 
<script type="text/javascript">
var chart = new AChart({
  <?php
  if(!$data){
  ?>
	  title: {
		text: '暂无数据'
	  },
  <?php }?>
  id : '<?php echo $cFun;?>canvas',
  height : 400,
  xAxis : {
	categories: [<?php foreach ($timeArr as $k=>$v){echo "'{$v}',";}?>]
  },
  yAxis : {
	min : 0
  },
  tooltip : {
	shared : true
  },
  seriesOptions : {
	  columnCfg : {}
  },
  series:[<?php
  if($data){
		$i=1;
		foreach ($data as $key=>$val)
		{
          	echo "{name:'{$key}',type:'line',stack: '次数',data:[";
          	foreach ($val as $k=>$v){echo "{$v},";}
          	echo ']}';
          	if( count($data) != $i )
          	{
          		echo ',';
          	}
			$i++;
		}
  }?>]
});

chart.render();
</script>

<div class="bjui-pageFooter">
    <ul>
        <li><button type="button" class="btn-close" data-icon="close">关闭</button></li>
    </ul>
</div>
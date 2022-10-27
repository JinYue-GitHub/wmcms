<div class="bjui-pageContent">
    <div style="margin:15px auto 0; width:96%;">
        <div class="row" style="padding: 0 8px;">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                    	<h3 class="panel-title"><i class="fa fa-bar-chart-o fa-fw"></i>日期查询</h3>
                    </div>
                    <div class="panel-body">
                    	请选择查询方式：
                    	<select data-toggle="selectpicker" id="<?php echo $cFun;?>Time">
                    	<?php
                    	foreach ($timeType as $k=>$v)
                    	{
                    		$select = str::CheckElse($k, $type , 'selected=""');
                       		echo '<option value="'.$k.'" '.$select.'>'.$v.'</option>';
                    	}
                    	?>
                    	</select>
					</div>
                </div>
            </div>
        </div>
    </div>
    
    <div style="margin:15px auto 0; width:96%;">
        <div class="row" style="padding: 0 8px;">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                    	<h3 class="panel-title"><i class="fa fa-bar-chart-o fa-fw"></i>
                    	<?php echo $nowTitle;?>数据增长走势图
                    	</h3>
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
	  columnCfg : {

	  }
  },
  series: [{
		  name: '用户',
		  data: [<?php foreach ($userArr as $k=>$v){echo "{$v},";}?>]

	  },{
		  name: '小说',
		  data: [<?php foreach ($novelArr as $k=>$v){echo "{$v},";}?>]

	  },{
		  name: '文章',
		  data: [<?php foreach ($articleArr as $k=>$v){echo "{$v},";}?>]

	  },{
		  name: '主题',
		  data: [<?php foreach ($bbsArr as $k=>$v){echo "{$v},";}?>]

	  },{
		  name: '留言',
		  data: [<?php foreach ($messageArr as $k=>$v){echo "{$v},";}?>]

	  },{
		name: '友链',
		data: [<?php foreach ($linkArr as $k=>$v){echo "{$v},";}?>]
	  }]

});

chart.render();

$(document).ready(function(){
	//选择查询时间
    $('#<?php echo $cFun;?>Time').change(function(){
    	var op = new Array();
    	op['url'] = '<?php echo $url?>&t='+$(this).val();
        $(this).navtab("reload",op);// 刷新Tab页面
    })
});
</script>

<div class="bjui-pageFooter">
    <ul>
        <li><button type="button" class="btn-close" data-icon="close">关闭</button></li>
    </ul>
</div>
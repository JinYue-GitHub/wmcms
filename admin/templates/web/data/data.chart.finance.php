<style>
.<?php echo $cFun?>Table tbody tr td{border: 1px solid #f3f3f3;font-size:20px}
.<?php echo $cFun?>Table .titleTd{font-size:14px}
.<?php echo $cFun?>Table .up{font-size:14px;color:red}
.<?php echo $cFun?>Table .down{font-size:14px;color:green}
.<?php echo $cFun?>Table .eq{font-size:14px;color:#b5b5b5}
</style>
<div class="bjui-pageContent">
    <div style="margin:15px auto 0; width:96%;">
        <div class="row" style="padding: 0 8px;">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                    	<h3 class="panel-title"><i class="fa fa-bar-chart-o fa-fw"></i>基本财务数据统计</h3>
                    </div>
                    <div class="panel-body" style="font-size: 18px">
                    	 <table class="table table-border table-bordered table-bg table-sort <?php echo $cFun?>Table">
					     <tr>
					        <td width="50" class="titleTd">日期</td>
					        <td width="150" class="titleTd">订单总金额</td>
					        <td width="150" class="titleTd">充值总金额</td>
					        <td width="150" class="titleTd">提现总金额</td>
					      	<td width="150" class="titleTd">盈利小计</td>
					      </tr>
					     <tr style="height:50px">
					        <td class="titleTd">今日</td>
					        <td><?php echo $data['today']['money']?><br/>
					        	<?php
					        	if($data['today']['money']-$data['yesterday']['money']>0)
					        	{
					        		echo '<span class="up">↑+'.str::GetPer($data['today']['money'],$data['yesterday']['money']).'%</span>';
					        	}
					        	else if($data['today']['money']==$data['yesterday']['money'])
					        	{
					        		echo '<span class="eq">=0.00%</span>';
					        	}
					        	else
					        	{
					        		echo '<span class="down">↓'.str::GetPer($data['today']['money'],$data['yesterday']['money']).'%</span>';
					        	}
					        	?>
					        </td>
					        <td><?php echo $data['today']['charge']?><br/>
					        	<?php
					        	if($data['today']['charge']-$data['yesterday']['charge']>0)
					        	{
					        		echo '<span class="up">↑+'.str::GetPer($data['today']['charge'],$data['yesterday']['charge']).'%</span>';
					        	}
					        	else if($data['today']['charge']==$data['yesterday']['charge'])
					        	{
					        		echo '<span class="up">=0.00%</span>';
					        	}
					        	else
					        	{
					        		echo '<span class="down">↓'.str::GetPer($data['today']['charge'],$data['yesterday']['charge']).'%</span>';
					        	}
					        	?>
					        </td>
					        <td><?php echo $data['today']['cash']?><br/>
					        	<?php
					        	if($data['today']['cash']-$data['yesterday']['cash']>0)
					        	{
					        		echo '<span class="up">↑+'.str::GetPer($data['today']['cash'],$data['yesterday']['cash']).'%</span>';
					        	}
					        	else if($data['today']['cash']==$data['yesterday']['cash'])
					        	{
					        		echo '<span class="eq">=0.00%</span>';
					        	}
					        	else
					        	{
					        		echo '<span class="down">↓'.str::GetPer($data['today']['cash'],$data['yesterday']['cash']).'%</span>';
					        	}
					        	?>
					        </td>
					      	<td><?php echo $data['today']['total'];?><br/>
					        	<?php
					        	if($data['today']['total']-$data['yesterday']['total']>0)
					        	{
					        		echo '<span class="up">↑+'.str::GetPer($data['today']['total'],$data['yesterday']['total']).'%</span>';
					        	}
					        	else if($data['today']['total']==$data['yesterday']['total'])
					        	{
					        		echo '<span class="eq">=0.00%</span>';
					        	}
					        	else
					        	{
					        		echo '<span class="down">↓'.str::GetPer($data['today']['total'],$data['yesterday']['total']).'%</span>';
					        	}
					        	?>
					        </td>
					      </tr>
					     <tr style="height:40px">
					        <td class="titleTd">昨日</td>
					        <td><?php echo $data['yesterday']['money']?></td>
					        <td><?php echo $data['yesterday']['charge']?></td>
					        <td><?php echo $data['yesterday']['cash']?></td>
					      	<td><?php echo $data['yesterday']['total']?></td>
					      </tr>
					     <tr style="height:40px;">
					        <td class="titleTd">全部</td>
					        <td><?php echo $data['all']['money']?></td>
					        <td><?php echo $data['all']['charge']?></td>
					        <td><?php echo $data['all']['cash']?></td>
					      	<td><?php echo $data['all']['total']?></td>
					      </tr>
					    </table>
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
                    	<h3 class="panel-title"><i class="fa fa-bar-chart-o fa-fw"></i>实时充值</h3>
                    </div>
                    <div class="panel-body">
					    <div id="<?php echo $cFun;?>timecanvas"></div>
					</div>
                </div>
            </div>
        </div>
    </div>
    
    
    <div style="margin:15px auto 0; width:96%;">
        <div class="row" style="padding: 0 8px;float:right;  width:50%">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                    	<h3 class="panel-title"><i class="fa fa-bar-chart-o fa-fw"></i>累积充值渠道统计</h3>
                    </div>
                    <div class="panel-body">
					    <div id="<?php echo $cFun;?>allTypecanvas"></div>
					</div>
                </div>
            </div>
        </div>
        
        <div class="row" style="padding: 0 8px;width:50%">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                    	<h3 class="panel-title"><i class="fa fa-bar-chart-o fa-fw"></i>今日充值渠道排行</h3>
                    </div>
                    <div class="panel-body">
					    <div id="<?php echo $cFun;?>todayTypecanvas"></div>
					</div>
                </div>
            </div>
        </div>
        
    </div>
</div>

<script type="text/javascript">
var chart = new AChart({
    id : '<?php echo $cFun;?>timecanvas',
    height : 350,
    xAxis: {
        categories: [<?php for($i=1;$i<=24;$i++){ echo "'{$i}点',";}?>]
    }, 
    series: [
        {
            name:'今日',
            data:[<?php foreach ($data['todayTime'] as $v){ echo "{$v},";}?>]
        },
        {
            name:'昨日',
            data:[<?php foreach ($data['yesterdayTime'] as $v){ echo "{$v},";}?>]
        }
    ]
  });
  chart.render();


  var todayTypechart = new AChart({
      id : '<?php echo $cFun;?>todayTypecanvas',
      height : 500,
      xAxis: {
    	  categories: [<?php if(!empty($data['todayTypeMoney'])){foreach ($data['todayTypeMoney'] as $k=>$v){ echo "'{$chargeMod->GetType($k)}',";}}?>],
          position : 'left', //x轴居左
          labels : {label : {'text-anchor' : 'start',x : 0,y : 0}}
      },
      invert : true,
      yAxis : {position : 'bottom',min : 0},
      seriesOptions : {columnCfg : {}},
      series: [{
              name:'今日',
              data:[<?php if(!empty($data['todayTypeMoney'])){foreach ($data['todayTypeMoney'] as $v){ echo "{$v},";}}?>]
      }]
    });
todayTypechart.render();
  
  var allTypechart = new AChart({
      theme : AChart.Theme.SmoothBase,
      id : '<?php echo $cFun;?>allTypecanvas',
      height : 500,
      legend : null ,//不显示图例
      seriesOptions : { //设置多个序列共同的属性
        pieCfg : {
          allowPointSelect : true,
          labels : {
            distance : 40,
          }
        }
      },
      tooltip : {
        pointRenderer : function(point){
          return (point.percent * 100).toFixed(2)+ '%【总充值'+point.value+'元 】';
        }
      },
      series : [{
          type: 'pie',
          name: '占比',
          data: [
			 <?php
			 if(!empty($data['allTypeMoney']))
			 {
				 foreach ($data['allTypeMoney'] as $k=>$v)
				 {
				 	echo "['".$chargeMod->GetType($k)."',{$v}],";
				 }
			 }
			 ?>
			]
      }]
    });
allTypechart.render();
</script>

<div class="bjui-pageFooter">
    <ul>
        <li><button type="button" class="btn-close" data-icon="close">关闭</button></li>
    </ul>
</div>
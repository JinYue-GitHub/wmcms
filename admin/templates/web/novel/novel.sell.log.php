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
                    	<h3 class="panel-title"><i class="fa fa-bar-chart-o fa-fw"></i>《<?php echo $data['novel_name'];?>》基本销售数据统计【签约等级：<b style="color:red"><?php echo $data['sign']['sign_name']?></b>，分成比例：<b style="color:red"><?php echo $data['sign']['sign_divide']?></b>】</h3>
                    </div>
                    <div class="panel-body" style="font-size: 18px">
                    	 <table class="table table-border table-bordered table-bg table-sort <?php echo $cFun?>Table">
                    	 <tr>
					        <td width="150" class="titleTd">选择日期</td>
					        <td class="titleTd" colspan="4">
					        	<form id="pagerForm" name="<?php echo $cFun;?>Form" data-toggle="ajaxsearch" data-loadingmask="true" action="<?php echo $url;?>" method="post">
					        		<input type="hidden" name="nid" value="<?php echo $nid;?>">
						        	<input type="text" name="start_time" value="<?php echo date('Y-m-d',$startTime);?>" data-toggle="datepicker" data-rule="required;date" size="15">
						        	 - 
									<input type="text" name="end_time" value="<?php echo date('Y-m-d',$endTime);?>" data-toggle="datepicker" data-rule="required;date" size="15">
						        	<button type="submit" class="btn-blue" data-icon="search" onclick="<?php echo $cFun;?>select()">查看</button>
					        	</form>
					        </td>
					     <tr>
					        <td width="50" class="titleTd">日期</td>
					        <td width="100" class="titleTd">订阅销售</td>
					        <td width="100" class="titleTd">打赏销售</td>
					        <td width="100" class="titleTd">道具销售</td>
					      	<td width="100" class="titleTd">盈利统计</td>
					      </tr>
					     <tr style="height:40px;">
					        <td class="titleTd">区间统计</td>
					        <td><?php echo $data['between']['sub'];?></td>
					        <td><?php echo $data['between']['props'];?></td>
					        <td><?php echo $data['between']['reward'];?></td>
					      	<td><?php echo $data['between']['all'];?></td>
					      </tr>
					     <tr style="height:40px;">
					        <td class="titleTd">全部</td>
					        <td><?php echo $data['all']['sub'];?></td>
					        <td><?php echo $data['all']['props'];?></td>
					        <td><?php echo $data['all']['reward'];?></td>
					      	<td><?php echo $data['all']['all'];?></td>
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
                    	<h3 class="panel-title"><i class="fa fa-bar-chart-o fa-fw"></i>今日/昨日销售数据统计</h3>
                    </div>
                    <div class="panel-body" style="font-size: 18px">
                    	 <table class="table table-border table-bordered table-bg table-sort <?php echo $cFun?>Table">
					     <tr>
					        <td width="50" class="titleTd">日期</td>
					        <td width="100" class="titleTd">订阅销售</td>
					        <td width="100" class="titleTd">打赏销售</td>
					        <td width="100" class="titleTd">道具销售</td>
					      	<td width="100" class="titleTd">盈利统计</td>
					      </tr>
					     <tr style="height:40px">
					        <td class="titleTd">今日</td>
					        <td><?php echo $data['sub']['today']?><br/>
					        	<?php
					        	if($data['sub']['today']-$data['sub']['yesterday']>0)
					        	{
					        		echo '<span class="up">↑+'.str::GetPer($data['sub']['today'],$data['sub']['yesterday']).'%</span>';
					        	}
					        	else if($data['sub']['today']==$data['sub']['yesterday'])
					        	{
					        		echo '<span class="eq">--0.00%</span>';
					        	}
					        	else
					        	{
					        		echo '<span class="down">↓'.str::GetPer($data['sub']['today'],$data['sub']['yesterday']).'%</span>';
					        	}
					        	?>
					        </td>
					        <td><?php echo $data['reward']['today']?><br/>
					        	<?php
					        	if($data['reward']['today']-$data['reward']['yesterday']>0)
					        	{
					        		echo '<span class="up">↑+'.str::GetPer($data['reward']['today'],$data['reward']['yesterday']).'%</span>';
					        	}
					        	else if($data['reward']['today']==$data['reward']['yesterday'])
					        	{
					        		echo '<span class="eq">--0.00%</span>';
					        	}
					        	else
					        	{
					        		echo '<span class="down">↓'.str::GetPer($data['reward']['today'],$data['reward']['yesterday']).'%</span>';
					        	}
					        	?>
					        </td>
					        <td><?php echo $data['props']['today']?><br/>
					        	<?php
					        	if($data['props']['today']-$data['props']['yesterday']>0)
					        	{
					        		echo '<span class="up">↑+'.str::GetPer($data['props']['today'],$data['props']['yesterday']).'%</span>';
					        	}
					        	else if($data['props']['today']==$data['props']['yesterday'])
					        	{
					        		echo '<span class="eq">--0.00%</span>';
					        	}
					        	else
					        	{
					        		echo '<span class="down">↓'.str::GetPer($data['props']['today'],$data['props']['yesterday']).'%</span>';
					        	}
					        	?>
					        </td>
					      	<td><?php echo $data['total']['today'];?><br/>
					        	<?php
					        	if($data['total']['today']-$data['total']['yesterday']>0)
					        	{
					        		echo '<span class="up">↑+'.str::GetPer($data['total']['today'],$data['total']['yesterday']).'%</span>';
					        	}
					        	else if($data['total']['today']==$data['total']['yesterday'])
					        	{
					        		echo '<span class="eq">--0.00%</span>';
					        	}
					        	else
					        	{
					        		echo '<span class="down">↓'.str::GetPer($data['total']['today'],$data['total']['yesterday']).'%</span>';
					        	}
					        	?>
					        </td>
					      </tr>
					     <tr style="height:40px">
					        <td class="titleTd">昨日</td>
					        <td><?php echo $data['sub']['yesterday'];?></td>
					        <td><?php echo $data['reward']['yesterday'];?></td>
					        <td><?php echo $data['props']['yesterday'];?></td>
					      	<td><?php echo $data['total']['yesterday'];?></td>
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
                    	<h3 class="panel-title"><i class="fa fa-bar-chart-o fa-fw"></i>实时销售数据</h3>
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
                    	<h3 class="panel-title"><i class="fa fa-bar-chart-o fa-fw"></i>累积销售方式统计</h3>
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
                    	<h3 class="panel-title"><i class="fa fa-bar-chart-o fa-fw"></i>今日销售方式排行</h3>
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
            data:[<?php foreach ($data['sub']['todayTime'] as $v){ echo "{$v},";}?>]
        },
        {
            name:'昨日',
            data:[<?php foreach ($data['sub']['yesterdayTime'] as $v){ echo "{$v},";}?>]
        }
    ]
  });
  chart.render();


  var todayTypechart = new AChart({
      id : '<?php echo $cFun;?>todayTypecanvas',
      height : 500,
      xAxis: {
    	  categories: [<?php foreach ($data['sell']['today'] as $k=>$v){ echo "'{$novelMod->GetSellType($k)}',";}?>],
          position : 'left', //x轴居左
          labels : {label : {'text-anchor' : 'start',x : 0,y : 0}}
      },
      invert : true,
      yAxis : {position : 'bottom',min : 0},
      seriesOptions : {columnCfg : {}},
      series: [{
              name:'今日',
              data:[<?php foreach ($data['sell']['today'] as $v){ echo "{$v},";}?>]
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
          return (point.percent * 100).toFixed(2)+ '%【总销售'+point.value+'】';
        }
      },
      series : [{
          type: 'pie',
          name: '占比',
          data: [
			 <?php
			 foreach ($data['sell']['all'] as $k=>$v)
			 {
			 	echo "['".$novelMod->GetSellType($k)."',{$v}],";
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
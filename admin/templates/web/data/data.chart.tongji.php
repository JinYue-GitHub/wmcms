<div class="bjui-pageContent">
    <div style="margin:15px auto 0; width:96%;">
        <div class="row" style="padding: 0 8px;">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                    	<h3 class="panel-title"><i class="fa fa-bar-chart-o fa-fw"></i>网站运行时间统计</h3>
                    </div>
                    <div class="panel-body" style="font-size: 18px">
                    	网站创建于：<?php echo $startTime;?>
                    	距离今日 共稳定运行<span style="color: red;font-size: 18px"><?php echo $runTime;?></span> 天
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
                    	<h3 class="panel-title"><i class="fa fa-bar-chart-o fa-fw"></i>数据统计图</h3>
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
    theme : AChart.Theme.SmoothBase,
    id : '<?php echo $cFun;?>canvas',
    height : 500,
    legend : null ,//不显示图例
    seriesOptions : { //设置多个序列共同的属性
      pieCfg : {
        allowPointSelect : true,
        labels : {
          distance : 40,
          label : {
            //文本信息可以在此配置
          },
          renderer : function(value,item){ //格式化文本
            return value + ' ' + (item.point.percent * 100).toFixed(2)  + '%';
          }
        }
      }
    },
    tooltip : {
      pointRenderer : function(point){
        return (point.percent * 100).toFixed(2)+ '%';
      }
    },
    series : [{
        type: 'pie',
        name: '总数据量',
        data: [
          ['用户量['+<?php echo $userCount;?>+'人]',<?php echo $userCount;?>],
          ['小说量['+<?php echo $novelCount;?>+'本]',<?php echo $novelCount;?>],
          ['主题量['+<?php echo $bbsCount;?>+'帖]',<?php echo $bbsCount;?>],
          ['文章量['+<?php echo $articleCount;?>+'篇]',<?php echo $articleCount;?>],
          ['留言量['+<?php echo $messageCount;?>+'条]',<?php echo $messageCount;?>],
          ['友链量['+<?php echo $linkCount;?>+'条]',<?php echo $linkCount;?>],
        ]
    }]
  });

  chart.render();
</script>

<div class="bjui-pageFooter">
    <ul>
        <li><button type="button" class="btn-close" data-icon="close">关闭</button></li>
    </ul>
</div>
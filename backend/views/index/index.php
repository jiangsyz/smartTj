<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use \kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '今日统计';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-header">
    <h1>实时概况
    </h1>
</div>
<div class="user-index">
    <div class="row">
        <div class="col-sm-3">
            <div  id="w6" class="panel panel-default">
                <div class="panel-heading">
                    <h3>访客数 : <?=$uv?></h3>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div id="w6" class="panel panel-default">
                <div class="panel-heading">
                    <h3>浏览量 : <?=$pv?></h3>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div id="w6" class="panel panel-default">
                <div class="panel-heading">
                    <h3>付款笔数 : <?=$order_count?></h3>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div id="w6" class="panel panel-default">
                <div class="panel-heading">
                    <h3>付款人数 : <?=$member_count?></h3>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div id="w6" class="panel panel-default">
                <div class="panel-heading">
                    <h3>付款金额 : <?=$pay_count?></h3>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div id="w6" class="panel panel-default">
                <div class="panel-heading">
                    <h3>转化率 : <?=$tran_rate?></h3>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div id="w6" class="panel panel-default">
                <div class="panel-heading">
                    <h3>客单价 : <?=$single_price?></h3>
                </div>
            </div>
        </div>
    </div>
</div>
<canvas height="100"  id="chart1"  ></canvas>
<div class="page-header">
    <h1>核心指标
    </h1>
</div>
<div class="panel panel-default">
    <div class="panel-body">
        <h4>选择日期</h4>
    </div>
    <div class="panel-footer"><div class="user-search">
            <?php $form = ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get',
            ]); ?>
            <div style="float: left; margin-right: 5px" class="form-group">
                <?php
                echo DatePicker::widget([
                    'name' => 'start_date',
                    'type' => DatePicker::TYPE_INPUT,
                    'value' => Yii::$app->request->get('start_date', $start_date),
                    'pluginOptions' => [
                        'autoclose'=>true,
                        'format' => 'yyyy-mm-dd'
                    ]
                ]);
                ?>
            </div>
            <div style="float: left; margin-right: 5px; margin-top:5px;" class="form-group">至</div>
            <div style="float: left; margin-right: 5px" class="form-group">
                <?php
                echo DatePicker::widget([
                    'name' => 'end_date',
                    'type' => DatePicker::TYPE_INPUT,
                    'value' => Yii::$app->request->get('end_date', $end_date),
                    'pluginOptions' => [
                        'autoclose'=>true,
                        'format' => 'yyyy-mm-dd'
                    ]
                ]);
                ?>
            </div>
            <div class="form-group">
                <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div></div>

</div>
<div class="panel panel-default">
<div class="panel-footer">
    <h4>总收入 : <?php echo array_sum($day_income);?></h4>
</div>
</div>

<canvas height="100"  id="chart2"  ></canvas>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
<?php
$hours = array_keys($today_hour_income);
$days = array_keys($day_income);

$today_income = array_values($today_hour_income);
$yesterday_hour_income = array_values($yesterday_hour_income);
$H = date('H');
foreach($today_income as $k=>$v){
    if($H<$k){
        unset($today_income[$k]);
    }
}

?>
<script>
    var ctx = document.getElementById("chart1");
    new Chart(ctx, {
        // The type of chart we want to create
        type: 'line',

        // The data for our dataset
        data: {
            labels: <?php echo json_encode($hours);?>,
            datasets: [
                {
                    label: "今日收入",
                    backgroundColor: 'rgba(61, 133, 198, 0.5)',
                    data: <?php echo json_encode($today_income);?>
                },
                {
                    label: "昨日收入",
                    backgroundColor:  'rgba(221, 126, 107, 0.5)',
                    data: <?php echo json_encode($yesterday_hour_income);?>
                }
            ]
        },

        // Configuration options go here
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true
                    }
                }]
            }
        }
    });

    var ctx2 = document.getElementById("chart2");
    new Chart(ctx2, {
        // The type of chart we want to create
        type: 'line',

        // The data for our dataset
        data: {
            labels: <?php echo json_encode($days);?>,
            datasets: [
                {
                    label: "每日收入",
                    borderColor: 'rgba(221, 126, 107)',
                    fill: false,
                    data: <?php echo json_encode(array_values($day_income));?>
                },
            ]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:true
                    }
                }]
            }
        }
    });
</script>
<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '统计';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
    <div class="row">
        <div class="col-sm-3">
            <div id="w6" class="panel panel-default">
                <div class="panel-heading">
                    <h3><i class="glyphicon glyphicon-yen"></i> 付款金额 : 10000</h3>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div id="w6" class="panel panel-default">
                <div class="panel-heading">
                    <h3><i class="glyphicon glyphicon-user"></i> 付款人数 : 10000</h3>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div id="w6" class="panel panel-default">
                <div class="panel-heading">
                    <h3><i class="glyphicon glyphicon-fire"></i> 付款笔数 : 10000</h3>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div id="w6" class="panel panel-default">
                <div class="panel-heading">
                    <h3><i class="glyphicon glyphicon-align-left"></i> 客单价 : 10000</h3>
                </div>
            </div>
        </div>
    </div>
</div>
<canvas height="100"  id="myChart"  ></canvas>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
<?php
$hours = array_keys($today_hour_income);
$today_income = array_values($today_hour_income);
$yesterday_hour_income = array_values($yesterday_hour_income);
?>
<script>
    var ctx = document.getElementById("myChart");
    var chart = new Chart(ctx, {
        // The type of chart we want to create
        type: 'line',

        // The data for our dataset
        data: {
            labels: <?php echo json_encode($hours);?>,
            datasets: [
                {
                    label: "今日",
                    backgroundColor: [
                        'rgba(61, 133, 198, 0.5)',
                    ],
                    data: <?php echo json_encode($today_income);?>
                },
                {
                    label: "昨日",
                    backgroundColor: [
                        'rgba(221, 126, 107, 0.5)',
                    ],
                    data: <?php echo json_encode($yesterday_hour_income);?>
                }
            ]
        },

        // Configuration options go here
        options: {}
    });
</script>
<?php
namespace backend\controllers;

use app\models\Sku;
use app\models\Spu;
use app\models\VirtualItem;
use backend\library\BaseController as Controller;
use backend\library\service\ItemService;
use backend\library\service\OrderService;
use backend\library\service\VipService;
use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;

class LogController extends Controller
{
    public $limit = 1000;

    public function actionIndex()
    {
        $date = $this->getGet('date', date('Y-m-d'));
        # 获取实收
        $amount = OrderService::getTotalPriceByDate($date, 1);
        # 获取销售数据
        $list = ItemService::getLogByDate($date);
        $spu_ids = $log_data = $pending_data = [];
        foreach ($list as $v) {
            $log_data[$v['sourceId']] = $v;
        }
        $sku_ids = array_keys($log_data);
        $skus = Sku::find()->where(['id' => $sku_ids])->asArray()->all();

        # 获取sku的spu名
        foreach ($skus as $sku) {
            $spu_ids[] = $sku['spuId'];
        }
        $spus = Spu::find()->select('id,title')->where(['id' => $spu_ids])->indexBy('id')->asArray()->all();

        $total_income = $total_buy_count = 0;
        foreach ($skus as $k => $sku) {
            $skus[$k]['name'] = ArrayHelper::getValue($spus, $sku['spuId'] . '.title', '') . $sku['title'];
            $skus[$k]['spu_id'] = ArrayHelper::getValue($spus, $sku['spuId'] . '.id', 0);

            $income = ArrayHelper::getValue($log_data, $sku['id'] . '.price', 0);
            $skus[$k]['income'] = $income;
            $total_income += $income;

            $buy_count = ArrayHelper::getValue($log_data, $sku['id'] . '.buy_count', 0);
            $skus[$k]['buy_count'] = $buy_count;
            $total_buy_count += $buy_count;
        }

        return $this->render('index', [
            'amount' => $amount,
            'total_income' => $total_income,
            'total_buy_count' => $total_buy_count,
            'dataProvider' => new ArrayDataProvider([
                'allModels' => $skus,
                'sort' => [
                    'attributes' => ['income', 'buy_count'],
                ],
                'pagination' => [
                    'pageSize' => $this->limit,
                ],
            ]),
        ]);
    }

    public function actionVip()
    {
        $date = $this->getGet('date', date('Y-m-d'));
        # 获取实收
        $amount = OrderService::getTotalPriceByDate($date, 0); 
        $vip_cards = VirtualItem::find()->asArray()->all();
        $log_data = [];
        # 获取销售数据
        $list = VipService::getLogByDate($date);
        foreach ($list as $v) {
            $log_data[$v['sourceId']] = $v;
        }
        $total_income = $total_buy_count = 0;
        foreach ($vip_cards as $k => $card) {
            $income = ArrayHelper::getValue($log_data, $card['id'] . '.price', 0);
            $vip_cards[$k]['income'] = $income;
            $total_income += $income;

            $buy_count = ArrayHelper::getValue($log_data, $card['id'] . '.buy_count', 0);
            $vip_cards[$k]['buy_count'] = $buy_count;
            $total_buy_count += $buy_count;
        }

        return $this->render('vip', [
            //  'searchModel' => $searchModel,
            'amount' => $amount,
            'total_income' => $total_income,
            'total_buy_count' => $total_buy_count,
            'dataProvider' => new ArrayDataProvider([
                'allModels' => $vip_cards,
                'sort' => [
                    'attributes' => ['income', 'buy_count'],
                ],
                'pagination' => [
                    'pageSize' => $this->limit,
                ],
            ]),
        ]);
    }
}

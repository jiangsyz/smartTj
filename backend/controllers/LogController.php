<?php
namespace backend\controllers;

use app\models\Sku;
use app\models\SkuMemberPrice;
use app\models\Spu;
use backend\library\BaseController as Controller;
use backend\library\service\LogService;
use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;

class LogController extends Controller
{
    public function actionIndex()
    {
        $date = $this->getGet('date',date('Y-m-d'));
        $skus = Sku::find()->asArray()->all();
        $sku_ids = $spu_ids = $logData = [];
        foreach ($skus as $sku) {
            $sku_ids[] = $sku['id'];
            $spu_ids[] = $sku['spuId'];
        }
        # 获取sku的spu名
        $spus = Spu::find()->select('id,title')->where(['id' => $spu_ids])->indexBy('id')->asArray()->all();
        # 获取今日销售数据
        $list = LogService::getLogByDate($date);
        foreach ($list as $v) {
            $logData[$v['sourceId']] = $v;
        }
        # 获取sku价格
        $sku_price = SkuMemberPrice::getData();
        foreach ($skus as $k => $sku) {
            $skus[$k]['name'] = ArrayHelper::getValue($spus, $sku['id'] . '.title', '') . $sku['title'];
            $skus[$k]['income'] = ArrayHelper::getValue($logData, $sku['id'] . '.price', 0);
            $skus[$k]['buy_count'] = ArrayHelper::getValue($logData, $sku['id'] . '.buy_count', 0);
            $skus[$k]['price'] = ArrayHelper::getValue($sku_price, $sku['id'] . '.0', 0);
            $skus[$k]['price_v1'] = ArrayHelper::getValue($sku_price, $sku['id'] . '.1', 0);
        }

        return $this->render('index', [
            //  'searchModel' => $searchModel,
            'dataProvider' => new ArrayDataProvider([
                'allModels' => $skus,
                'sort' => [
                    'attributes' => ['income', 'buy_count'],
                ],
                'pagination' => [
                    'pageSize' => 1000,
                ],
            ]),
        ]);
    }

}

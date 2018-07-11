<?php
namespace backend\controllers;

use app\models\Sku;
use app\models\Spu;
use backend\library\BaseController as Controller;
use backend\library\service\LogService;
use Yii;
use yii\data\ArrayDataProvider;

class LogController extends Controller
{
    public function actionIndex()
    {
        $skus = Sku::find()->asArray()->all();
        $sku_ids = $spu_ids = $logData = [];
        foreach ($skus as $sku) {
            $sku_ids[] = $sku['id'];
            $spu_ids[] = $sku['spuId'];
        }
        # 获取sku的spu名
        $spus = Spu::find()->select('id,title')->where(['id' => $spu_ids])->indexBy('id')->asArray()->all();
        # 获取今日销售数据
        $list = LogService::getLogByDate();
        foreach ($list as $v) {
            $logData[$v['sourceId']] = $v;
        }

        foreach ($skus as $k => $sku) {
            $skus[$k]['name'] = $spus[$sku['spuId']]['title'] . $sku['title'];
            $skus[$k]['price'] = !empty($logData[$sku['id']]) ? $logData[$sku['id']]['price'] : 0;
            $skus[$k]['buy_count'] = !empty($logData[$sku['id']]) ? $logData[$sku['id']]['buy_count'] : 0;
        }

        return $this->render('index', [
            //  'searchModel' => $searchModel,
            'dataProvider' => new ArrayDataProvider([
                'allModels' => $skus,
                'sort' => [
                    'attributes' => ['price', 'buy_count'],
                ],
                'pagination' => [
                    'pageSize' => 1000,
                ],
            ]),
        ]);
    }

}

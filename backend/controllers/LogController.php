<?php
namespace backend\controllers;

use app\models\Sku;
use app\models\SkuMemberPrice;
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
    protected $excel_title;

    public function actionIndex()
    {
        $date = $this->getGet('date', date('Y-m-d'));
        $format = $this->getGet('format','');
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
            $sku_price = SkuMemberPrice::find()->where(['skuId'=> $sku['id']])->andWhere(['lv' => 1])->asArray()->one();
            $skus[$k]['price'] = ArrayHelper::getValue($sku_price, 'price', 0);
            $skus[$k]['name'] = ArrayHelper::getValue($spus, $sku['spuId'] . '.title', '') . $sku['title'];
            $skus[$k]['spu_id'] = ArrayHelper::getValue($spus, $sku['spuId'] . '.id', 0);

            $income = ArrayHelper::getValue($log_data, $sku['id'] . '.price', 0);
            $skus[$k]['income'] = $income;
            $total_income += $income;

            $buy_count = ArrayHelper::getValue($log_data, $sku['id'] . '.buy_count', 0);
            $skus[$k]['buy_count'] = $buy_count;
            $total_buy_count += $buy_count;
        }
        # 获取当日退款金额
        $total_refund = ItemService::getRefundCount($date);

        if($format == 'excel'){
             $this->excel_title = '正善小程序商品销量';
             $this->outputExcel($skus,$date);
        }else{
            return $this->render('index', [
                'total_refund' => $total_refund,
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
    }

    public function actionVip()
    {
        $format = $this->getGet('format', '');
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
            if ($format == 'excel') {
                $vip_cards[$k]['name'] = $card['title'];
                $vip_cards[$k]['uniqueId'] = '';
            }
        }
        if ($format == 'excel') {
            $this->excel_title = '正善小程序会员卡销量';
            $this->outputExcel($vip_cards, $date);
        } else {
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

    public function outputExcel($skus,$date)
    {
        $objPhpExcel = new \PHPExcel();
        $fileName = $this->excel_title.$date. '.xls';

        //设值
        $objPhpExcel->getProperties()->setCreator("zs")
            ->setLastModifiedBy("zs")
            ->setTitle($this->excel_title)
            ->setSubject("")
            ->setDescription("")
            ->setKeywords("")
            ->setCategory("");

        $objActSheet = $objPhpExcel->getActiveSheet();


        //设置宽度
        $objActSheet->getColumnDimension('A')->setWidth(25);
        $objActSheet->getColumnDimension('B')->setWidth(25);
        $objActSheet->getColumnDimension('C')->setWidth(25);
        $objActSheet->getColumnDimension('D')->setWidth(25);
        $objActSheet->getColumnDimension('E')->setWidth(25);

        $objPhpExcel->getActiveSheet()->setCellValue('A1', '日期');
        $objPhpExcel->getActiveSheet()->setCellValue('B1', '商品编码');
        $objPhpExcel->getActiveSheet()->setCellValue('C1', '商品标题');
        $objPhpExcel->getActiveSheet()->setCellValue('D1', '商品价格');
        $objPhpExcel->getActiveSheet()->setCellValue('E1', '商品付款件数');

        //子产品清算明细
        $i = 1;
        foreach($skus as $val)
        {
            $i++;
            $objPhpExcel->getActiveSheet()->setCellValue('A' . $i, $date );
            $objPhpExcel->getActiveSheet()->setCellValueExplicit('B' . $i, $val['uniqueId'],\PHPExcel_Cell_DataType::TYPE_STRING);
            $objPhpExcel->getActiveSheet()->setCellValue('C' . $i, $val["name"]);
            $objPhpExcel->getActiveSheet()->setCellValue('D' . $i,  $val["price"]);
            $objPhpExcel->getActiveSheet()->setCellValue('E' . $i, $val["buy_count"]);
        }

        $objWriter = \PHPExcel_IOFactory::createWriter($objPhpExcel, 'Excel5');

        // $objWriter->save($fileName);
        ob_end_clean();
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header('Content-Disposition:attachment;filename="' . $fileName . '"');
        header("Content-Transfer-Encoding:binary");
        $objWriter->save('php://output');
    }
}

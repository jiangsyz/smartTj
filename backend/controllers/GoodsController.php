<?php
namespace backend\controllers;
use app\models\OrderRecord;
use app\models\Sku;
use backend\library\BaseController as Controller;
use backend\library\service\ItemService;
use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class GoodsController extends Controller
{
    public function actionStock()
    {
        $format = $this->getGet('format', '');
        $data = ItemService::getStockData();
        if ($format == 'excel') {
            $objPhpExcel = new \PHPExcel();
            $fileName = '小程序待发货商品.xls';

            //设值
            $objPhpExcel->getProperties()->setCreator("zs")
                ->setLastModifiedBy("zs")
                ->setTitle('小程序待发货商品')
                ->setSubject("")
                ->setDescription("")
                ->setKeywords("")
                ->setCategory("");

            $objActSheet = $objPhpExcel->getActiveSheet();


            //设置宽度
            $objActSheet->getColumnDimension('A')->setWidth(50);
            $objActSheet->getColumnDimension('B')->setWidth(25);
            $objActSheet->getColumnDimension('C')->setWidth(25);
            $objActSheet->getColumnDimension('D')->setWidth(25);
            $objActSheet->getColumnDimension('E')->setWidth(25);

            $objPhpExcel->getActiveSheet()->setCellValue('A1', '商品名称');
            $objPhpExcel->getActiveSheet()->setCellValue('B1', '商品编码');
            $objPhpExcel->getActiveSheet()->setCellValue('C1', '库存');
            $objPhpExcel->getActiveSheet()->setCellValue('D1', '待发货数量');
            $objPhpExcel->getActiveSheet()->setCellValue('E1', '配货中数量');

            //子产品清算明细
            $i = 1;
            foreach ($data as $val) {
                $i++;
                $objPhpExcel->getActiveSheet()->setCellValue('A' . $i, $val['name']);
                $objPhpExcel->getActiveSheet()->setCellValueExplicit('B' . $i, $val['uniqueId'], \PHPExcel_Cell_DataType::TYPE_STRING);
                $objPhpExcel->getActiveSheet()->setCellValue('C' . $i, $val["count"]);
                $objPhpExcel->getActiveSheet()->setCellValue('D' . $i, $val["buy_count"]);
                $objPhpExcel->getActiveSheet()->setCellValue('E' . $i, $val["prepare_count"]);
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
        } else {
            return $this->render('index', [
                'dataProvider' => new ArrayDataProvider([
                    'allModels' => $data,
                    'sort' => [
                        'attributes' => ['buy_count','prepare_count'],
                    ],
                    'pagination' => [
                        'pageSize' => $this->limit,
                    ],
                ]),
            ]);
        }
    }

    public function actionPendingDetail()
    {
        $sku_id = $this->getGet('sku_id', 1);
        $sku = Sku::find()->where(['id' => $sku_id])->one();
        if (!$sku) {
            throw new NotFoundHttpException('sku not found');
        }
        $sub_order = ItemService::getPendingDetail($sku_id);
        $sub_order_info = [];
        foreach($sub_order as $v){
            $sub_order_info[$v['orderId']] = $v['buyingCount'];
        }
        $sub_order_ids = array_keys($sub_order_info);
        $list = OrderRecord::find()->select('id,parentId')->where(['id' => $sub_order_ids])->asArray()->all();
        foreach($list as $k=>$v){
            $list[$k]['buyingCount'] = ArrayHelper::getValue($sub_order_info,$v['id'],0);
        }
        return $this->render('pending-detail', [
            'sku' => $sku,
            'dataProvider' => new ArrayDataProvider([
                'allModels' => $list,
                'pagination' => [
                    'pageSize' => 30,
                ],
            ]),
        ]);
    }

    /**
     * 待配货详情
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionPrepareDetail()
    {
        $sku_id = $this->getGet('sku_id', 1);
        $sku = Sku::find()->where(['id' => $sku_id])->one();
        if (!$sku) {
            throw new NotFoundHttpException('sku not found');
        }
        $sub_order = ItemService::getPrepareDetail($sku_id);
        $sub_order_info = [];
        foreach($sub_order as $v){
            $sub_order_info[$v['orderId']] = $v['buyingCount'];
        }
        $sub_order_ids = array_keys($sub_order_info);
        $list = OrderRecord::find()->select('id,parentId')->where(['id' => $sub_order_ids])->asArray()->all();
        foreach($list as $k=>$v){
            $list[$k]['buyingCount'] = ArrayHelper::getValue($sub_order_info,$v['id'],0);
        }
        return $this->render('prepare-detail', [
            'sku' => $sku,
            'dataProvider' => new ArrayDataProvider([
                'allModels' => $list,
                'pagination' => [
                    'pageSize' => 30,
                ],
            ]),
        ]);
    }
}
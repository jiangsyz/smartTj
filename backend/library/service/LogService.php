<?php
namespace backend\library\service;
use app\models\ActionTracker;
use Yii;

class LogService extends Service
{
    public static function getPvByDate($date)
    {
        try
        {
            $start_time = strtotime($date.' 00:00:00');
            if(empty($start_time)){
                throw new \Exception('date error');
            }
            $end_time = $start_time + 86400;
            $data = ActionTracker::find()->select('count(sourceId) as pv')->where([
                'sourceType' => 3,
            ])
                ->andWhere(['>=', 'requestTime', $start_time])
                ->andWhere(['<', 'requestTime', $end_time])
                ->asArray()
                ->one();
            return (empty($data['pv'])) ? 0 : $data['pv'];
        }
        catch(\Exception $e){

            return 0;
        }
    }

    public static function getUvByDate($date)
    {
        try
        {
            $start_time = strtotime($date.' 00:00:00');
            if(empty($start_time)){
                throw new \Exception('date error');
            }
            $end_time = $start_time + 86400;
            $data = ActionTracker::find()->select('count(distinct(sourceId)) as uv')->where([
                'sourceType' => 3,
            ])
                ->andWhere(['>=', 'requestTime', $start_time])
                ->andWhere(['<', 'requestTime', $end_time])
                ->asArray()
                ->one();
            return (empty($data['uv'])) ? 0 : $data['uv'];
        }
        catch(\Exception $e){

            return 0;
        }
    }
}
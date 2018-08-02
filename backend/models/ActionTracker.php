<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "action_tracker".
 *
 * @property int $id 主键
 * @property int $logId 日志id
 * @property int $sourceType 资源类型
 * @property int $sourceId 资源id
 * @property string $runningId 回调请求的runningId
 * @property string $controllerId controllerId
 * @property string $actionId actionId
 * @property string $desc 接口描述
 * @property string $data 日志内容
 * @property int $requestTime 请求时间
 * @property int $responseTime 应答时间
 * @property int $trackTime 追踪时间
 * @property int $runningTime 业务处理时间
 * @property string $result 业务处理结果
 */
class ActionTracker extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'action_tracker';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('log_db');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['logId', 'sourceType', 'sourceId', 'runningId', 'controllerId', 'actionId', 'desc', 'data', 'requestTime', 'trackTime', 'result'], 'required'],
            [['logId', 'sourceType', 'sourceId', 'requestTime', 'responseTime', 'trackTime', 'runningTime'], 'integer'],
            [['data'], 'string'],
            [['runningId', 'controllerId', 'actionId', 'desc', 'result'], 'string', 'max' => 200],
            [['logId'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'logId' => 'Log ID',
            'sourceType' => 'Source Type',
            'sourceId' => 'Source ID',
            'runningId' => 'Running ID',
            'controllerId' => 'Controller ID',
            'actionId' => 'Action ID',
            'desc' => 'Desc',
            'data' => 'Data',
            'requestTime' => 'Request Time',
            'responseTime' => 'Response Time',
            'trackTime' => 'Track Time',
            'runningTime' => 'Running Time',
            'result' => 'Result',
        ];
    }
}

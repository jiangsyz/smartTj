<?php
namespace backend\library;

use kartik\datetime\DateTimePicker;
use yii\helpers\Html;

class ActiveField extends \yii\widgets\ActiveField
{
    /**
     * 可以参考http://www.malot.fr/bootstrap-datetimepicker/demo.php
     */
    public function dateTimePicker($options = [])
    {
        $attribute = $this->attribute;
        $options = array_merge($this->inputOptions, $options);
        $this->adjustLabelFor($options);
        $pluginOptions = isset($options['plugin_options']) ? $options['plugin_options'] : array();
        $pluginOptions = $pluginOptions + [
                'autoclose' => true,
                'format' => 'yyyy-MM-dd HH:i:00',
                'todayHighlight' => true,
                'value_format' => 'Y-m-d H:i:s'
            ];
        $this->parts['{input}'] = DateTimePicker::widget([
                'name' => isset($options['name']) ? $options['name'] : Html::getInputName($this->model, $attribute),
                'options' => ['placeholder' => isset($options['placeholder']) ? $options['placeholder'] : '点击选择时间'],
                'convertFormat' => true,
                'type' => DateTimePicker::TYPE_INPUT,
                'pluginOptions' => $pluginOptions,
            ] + ($this->model->__get($attribute) ?
                ['value' => is_numeric($this->model->$attribute) ? date($pluginOptions['value_format'], $this->model->$attribute) : $this->model->$attribute]
                : []));

        return $this;
    }
}
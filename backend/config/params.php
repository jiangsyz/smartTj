<?php
use \kartik\datecontrol\Module;
return [
    'adminEmail' => 'admin@example.com',
    'dateControlDisplay' => [
        Module::FORMAT_DATE => 'yyyy-MM-dd',
        Module::FORMAT_TIME => 'hh:mm:ss a',
        Module::FORMAT_DATETIME => 'yyyy-MM-dd hh:mm:ss p',
    ],

    // format settings for saving each date attribute (PHP format example)
    'dateControlSave' => [
        Module::FORMAT_DATE => 'php:U', // saves as unix timestamp
        Module::FORMAT_TIME => 'php:H:i:s',
        Module::FORMAT_DATETIME => 'php:Y-m-d H:i:s',
    ]
];

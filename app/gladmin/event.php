<?php
// 事件定义文件
return [
    'bind' => [
    ],

    'listen' => [
        'AppInit'  => [
            \app\gladmin\listener\ViewInitListener::class,
        ],
        'HttpRun'  => [
            \app\gladmin\listener\ViewInitListener::class,
        ],
        'HttpEnd'  => [],
        'LogLevel' => [],
        'LogWrite' => [],
    ],

    'subscribe' => [
    ],
];

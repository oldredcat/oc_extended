<?php

return [

    'response_header'   => [
        'Access-Control-Allow-Origin: *',
        'Access-Control-Allow-Credentials: true',
        'Access-Control-Max-Age: 1000',
        'Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding',
        'Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE',
        'Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0',
        'Pragma: no-cache',
    ],

    'action_event'      => [
        'controller/*/before' => [
            'event/language.before',
        ],
        'controller/*/after' => [
            'event/language.after',
        ],
        'view/*/before'     => [
            'event/language',
            'event/document.before',
        ],
    ],

    'action_pre_action' => [
        //'test/test',
        //'startup/setting',
        //'startup/login',
    ],
];
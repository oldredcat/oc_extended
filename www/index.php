<?php

define(
    'START_UP' ,
    [
        'start_time'    => microtime(true),
        'start_mem'     => memory_get_usage(),
    ]
);

const DS = DIRECTORY_SEPARATOR;
const DIR_HTTP = __DIR__ . DS;

require_once '../red/startup.php';

start('store');
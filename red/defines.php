<?php
const DIR_ROOT      = __DIR__   . DS;
const DIR_APP       = DIR_ROOT  . 'app'     . DS;
const DIR_CONFIG    = DIR_ROOT  . 'config'  . DS;
const DIR_SYSTEM    = DIR_ROOT  . 'system'  . DS;
const DIR_ENGINE    = DIR_SYSTEM  . 'engine'  . DS;
const DIR_HELPER    = DIR_SYSTEM  . 'helper'  . DS;
const DIR_LIBRARY   = DIR_SYSTEM  . 'library' . DS;
const DIR_VENDOR    = DIR_SYSTEM  . 'vendor'  . DS;

// DB
const DB_ENGINE = 'mysqli';
const DB_HOST   = 'localhost';
const DB_USER   = 'root';
const DB_PASS   = '';
const DB_NAME   = 'red';
const DB_PORT   = '3306';
const DB_PREFIX = 'ext_';
const DB_DEBUG  = true;
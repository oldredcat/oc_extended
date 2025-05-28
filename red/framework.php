<?php

define('APP_NAME',        strtolower($application));
define('DIR_APPLICATION', DIR_APP         . APP_NAME . DS);
define('DIR_STORAGE',     DIR_APPLICATION . 'storage'    . DS);
define('DIR_CACHE',       DIR_STORAGE     . 'cache'      . DS);
define('DIR_LOGS',        DIR_STORAGE     . 'logs'       . DS);
define('DIR_SESSION',     DIR_STORAGE     . 'session'    . DS);

use \System\Engine\Action;
use \System\Engine\Config;
//use \System\Engine\Data;
use \System\Engine\Event;
use \System\Engine\Loader;
use \System\Engine\Registry;
use \System\Engine\Router;
use \System\Library\Cache;
use \System\Library\DB;
use \System\Library\Document;
use \System\Library\Language;
use \System\Library\Log;
use \System\Library\Request;
use \System\Library\Response;
use \System\Library\Session;
use \System\Library\Template;
use \System\Library\Url;

// Registry
$registry = new Registry();

// Config
$config = new Config();
$registry->set('config', $config);

date_default_timezone_set($config->get('date_timezone', 'UTC'));

// Log
if ($config->get('error_log', true)) {

    $log = new Log($config->get('error_filename', 'error.log'));
    $registry->set('log', $log);

    set_error_handler(
        function($code, $message, $file, $line) use($log, $config): bool
        {
            // error suppressed with @
            if (!(error_reporting() & $code)) return false;

            $error = match($code) {
                E_USER_NOTICE   => 'Notice',
                E_WARNING,
                E_USER_WARNING  => 'Warning',
                E_ERROR,
                E_USER_ERROR    => 'Fatal Error',
                default         => 'Unknown',
            };

            if ($config->get('error_display', true)) {
                echo '<b>' . $error . '</b>: ' . $message . ' in <b>' . $file . '</b> on line <b>' . $line . '</b>';
            }

            $log->write('PHP ' . $error . ':  ' . $message . ' in ' . $file . ' on line ' . $line);

            return true;
        }
    );
}

// Database
$registry->set('db', new DB(DB_ENGINE, DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT));

// Session
$registry->set('session', new Session($config->get('session_engine', 'file'), $registry));
if (isset($_COOKIE[$config->get('session_name', 'sid')])) {
    $session_id = $_COOKIE[$config->get('session_name', 'sid')];
} else {
    $session_id = '';
}
$registry->session->start($session_id);

// Cache
$registry->set('cache', new Cache($config->get('cache_engine', 'file')));

// Language
$registry->set('language', new Language($config->get('language_directory', 'en-gb')));

// Request
$registry->set('request', new Request());

// Response
$registry->set('response', new Response($registry));

// Url
$registry->set('url', new Url($config->get('site_url', ''), $_SERVER['HTTPS']));

// Loader
$registry->set('load', new Loader($registry));

// Document
$registry->set('document', new Document());

// Template
define('DIR_TEMPLATE', DIR_APPLICATION . 'theme' . DS . $config->get('template', 'default') . DS);
$registry->set('template', new Template($config->get('template_engine', 'twig')));

// Route
$route = new Router($registry);

// Event
$registry->set('event', new Event($registry));

// Event Register
if ($config->has('action_event')) {
    foreach ($config->get('action_event', []) as $key => $value) {
        foreach ($value as $priority => $action) {
            $registry->event->register($key, new Action($action), $priority);
        }
    }
}

// Pre Actions
if ($config->has('action_pre_action')) {
    foreach ($config->get('action_pre_action', []) as $value) {
        $route->addPreAction(new Action($value));
    }
}

// Dispatch
$route->dispatch(new Action($config->get('action_router', 'startup/router')), new Action($config->get('action_error', 'error/not_found')));

// Output
$registry->response->output();
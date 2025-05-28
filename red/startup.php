<?php

// Error Reporting
error_reporting(E_ALL);

// Check Version
if (version_compare(phpversion(), '8.1.0', '<')) exit('PHP8.1+ Required');

mb_internal_encoding('UTF-8');

// Windows IIS Compatibility
if (!isset($_SERVER['DOCUMENT_ROOT'])) {
    if (isset($_SERVER['SCRIPT_FILENAME'])) {
        $_SERVER['DOCUMENT_ROOT'] = str_replace('\\', '/', substr($_SERVER['SCRIPT_FILENAME'], 0, 0 - strlen($_SERVER['PHP_SELF'])));
    }
    if (isset($_SERVER['PATH_TRANSLATED'])) {
        $_SERVER['DOCUMENT_ROOT'] = str_replace('\\', '/', substr(str_replace('\\\\', '\\', $_SERVER['PATH_TRANSLATED']), 0, 0 - strlen($_SERVER['PHP_SELF'])));
    }
}

if (!isset($_SERVER['REQUEST_URI'])) {
    $_SERVER['REQUEST_URI'] = substr($_SERVER['PHP_SELF'], 1);
    if (isset($_SERVER['QUERY_STRING'])) {
        $_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
    }
}

if (!isset($_SERVER['HTTP_HOST'])) {
    $_SERVER['HTTP_HOST'] = getenv('HTTP_HOST');
}

// Check if SSL
if ((isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) || (isset($_SERVER['HTTPS']) && (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443))) {
    $_SERVER['HTTPS'] = true;
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
    $_SERVER['HTTPS'] = true;
} else {
    $_SERVER['HTTPS'] = false;
}

// Check IP if forwarded IP
if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
} elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CLIENT_IP'];
}

require_once 'defines.php';

// Autoloader
require_once(DIR_VENDOR . 'autoload.php');

spl_autoload_register(
    function(string $class): bool
    {
        $file = __DIR__ . DS . strtolower(str_replace('\\', DS, $class)) . '.php';
        if (!is_file($file)) return false;
        require_once $file;
        return true;
    }
);

function start(string $application): void
{
    require_once 'framework.php';
}
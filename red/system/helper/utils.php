<?php

namespace System\Helper;

class Utils
{
    public static function pr(mixed $data): void
    {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }

    public static function isDocument(string $route): bool
    {
        $result = false;
        $class = '\App\\' . ucfirst(APP_NAME) . '\Controller\\' . str_replace('/', '\\', ucwords($route, '/'));
        $parents = class_parents($class);
        foreach ($parents as $parent) {
            if (str_contains($parent, 'Document_Html')) {
                $result = true;
                break;
            }
        }
        return $result;
    }

    public static function parseRoute(string $route): array
    {
        $result = [];
        $route  = preg_replace('/[^a-zA-Z0-9_|\/\.]/', '', $route);
        $pos = strrpos($route, '.');
        if ($pos === false) {
            $result['route']  = $route;
            $result['method'] = 'index';
        } else {
            $result['route']  = substr($route, 0, $pos);
            $result['method'] = substr($route, $pos + 1);
        }
        return $result;
    }
}
<?php


$route = 'common/dashboard/test';
//$route = 'common';

$pos = strrpos($route, '.');
if ($pos) {
    $method = substr($route, $pos + 1);
    $route = substr($route, 0, $pos);
} else {
    $method = 'index';
}

$parts = explode('/', $route);

echo '<pre>';
print_r($parts);
echo '</pre>';

$breadcrumbs = [];
$__route = '';
$__text  = 'text_breadcrumb_';

if (count($parts) == 1) {
    $breadcrumbs[] = [
        'text' => $__text . $method,
        'href' => ($method == 'index') ? $parts[0] : $parts[0] . '.' . $method,
    ];
} elseif(count($parts) > 1) {
    for ($i = 0; $i < count($parts); $i++) {
        if ($i == 0) {
            $__route = $parts[$i];
            $breadcrumbs[] = [
                'text' => $__text . $method,
                'href' => ($method == 'index') ? $__route : $__route . '.' . $method,
            ];
            $__text .= $parts[$i];
        } else {
            $__route .= '/' .$parts[$i];
            $breadcrumbs[] = [
                'text' => $__text . '_' . $method,
                'href' => ($method == 'index') ? $__route : $__route . '.' . $method,
            ];
            $__text .= '_' . $parts[$i];
        }
    }
    array_shift($breadcrumbs);
}

/*
$__route = '';
$__text  = 'text_breadcrumb';
for ($i = 0; $i < count($_route); $i++) {
    if ($i > count($_route) - 2) {
        $__route .= $_route[$i];
        $__text  .= '_' . $method;
        $breadcrumbs[] = [
            'text' => $__text,
            'href' => $__route,
        ];
    } else {
        $__route .= $_route[$i] . '/';
        $__text  .= '_' . $_route[$i];
        $breadcrumbs[] = [
            'text' => $__text,
            'href' => $__route,
        ];
    }
}
*/
echo '<pre>';
print_r($breadcrumbs);
echo '</pre>';

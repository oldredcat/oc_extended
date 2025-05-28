<?php

namespace App\Default\Controller\Startup;

use System\Engine\Action;
use System\Engine\Controller;

class Router extends Controller
{
    public function index(): object|string|null
    {
        // Route
        if (isset($this->request->get['route']) && $this->request->get['route'] != 'startup/router') {
            $route = $this->request->get['route'];
        } else {
            $route = $this->config->get('action_default');
        }

        $data = [];

        // Sanitize the call
        $route = preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$route);

        $this->request->get['route'] = $route;

        // Trigger the pre events
        $result = $this->event->trigger('controller/' . $route . '/before', [&$route, &$data]);

        if (!is_null($result)) return $result;

        $action = new Action($route);

        $output = $action->execute($this->registry, $data);

        // Trigger the post events
        $result = $this->event->trigger('controller/' . $route . '/after', [&$route, &$output]);

        if (!is_null($result)) return $result;

        return $output;
    }
}
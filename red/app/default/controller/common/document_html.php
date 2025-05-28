<?php

namespace App\Default\Controller\Common;

use System\Engine\Controller;
use System\Engine\Registry;
use System\Helper\Utils;

class Document_Html extends Controller
{
    protected string $route;
    protected string $method;

    public function __construct(Registry $registry)
    {
        parent::__construct($registry);

        $parsed_route = Utils::parseRoute($this->request->get('route'));
        $this->route  = $parsed_route['route'];
        $this->method = $parsed_route['method'];

        $reflectionClass = new \ReflectionClass(get_called_class());
        if ($reflectionClass->getMethod($this->method)->class != get_called_class()) {
            if (empty($this->data['token'])) {
                $this->response->redirect($this->url->link('error/not_found'));
            } else {
                $this->response->redirect($this->url->link('error/not_found'), ['token' => $this->data['token']]);
            }
            exit;
        }

        $this->data['breadcrumbs'] = [];

        if (empty($this->data['token'])) {
            $this->data['breadcrumbs'][] = [
                'text' => $this->language->get('text_breadcrumb_home'),
                'href' => $this->url->link($this->config->get('action_default', 'common/home')),
            ];
        } else {
            $this->data['breadcrumbs'][] = [
                'text' => $this->language->get('text_breadcrumb_home'),
                'href' => $this->url->link($this->config->get('action_default', 'common/dashboard'), ['token' => $this->data['token']]),
            ];
        }

        $parts = explode('/', $this->route);

        $breadcrumbs = [];
        $__route = '';
        $__text  = 'text_breadcrumb_';

        if (count($parts) == 1) {
            $breadcrumbs[] = [
                'text' => $__text . $this->method,
                'href' => ($this->method == 'index') ? $parts[0] : $parts[0] . '.' . $this->method,
            ];
        } elseif(count($parts) > 1) {
            for ($i = 0; $i < count($parts); $i++) {
                $temp = [];
                if ($i == 0) {
                    $temp['text'] = $__text . $this->method;
                    $__text .= $parts[$i];
                    $__route = $parts[$i];
                } else {
                    $temp['text'] = $__text . '_' . $this->method;
                    $__text  .= '_' . $parts[$i];
                    $__route .= '/' .$parts[$i];
                }
                if (empty($this->data['token'])) {
                    $temp['href'] = ($this->method == 'index') ? $this->url->link($__route) : $this->url->link($__route . '.' . $this->method);
                } else {
                    $temp['href'] = ($this->method == 'index') ? $this->url->link($__route, ['token' => $this->data['token']]) : $this->url->link($__route . '.' . $this->method, ['token' => $this->data['token']]);
                }
                $breadcrumbs[] = $temp;
            }
            //array_shift($breadcrumbs);
        }

        $this->data['breadcrumbs'] += $breadcrumbs;
    }
}
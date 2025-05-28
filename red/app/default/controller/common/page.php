<?php

namespace App\Default\Controller\Common;

use System\Engine\Action;
use System\Engine\Controller;
use System\Engine\Registry;

class Page extends Controller
{
    public function __construct(Registry $registry)
    {
        parent::__construct($registry);

        //$this->document->setTitle($this->language->get('page_title'));
        $this->data['token'] = $this->session->get('token', '');

        $class = strtolower(get_called_class());
        $class = explode('controller\\', $class);
        $class = array_pop($class);
        $route = str_replace('\\', '/', $class);

        if (empty($this->data['token'])) {
            $home_link = $this->url->link($this->config->get('action_default', 'common/home'), '');
            $page_link = $this->url->link($route, '');
        } else {
            $home_link = $this->url->link($this->config->get('action_default', 'common/home'), ['token' => $this->data['token']]);
            $page_link = $this->url->link($route, ['token' => $this->data['token']]);
        }

        $this->data['breadcrumbs'] = [];
        $this->data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $home_link,
        ];

        if ($this->request->get['route'] != $this->config->get('action_default')) {
            $this->data['breadcrumbs'][] = [
                'text' => $this->language->get('text_breadcrumb_index'),
                'href' => $page_link,
            ];
        }

        $object = debug_backtrace()[1]['object'];

        if ($object instanceof Action) {
            $method = $object->getMethod();
        } else {
            $method = '';
        }

        if ($method && $method != 'index') {
            $this->data['breadcrumbs'][] = [
                'text' => $this->language->get('text_breadcrumb_' . $method),
                'href' => $page_link . '.' . $method,
            ];
        }

    }

    /*
    public function index(): void
    {
        $reflectionClass = new \ReflectionClass(get_called_class());
        if ($reflectionClass->getMethod('index')->class != get_called_class()) {
            $this->load->controller('error/not_found');
        }
    }
    */
}
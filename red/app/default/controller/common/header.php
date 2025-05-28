<?php

namespace App\Default\Controller\Common;

use System\Engine\Controller;

class Header extends Controller
{
    public function index(): string
    {
        $protocol = $this->request->server['HTTPS'] ? 'https://' : 'http://';
        $this->data['title'] = $this->document->getTitle();
        $this->data['base'] = $protocol . $this->request->server['SERVER_NAME'] . '/';
        $this->data['description'] = $this->document->getDescription();
        $this->data['keywords'] = $this->document->getKeywords();
        $this->data['links'] = $this->document->getLinks();
        $this->data['styles'] = $this->document->getStyles();
        $this->data['scripts'] = $this->document->getScripts();
        $this->data['lang'] = $this->language->get('code');
        $this->data['direction'] = $this->language->get('direction');
        return '';
    }
}

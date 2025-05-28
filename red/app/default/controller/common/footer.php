<?php

namespace App\Default\Controller\Common;

use System\Engine\Controller;

class Footer extends Controller
{
    public function index(): string
    {
        $this->data['scripts'] = $this->document->getScripts('footer');
        $this->data['styles'] = $this->document->getStyles('footer');
        return '';
    }
}
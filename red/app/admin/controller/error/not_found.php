<?php

namespace App\Admin\Controller\Error;

use App\Default\Controller\Common\Document_Admin;

final class Not_Found extends Document_Admin
{
    public function index(): void
    {
        parent::index();
        \System\Helper\Utils::pr($this->data);
        $this->response->addHeader('HTTP/1.1 404 Not Found');
        $this->response->setOutput($this->load->view('error/not_found', $this->data));
    }
}
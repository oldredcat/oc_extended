<?php

namespace App\Default\Controller\Error;

use App\Default\Controller\Common\Document_Html;

class Not_Found extends Document_Html
{
    public function index(): void
    {
        $this->response->addHeader('HTTP/1.1 404 Not Found');
        $this->response->setOutput($this->load->view('error/not_found', $this->data));
    }
}
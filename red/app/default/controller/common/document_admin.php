<?php

namespace App\Default\Controller\Common;

class Document_Admin extends Document_Html
{
    public function index(): void
    {
        $this->document->addStyle('/common/bootstrap/css/bootstrap.min.css');
        $this->document->addStyle('/common/fontawesome/css/all.min.css');
        $this->document->setTitle($this->language->get('page_title'));
        $this->data['column_left'] = $this->load->controller('common/column_left');
    }
}
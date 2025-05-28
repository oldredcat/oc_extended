<?php

namespace App\Admin\Controller\Common;

final class Footer extends \App\Default\Controller\Common\Footer
{
    public function index(): string
    {
        parent::index();
        return $this->load->view('common/footer', $this->data);
    }
}
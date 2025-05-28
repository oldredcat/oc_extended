<?php

namespace App\Admin\Controller\Common;

final class Header extends \App\Default\Controller\Common\Header
{
    public function index(): string
    {
        parent::index();
        return $this->load->view('common/header', $this->data);
    }
}
<?php

namespace App\Admin\Controller\Common;

use App\Default\Controller\Common\Document_Admin;

final class Dashboard extends Document_Admin
{
    public function index2(): void
    {

        \System\Helper\Utils::pr($this->data);
    }
}
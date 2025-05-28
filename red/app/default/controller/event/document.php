<?php

namespace App\Default\Controller\Event;

use System\Engine\Controller;
use System\Helper\Utils;

class Document extends Controller
{
    public function before(string &$route, array &$data, string &$code = ''): void
    {
        if (Utils::isDocument($route)) {
            $data['header'] = $this->load->controller('common/header');
            $data['footer'] = $this->load->controller('common/footer');
        }
    }
}
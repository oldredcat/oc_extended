<?php

namespace App\Default\Controller\Event;

use System\Engine\Controller;

class Language extends Controller
{
	public function index(string &$route, array &$data): void
    {
        foreach ($this->language->all() as $key => $value) {
            if (!isset($data[$key])) {
                $data[$key] = $value;
            }
        }
	}

    public function before(string &$route, array|null &$args = null): void
    {
        $this->language->backup();
    }

    public function after(string &$route, array|null &$args = null, string|null &$output = ''): void
    {
        $this->language->restore();
    }
}

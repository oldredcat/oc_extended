<?php

namespace App\Default\Controller\Startup;

class Setting extends \Engine\Controller
{
    public function index(): void
    {
        // @TODO store_id must be set
        $store_id = 0;
        $this->load->model('setting/setting');
        $settings = $this->model_setting_setting->getSettings('default');
        $settings = array_merge($settings, $this->model_setting_setting->getSettings(APP_NAME, $store_id));
        foreach ($settings as $key => $value) {
            $this->config->set($key, $value);
        }
    }
}
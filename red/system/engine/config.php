<?php

namespace System\Engine;

class Config extends Base
{
    /**
     * Constructor
     *
     */
    public function __construct()
    {
        $this->load('default');
        $this->load(APP_NAME);
    }

    /**
     * Load
     *
     * @param string $filename
     */
    protected function load(string $filename): array
    {
        $filename = str_replace(['\\', '/'], DS, $filename);
        $file = DIR_CONFIG . $filename . '.php';
        if (is_file($file)) $this->data = array_merge($this->data, include $file);
        return $this->data;
    }
}
<?php

namespace System\Engine;

/**
* Registry class
*/
final class Registry extends Base
{
    /**
     * __get
     *
     * https://www.php.net/manual/en/language.oop5.overloading.php#object.get
     *
     * @param    string  $key
     *
     * @return   object
     */
    public function __get(string $key): object|null
    {
        if (str_starts_with($key, 'model_')) {
            if (!$this->has($key)) {
                $route = substr($key, 6);
                $route = preg_replace('/_/', '/', $route, 1);
                $this->load->model($route);
            }
        }
        return $this->get($key);
    }

    /**
     * __set
     *
     * https://www.php.net/manual/en/language.oop5.overloading.php#object.set
     *
     * @param    string  $key
     * @param    object  $value
     *
     * @return   void
     */
    public function __set(string $key, object $value): void
    {
        $this->set($key, $value);
    }
}
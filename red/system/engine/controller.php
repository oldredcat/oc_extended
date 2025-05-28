<?php

namespace System\Engine;

/**
* Controller class
*/
abstract class Controller
{
    /**
     * @var object|\Engine\Registry
     */
	protected Registry $registry;

    /**
     * @var array
     */
    protected array $data = [];

    /**
     * @var array
     */
    protected array $errors = [];

	public function __construct(Registry $registry)
    {
		$this->registry = $registry;
        $class = get_called_class();
        $class = strtolower($class);
        $class = explode('controller\\', $class);
        $route = str_replace('\\', '/', array_pop($class));
        $this->data = array_merge($this->data, $registry->language->load($route));
        $this->data['token']  = $this->session->get('token');
	}

    /**
     * __get
     *
     * @param	string	$key
     *
     * @return object
     */
    public function __get(string $key): object
    {
        if ($this->registry->has($key)) {
            return $this->registry->get($key);
        } else {
            throw new \Exception('Error: Could not call registry key ' . $key . '!');
        }
    }

    /**
     * __set
     *
     * @param	string	$key
     * @param	object	$value
     *
     * @return void
     */
    public function __set(string $key, object $value): void
    {
        $this->registry->set($key, $value);
    }
}
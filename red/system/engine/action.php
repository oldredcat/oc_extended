<?php

namespace System\Engine;

use System\Helper\Utils;

class Action
{
    /**
     * @var string
     */
    private string $class;

    /**
     * @var string
     */
	private string $route;

    /**
     * @var string
     */
	private string $method;
	
	/**
	 * Constructor
	 *
	 * @param	string	$route
 	*/
	public function __construct(string $route)
    {
        $parsed_route = Utils::parseRoute($route);
        $this->route  = $route;
        $this->method = $parsed_route['method'];
        $this->class  = 'Controller\\' . str_replace('/', '\\', ucwords($parsed_route['route'], '_/'));
	}

    /**
     * getId
     *
     * @return    string
     *
     */
    public function getId(): string
    {
        return $this->route;
    }

    /**
     * getMethod
     *
     * @return    string
     *
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     *
     * Execute
     *
     * @param object $registry
     * @param array  $args
     *
     * @return    mixed
     */
	public function execute(Registry $registry, array &$args = []): object|string|null
    {
        // Stop any magical methods being called
        if (substr($this->method, 0, 2) == '__') {
            return new \Exception('Error: Calls to magic methods are not allowed!');
        }

        // Get the current namespace being used by the config
        $class = '\App\\' . ucfirst(APP_NAME) . '\\' . $this->class;
        if (!class_exists($class)) $class = '\App\Default\\' . $this->class;

        // Initialize the class
        if (class_exists($class)) {
            $controller = new $class($registry);
        } else {
            return new \Exception('Error: Could not call route ' . $this->route . '!');
        }

        if (is_callable([$controller, $this->method])) {
            return call_user_func_array([$controller, $this->method], $args);
        } else {
            return new \Exception('Error: Could not call route ' . $this->route . '!');
        }
	}
}

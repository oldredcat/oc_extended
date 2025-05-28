<?php

namespace System\Engine;

use System\Helper\Utils;

class Loader
{
    /**
     * @var object|Registry
     */
	protected Registry $registry;

	/**
	 * Constructor
	 *
	 * @param	object	$registry
 	*/
	public function __construct(Registry $registry)
    {
		$this->registry = $registry;
	}

    /**
     * __get
     *
     * https://www.php.net/manual/en/language.oop5.overloading.php#object.get
     *
     * @param string $key
     *
     * @return   object
     */
    public function __get(string $key): object
    {
        return $this->registry->get($key);
    }

    /**
     * __set
     *
     * https://www.php.net/manual/en/language.oop5.overloading.php#object.set
     *
     * @param string $key
     * @param object $value
     *
     * @return    void
     */
    public function __set(string $key, object $value): void
    {
        $this->registry->set($key, $value);
    }

    /**
     * Controller
     *
     * https://wiki.php.net/rfc/variadics
     *
     * @param string $route
     * @param array  $data
     *
     * @return    mixed
     */
    public function controller(string $route, mixed ...$args): mixed
    {
        // Sanitize the call
        $route = Utils::parseRoute($route)['route'];

        $output = '';

        // Keep the original trigger
        $action = new Action($route);

        while ($action) {
            $route = $action->getId();

            // Trigger the pre events
            $result = $this->event->trigger('controller/' . $route . '/before', [&$route, &$args]);

            if ($result instanceof Action) $action = $result;

            // Execute action
            $result = $action->execute($this->registry, $args);

            // Make action a non-object so it's not infinitely looping
            $action = '';

            // Action object returned then we keep the loop going
            if ($result instanceof Action) $action = $result;

            // If not an object then it's the output
            if (!$action) $output = $result;

            // Trigger the post events
            $result = $this->event->trigger('controller/' . $route . '/after', [&$route, &$args, &$output]);

            if ($result instanceof Action) $action = $result;
        }

        return $output;
    }

    /**
     * Model
     *
     * @param string $route
     *
     * @return     void
     */
    public function model(string $route): void
    {
        // Sanitize the call
        $route = Utils::parseRoute($route)['route'];

        // Converting a route path to a class name
        $class = '\App\\' . ucfirst(APP_NAME) . '\Model\\' . str_replace(['_', '/'], ['', '\\'], ucwords($route, '_/'));

        if (!class_exists($class)) $class = '\App\Default\Model\\' . str_replace(['_', '/'], ['', '\\'], ucwords($route, '_/'));

        // Create a key to store the model object
        $key = 'model_' . str_replace('/', '_', $route);

        // Check if the requested model is already stored in the registry.
        if (!$this->registry->has($key)) {
            if (class_exists($class)) {
                $model = new $class($this->registry);

                $proxy = new Proxy();

                foreach (get_class_methods($model) as $method) {
                    if ((substr($method, 0, 2) != '__') && is_callable($class, $method)) {
                        // Grab args using function because we don't know the number of args being passed.
                        // https://www.php.net/manual/en/functions.arguments.php#functions.variable-arg-list
                        // https://wiki.php.net/rfc/variadics
                        $proxy->{$method} = function (mixed &...$args) use ($route, $model, $method): mixed
                        {
                            $route = $route . '/' . $method;

                            $output = '';

                            // Trigger the pre events
                            $result = $this->event->trigger('model/' . $route . '/before', [&$route, &$args]);

                            if ($result) $output = $result;

                            if (!$output) {
                                // Get the method to be used
                                $callable = [$model, $method];

                                if (is_callable($callable)) {
                                    $output = call_user_func_array($callable, $args);
                                } else {
                                    throw new \Exception('Error: Could not call model/' . $route . '!');
                                }
                            }

                            // Trigger the post events
                            $result = $this->event->trigger('model/' . $route . '/after', [&$route, &$args, &$output]);

                            if ($result) $output = $result;

                            return $output;
                        };
                    }
                }

                $this->registry->set($key, $proxy);
            } else {
                throw new \Exception('Error: Could not load model ' . $class . '!');
            }
        }
    }

    /**
     * View
     *
     * Loads the template file and generates the html code.
     *
     * @param string $route
     * @param array  $data
     * @param string $code
     *
     * @return   string
     */
    public function view(string $route, array $data = [], string $code = ''): string
    {
        // Sanitize the call
        $route = Utils::parseRoute($route)['route'];

        $output = '';

        // Trigger the pre events
        $result = $this->event->trigger('view/' . $route . '/before', [&$route, &$data, &$code]);

        if ($result) $output = $result;

        // Make sure it's only the last event that returns an output if required.
        if (!$output) $output = $this->template->render($route, $data, $code);

        // Trigger the post events
        $result = $this->event->trigger('view/' . $route . '/after', [&$route, &$data, &$output]);

        if ($result) $output = $result;

        return $output;
    }


    /**
     * Config
     *
     * @param string $route
     *
     * @return     array
     */
    public function config(string $route): array
    {
        // Sanitize the call
        $route = Utils::parseRoute($route)['route'];

        $output = [];

        // Trigger the pre events
        $result = $this->event->trigger('config/' . $route . '/before', [&$route]);

        if ($result) $output = $result;

        if (!$output) $output = $this->config->load($route);

        // Trigger the post events
        $result = $this->event->trigger('config/' . $route . '/after', [&$route, &$output]);

        if ($result) $output = $result;

        return $output;
    }

    /**
     * Language
     *
     * @param string $route
     * @param string $prefix
     * @param string $code
     *
     * @return    array
     */
    public function language(string $route, string $prefix = '', string $code = ''): array
    {
        // Sanitize the call
        $route = Utils::parseRoute($route)['route'];

        $output = [];

        // Trigger the pre events
        $result = $this->event->trigger('language/' . $route . '/before', [&$route, &$prefix, &$code]);

        if (!empty($result)) $output = $result;

        if (empty($output)) $output = $this->language->load($route, $prefix, $code);

        // Trigger the post events
        $result = $this->event->trigger('language/' . $route . '/after', [&$route, &$prefix, &$code, &$output]);

        if (!empty($result)) $output = $result;

        return $output;
    }
}
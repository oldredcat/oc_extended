<?php

namespace System\Engine;

/**
* Model class
*/
abstract class Model
{
    /**
     * @var object|Registry
     */
	protected Registry $registry;

	public function __construct(Registry $registry)
    {
		$this->registry = $registry;
	}

    /**
     * __get
     *
     * @param	string	$key
     *
     * @return	object
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
     * @param	string	$value
     *
     * @return	void
     */
    public function __set(string $key, object $value): void
    {
        $this->registry->set($key, $value);
    }

    protected function getTotal(\stdClass $query): int
    {
        if ($query->num_rows) {
            return (int)$query->row['total'];
        } else {
            return 0;
        }
    }
}
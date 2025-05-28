<?php

namespace System\Engine;

class Base
{
    /**
     * @var array
     */
    protected array $data = [];

    /**
     * All
     *
     * @return	array
     */
    public function all(): array
    {
        return $this->data;
    }

    /**
     * Get
     *
     * @param	string	$key
     * @param	mixed	$default
     *
     * @return	mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        if (str_contains(strtolower(get_called_class()), 'language')) {
            return $this->data[$key] ?? $key;
        }
        return $this->data[$key] ?? $default;
    }

    /**
     * Has
     *
     * @param	string	$key
     *
     * @return	bool
     */
    public function has(string $key): bool
    {
        return isset($this->data[$key]);
    }

    /**
     * Set
     *
     * @param	string	$key
     * @param	mixed	$value
     *
     * @return void
     */
    public function set(string $key, mixed $value): void
    {
        $this->data[$key] = $value;
    }

    /**
     * Unset
     *
     * Unsets registry value by key.
     *
     * @param	string	$key
     *
     * @return	void
     */
    public function unset(string $key): void
    {
        if (isset($this->data[$key])) unset($this->data[$key]);
    }
}
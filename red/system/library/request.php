<?php

namespace System\Library;
/**
 * Class Request
 */
class Request
{
	/**
	 * @var array|mixed
	 */
	public array $get = [];
	/**
	 * @var array|mixed
	 */
	public array $post = [];
	/**
	 * @var array|mixed
	 */
	public array $cookie = [];
	/**
	 * @var array|mixed
	 */
	public array $files = [];
	/**
	 * @var array|mixed
	 */
	public array $server = [];
	
	/**
	 * Constructor
 	*/
	public function __construct()
    {
		$this->get = $this->clean($_GET);
		$this->post = $this->clean($_POST);
		$this->cookie = $this->clean($_COOKIE);
		$this->files = $this->clean($_FILES);
		$this->server = $this->clean($_SERVER);
	}

    /**
     * Get
     *
     * @param   string  $key
     * @param	mixed	$default
     *
     * @return	string
     */
    public function get(string $key, mixed $default = ''): mixed
    {
        return $this->get[$key] ?? $default;
    }

    /**
     * getString
     *
     * @param   string  $key
     * @param	string	$default
     *
     * @return	string
     */
    public function getString(string $key, string $default = ''): string
    {
        return (string)$this->get($key, $default);
    }

    /**
     * getInt
     *
     * @param   string  $key
     * @param	int	$default
     *
     * @return	int
     */
    public function getInt(string $key, int $default = 0): int
    {
        return (int)$this->get($key, $default);
    }

    /**
     * getInt
     *
     * @param   string  $key
     * @param	float	$default
     *
     * @return	float
     */
    public function getFloat(string $key, float $default = 0): float
    {
        return (float)$this->get($key, $default);
    }

    /**
     * getBool
     *
     * @param   string  $key
     * @param	bool	$default
     *
     * @return	bool
     */
    public function getBool(string $key, bool $default = false): bool
    {
        return (bool)$this->get($key, $default);
    }

    /**
     * getArray
     *
     * @param   string  $key
     * @param	array	$default
     *
     * @return	array
     */
    public function getArray(string $key, array $default = []): array
    {
        return (array)$this->get($key, $default);
    }

    /**
     * Post
     *
     * @param   string  $key
     * @param	mixed	$default
     *
     * @return	mixed
     */
    public function post(string $key, mixed $default = ''): mixed
    {
        return $this->post[$key] ?? $default;
    }

    /**
     * postString
     *
     * @param   string  $key
     * @param	string	$default
     *
     * @return	string
     */
    public function postString(string $key, string $default = ''): string
    {
        return (string)$this->post($key, $default);
    }

    /**
     * postInt
     *
     * @param   string  $key
     * @param	int	$default
     *
     * @return	int
     */
    public function postInt(string $key, int $default = 0): int
    {
        return (int)$this->post($key, $default);
    }

    /**
     * postInt
     *
     * @param   string  $key
     * @param	float	$default
     *
     * @return	float
     */
    public function postFloat(string $key, float $default = 0): float
    {
        return (float)$this->post($key, $default);
    }

    /**
     * postBool
     *
     * @param   string  $key
     * @param	bool	$default
     *
     * @return	bool
     */
    public function postBool(string $key, bool $default = false): bool
    {
        return (bool)$this->post($key, $default);
    }

    /**
     * postArray
     *
     * @param   string  $key
     * @param	array	$default
     *
     * @return	array
     */
    public function postArray(string $key, array $default = []): array
    {
        return (array)$this->post($key, $default);
    }

    /**
     * Cookie
     *
     * @param   string  $key
     * @param	string	$default
     *
     * @return	mixed
     */
    public function cookie(string $key, string $default = ''): string
    {
        return $this->cookie[$key] ?? $default;
    }
	
	/**
     * Clean
	 *
	 * @param	array|string	$data
	 *
     * @return	mixed
     */
	public function clean(array|string $data): array|string
    {
		if (is_array($data)) {
			foreach ($data as $key => $value) {
				unset($data[$key]);
				$data[$this->clean($key)] = $this->clean($value);
			}
		} else {
			$data = trim(htmlspecialchars($data, ENT_COMPAT, 'UTF-8'));
		}
		return $data;
	}
}

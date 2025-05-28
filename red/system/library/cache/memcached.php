<?php

namespace Library\Cache;
/**
 * Class Memcached
 *
 * @package
 */
class Memcached
{
	/**
	 * @var object|\Memcached
	 */
	private object $memcached;

	/**
	 *
	 */
	const CACHEDUMP_LIMIT = 9999;

	/**
	 * Constructor
	 *
	 * @param    int  $expire
	 */
	public function __construct()
    {
		$this->memcached = new \Memcached();
		$this->memcached->addServer(CACHE_HOSTNAME, CACHE_PORT);
	}

	/**
	 * Get
	 *
	 * @param    string  $key
	 *
	 * @return	 array|string|null
	 */
	public function get(string $key): array|string|null
    {
		return $this->memcached->get(CACHE_PREFIX . $key);
	}

	/**
	 * Set
	 *
	 * @param    string  $key
	 * @param    array|string|null  $value
	 * @param	 int  $expire
	 */
	public function set(string $key, array|string|null $value): void
    {
		$this->memcached->set(CACHE_PREFIX . $key, $value);
	}

	/**
	 * Delete
	 *
	 * @param    string  $key
	 */
	public function delete(string $key): void
    {
		$this->memcached->delete(CACHE_PREFIX . $key);
	}
}

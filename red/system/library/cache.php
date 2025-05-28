<?php

namespace System\Library;
/**
 * Class Cache
 */
class Cache
{
	/**
	 * @var object|mixed
	 */
	private object $adaptor;
	
	/**
	 * Constructor
	 *
	 * @param	string	$adaptor	The type of storage for the cache.
	 * @param	int		$expire		Optional parameters
	 *
 	*/
	public function __construct(string $adaptor)
    {
		$class = '\System\Library\Cache\\' . $adaptor;
		if (class_exists($class)) {
			$this->adaptor = new $class();
		} else {
			throw new \Exception('Error: Could not load cache adaptor ' . $adaptor . ' cache!');
		}
	}
	
    /**
     * Gets a cache by key name.
     *
     * @param	string $key	The cache key name
     * @param	string $prefix	The cache prefix
     *
     * @return	string
     */
	public function get(string $key, string $prefix = ''): array|string|null
    {
		return $this->adaptor->get($key, $prefix);
	}
	
    /**
	 * Set
	 *
     * Sets a cache by key value.
     *
     * @param	string	$key	The cache key
	 * @param	string	$value	The cache value
     * @param	string  $prefix	The cache prefix
	 * 
	 * @return	string
     */
	public function set(string $key, array|string $value, string $prefix = ''): void
    {
		$this->adaptor->set($key, $value, $prefix);
	}
   
    /**
     * Deletes a cache by key name.
     *
     * @param	string	$key	The cache key
     * @param	string  $prefix	The cache prefix
     */
	public function delete(string $key, string $prefix = ''): void
    {
		$this->adaptor->delete($key, $prefix);
	}

    /**
     * Deletes a cache by prefix.
     *
     * @param	string  $prefix	The cache prefix
     */
    public function deleteByPrefix(string $prefix): void
    {
        $this->adaptor->deleteByPrefix($prefix);
    }
}

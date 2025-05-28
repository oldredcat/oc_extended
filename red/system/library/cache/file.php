<?php

namespace System\Library\Cache;
/**
 * Class File
 *
 * @package
 */
class File
{
	/**
	 * Get
	 *
	 * @param   string  $key
     * @param	string $prefix	The cache prefix
	 *
	 * @return  array|string|null
	 */
	public function get(string $key, string $prefix = 'cache'): array|string|null
    {
        $file = $this->getCacheFileName($key, $prefix);
		if (is_file($file)) {
			return json_decode(file_get_contents($file), true);
		} else {
			return null;
		}
	}

	/**
	 * Set
	 *
	 * @param  string  $key
	 * @param  array|string|null  $value
     * @param  string  $prefix	The cache prefix
	 *
	 * @return void
	 */
	public function set(string $key, array|string|null $value, string $prefix = 'cache'): void
    {
		$this->delete($key, $prefix);
        $file = $this->getCacheFileName($key, $prefix);
        file_put_contents($file, json_encode($value));
	}

	/**
	 * Delete
	 *
	 * @param  string  $key
     * @param  string  $prefix	The cache prefix
	 *
	 * @return void
	 */
	public function delete(string $key, string $prefix = 'cache'): void
    {
        $file = $this->getCacheFileName($key, $prefix);
        if (is_file($file)) {
            if (!@unlink($file)) {
                clearstatcache(false, $file);
            }
		}
	}

    /**
     * Deletes a cache by prefix.
     *
     * @param	string  $prefix	The cache prefix
     */
    public function deleteByPrefix(string $prefix = 'cache'): void
    {
        if ($prefix) {
            $files = glob(DIR_CACHE . $prefix . '.*', GLOB_NOSORT);
        } else {
            $files = glob(DIR_CACHE . '*', GLOB_NOSORT);
        }
        if (!empty($files)) {
            foreach ($files as $file) {
                if (is_file($file)) {
                    if (!@unlink($file)) {
                        clearstatcache(false, $file);
                    }
                }
            }
        }
    }

    /**
     * Get cache filename
     *
     * @param  string  $key
     * @param  string  $prefix	The cache prefix
     *
     * @return string
     */
    private function getCacheFileName(string $key, string $prefix = 'cache'): string
    {
        $key = preg_replace('/[^A-Z0-9\._-]/i', '', $key);
        if ($prefix) {
            return DIR_CACHE . $prefix . '.' . $key;
        } else {
            return DIR_CACHE . $key;
        }
    }
}

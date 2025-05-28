<?php

namespace System\Library;
/**
 * Class DB Adapter
 */
class DB
{
	/**
	 * @var object|mixed
	 */
	private object $adaptor;

    /**
     * @var array
     */
    private array $results = [];

    /**
     * @var array
     */
    private array $queries = [];

    /**
     * @var array
     */
    private array $opt_queries = [];

	/**
	 * Constructor
	 *
	 * @param string $adaptor
	 * @param string $hostname
	 * @param string $username
	 * @param string $password
	 * @param string $database
	 * @param int    $port
	 *
	 */
	public function __construct(string $adaptor, string $hostname, string $username, string $password, string $database, string $port = '')
    {
		$class = '\System\Library\DB\\' . $adaptor;

		if (class_exists($class)) {
			$this->adaptor = new $class($hostname, $username, $password, $database, $port);
            // Sync PHP and DB time zones
            $this->query("SET time_zone = '" . $this->escape(date('P')) . "'");
		} else {
			throw new \Exception('Error: Could not load database adaptor ' . $adaptor . '!');
		}
	}

	/**
	 * Query
	 *
	 * @param   string $sql SQL statement to be executed
	 *
	 * @return  bool|object
	 */
	public function query(string $sql): bool|object
    {
        $sql = str_replace('#__', DB_PREFIX, $sql);
        if (DB_DEBUG) $this->queries[] = $sql;
        if (str_starts_with(\System\Helper\Utf8::strtolower($sql), 'select')) {
            $key = md5($sql);
            if (!array_key_exists($key, $this->results)) {
                if (DB_DEBUG) $this->opt_queries[] = $sql;
                $this->results[$key] = $this->adaptor->query($sql);
            }
            return $this->results[$key];
        } else {
            if (DB_DEBUG) $this->opt_queries[] = $sql;
            return $this->adaptor->query($sql);
        }
	}

	/**
	 * Escape
	 *
	 * @param   string $value Value to be protected against SQL injections
	 *
	 * @return  string    returns escaped value
	 */
	public function escape(string $value): string
    {
		return $this->adaptor->escape($value);
	}

	/**
	 * Count Affected
	 *
	 * Gets the total number of affected rows from the last query
	 *
	 * @return    int    returns the total number of affected rows.
	 */
	public function countAffected(): int
    {
		return $this->adaptor->countAffected();
	}

	/**
	 * Get Last ID
	 *
	 * Get the last ID gets the primary key that was returned after creating a row in a table.
	 *
	 * @return    int returns last ID
	 */
	public function getLastId(): int
    {
		return $this->adaptor->getLastId();
	}

	/**
	 * Is Connected
	 *
	 * Checks if a DB connection is active.
	 *
	 * @return    bool
	 */
	public function isConnected(): bool
    {
		return $this->adaptor->isConnected();
	}

    /**
     * Debug info
     *
     * Return a DB Debug info.
     *
     * @return    array
     */
    public function getDebugInfo(): array
    {
        if (DB_DEBUG) {
            return [
                'queries'     => $this->queries,
                'opt_queries' => $this->opt_queries,
            ];
        } else {
            return [];
        }
    }
}

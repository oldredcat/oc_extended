<?php

namespace Library\DB;
/**
 * Class PDO
 *
 * @package
 */
class PDO
{
	/**
	 * @var object|\PDO|null
	 */
	private object|null $connection;
	/**
	 * @var array
	 */
	private array $data = [];
	/**
	 * @var int
	 */
	private int $affected;
	
	/**
	 * Constructor
	 *
	 * @param    string  $hostname
	 * @param    string  $username
	 * @param    string  $password
	 * @param    string  $database
	 * @param    string  $port
	 */
	public function __construct(string $hostname, string $username, string $password, string $database, int $port = 3306)
    {
		try {
			$pdo = @new \PDO('mysql:host=' . $hostname . ';port=' . $port . ';dbname=' . $database . ';charset=utf8mb4', $username, $password, array(\PDO::ATTR_PERSISTENT => false, \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_general_ci'));
		} catch (\PDOException $e) {
			throw new \Exception('Error: Could not make a database link using ' . $username . '@' . $hostname . '!');
		}

		if ($pdo) {
			$this->connection = $pdo;

			$this->query("SET SESSION sql_mode = 'NO_ZERO_IN_DATE,NO_ENGINE_SUBSTITUTION'");
			$this->query("SET FOREIGN_KEY_CHECKS = 0");

			// Sync PHP and DB time zones
			$this->query("SET `time_zone` = '" . $this->escape(date('P')) . "'");
		}
	}
	
	/**
	 * Query
	 *
	 * @param    string  $sql
	 *
	 * @return   bool|object
	 */
	public function query(string $sql): bool|object
    {
		$sql = preg_replace('/(?:\'\:)([a-z0-9]*.)(?:\')/', ':$1', $sql);

		$statement = $this->connection->prepare($sql);

		try {
			if ($statement && $statement->execute($this->data)) {
				$this->data = [];

				if ($statement->columnCount()) {
					$data = $statement->fetchAll(\PDO::FETCH_ASSOC);

					$result = new \stdClass();
					$result->row = isset($data[0]) ? $data[0] : [];
					$result->rows = $data;
					$result->num_rows = count($data);
					$this->affected = 0;

					return $result;
				} else {
					$this->affected = $statement->rowCount();

					return true;
				}

				$statement->closeCursor();
			} else {
				return true;
			}
		} catch (\PDOException $e) {
			throw new \Exception('Error: ' . $e->getMessage() . ' <br/>Error Code : ' . $e->getCode() . ' <br/>' . $sql);
		}

		return false;
	}

	/**
	 * Escape
	 *
	 * @param    string  value
	 *
	 * @return   string
	 */
	public function escape(string $value): string
    {
		$key = ':' . count($this->data);

		$this->data[$key] = $value;

		return $key;
	}

	/**
	 * countAffected
	 *
	 * @return   int
	 */
	public function countAffected(): int
    {
		return $this->affected;
	}

	/**
	 * getLastId
	 *
	 * @return   int
	 */
	public function getLastId(): int
    {
		return $this->connection->lastInsertId();
	}

	/**
	 * isConnected
	 *
	 * @return   bool
	 */
	public function isConnected(): bool
    {
		return $this->connection;
	}

	/**
	 * Destructor
	 *
	 * Closes the DB connection when this object is destroyed.
	 *
	 */
	public function __destruct()
    {
		$this->connection = null;
	}
}

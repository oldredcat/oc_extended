<?php

namespace Library\Session;
/**
 * Class DB
 *
 * @package
 */
class DB
{
	private object $db;
	private object $config;

	/**
	 * Constructor
	 *
	 * @param    object  $registry
	 */
	public function __construct(\Engine\Registry $registry)
    {
		$this->db = $registry->get('db');
		$this->config = $registry->get('config');
	}

	/**
	 * Read
	 *
	 * @param    string  $session_id
	 *
	 * @return   array
	 */
	public function read(string $session_id): array
    {
		$query = $this->db->query("SELECT `data` FROM `#__session` WHERE `session_id` = '" . $this->db->escape($session_id) . "' AND `expire` > '" . $this->db->escape(gmdate('Y-m-d H:i:s'))  . "'");

		if ($query->num_rows) {
			return (array)json_decode($query->row['data'], true);
		} else {
			return [];
		}
	}
	
	/**
	 * Write
	 *
	 * @param    string  $session_id
	 * @param    array  $data
	 *
	 * @return   bool
	 */
	public function write(string $session_id, array $data): bool
    {
		if ($session_id) {
			$this->db->query("REPLACE INTO `#__session` SET `session_id` = '" . $this->db->escape($session_id) . "', `data` = '" . $this->db->escape($data ? json_encode($data) : '') . "', `expire` = '" . $this->db->escape(gmdate('Y-m-d H:i:s', time() + $this->config->get('session_expire'))) . "'");
		}

		return true;
	}

	/**
	 * Destroy
	 *
	 * @param    string  $session_id
	 *
	 * @return   bool
	 */
	public function destroy(string $session_id): bool
    {
		$this->db->query("DELETE FROM `#__session` WHERE `session_id` = '" . $this->db->escape($session_id) . "'");

		return true;
	}

	/**
	 * GC
	 *
	 * @return   bool
	 */
	public function gc(): bool
    {
		if (round(rand(1, $this->config->get('session_divisor') / $this->config->get('session_probability'))) == 1) {
			$this->db->query("DELETE FROM `#__session` WHERE `expire` < '" . $this->db->escape(gmdate('Y-m-d H:i:s', time())) . "'");
		}

		return true;
	}
}

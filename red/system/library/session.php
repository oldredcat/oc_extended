<?php

namespace System\Library;
/**
 * Class Session
 */
class Session
{
	/**
	 * @var object|mixed
	 */
	protected object $adaptor;
	/**
	 * @var string
	 */
	protected string $session_id;
	/**
	 * @var array
	 */
	public array $data = [];

    private \System\Engine\Registry $registry;

	/**
	 * Constructor
	 *
	 * @param	string	$adaptor
	 * @param	object	$registry
 	*/
	public function __construct(string $adaptor, \System\Engine\Registry $registry)
    {
        $this->registry = $registry;

		$class = '\System\Library\Session\\' . $adaptor;
		
		if (class_exists($class)) {
            $this->adaptor = new $class($registry);
			register_shutdown_function([&$this, 'close']);
			register_shutdown_function([&$this, 'gc']);
		} else {
			throw new \Exception('Error: Could not load session adaptor ' . $adaptor . ' session!');
		}
	}

    /**
     * Get
     *
     * @param	string	$key
     * @param	mixed	$default
     *
     * @return	mixed
     */
    public function get(string $key, mixed $default = ''): mixed
    {
        return $this->data[$key] ?? $default;
    }

    /**
     * Set
     *
     * @param	string	$key
     * @param	mixed	$value
     *
     * @return	mixed
     */
    public function set(string $key, mixed $value): void
    {
        $this->data[$key] = $value;
    }
	
	/**
	 * Get Session ID
	 *
	 * @return	string
 	*/	
	public function getId(): string
    {
		return $this->session_id;
	}

	/**
	 * Start
	 *
	 * Starts a session.
	 *
	 * @param	string	$session_id
	 *
	 * @return	string	Returns the current session ID.
 	*/	
	public function start(string $session_id = ''): string
    {
		if (!$session_id) {
			if (function_exists('random_bytes')) {
				$session_id = substr(bin2hex(random_bytes(26)), 0, 26);
			} else {
				$session_id = substr(bin2hex(openssl_random_pseudo_bytes(26)), 0, 26);
			}
		}

		if (preg_match('/^[a-zA-Z0-9,\-]{22,52}$/', $session_id)) {
			$this->session_id = $session_id;
		} else {
			throw new \Exception('Error: Invalid session ID!');
		}
		
		$this->data = $this->adaptor->read($session_id);

        //setcookie($this->registry->config->get('session_name', 'sid'), $session_id, ini_get('session.cookie_lifetime'), ini_get('session.cookie_path'), ini_get('session.cookie_domain'));
        setcookie($this->registry->config->get('session_name', 'sid'), $session_id, $this->registry->config->get('cookie_lifetime', 0), $this->registry->config->get('cookie_path', '/'), $this->registry->config->get('cookie_domain', ''));

        return $session_id;
	}

	/**
	 * Close
	 *
	 * Writes the session data to storage
	 *
	 * @return	void
 	*/
	public function close(): void
    {
		$this->adaptor->write($this->session_id, $this->data);
	}

	/**
	 * Destroy
	 *
	 * Deletes the current session from storage
	 *
	 * @return	void
 	*/
	public function destroy(): void
    {
		$this->data = [];
		$this->adaptor->destroy($this->session_id);
	}

	/**
	 * GC
	 *
	 * Garbage Collection
	 *
	 * @return	void
	 */
	public function gc(): void
    {
		$this->adaptor->gc($this->session_id);
	}
}

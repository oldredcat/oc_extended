<?php

namespace System\Library;
use System\Engine\Base;

/**
 * Class Language
 */
class Language extends Base
{
    /**
     * @var array
     */
    protected array $backup = [];

	/**
	 * @var string
	 */
	protected string $code;

	/**
	 * Constructor
	 *
	 * @param    string  $code
	 *
	 */
	public function __construct(string $code)
    {
		$this->code = $code;
        $this->load($code);
	}

    /**
     * Backup
     *
     * @return	void
     */
    public function backup(): void
    {
        $this->backup = $this->all();
    }

    /**
     * Restore from backup
     *
     * @return	void
     */
    public function restore(): void
    {
        if (!empty($this->backup)) {
            $this->data = $this->backup;
            $this->backup = [];
        }
    }

	/**
	 * Clear
	 *
	 * @return	void
	 */
	public function clear(): void
    {
        $this->backup = [];
		$this->data   = [];
	}

	/**
     * Load
     *
     * @param	string	$route
	 * @param	string	$code 		Language code
	 * 
	 * @return	array
     */
	public function load(string $route): array
    {
        $filename = str_replace('/', DS, $route);
        $files = [
            DIR_APP . 'default' . DS . 'language' . DS . $this->code . DS . $filename . '.php',
            DIR_APPLICATION . 'language' . DS . $this->code . DS . $filename . '.php',
        ];
        foreach ($files as $file) {
            if (is_file($file)) $this->data = array_merge($this->data, include $file);
        }
        return $this->data;
	}
}

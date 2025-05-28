<?php

namespace System\Library;

/**
 * Class Log
 */
class Log
{
	/**
	 * @var string
	 */
	private string $file;

	/**
	 * Constructor
	 *
	 * @param	string	$filename
 	*/
	public function __construct(string $filename)
    {
		$this->file = DIR_LOGS . $filename;
	}
	
	/**
     * Write
     *
     * @param	string	$message
	 *
	 * @return  void
     */
	public function write(string|array $message): void
    {
		file_put_contents($this->file, date('Y-m-d H:i:s') . ' - ' . print_r($message, true) . "\n", FILE_APPEND);
	}
}

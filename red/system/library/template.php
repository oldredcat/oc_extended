<?php

namespace System\Library;
/**
 * Class Template
 */
class Template
{
	/**
	 * @var object|mixed
	 */
	private object $adaptor;

	/**
	 * Constructor
	 *
	 * @param    string $adaptor
	 *
	 */
	public function __construct(string $adaptor)
    {
		$class = '\System\Library\Template\\' . $adaptor;

		if (class_exists($class)) {
			$this->adaptor = new $class();
		} else {
			throw new \Exception('Error: Could not load template adaptor ' . $adaptor . '!');
		}
	}

	/**
	 * Render
	 *
	 * @param    string $filename
	 * @param	 array	$data
	 * @param    string $code
	 *
	 * @return    string
	 */
	public function render(string $filename, array $data = [], string $code = ''): string
    {
		return $this->adaptor->render($filename, $data, $code);
	}
}

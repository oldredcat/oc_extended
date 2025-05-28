<?php

namespace System\Library\Template;
/**
 * Class Twig
 *
 * @package
 */
class Twig
{
	/**
	 * @var string
	 */
	protected string $root;
	/**
	 * @var object|\Twig\Loader\FilesystemLoader
	 */
	protected object $loader;

	/**
	 * Constructor
	 *
	 * @param    string $adaptor
	 *
	 */
	public function __construct()
    {
		// Unfortunately, we have to set the web root directory as the base since Twig confuses which template cache to use.
		$this->root = substr(DIR_ROOT, 0, -1);

		// We have to add the C directory as the base directory because twig can only accept the first namespace/,
		// rather than a multiple namespace system, which took me less than a minute to write. If symphony is like
		// this, then I have no idea why people use the framework.
		$this->loader = new \Twig\Loader\FilesystemLoader('/', $this->root);
	}

	/**
	 * Render
	 *
	 * @param	string	$filename
	 * @param	array	$data
	 * @param	string	$code
	 *
	 * @return	string
	 */
	public function render(string $filename, array $data = [], string $code = ''): string
    {
        $filename = str_replace(['\\', '/'], DS, $filename);

		$file = DIR_TEMPLATE . $filename . '.twig';

		// We have to remove the root web directory.
		$file = substr($file, strlen($this->root) + 1);

		if ($code) {
			// render from modified template code
			$loader = new \Twig\Loader\ArrayLoader([$file => $code]);
		} else {
			$loader = $this->loader;
		}

		try {
			// Initialize Twig environment
			$config = [
				'charset'     => 'utf-8',
				'autoescape'  => false,
				'debug'       => false,
				'auto_reload' => true,
				'cache'       => DIR_CACHE . 'template' . DS,
			];
			$twig = new \Twig\Environment($loader, $config);
			return $twig->render($file, $data);
		} catch (\Exception $e) {
            throw new \Exception($e->getMessage());
		}
	}
}

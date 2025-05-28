<?php

namespace System\Library;
/**
 * Class Response
 *
 * Stores the response so the correct headers can go out before the response output is shown.
 *
 */
class Response
{
	/**
	 * @var array
	 */
	private array $headers = [];
	/**
	 * @var int
	 */
	private int $level = 0;
	/**
	 * @var string
	 */
	private string $output = '';

    public function __construct(\System\Engine\Registry $registry)
    {
        $this->setCompression($registry->config->get('response_compression', 0));
        foreach ($registry->config->get('response_header', []) as $header) {
            $this->addHeader($header);
        }
    }

	/**
	 * Constructor
	 *
	 * @param	string	$header
	 *
 	*/
	public function addHeader(string $header): void
    {
		$this->headers[] = $header;
	}

	/**
	 * Get Headers
	 *
	 * @param	array
	 *
 	*/
	public function getHeaders(): array
    {
		return $this->headers;
	}

	/**
	 * Redirect
	 *
	 * @param	string	$url
	 * @param	int		$status
	 *
 	*/
	public function redirect(string $url, int $status = 302): void
    {
		header('Location: ' . str_replace(['&amp;', "\n", "\r"], ['&', '', ''], $url), true, $status);
		exit();
	}

	/**
	 * Set Compression
	 *
	 * @param	int		$level
 	*/
	public function setCompression(int $level): void
    {
        if ($level < 0) {
            $this->level = 0;
        } elseif ($level > 9) {
            $this->level = 9;
        } else {
            $this->level = $level;
        }
	}

	/**
	 * Set Output
	 *
	 * @param	string	$output
 	*/	
	public function setOutput(string $output, string $content_type = 'html'): void
    {

        $content_type = match($content_type){
            'xml'   => 'Content-type: application/xml',
            'json'  => 'Content-Type: application/json; charset=utf-8',
            'text'  => 'Content-Type: text/plain; charset=utf-8',
            'doc'   => 'Content-type: application/vnd.ms-word',
            'xls'   => 'Content-Type: application/vnd.ms-excel; charset=utf-8',
            'docx',
            'xlsx'  => 'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'pdf'   => 'Content-type: application/pdf',
            'jpeg'  => 'Content-type: image/jpeg',
            'gif'   => 'Content-type: image/gif',
            'png'   => 'Content-type: image/png',
            'webp'  => 'Content-type: image/webp',
            default => 'Content-Type: text/html; charset=utf-8',
        };

        $this->addHeader($content_type);
		$this->output = $output;
	}

	/**
	 * Get Output
	 *
	 * @return	array
	 */
	public function getOutput(): string
    {
		return $this->output;
	}

	/**
	 * Compress
	 *
	 * @param	string	$data
	 * @param	int		$level
	 * 
	 * @return	string
 	*/
	private function compress(string $data): string
    {
		if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && (str_contains($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))) {
			$encoding = 'gzip';
		}

		if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && (str_contains($_SERVER['HTTP_ACCEPT_ENCODING'], 'x-gzip'))) {
			$encoding = 'x-gzip';
		}

		if (!isset($encoding)) {
			return $data;
		}

		if (!extension_loaded('zlib') || ini_get('zlib.output_compression')) {
			return $data;
		}

		if (headers_sent()) {
			return $data;
		}

		if (connection_status()) {
			return $data;
		}

		$this->addHeader('Content-Encoding: ' . $encoding);

		return gzencode($data, $this->level);
	}

	/**
	 * Output
	 *
	 * Displays the set HTML output
 	*/
	public function output(): void
    {
		if ($this->output) {

			$output = $this->level ? $this->compress($this->output) : $this->output;

			if (!headers_sent()) {
				foreach ($this->headers as $header) {
					header($header);
				}
			}

			echo $output;
		}
	}
}

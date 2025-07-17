<?php

namespace App\Http;

/**
 * Class representing an HTTP response.
 *
 * This class define the response content, HTTP status code, and headers to be sent to the client.
 */
final class Response
{

	/**
	 * @var string $content The body content of the HTTP response.
	 */
	public string $content
	{
		get => $this->content;
		set => $this->content = $value;
	}

	/**
	 * @var int $statusCode The HTTP status code (e.g., 200, 404, 500).
	 */
	public int $statusCode
	{
		get => $this->statusCode;
		set => $this->statusCode = $value;
	}

	/**
	 * @var array $headers Associative array of HTTP headers to send.
	 */
	public array $headers = []
	{
		get => $this->headers;
		set => array_merge($this->headers, $value);
	}

	/**
	 * Construct a new Response instance.
	 *
	 * @param string $content The body of the response (default: empty string).
	 * @param int $status The HTTP status code (default: 200).
	 * @param array $headers HTTP headers to send (default: empty array).
	 */
	public function __construct(string $content = "", int $status = 200, array $headers = [])
	{
		$this->content = $content;
		$this->statusCode = $status;
		$this->headers = $headers;
	}

	/**
	 * Sends the HTTP response to the client.
	 *
	 * @return void
	 */
	public function send(): void
	{
		http_response_code($this->statusCode);

		foreach ($this->headers as $name => $value)
		{
			header("$name: $value");
		}

		echo $this->content;
	}

}

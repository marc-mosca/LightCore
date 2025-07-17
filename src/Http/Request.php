<?php

namespace App\Http;

/**
 * Class representing an HTTP request.
 *
 * This class captures key information from the global PHP request environment, such as the request method, URI, path,
 * query parameters, POST data, and server variables.
 */
final class Request
{

	/**
	 * @var string $method The HTTP method used for the request (e.g., GET, POST, PUT, DELETE).
	 */
	public readonly string $method;

	/**
	 * @var string $uri The full URI of the current request (including query string).
	 */
	public readonly string $uri;

	/**
	 * @var string $path The path portion of the URI (excluding query string), without trailing slash.
	 */
	public readonly string $path;

	/**
	 * @var array $queryParameters Query parameters parsed from the URL (i.e., $_GET).
	 */
	public array $queryParameters = []
	{
		get => $this->queryParameters;
		set => array_merge($this->queryParameters, $value);
	}

	/**
	 * @var array $body POST data (i.e., $_POST).
	 */
	public array $body = []
	{
		get => $this->body;
		set => array_merge($this->body, $value);
	}

	/**
	 * @var array $server Server and execution environment information (i.e., $_SERVER).
	 */
	public array $server = []
	{
		get => $this->server;
		set => array_merge($this->server, $value);
	}

	/**
	 * Constructs a new Request object and populates it from global variables.
	 *
	 * Normalizes the method and path. Defaults to GET method and "/" path if unavailable.
	 */
	public function __construct()
	{
		$this->method = strtoupper($_SERVER["REQUEST_METHOD"]) ?? "GET";
		$this->uri = $_SERVER["REQUEST_URI"] ?? "/";
		$this->path = rtrim(parse_url($this->uri, PHP_URL_PATH) ?? "/", "/") ?: "/";
		$this->queryParameters = $_GET;
		$this->body = $_POST;
		$this->server = $_SERVER;
	}

}

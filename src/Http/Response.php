<?php

namespace App\Http;

/**
 * Class representing an HTTP response.
 *
 * This class define the response content, HTTP status code, and headers to be sent to the client.
 */
final class Response
{

	public const int HTTP_CONTINUE = 100;
	public const int HTTP_SWITCHING_PROTOCOLS = 101;
	public const int HTTP_PROCESSING = 102;
	public const int HTTP_EARLY_HINTS = 103;
	public const int HTTP_OK = 200;
	public const int HTTP_CREATED = 201;
	public const int HTTP_ACCEPTED = 202;
	public const int HTTP_NON_AUTHORITATIVE_INFORMATION = 203;
	public const int HTTP_NO_CONTENT = 204;
	public const int HTTP_RESET_CONTENT = 205;
	public const int HTTP_PARTIAL_CONTENT = 206;
	public const int HTTP_MULTI_STATUS = 207;
	public const int HTTP_ALREADY_REPORTED = 208;
	public const int HTTP_IM_USED = 226;
	public const int HTTP_MULTIPLE_CHOICES = 300;
	public const int HTTP_MOVED_PERMANENTLY = 301;
	public const int HTTP_FOUND = 302;
	public const int HTTP_SEE_OTHER = 303;
	public const int HTTP_NOT_MODIFIED = 304;
	public const int HTTP_USE_PROXY = 305;
	public const int HTTP_RESERVED = 306;
	public const int HTTP_TEMPORARY_REDIRECT = 307;
	public const int HTTP_PERMANENTLY_REDIRECT = 308;
	public const int HTTP_BAD_REQUEST = 400;
	public const int HTTP_UNAUTHORIZED = 401;
	public const int HTTP_PAYMENT_REQUIRED = 402;
	public const int HTTP_FORBIDDEN = 403;
	public const int HTTP_NOT_FOUND = 404;
	public const int HTTP_METHOD_NOT_ALLOWED = 405;
	public const int HTTP_NOT_ACCEPTABLE = 406;
	public const int HTTP_PROXY_AUTHENTICATION_REQUIRED = 407;
	public const int HTTP_REQUEST_TIMEOUT = 408;
	public const int HTTP_CONFLICT = 409;
	public const int HTTP_GONE = 410;
	public const int HTTP_LENGTH_REQUIRED = 411;
	public const int HTTP_PRECONDITION_FAILED = 412;
	public const int HTTP_REQUEST_ENTITY_TOO_LARGE = 413;
	public const int HTTP_REQUEST_URI_TOO_LONG = 414;
	public const int HTTP_UNSUPPORTED_MEDIA_TYPE = 415;
	public const int HTTP_REQUESTED_RANGE_NOT_SATISFIABLE = 416;
	public const int HTTP_EXPECTATION_FAILED = 417;
	public const int HTTP_I_AM_A_TEAPOT = 418;
	public const int HTTP_MISDIRECTED_REQUEST = 421;
	public const int HTTP_UNPROCESSABLE_ENTITY = 422;
	public const int HTTP_LOCKED = 423;
	public const int HTTP_FAILED_DEPENDENCY = 424;
	public const int HTTP_TOO_EARLY = 425;
	public const int HTTP_UPGRADE_REQUIRED = 426;
	public const int HTTP_PRECONDITION_REQUIRED = 428;
	public const int HTTP_TOO_MANY_REQUESTS = 429;
	public const int HTTP_REQUEST_HEADER_FIELDS_TOO_LARGE = 431;
	public const int HTTP_UNAVAILABLE_FOR_LEGAL_REASONS = 451;
	public const int HTTP_INTERNAL_SERVER_ERROR = 500;
	public const int HTTP_NOT_IMPLEMENTED = 501;
	public const int HTTP_BAD_GATEWAY = 502;
	public const int HTTP_SERVICE_UNAVAILABLE = 503;
	public const int HTTP_GATEWAY_TIMEOUT = 504;
	public const int HTTP_VERSION_NOT_SUPPORTED = 505;
	public const int HTTP_VARIANT_ALSO_NEGOTIATES_EXPERIMENTAL = 506;
	public const int HTTP_INSUFFICIENT_STORAGE = 507;
	public const int HTTP_LOOP_DETECTED = 508;
	public const int HTTP_NOT_EXTENDED = 510;
	public const int HTTP_NETWORK_AUTHENTICATION_REQUIRED = 511;

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
	public function __construct(string $content = "", int $status = self::HTTP_OK, array $headers = [])
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

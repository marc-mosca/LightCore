<?php

namespace App\Http;

use App\Views\TemplateEngine;

/**
 * This abstract class provides common functionality for handling HTTP requests and responses,
 * including rendering HTML templates and returning JSON responses.
 */
abstract class AbstractController
{

	/**
	 * @var TemplateEngine $templateEngine Template engine used to render views.
	 */
	private readonly TemplateEngine $templateEngine;

	/**
	 * @var Request $request Current HTTP request.
	 */
	public readonly Request $request;

	/**
	 * Initializes the template engine and request object.
	 */
	public function __construct()
	{
		$this->templateEngine = new TemplateEngine();
		$this->request = new Request();
	}

	/**
	 * Renders an HTML template with the given parameters and returns an HTTP response.
	 *
	 * @param string $template The name or path of the template to render.
	 * @param array  $params Optional parameters to pass to the template.
	 *
	 * @return Response The HTTP response containing the rendered HTML.
	 */
	public function render(string $template, array $params = []): Response
	{
		$html = $this->templateEngine->render($template, $params);
		return $this->response($html, ["Content-Type" => "text/html"]);
	}

	/**
	 * Returns a JSON response from an associative array.
	 *
	 * @param array $data The data to be returned as JSON.
	 *
	 * @return Response The HTTP response containing the JSON-encoded data.
	 */
	public function json(array $data): Response
	{
		$json = json_encode($data, JSON_PRETTY_PRINT);
		return $this->response($json, ["Content-Type" => "application/json"]);
	}

	/**
	 * Creates and sends an HTTP response with the given content and headers.
	 *
	 * @param string $content The response body content.
	 * @param array  $headers Optional associative array of HTTP headers.
	 *
	 * @return Response The HTTP response object after sending the content.
	 */
	private function response(string $content, array $headers = []): Response
	{
		$response = new Response($content, Response::HTTP_OK, $headers);
		$response->send();
		return $response;
	}

}

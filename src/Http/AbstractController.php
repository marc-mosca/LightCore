<?php

namespace App\Http;

use App\Views\TemplateEngine;

abstract class AbstractController
{

	private readonly TemplateEngine $templateEngine;

	public readonly Request $request;

	public function __construct()
	{
		$this->templateEngine = new TemplateEngine();
		$this->request = new Request();
	}

	public function render(string $template, array $params = []): Response
	{
		$content = $this->templateEngine->render($template, $params);
		$response = new Response($content, Response::HTTP_OK, ['Content-Type' => 'text/html']);
		$response->send();
		return $response;
	}

}

<?php

namespace App\Http;

use App\Attributes\Route;

/**
 * Class responsible for routing HTTP requests to the appropriate controller method.
 *
 * This class scans controller classes for `Route` attributes, compiles route patterns, stores them,
 * and matches incoming requests against the registered routes.
 */
final class Router
{

	/**
	 * @var array $routes An associative array of registered routes.
	 */
	public array $routes = []
	{
		get => $this->routes;
		set => array_merge($this->routes, $value);
	}

	/**
	 * Constructs a new Router instance.
	 *
	 * Automatically scans controller classes and registers all routes.
	 */
	public function __construct()
	{
		$this->registerControllers();
	}

	/**
	 * Dispatches the current request to the appropriate controller action.
	 *
	 * It matches the request's path and method against the registered routes.
	 * If a match is found, the corresponding controller method is invoked.
	 * Otherwise, a 404 HTTP status code is returned.
	 *
	 * @return void
	 */
	public function dispatch(): void
	{
		$request = new Request();

		foreach ($this->routes as $route)
		{
			if (in_array($request->method, $route["methods"]) === false)
			{
				continue ;
			}
			if (preg_match($route["pattern"], $request->path, $matches))
			{
				$params = array_filter($matches, fn ($k) => is_int($k) === false, ARRAY_FILTER_USE_KEY);
				$params = array_map("urldecode", $params);
				$instance = new $route["controller"]();
				call_user_func_array([$instance, $route["method"]], $params);
				return ;
			}
		}
		http_response_code(404);
		require BASE_PATH . "/templates/not-found.html";
	}

	/**
	 * Scans the controller directory and registers all routes found via `Route` attributes.
	 *
	 * It builds regex patterns based on the route paths and any parameter constraints.
	 * The resulting route definitions are stored in the `routes` property.
	 *
	 * @return void
	 */
	private function registerControllers(): void
	{
		$routes = [];
		$directory = BASE_PATH . "/src/Http/Controllers";

		foreach (scandir($directory) as $file)
		{
			if (str_ends_with($file, ".php") === false)
			{
				continue ;
			}

			$classname = "App\\Http\\Controllers\\" . pathinfo($file, PATHINFO_FILENAME);
			$reflection = new \ReflectionClass($classname);

			foreach ($reflection->getMethods() as $method)
			{
				$attributes = $method->getAttributes(Route::class);

				foreach ($attributes as $attribute)
				{
					/** @var Route $route */
					$route = $attribute->newInstance();

					$pattern = preg_replace_callback(
						"/\{(\w+)\}/",
						function ($matches) use ($route)
						{
							$param = $matches[1];
							$regex = $route->requirements[$param] ?? "[^/]+";
							return "(?P<" . $param . ">" . $regex . ")";
						},
						$route->path
					);

					$routes[$route->name] = [
						"name" => $route->name,
						"path" => $route->path,
						"pattern" => "#^$pattern$#",
						"methods" => $route->methods,
						"requirements" => $route->requirements,
						"controller" => $classname,
						"method" => $method->getName(),
					];
				}
			}
		}
		$this->routes = $routes;
	}

}

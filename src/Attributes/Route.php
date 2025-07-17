<?php

namespace App\Attributes;

/**
 * Attribute used to define routing metadata for controllers or methods.
 *
 * This class can be applied to controller methods to define a route path, name, allowed HTTP methods,
 * and optional parameter requirements.
 */
#[\Attribute(\Attribute::TARGET_METHOD)]
final class Route
{

	/**
	 * @var string $path The URL path pattern for the route (e.g., "/posts/{id}").
	 */
	public readonly string $path;

	/**
	 * @var string $name The internal name of the route (used for identification or generation).
	 */
	public readonly string $name;

	/**
	 * @var string[] $methods Allowed HTTP methods for this route (e.g., ["GET", "POST"]).
	 */
	public readonly array $methods;

	/**
	 * @var array $requirements Requirements for dynamic parameters in the route path (e.g., ["id" => "\d+"]).
	 */
	public readonly array $requirements;

	/**
	 * Constructs a new Route attribute instance.
	 *
	 * @param string $path The path pattern associated with the route.
	 * @param string $name A unique name for the route.
	 * @param string[] $methods HTTP methods allowed (default: ["GET"]).
	 * @param array $requirements Regex requirements for route parameters (default: []).
	 */
	public function __construct(string $path, string $name, array $methods = ["GET"], array $requirements = [])
	{
		$this->path = $path;
		$this->name = $name;
		$this->methods = $methods;
		$this->requirements = $requirements;
	}

}

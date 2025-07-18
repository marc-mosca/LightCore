<?php

namespace App\Views;

/**
 * TemplateEngine is a class that allows loading, parsing, compiling, and rendering templates with a syntax similar to
 * template engines like Twig. It supports include blocks, extends blocks, variable blocks, conditionals, and loops.
 */
final class TemplateEngine
{

	/**
	 * @var string $baseDirectory Base directory where templates are stored.
	 */
	private string $baseDirectory;

	/**
	 * Constructor for the TemplateEngine class. Initializes the base directory for templates.
	 */
	public function __construct()
	{
		$this->baseDirectory = BASE_PATH . "/templates/";
	}

	/**
	 * Renders the specified template with the provided parameters.
	 *
	 * @param string $template The name of the template file to load.
	 * @param array  $params An associative array of parameters to pass to the template.
	 *
	 * @return string The generated content from the template.
	 */
	public function render(string $template, array $params = []): string
	{
		$template = $this->load($this->baseDirectory . $template);
		$parsed = $this->parse($template);
		$compiled = $this->compile($parsed);

		extract($params, EXTR_SKIP);
		ob_start();

		$tmp = tmpfile();
		fwrite($tmp, $compiled);
		$meta = stream_get_meta_data($tmp);
		include $meta['uri'];
		fclose($tmp);

		return ob_get_clean();
	}

	// MARK: -- Loader

	/**
	 * Loads the content of a template file.
	 *
	 * @param string $path The path of the template file to load.
	 *
	 * @return string The content of the template file.
	 */
	private function load(string $path): string
	{
		if (file_exists($path) === false)
		{
			throw new \RuntimeException("Template file $path does not exist");
		}
		return file_get_contents($path);
	}

	// MARK: -- Parser

	/**
	 * Parses a template by processing include and extend blocks.
	 *
	 * @param string $template The content of the template.
	 *
	 * @return string The content of the template after processing include and extend blocks.
	 */
	private function parse(string $template): string
	{
		$template = $this->parseExtendsBlock($template);
		return $this->parseIncludeBlock($template);
	}

	/**
	 * Processes include blocks in a template.
	 *
	 * @param string $template The content of the template.
	 *
	 * @return string The template with include blocks processed.
	 */
	private function parseIncludeBlock(string $template): string
	{
		return preg_replace_callback(
			'/{%\s*include\s+"([^"]+)"\s*%}/',
			fn ($matches) => $this->load($this->baseDirectory . "/" . $matches[1]),
			$template
		);
	}

	/**
	 * Processes extend blocks in a template.
	 *
	 * @param string $template The content of the template.
	 *
	 * @return string The template with extend blocks processed.
	 */
	private function parseExtendsBlock(string $template): string
	{
		if (preg_match('/{%\s*extends\s+"([^"]+)"\s*%}/', $template, $matches) === 0)
		{
			return $template;
		}

		$parent = $this->load($this->baseDirectory . "/" . $matches[1]);
		$parent = $this->parseIncludeBlock($parent);

		preg_match_all('/{%\s*block\s+(\w+)\s*%}(.*?){%\s*endblock\s*%}/s', $template, $blocks);
		$blockMap = array_combine($blocks[1], $blocks[2]);
		return preg_replace_callback(
			'/{%\s*block\s+(\w+)\s*%}(.*?){%\s*endblock\s*%}/s',
			function ($m) use ($blockMap)
			{
				return $blockMap[$m[1]] ?? $m[2];
			},
			$parent
		);
	}

	// MARK: -- Compiler

	/**
	 * Compiles the template by replacing variable, if, and for blocks.
	 *
	 * @param string $template The content of the template to compile.
	 *
	 * @return string The compiled template content.
	 */
	private function compile(string $template): string
	{
		$template = $this->compileVariableBlock($template);
		$template = $this->compileIfBlock($template);
		return $this->compileForBlock($template);
	}

	/**
	 * Processes variable blocks in a template.
	 *
	 * @param string $template The content of the template.
	 *
	 * @return string The template with variable blocks processed.
	 */
	private function compileVariableBlock(string $template): string
	{
		return preg_replace_callback(
			'/{{\s*(.+?)\s*}}/',
			function ($matches)
			{
				$variable = trim($matches[1]);
				if (str_starts_with($variable, '$') === false)
				{
					$variable = '$' . $variable;
				}
				return "<?= htmlspecialchars($variable) ?>";
			},
			$template
		);
	}

	/**
	 * Processes if blocks in a template.
	 *
	 * @param string $template The content of the template.
	 *
	 * @return string The template with if blocks processed.
	 */
	private function compileIfBlock(string $template): string
	{
		$template = preg_replace_callback(
			'/{%\s*if\s+(.+?)\s*%}/',
			function ($matches)
			{
				$condition = preg_replace('/\b(\w+)\b/', '\$$1', trim($matches[1]));
				return "<?php if ($condition): ?>";
			},
			$template
		);
		return preg_replace('/{%\s*endif\s*%}/', '<?php endif ?>', $template);
	}

	/**
	 * Processes for loops in a template.
	 *
	 * @param string $template The content of the template.
	 *
	 * @return string The template with for loop blocks processed.
	 */
	private function compileForBlock(string $template): string
	{
		$template = preg_replace_callback(
			'/{%\s*for\s+(\w+)\s+in\s+(\w+)\s*%}/',
			fn ($matches) => "<?php foreach (\${$matches[2]} as \${$matches[1]}): ?>",
			$template
		);

		return preg_replace('/{%\s*endfor\s*%}/', '<?php endforeach ?>', $template);
	}

}

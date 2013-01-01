<?php

/*
Class for rendering views
*/
class Load
{
	public function __construct(){}

	/*
	Renders a view
	@param the filename (including '.php') using the current directory as the views folder OR the template name
	@param optional data array
	*/
	public function render($name, $data = false, $templateProperties = false)
	{
		if($data)
		{
			extract($data);
		}

		function put($variable)
		{
			if (isset($$variable))
			{
				echo $$variable;
			}
		}
		if ($templateProperties)
		{
			$parts = $this->getTemplate($name, $templateProperties);
			foreach($parts as $part)
			{
				require($part);
			}
		}
		else
		{
			require('./app/views/' . $name);
		}
	}

	private function getTemplate($name, $templateProperties)
	{
		$head = 'index.php';
		$body = 'index.php';
		$footer = 'index.php';

		$url = './app/views/templates/' . ucfirst(strtolower($name)) . '/';
		if (isset($templateProperties['body']))
		{
			$body = $templateProperties . '.php';
		}

		if (isset($templateProperties['head']))
		{
			$head = $templateProperties['head'] . '.php';
		}

		if (isset($templateProperties['footer']))
		{
			$footer = $templateProperties['footer'] . '.php';
		}

		return array($url. 'headers/' . $head, $url. 'bodies/' . $body, $url. 'footers/' . $footer);
	}
}
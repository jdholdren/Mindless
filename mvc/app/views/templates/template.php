<?php

/*
Object that manipulates and renders a template
*/
class Template
{
	private $head = 'index.php';
	private $body = 'index.php';
	private $footer = 'index.php';
	private $name;

	/*
	Constructor fot the template (params set in array)
	@param the template name
	The following params are set as key/value pairs in the properties array
	@param "header" optional the header to use
	@param "body" optional the body to use
	@param "footer" optional the footer to use
	@return void
	@note if an optional parameter is not provided, then the index.php file in the appropriate folder is used
	*/
	public function __construct($name, $properties)
	{
		$this->name = ucfirst(strtolower($name));
		if (isset($properties['body']))
		{
			$this->body = body . '.php';
		}

		if (isset($properties['head']))
		{
			$this->head = $properties['head'] . '.php';
		}

		if (isset($properties['footer']))
		{
			$this->head = $properties['footer'] . '.php';
		}
	}

	/*
	Renders a view using the template information defined in the current object
	@return void
	*/
	public function render()
	{
		$url = './app/views/templates/' . $this->name . '/';

		require($url . 'headers/' . $this->head);
		require($url . 'bodies/' . $this->body);
		require($url . 'footers/' . $this->footer);
	}
}
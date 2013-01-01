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
			require('./app/views/templates/template.php');
			$template = new Template($name, $templateProperties);
			$template->render();
		}
		else
		{
			require('./app/views/' . $name);
		}
	}
}
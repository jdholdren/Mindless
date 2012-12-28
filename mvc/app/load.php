<?php

/*
Class for rendering views
*/
class Load
{
	public function __construct(){}

	/*
	Renders a view
	@param the filename (including '.php') of the view OR the body if a template is specified
	@param optional data array
	*/
	public function render($view, $data = NULL)
	{
		if (is_array($data))
		{
			extract($data);
		}
		require_once("./app/views/" . $view);
	}

	/*
	Renders a view using a template
	@param the template name (Corresponding to the template folder, i.e. Demo)
	@properties array of filenames(excluding '.php')
	*/
	public function useTemplate($name, $data = NULL, $properties = NULL)
	{
		require('./app/views/templates/template.php');
		$template = new Template($name, $properties);
		if (is_array($data))
		{
			extract($data);
		}
		$template->render();
	}
}
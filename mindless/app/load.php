<?php

/*
Class for rendering views
*/
class Load
{
	public function __construct(){}
	private $data;
	private $js = array();
	private $css = array();

	/*
	Renders a view
	@param the filename (including '.php') using the current directory as the views folder OR the template name
	@param optional data array
	*/
	public function render($name, $data = array(), $templateProperties = false)
	{
		$this->data = $data;

		if (isset($templateProperties['css']))
		{
			$this->css = $templateProperties['css'];
		}

		if (isset($templateProperties['js']))
		{
			$this->js = $templateProperties['js'];
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
			$body = $templateProperties['body'] . '.php';
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

	/**
	* Checks to see if a variable has been passed and outputs it
	* @param the variable name
	* @return void
	**/
	private function put($varName)
	{
		if (isset($this->data[$varName]))
		{
			echo $this->data[$varName];
		}
	}

	/**
	 * Outputs the css array
	 * @return void
	 **/
	private function outputCss()
	{
		if (empty($this->css))
		{
			return false;
		}

		foreach($this->css as $url)
		{
			$html = '<link rel="stylesheet" href="' . $url . '" />';
			echo $html;
		}
	}

	/**
	 * Outputs the js array
	 * @return void
	 **/
	private function outputJs()
	{
		if (empty($this->css))
		{
			return false;
		}

		foreach($this->js as $url)
		{
			$html = '<script src="' . $url . '" type="text/javascript"></script>';
			echo $html;
		}
	}

}
<?php

abstract class Model
{
	protected $ar;
	
	public function __construct()
	{
		global $ar;
		$this->ar = $ar;
	}
}
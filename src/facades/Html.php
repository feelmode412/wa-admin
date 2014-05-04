<?php namespace Admin;
use Illuminate\Support\Facades\Facade;
class Html extends Facade {
	protected static function getFacadeAccessor()
	{
		return '\Webarq\Admin\Html';
	}
}
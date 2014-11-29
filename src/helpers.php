<?php

/*
|--------------------------------------------------------------------------
| Custom Helper for Admin Panel
|--------------------------------------------------------------------------
|
| A url()-like helper for admin panel.
| 
|
*/

if ( ! function_exists('admin_url'))
{
	function admin_url($path = null)
	{
		$url = $_SERVER['SCRIPT_NAME'].'/'.Admin::getUrlPrefix();
		if ($path)
		{
			$url .= '/'.$path;
		}
		
		return $url;
	}	
}
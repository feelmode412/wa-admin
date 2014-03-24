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

function admin_url($path = null)
{
	$url = $_SERVER['SCRIPT_NAME'].'/admin-cp';
	if ($path)
	{
		$url .= '/'.$path;
	}
	
	return $url;
}
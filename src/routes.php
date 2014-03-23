<?php
Route::group(array('prefix' => 'admin-cp'), function()
{
	Route::get('/', 'Webarq\Admin\AdminController@getIndex');

	Route::controller('auth', 'Webarq\Admin\AuthController');
	Route::controller('setting', 'Webarq\Admin\SettingController');
});
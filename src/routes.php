<?php
Route::group(array('prefix' => Admin::getUrlPrefix()), function()
{
	Route::get('/', 'Webarq\Admin\AdminController@getIndex');

	Route::controller('auth', 'Webarq\Admin\AuthController');
	Route::controller('setting', 'Webarq\Admin\SettingController');
	Route::controller('user/role', 'Webarq\Admin\User\RoleController');
	Route::controller('user', 'Webarq\Admin\UserController');
});
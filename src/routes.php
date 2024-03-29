<?php
Route::group(array('before' => 'admin_auth', 'prefix' => Admin::getUrlPrefix()), function()
{
	Route::get('/', 'Webarq\Admin\Controller@getIndex');

	Route::controller('auth', 'Webarq\Admin\AuthController');
	Route::controller('email/template', 'Webarq\Admin\Email\TemplateController');
	Route::controller('email/tester', 'Webarq\Admin\Email\TesterController');
	Route::controller('setting', 'Webarq\Admin\SettingController');
	Route::controller('user/role', 'Webarq\Admin\User\RoleController');
	Route::controller('user', 'Webarq\Admin\UserController');
});
<?php namespace Webarq\Admin\User;

class Role extends \Eloquent {
	
	protected $table = 'admin_roles';

	public function menu()
	{
		return $this->hasMany('\Webarq\Admin\User\Role\Menu', 'admin_role_id');
	}

}
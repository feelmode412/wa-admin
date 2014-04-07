<?php namespace Webarq\Admin;

class User extends \Eloquent {

	public $timestamps = false;
	protected $table = 'admins';

	public function role()
	{
		return $this->belongsTo('\Webarq\Admin\User\Role');
	}

	public function user()
	{
		return $this->belongsTo('\Webarq\Site\User');
	}

}
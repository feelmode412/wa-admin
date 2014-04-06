<?php namespace Webarq\Admin\Model;

class Admin extends \Eloquent {

	public function user()
	{
		return $this->belongsTo('\Webarq\Site\User');
	}

}
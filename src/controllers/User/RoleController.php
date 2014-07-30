<?php namespace Webarq\Admin\User;

class RoleController extends \Webarq\Admin\Controller {

	public function __construct()
	{
		parent::__construct();
		$this->model = new Role();
		$this->pageTitle = 'Roles';
		$this->section = 'user/role';
		$this->activeMainMenu = 'system';
		$this->breadcrumbs = array(
			'System &amp; Utilities' => '#',
			'Administrators' => admin_url('user'),
			$this->pageTitle => admin_url($this->section)
		);

		$this->fieldTitles = array('name' => 'Name');
		$this->viewPath = 'admin::user.role';
	}

	public function getIndex()
	{
		$this->defaultSortField = 'name';
		$this->searchableFields = array_keys($this->fieldTitles);
		return parent::getIndex();
	}

	public function postAddedit()
	{
		$this->processAddEditPost();

		if ($this->addEditStatus && $this->row->id > 1)
		{
			if (\Input::get('id'))
			{
				$route = new Role\Route();
				$route->whereAdminRoleId($this->id)->delete();
			}

			foreach (\Input::get('routes', array()) as $route)
			{
				$roleRoute = new Role\Route();
				$roleRoute->admin_role_id = $this->row->id;
				$roleRoute->route = $route;
				$roleRoute->save();
			}
		}
		
		return $this->processAddEditStatus();
	}

}
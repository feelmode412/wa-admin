<?php namespace Webarq\Admin\User;

class RoleController extends \Webarq\Admin\AdminController {

	public function __construct()
	{
		parent::__construct();
		$this->model = new Role();
		$this->pageTitle = 'Roles';
		$this->section = 'user/role';
		$this->activeMainMenu = 'system';
		$this->breadcrumbs = array(
			'System &amp; Utilities' => '#',
			'Administrators' => 'administrator',
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
		$this->inputs = array_keys($this->fieldTitles);
		return parent::postAddedit();
	}

}
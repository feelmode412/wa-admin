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
				$menu = new Role\Menu();
				$menu->whereAdminRoleId($this->id)->delete();
			}
			
			foreach (\Input::get('item_ids', array()) as $itemId)
			{
				$menu = new Role\Menu();
				$menu->admin_role_id = $this->row->id;
				$menu->item_id = $itemId;
				$menu->save();
			}
		}
		
		return $this->processAddEditStatus();
	}

}
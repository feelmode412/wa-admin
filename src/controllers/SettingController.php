<?php namespace Webarq\Admin;

class SettingController extends AdminController {
	
	public function __construct()
	{
		parent::__construct();
		$this->activeMainMenu = 'system';
		$this->model = new \Webarq\Site\Setting;
		$this->pageTitle = 'Settings';
		$this->section = 'setting';
		$this->viewPath = 'admin::setting';
		
		$this->breadcrumbs = array(
			'System &amp; Utilities' => '#',
			$this->pageTitle => admin_url($this->section)
		);
	}

	public function getIndex()
	{
		$this->fieldTitles = array(
			'code' => 'Code',
			'type' => 'Type',
			'value' => 'Value',
		);
		
		$this->defaultSortField = 'type';
		$this->disabledActions = array('addNew', 'delete');
		$this->searchableFields = array_keys($this->fieldTitles);
		return $this->handleIndexAction();
	}
	
	public function postIndex()
	{
		return $this->handleIndexPost();
	}
	
	public function getAddedit()
	{
		return $this->handleAddEditAction();
	}
	
	public function postAddedit()
	{
		return $this->handleAddEditPost(array('value'));
	}
	
	public function getDelete()
	{
		return $this->handleDeleteAction();
	}

}
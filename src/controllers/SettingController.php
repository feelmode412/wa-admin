<?php namespace Webarq\Admin;

class SettingController extends AdminController {
	
	private $defaultSortField = 'type'; // Sorting with field 'type' as default value
	
	public function __construct()
	{
		parent::__construct();
		$this->activeMenu = 'system';
		$this->model = new \Webarq\Site\Setting;
		$this->pageTitle = 'Settings';
		$this->searchableFields = array('code', 'type', 'value');
		$this->section = 'setting';
		$this->viewPath = 'admin::setting';
		
		$this->breadcrumbs = array(
			'System &amp; Utilities' => '#',
			$this->pageTitle => admin_url($this->section)
		);
	}

	public function getIndex()
	{
		$this->handleBasicActions($this->defaultSortField);
		
		// Breadcrumbs
		$this->layout->breadcrumbs = $this->breadcrumbs;
		
		$this->layout->content = \View::make('admin::list', array(
			'defaultSortField' => $this->defaultSortField,
			'defaultSortType' => $this->defaultSortType,
			'disabledActions' => array('addNew', 'delete'),
			'fields' => array(
				'code' => 'Code',
				'type' => 'Type',
				'value' => 'Value',
			),
			'rows' => $this->model,
			'section' => $this->section,
		));
	}
	
	public function postIndex()
	{
		return $this->handleIndexPost();
	}
	
	public function getAddedit()
	{
		return $this->handleAddEditAction($this->viewPath);
	}
	
	public function postAddedit()
	{
		return $this->handleAddEditPost(array('code', 'type', 'value'));
	}
	
	public function getDelete()
	{
		return $this->handleDeleteAction();
	}

}
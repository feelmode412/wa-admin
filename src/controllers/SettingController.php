<?php namespace Webarq\Admin;

class SettingController extends AdminController {
	
	private $breadcrumbs;
	private $defaultSortField = 'type'; // Sorting with field 'type' as default value
	private $view_path = 'admin::setting';
	
	public function __construct()
	{
		parent::__construct();
		$this->activeMenu = 'system';
		$this->model = new \Webarq\Site\Setting;
		$this->pageTitle = 'Settings';
		$this->searchableFields = array('code', 'type', 'value');
		$this->section = 'setting';
		
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
	
	public function getAddedit() // Add and Edit
	{
		// Breadcrumbs
		$this->breadcrumbs[\Admin::getAddEditTitle()] = '#';
		$this->layout->breadcrumbs = $this->breadcrumbs;
		
		$this->layout->content = \View::make($this->view_path.'.add_edit', array(
			'row' => (\Input::get('id')) ? $this->model->find(\Input::get('id')) : null,
			'section' => $this->section,
		));
	}
	
	public function postAddedit()
	{
		return $this->handleAddeditPost(array('code', 'type', 'value'));
	}
	
	public function getDelete()
	{
		return $this->handleDeleteAction();
	}

}
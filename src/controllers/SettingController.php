<?php namespace Webarq\Admin;

class SettingController extends AdminController {
	
	public function __construct()
	{
		parent::__construct();

		// General
		$this->activeMainMenu = 'system';
		$this->model = new \Webarq\Site\Setting;

		// For list / index
		$this->pageTitle = 'Settings';
		$this->section = 'setting';
		$this->breadcrumbs = array(
			'System &amp; Utilities' => '#',
			$this->pageTitle => admin_url($this->section)
		);
		$this->defaultSortField = 'type';
		$this->disabledActions = array('addNew', 'delete');
		$this->fieldTitles = array(
			'code' => 'Code',
			'type' => 'Type',
			'value' => 'Value',
		);
		$this->searchableFields = array_keys($this->fieldTitles);
		$this->viewPath = 'admin::setting';

		// For add / edit
		$this->inputs = array('value');
	}

}
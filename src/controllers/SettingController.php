<?php namespace Webarq\Admin;

class SettingController extends AdminController {
	
	public function __construct()
	{
		parent::__construct();

		// General
		$this->activeMainMenu = 'system';
		$this->pageTitle = 'Settings';
		$this->model = new \Webarq\Site\Setting;
		$this->section = 'setting';
		$this->breadcrumbs = array(
			'System &amp; Utilities' => '#',
			$this->pageTitle => admin_url($this->section)
		);
		$this->viewPath = 'admin::setting';

		// For list / index
		$this->defaultSortField = 'type';
		$this->disabledActions = array('addNew', 'delete');
		$this->disabledSortFields = array('value');
		$this->fieldTitles = array(
			'code' => 'Code',
			'type' => 'Type',
			'value' => 'Value',
		);
		$this->searchableFields = array_keys($this->fieldTitles);

		// For add / edit
		$this->inputs = array('value');
	}

}
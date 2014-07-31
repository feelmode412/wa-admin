<?php namespace Webarq\Admin;

use Webarq\Site as Site;

class SettingController extends Controller {

	public function __construct()
	{
		parent::__construct();
		$this->model = new Site\Setting();
		$this->section = 'setting';
		$this->pageTitle = 'Settings';
		$this->activeMainMenu = 'system';
		$this->breadcrumbs = array(
			'System &amp; Utilities' => '#',
			$this->pageTitle => admin_url($this->section)
		);
	}

	public function getIndex()
	{
		$this->disabledActions = array('addNew', 'delete');
		$this->defaultSortField = 'type';
		$this->fieldTitles = array(
			'code' => 'Code',
			'type' => 'Type',
			'value' => 'Value',
		);
		
		return parent::getIndex();
	}

	public function getAddedit()
	{
		$this->viewPath = 'admin::setting';
		return parent::getAddedit();
	}

	public function postAddedit()
	{
		$this->inputs = array('value');
		return parent::postAddedit();
	}

}
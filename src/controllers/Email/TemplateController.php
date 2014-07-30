<?php namespace Webarq\Admin\Email;

use Webarq\Admin as Admin;
use Webarq\Site as Site;

class TemplateController extends Admin\Controller {

	public function __construct()
	{
		parent::__construct();
		$this->model = new Site\Email\Template();
		$this->section = 'email/template';
		$this->pageTitle = 'Email Templates';
		$this->activeMainMenu = 'system';
		$this->breadcrumbs = array(
			'System &amp; Utilities' => '#',
			$this->pageTitle => admin_url($this->section)
		);
	}

	public function getIndex()
	{
		$this->disabledActions = array('addNew', 'delete');
		$this->defaultSortField = 'code';
		$this->fieldTitles = array(
			'code' => 'Code',
			'title' => 'Title',
			'content' => 'Content',
			'updated_at' => 'Updated At',
		);
		
		return parent::getIndex();
	}

	public function getAddedit()
	{
		$this->viewPath = 'admin::email/template';
		return parent::getAddedit();
	}

	public function postAddedit()
	{
		$this->inputs = array('title', 'content');
		return parent::postAddedit();
	}

}
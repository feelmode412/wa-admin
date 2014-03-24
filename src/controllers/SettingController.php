<?php namespace Webarq\Admin;

class SettingController extends AdminController {
	
	private $breadcrumbs;
	private $defaultSortField = 'type'; // Sorting with field 'type' as default value
	private $model;
	private $section = 'setting';
	private $view_path = 'admin::setting';
	
	public function __construct()
	{
		parent::__construct();
		$this->activeMenu = 'system';
		$this->model = new \Webarq\Site\Setting;
		$this->pageTitle = 'Settings';
		
		$this->breadcrumbs = array(
			'System &amp; Utilities' => '#',
			$this->pageTitle => admin_url($this->section)
		);
	}

	public function getIndex()
	{
		// Handle searching
		$this->model = $this->handleSearch($this->model, array('code', 'type', 'value'));
		
		$rows = $this->model
			->orderBy(\Input::get('sort', $this->defaultSortField), \Input::get('sort_type', 'asc'))
			->paginate($this->getRowsPerPage());
		
		// By default, handle if $rows is empty
		$this->handleEmptyModel($rows);
		
		// Breadcrumbs
		$this->layout->breadcrumbs = $this->breadcrumbs;
		
		$this->layout->content = \View::make('admin::list', array(
			'disabledActions' => array('addNew', 'delete'),
			'fields' => array(
				'code' => 'Code',
				'type' => 'Type',
				'value' => 'Value',
			),
			'rows' => $rows,
			'search' => \Input::get('search'),
			'section' => $this->section,
			'sort' => \Input::get('sort', $this->defaultSortField),
			'sortType' => \Input::get('sort_type', 'asc'),
			'sortUrl' => admin_url($this->section.'?page='.\Input::get('page').'&search='.\Input::get('search').'&sort='),
		));
	}
	
	public function postIndex()
	{
		return $this->handleIndexPost($this->section, $this->model);
	}
	
	public function getAddedit() // Add and Edit
	{
		// Breadcrumbs
		$this->breadcrumbs[$this->getActionTitle()] = '#';
		$this->layout->breadcrumbs = $this->breadcrumbs;
		
		$this->layout->content = \View::make($this->view_path.'.add_edit', array(
			'legend' => $this->getActionTitle(),
			'row' => (\Input::get('id')) ? $this->model->find(\Input::get('id')) : null,
			'section' => $this->section,
		));
	}
	
	public function postAddedit()
	{
		$id = \Input::get('id');
		$row = ($id) ? $this->model->find($id) : $this->model;
		$row->value = \Input::get('value');
		$status = ($id)
			? $this->handleUpdate($row) 
			: $this->handleInsert($row);

		return $this->redirect($status ? $this->section : $this->section.'/addedit?id='.$id);
	}
	
	public function getDelete()
	{
		$row = $this->model->find(\Input::get('id'));
		$this->handleDelete($row);
		
		return $this->redirect($this->section);
	}

}
<?php namespace Webarq\Admin;

class UserController extends AdminController {

	public function __construct()
	{
		parent::__construct();
		$this->model = new User;
		$this->section = 'user';
		$this->pageTitle = 'Administrators';
		$this->activeMainMenu = 'system';
		$this->breadcrumbs = array(
			'System &amp; Utilities' => '#',
			$this->pageTitle => admin_url($this->section)
		);
	}

	public function getIndex()
	{
		$this->defaultSortField = 'user->username';
		$this->fieldTitles = array(
			'user->username' => 'Username',
			'user->email' => 'Email',
			'role->name' => 'Role',
			'user->created_at' => 'Created At',
			'user->updated_at' => 'Updated At',
		);

		$this->listFilters = array(
			'role_id' => $this->createListFilter('\Webarq\Admin\User\Role', 'role_id', 'name', 'Role')
		);

		$this->disabledActions = array('search');
		return parent::getIndex();
	}

	public function getAddedit()
	{
		$this->viewPath = 'admin::user';
		return parent::getAddedit();
	}

	public function postAddedit()
	{
		$id = \Input::get('id');
		if ( ! $id && ! \Input::get('password')) // Add
		{
			$this->createMessage(__('Password must be filled.'), $type = 'error', true);
			return \Redirect::back();
		}
		
		if (\Input::get('password_confirmation') !== \Input::get('password')) // Add & Edit
		{
			$this->createMessage(__('Your confirmation password did not match the password.'), $type = 'error', true);
			return \Redirect::back();
		}

		$user = ($id) ? $this->model->find($id)->user : new \Webarq\Site\User();
		$user->username = \Input::get('username');
		$user->email = \Input::get('email');
		if (\Input::get('password'))
			$user->password = \Hash::make(\Input::get('password'));

		$status = ($id)
			? $this->handleUpdate($user)
			: $this->handleInsert($user);

		if ( ! $status)
		{
			$url = $this->section.'/addedit?id='.$id;
			return $this->redirect($url);
		}

		$this->inputs = array('role_id');
		$this->customInputs = array('user_id' => $user->id);
		return parent::postAddedit();
	}

	public function getDelete()
	{
		$this->idToDelete = $this->model->find(\Input::get('id'))->user_id;
		$this->model = new \Webarq\Site\User();
		return parent::getDelete();
	}

	public function postIndex()
	{
		foreach (array_keys(\Input::get('list-check')) as $id)
		{
			$this->listCheckedIds[] = $this->model->find($id)->user_id;
		}

		$this->model = new \Webarq\Site\User();
		return parent::postIndex();
	}
	
}
<?php namespace Webarq\Admin;
class Controller extends \Controller {
	
	protected $activeMainMenu;
	protected $addEditStatus = false;
	protected $customInputs = array();
	protected $defaultSortField;
	protected $defaultSortType = 'asc'; // Must be lower case
	protected $disabledSortFields = array();
	protected $disabledActions = array(); // array('addNew', 'delete', 'search')
	protected $fieldTitles = array();
	protected $id;
	protected $idToDelete;
	protected $inputs = array();
	protected $layout = 'admin::layouts.master';
	protected $listCheckedIds = array();
	protected $listFilters = array();
	protected $model;
	protected $pageTitle;
	protected $row;
	protected $searchableFields = array();
	protected $section;
	protected $settings;

	private $additionalMessage;
	private $sortedField;
	
	public function __construct()
	{
		$this->beforeFilter(function()
		{
			if (\Request::segment(2) !== 'auth')
			{
				$admin = new Admin();
				$adminUrlPrefix = $admin->getUrlPrefix();

				if (\Auth::guest() || ! \Auth::user()->admin)
					return \Redirect::to($adminUrlPrefix.'/auth/login');

				if (\Auth::user()->admin->id > 1)
				{
					$roleRoutes = \Auth::user()->admin->role->routes->lists('route', 'id');
					if ($this->section && ! in_array($this->section, $roleRoutes))
						\App::abort(401, 'You are not authorized.');
				}
			}
		});

		$this->setting = new \Webarq\Site\Setting;
	}

	protected function addListFilter($model, $foreignKey, $foreignName, $label)
	{
		$this->listFilters[$foreignKey] = $this->createListFilter($model, $foreignKey, $foreignName, $label);
	}

	protected function createBreadcrumbs($additionalCrumbs = array())
	{
		$menuArray = \Admin::getMenuArray();
		$breadcrumbs = array();
		for ($i = 0; $i < count($menuArray[$this->section]); $i++)
		{
			$breadcrumbs[$menuArray[$this->section][$i]] = ($i === (count($menuArray[$this->section]) - 1))
				? admin_url($this->section)
				: '#';
		}

		return \View::make('admin::breadcrumbs', array(
			'breadcrumbs' => $breadcrumbs + $additionalCrumbs,
			//
		));
	}

	protected function createFailedInsertUpdateMessage()
	{
		$msg = 'Adding / updating data failed. Please make sure:<br/>1. Any of the required fields was not left empty<br/>2. The new data would not make duplication';

		if (\App::environment() !== 'production' && $this->additionalMessage)
		{
			$msg .= '<br/><br/><i>'.$this->additionalMessage.'</i>';
		}

		$this->createMessage($msg, 'error');
	}

	public function createListFilter($model, $foreignKey, $foreignName, $label)
	{
		$selected = null;
		$list = array(
			append_current_url(array($foreignKey => '', 'page' => '')) => '- All '.\Str::plural($label).' -'
		);
		
		$model = new $model;
		$rows = $model->orderBy($foreignName)->get();
		foreach ($rows as $row)
		{
			$url = append_current_url(array($foreignKey => $row->id, 'page' => ''));
			
			if ($row->id == \Input::get($foreignKey))
				$selected = $url;

			$list[$url] = $row->{$foreignName};
		}

		return \Form::select($foreignKey, $list, $selected, array('class' => 'list-filter'));
	}

	/**
	 * Options for $type: info, done, warning, error
	 */
	protected function createMessage($content, $type = 'info', $forRedirect = true)
	{
		if ($forRedirect === true)
		{
			\Session::flash('message', array(
				'content' => $content,
				'type' => $type,
			));
		}
		else
		{
			$this->layout->message = array(
				'content' => $content,
				'type' => $type,
			);
		}
	}
	
	public function getIndex()
	{
		if ( ! \Request::segment(2)) // Admin Panel's landing
		{
			if (\Auth::user()->admin->id == 1)
			{
				return $this->redirect(\Config::get('admin::admin.landingSection'));
			}
			else
			{
				$roleId = \Auth::user()->admin->role->id;

				// Get first route with no # as prefix
				$route = User\Role\Route::whereAdminRoleId($roleId)->where('route', 'NOT LIKE', '%#%')->first()->route;

				return $this->redirect($route);
			}
		}
		
		$this->handleBasicActions();
		$this->handleIndexLayout();
		
	}

	protected function getRowsPerPage()
	{
		return $this->setting->ofCodeType('rows_per_page', 'admin_panel')->value;
	}

	public function getAddedit()
	{
		$this->layout->breadcrumbs = $this->createBreadcrumbs(array(\Admin::getAddEditTitle() => '#'));

		$content = \View::make($this->viewPath.'.add_edit', array(
			'row' => (\Input::get('id')) ? $this->model->find(\Input::get('id')) : null,
		));

		$this->layout->content = \View::make('admin::layouts.add_edit', array(
			'content' => $content,
			'section' => $this->section,
		));
	}

	protected function handleDelete($model)
	{
		$status = true;
		try
		{
			$model->delete();
		}
		catch (\Exception $e)
		{
			$status = false;
		}

		if ($status)
		{
			$this->createMessage('The item has been successfully deleted.', 'done');
		}
		else
		{
			$this->createMessage('Delete failed. The item might still be used by other sections.', 'error');
		}

		return $status;
	}

	public function getDelete()
	{
		if ( ! $this->idToDelete)
			$this->idToDelete = \Input::get('id');

		$row = $this->model->find($this->idToDelete);
		$this->handleDelete($row);
		
		return $this->redirect($this->section);
	}

	protected function processAddEditStatus()
	{
		\Session::put('addMore', (bool) \Input::get('add_more'));
		if ( ! $this->addEditStatus)
		{
			$url = $this->section.'/addedit?id='.$this->id;
		}
		elseif (\Session::get('addMore'))
		{
			$url = $this->section.'/addedit';	
		}
		else
		{
			$url = $this->section;
		}
		
		return $this->redirect($url);
	}

	protected function handleIndexLayout()
	{
		$this->layout->breadcrumbs = $this->createBreadcrumbs();

		$this->layout->content = \View::make('admin::list', array(
			'defaultSortField' => $this->defaultSortField,
			'defaultSortType' => $this->defaultSortType,
			'disabledActions' => $this->disabledActions,
			'disabledSortFields' => $this->disabledSortFields,
			'fields' => $this->fieldTitles,
			'filters' => $this->listFilters,
			'rows' => $this->model,
			'section' => $this->section,
		));
	}

	protected function prepareSorting()
	{
		$this->sortedField = \Input::get('sort', $this->defaultSortField);

		// Sort foreign table's field
		if (strpos($this->sortedField, '->') !== false && count(explode('->', $this->sortedField) == 2))
		{
			list($relationName, $this->sortedField) = explode('->', $this->sortedField);
			$relationModel = $this->model->first()->{$relationName};
			$this->model = $this->model
				->join($relationModel->getTable(), $this->model->getTable().'.'.$relationName.'_id', '=', $relationModel->getTable().'.id')

				// Don't let "id" from foreign tables replaces our "id"
				->select($this->model->getTable().'.*');
		}
	}

	public function postIndex()
	{
		if ( ! $this->listCheckedIds)
			$this->listCheckedIds = array_keys(\Input::get('list-check'));

		switch (\Input::get('list-action'))
		{
			case 'delete':
				$this->model = $this->model->whereIn('id', $this->listCheckedIds)->get();
				$this->handleMultipleRowDeletion($this->model);
				break;
		}

		return $this->redirect($this->section);
	}

	public function postAddedit()
	{
		$this->processAddEditPost();
		return $this->processAddEditStatus();
	}

	protected function processAddEditPost()
	{
		$this->id = \Input::get('id');
		$row = ($this->id) ? $this->model->find($this->id) : $this->model;

		$inputs = $this->inputs;

		// You don't need to declare $this->inputs if the input fields are the same with fields shown on List / Index
		if ( ! $inputs)
		{
			$inputs = array_keys($this->fieldTitles);
		}
		
		foreach ($inputs as $input)
		{
			$row->{$input} = \Admin::setFieldValue($input);
		}

		$customInputs = $this->customInputs;
		foreach ($customInputs as $fieldName => $fieldValue)
		{
			$row->{$fieldName} = $fieldValue;
		}

		$this->row = $row;
		
		$this->addEditStatus = ($this->id)
			? $this->handleUpdate($row)
			: $this->handleInsert($row);
	}

	protected function handleBasicActions()
	{
		$this->prepareSorting();
		$this->handleListFilters();
		$this->handleSearch();
		$this->model = $this->model->orderBy($this->sortedField, \Input::get('sort_type', $this->defaultSortType));

		// Pagination
		$this->model = $this->model->paginate($this->getRowsPerPage());
		
		// By default, handle if $rows is empty
		$this->handleEmptyModel();
	}

	protected function handleInsert($model)
	{
		$status = true;
		try
		{
			$model->save();
		}
		catch (\Exception $e)
		{
			$status = false;

			// For non-production
			$this->additionalMessage = $e->getMessage();
		}

		if ($status)
		{
			$this->createMessage('The item has been successfully added.', 'done');
		}
		else
		{
			$this->createFailedInsertUpdateMessage();
		}

		return $status;
	}

	protected function handleListFilters()
	{
		foreach (array_keys($this->listFilters) as $listFilter)
		{
			if (\Input::get($listFilter))
				$this->model = $this->model->where($listFilter, \Input::get($listFilter));
		}
	}

	protected function handleMultipleRowDeletion($model)
	{
		$deletedRows = $model->count();
		foreach ($model as $row)
		{
			try
			{
				$row->delete();
			}
			catch (\Exception $e)
			{
				$deletedRows--;
			}
		}

		if ($deletedRows == $model->count())
		{
			$message = 'All of the selected items have been successfully deleted.';
			$message_type = 'done';
		}
		else
		{
			$message = ($model->count() - $deletedRows).' item(s) could not be deleted. The item(s) might still be used by other sections.';
			$message_type = 'warning';
		}

		$this->createMessage($message, $message_type);
	}
	
	protected function handleEmptyModel()
	{
		if (\Session::get('message'))
		{
			return false;
		}
		
		if ($this->model->count() == 0)
		{
			$msg = 'No data found';
			$search = \Input::get('search');
			if ($search)
			{
				$msg .= ' for "'.$search.'"';
			}
			$this->createMessage($msg.'.', 'warning', false);
		}
	}
	
	protected function handleSearch()
	{
		$term = \Input::get('search');
		if ($term)
		{
			$model = $this->model;
			$searchableFields = $this->searchableFields;
			$this->model = $this->model->where(function($model) use ($searchableFields, $term)
			{
				foreach ($searchableFields as $field)
				{
					$model = $model->orWhere($field, 'LIKE', '%'.$term.'%');
				}
			});
		}
	}

	protected function handleUpdate($model)
	{
		$status = true;
		try
		{
			$model->update();
		}
		catch (\Exception $e)
		{
			$status = false;
		}

		if ($status)
		{
			$this->createMessage('The item has been successfully updated.', 'done');
		}
		else
		{
			$this->createFailedInsertUpdateMessage();
		}

		return $status;
	}

	public function handleUpload($inputName, $prefix, $model = null, $resizeWidth = null, $resizeHeight = null, $ratio = true)
	{
		return handle_upload($inputName, $prefix, $model, $resizeWidth, $resizeHeight, $ratio);
	}
	
	protected function redirect($suffix = null)
	{
		$admin = new Admin();
		$url = $admin->getUrlPrefix();
		if ($suffix)
		{
			$url .= '/'.$suffix;
		}
		return \Redirect::to($url);
	}

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			if (\Auth::check())
			{
				$role = \Auth::user()->admin->role;
				$menuRoutes = ($role->id == 1) ? array_keys(\Admin::getMenuRoutes()) : $role->routes->lists('route', 'id');
			}
			
			$this->layout = \View::make($this->layout, array(
				'isLoginPage' => false,
				'menu'        => \View::make('admin::menu', array(
					'activeMainMenu' => $this->activeMainMenu,
					'menuRoutes' => (isset($menuRoutes)) ? $menuRoutes : array(),
				)),
				'message'     => \Session::get('message'),
				'pageTitle'   => $this->pageTitle,
				'websiteName' => \Config::get('app.name'),
			));
		}
	}

	// Deprecated
	protected function handleAddEditAction()
	{
		return self::getAddedit();
	}

	// Deprecated
	protected function handleAddEditPost($inputs = array(), $customInputs = array())
	{
		$this->inputs = $inputs;
		$this->customInputs = $customInputs;
		return self::postAddedit();
	}

	// Deprecated
	protected function handleDeleteAction()
	{
		return self::getDelete();
	}

	// Deprecated
	protected function handleIndexAction()
	{
		return self::getIndex();
	}
	
	// Deprecated
	protected function handleIndexPost()
	{
		return self::postIndex();
	}

}
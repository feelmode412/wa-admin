<?php namespace Webarq\Admin;
class AdminController extends \Controller {
	
	protected $activeMainMenu;
	protected $breadcrumbs;
	protected $customInputs = array();
	protected $defaultSortField;
	protected $defaultSortType = 'asc'; // Must be lower case
	protected $disabledSortFields = array();
	protected $disabledActions = array(); // array('addNew', 'delete', 'search')
	protected $fieldTitles = array();
	protected $inputs = array();
	protected $layout = 'admin::layouts.master';
	protected $listFilters = array();
	protected $model;
	protected $pageTitle;
	protected $searchableFields = array();
	protected $section;
	protected $settings;

	private $sortedField;
	
	public function __construct()
	{
		$this->beforeFilter(function()
		{
			if ((\Auth::guest() || \Auth::user()->role->code !== 'admin') && \Request::segment(2) !== 'auth')
			{
				$admin = new Admin();
				return \Redirect::to($admin->getUrlPrefix().'/auth/login');
			}
		});

		$this->setting = new \Webarq\Site\Setting;
	}

	protected function createFailedInsertUpdateMessage()
	{
		$this->createMessage('Adding / updating data failed. Please make sure:<br/>1. Any of the required fields was not left empty<br/>2. The new data would not make duplication', 'error');
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
			return $this->redirect(\Config::get('admin::admin.landingSection'));	
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
		// Breadcrumbs
		$this->breadcrumbs[\Admin::getAddEditTitle()] = '#';
		$this->layout->breadcrumbs = $this->breadcrumbs;
		
		$this->layout->content = \View::make($this->viewPath.'.add_edit', array(
			'row' => (\Input::get('id')) ? $this->model->find(\Input::get('id')) : null,
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
		$row = $this->model->find(\Input::get('id'));
		$this->handleDelete($row);
		
		return $this->redirect($this->section);
	}

	protected function handleIndexLayout()
	{
		// Breadcrumbs
		$this->layout->breadcrumbs = $this->breadcrumbs;

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
			$this->model = $this->model->join($relationModel->getTable(), $this->model->getTable().'.'.$relationName.'_id', '=', $relationModel->getTable().'.id');
		}
	}

	public function postIndex()
	{
		switch (\Input::get('list-action'))
		{
			case 'delete':
				$this->model = $this->model->whereIn('id', array_keys(\Input::get('list-check')))->get();
				$this->handleMultipleRowDeletion($this->model);
				break;
		}

		return $this->redirect($this->section);
	}

	public function postAddedit()
	{
		$id = \Input::get('id');
		$row = ($id) ? $this->model->find($id) : $this->model;

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
		
		$status = ($id)
			? $this->handleUpdate($row)
			: $this->handleInsert($row);

		\Session::put('addMore', (bool) \Input::get('add_more'));

		if ( ! $status)
		{
			$url = $this->section.'/addedit?id='.$id;
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
			$this->layout = \View::make($this->layout, array(
				'isLoginPage' => false,
				'menu'        => \View::make('admin::menu', array(
					'activeMainMenu' => $this->activeMainMenu,
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
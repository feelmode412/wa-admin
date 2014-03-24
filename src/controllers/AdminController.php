<?php namespace Webarq\Admin;
class AdminController extends \Controller {
	
	protected $activeMainMenu;
	protected $breadcrumbs;
	protected $defaultSortField;
	protected $defaultSortType = 'asc'; // Must be lower case
	protected $disabledActions = array(); // array('addNew', 'delete', 'search')
	protected $fieldTitles = array();
	protected $layout = 'admin::layouts.master';
	protected $model;
	protected $pageTitle;
	protected $searchableFields = array();
	protected $section;
	protected $settings;
	
	public function __construct()
	{
		$this->beforeFilter(function()
		{
			if ((\Auth::guest() || \Auth::user()->role->code !== 'admin') && \Request::segment(2) !== 'auth')
			{
				return \Redirect::to('admin-cp/auth/login');
			}
		});

		$this->setting = new \Webarq\Site\Setting;
	}

	protected function createFailedInsertUpdateMessage()
	{
		$this->createMessage('Adding / updating data failed. Please make sure:<br/>1. Any of the required fields was not left empty<br/>2. The new data would not make duplication', 'error');
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
		return $this->redirect(\Config::get('admin::admin.landingSection'));
	}

	protected function getRowsPerPage()
	{
		return $this->setting->ofCodeType('rows_per_page', 'admin_panel')->value;
	}

	protected function handleAddEditAction($viewPath)
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

	protected function handleDeleteAction()
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
			'fields' => $this->fieldTitles,
			'rows' => $this->model,
			'section' => $this->section,
		));
	}

	protected function handleIndexAction()
	{
		$this->handleBasicActions();
		$this->handleIndexLayout();
	}
	
	protected function handleIndexPost()
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

	protected function handleAddEditPost($inputs = array(), $customInputs = array())
	{
		$id = \Input::get('id');
		$row = ($id) ? $this->model->find($id) : $this->model;
		
		foreach ($inputs as $input)
		{
			$row->{$input} = \Input::get($input);
		}

		foreach ($customInputs as $fieldName => $fieldValue)
		{
			$row->{$fieldName} = $fieldValue;
		}
		
		$status = ($id)
			? $this->handleUpdate($row)
			: $this->handleInsert($row);

		return $this->redirect($status ? $this->section : $this->section.'/addedit?id='.$id);
	}

	protected function handleBasicActions()
	{
		// Handle searching
		$this->model = $this->handleSearch($this->model, $this->searchableFields);
		
		// Sorting and pagination
		$this->model = $this->model
			->orderBy(\Input::get('sort', $this->defaultSortField), \Input::get('sort_type', $this->defaultSortType))
			->paginate($this->getRowsPerPage());
		
		// By default, handle if $rows is empty
		$this->handleEmptyModel($this->model);
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
	
	protected function handleEmptyModel($model)
	{
		if (\Session::get('message'))
		{
			return false;
		}
		
		if ($model->count() == 0)
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
	
	protected function handleSearch($model, $involvedFields = array())
	{
		$term = \Input::get('search');
		if ($term)
		{
			foreach ($involvedFields as $field)
			{
				$model = $model->orWhere($field, 'LIKE', '%'.$term.'%');
			}
		}
		
		return $model;
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
		$url = 'admin-cp';
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

}
<?php namespace Webarq\Admin;
class Admin {
	
	private $currencyFields = array();
	private $customListActions = array();
	private $customRowActions = array();
	private $dateFields = array();
	private $dateTimeFields = array('created_at', 'updated_at');
	private $imageFields = array();
	private $recursiveFields = array();

	// For enum('Y', 'N') column type
	private $yesNoFields = array();
	private $customYesNoFields = array();

	/**
	* addCustomListAction()
	*
	* Usage: \Admin::addCustomListAction('verify', 'Set as Verified', 'ver-button', 'Set selected items as verified?');
	*/
	public function addCustomListAction($id, $label, $cssClass = null, $confMessage = null)
	{
		$this->customListActions[] = array('id' => $id, 'label' => $label, 'cssClass' => $cssClass, 'confMessage' => $confMessage);
	}

	/**
	* addCustomRowAction()
	*
	* Usage: \Admin::addCustomRowAction('verify', 'Verify', asset('admin/img.jpg'));
	*/
	public function addCustomRowAction($id, $label, $img, $confMessage = null, $linkClass = null, $imgClass = null)
	{
		$this->customRowActions[] = array(
			'id' => $id,
			'label' => $label,
			'img' => $img,
			'confMessage' => $confMessage,
			'linkClass' => $linkClass,
			'imgClass' => $imgClass
		);
	}

	public function formatDate($rowValue)
	{
		return date('M j, Y', strtotime($rowValue));
	}

	public function formatDateTime($rowValue)
	{
		return date('M j, Y H:i:s', strtotime($rowValue));
	}

	public function getAddEditTitle()
	{
		return (\Input::get('id')) ? 'Edit' : 'Add New';
	}

	public function getCustomListActions()
	{
		return $this->customListActions;
	}

	public function getCustomRowActions()
	{
		return $this->customRowActions;
	}
	
	public function getFieldValue($row, $fieldName)
	{
		$exploded = explode('->', $fieldName);
		if (count($exploded) == 1)
		{
			$fieldValue = $row->{$fieldName};
		}
		elseif ( ! in_array($fieldName, $this->recursiveFields))
		{
			$fieldValue = $row->{$exploded[0]};
			unset($exploded[0]);
			foreach ($exploded as $val)
				$fieldValue = $fieldValue->{$val};
		}
		else
		{
			$parentRow = $row->find($row->{$exploded[0]});
			$fieldValue = ($parentRow) ? $parentRow->{$exploded[1]} : NULL;
		}

		if (in_array($fieldName, $this->currencyFields))
		{
			$fieldValue = currency_format($fieldValue);
		}
		elseif (in_array($fieldName, $this->dateFields))
		{
			$fieldValue = $this->formatDate($fieldValue);
		}
		elseif (in_array($fieldName, $this->dateTimeFields))
		{
			$fieldValue = $this->formatDateTime($fieldValue);
		}
		elseif (in_array($fieldName, array_keys($this->imageFields)))
		{
			$fieldValue = \HTML::image(asset('contents/'.$fieldValue), $fieldValue, array('width' => $this->imageFields[$fieldName]));
		}
		elseif (in_array($fieldName, $this->yesNoFields))
		{
			$fieldValue = ($fieldValue == 'Y' || $fieldValue == 1) ? __('Yes') : __('No');
		}
		elseif (in_array($fieldName, array_keys($this->customYesNoFields)))
		{
			$fieldValue = ($fieldValue == 'Y' || $fieldValue == 1)
				? $this->customYesNoFields[$fieldName][0]
				: $this->customYesNoFields[$fieldName][1];
		}

		return $fieldValue;
	}

	public function getMenuRoutes()
	{
		$menu = \Config::get('admin::menu');
		$items = array();
		foreach ($menu as $level1Value)
		{
			$items[$level1Value['route']] = $level1Value['title'];
			if ( ! isset($level1Value['subs'])) continue;

			foreach ($level1Value['subs'] as $level2Value)
			{
				$items[$level2Value['route']] = $level1Value['title'].' -> '.$level2Value['title'];
				if ( ! isset($level2Value['subs'])) continue;

				foreach ($level2Value['subs'] as $level3Value)
				{
					$items[$level3Value['route']] = $level1Value['title'].' -> '.$level2Value['title'].' -> '.$level3Value['title'];
				}
			}
		}

		asort($items);
		return $items;
	}

	public function getUrlPrefix()
	{
		return \Config::get('admin::admin.urlPrefix');
	}

	public function setCurrencyFields($fields)
	{
		$this->currencyFields = $fields;
	}

	public function setDateFields($fields)
	{
		$this->dateFields = $fields;
	}

	public function setDateTimeFields($fields)
	{
		$this->dateTimeFields += $fields;
	}

	public function setFieldValue($fieldName)
	{
		$input = \Input::get($fieldName);
		$fieldValue = $input;
		if (in_array($fieldName, $this->dateFields))
		{
			// From MM/DD/YY to YYYY/MM/DD
			$fieldValue = '20'.substr($input, -2).'-'.substr($input, 0, 2).'-'.substr($input, 3, 2);
		}
		
		return $fieldValue;
	}

	// Usage: \Admin::setImageFields(array('img' => 100, 'banner' => 80));
	public function setImageFields($fields)
	{
		$this->imageFields = $fields;
	}

	public function setRecursiveFields($fields)
	{
		$this->recursiveFields = $fields;
	}

	// Usage: \Admin::setYesNoFields(array('field_name1', 'field_name2'))
	public function setYesNoFields($fields)
	{
		$this->yesNoFields = $fields;
	}

	// Usage: \Admin::setCustomYesNoFields(array('field_name' => array('Paid', 'Unpaid')))
	public function setCustomYesNoFields($fields)
	{
		$this->customYesNoFields = $fields;
	}

}
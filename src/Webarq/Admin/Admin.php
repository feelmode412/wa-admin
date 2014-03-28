<?php namespace Webarq\Admin;
class Admin {
	
	private $currencyFields = array();
	private $dateFields = array();
	private $dateTimeFields = array('created_at', 'updated_at');
	private $imageFields = array();
	private $recursiveFields = array();

	// For enum('Y', 'N') column type
	private $yesNoFields = array();

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
			$fieldValue = ($fieldValue == 'Y') ? __('Yes') : __('No');
		}

		return $fieldValue;
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

	public function setYesNoFields($fields)
	{
		$this->yesNoFields = $fields;
	}

}
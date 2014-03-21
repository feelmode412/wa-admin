<?php namespace Webarq\Admin;
class Admin {
	
	private $currencyFields = array();
	private $dateFields = array();
	private $dateTimeFields = array();
	private $imageFields = array();

	public function formatDate($rowValue)
	{
		return date('M j, Y', strtotime($rowValue));
	}

	public function formatDateTime($rowValue)
	{
		return date('M j, Y H:i:s', strtotime($rowValue));
	}
	
	public function getFieldValue($row, $fieldName)
	{
		$exploded = explode('->', $fieldName);
		if (count($exploded) == 1)
		{
			$fieldValue = $row->{$fieldName};
		}
		else
		{
			$fieldValue = $row->{$exploded[0]};
			unset($exploded[0]);
			foreach ($exploded as $val)
				$fieldValue = $fieldValue->{$val};
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
			$fieldValue = \HTML::image(asset('contents/'.$row->{$fieldName}), $row->{$fieldName}, array('width' => $this->imageFields[$fieldName]));
		}

		return $fieldValue;
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
		$this->dateTimeFields = $fields;
	}

	public function setImageFields($fields)
	{
		$this->imageFields = $fields;
	}

}
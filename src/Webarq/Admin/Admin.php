<?php namespace Webarq\Admin;
class Admin {
	
	public $currencyFields = array();
	public $dateFields = array();
	public $dateTimeFields = array();

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
			$fieldValue = Site::formatCurrency($fieldValue);
		}
		elseif (in_array($fieldName, $this->dateFields))
		{
			$fieldValue = $this->formatDate($fieldValue);
		}
		elseif (in_array($fieldName, $this->dateTimeFields))
		{
			$fieldValue = $this->formatDateTime($fieldValue);
		}

		return $fieldValue;
	}

}
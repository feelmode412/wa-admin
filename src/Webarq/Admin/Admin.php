<?php namespace Webarq\Admin;
class Admin {

	public function formatDate($rowValue)
	{
		return date('M j, Y', strtotime($rowValue));
	}

	public function formatDateTime($rowValue)
	{
		return date('M j, Y H:i:s', strtotime($rowValue));
	}
	
	public function getFieldValue($row, $fieldName, $currencyFields = array(), $dateFields = array(), $dateTimeFields = array())
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

		if (in_array($fieldName, $currencyFields))
		{
			$fieldValue = Site::formatCurrency($fieldValue);
		}
		elseif (in_array($fieldName, $dateFields))
		{
			$fieldValue = $this->formatDate($fieldValue);
		}
		elseif (in_array($fieldName, $dateTimeFields))
		{
			$fieldValue = $this->formatDateTime($fieldValue);
		}

		return $fieldValue;
	}

}
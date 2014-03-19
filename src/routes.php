<?php

Route::get('/crap', function()
{
	$rows = \Bank\Account::all();
	$fields = array(
		'account_no' => 'Account Number',
		'branch->bank->bank_desc' => 'Bank',
		'branch->branch_desc' => 'Branch Name',
		'branch->city->province->province_desc' => 'Branch Province',
		'created_at' => 'Created At',
		'updated_at' => 'Updated At',
	);

	echo '<table border="1">';
	echo '<tr>';
	foreach ($fields as $field)
	{
		echo '<th>';
		echo $field;
		echo '</th>';
	}
	echo '</tr>';

	foreach ($rows as $row)
	{
		echo '<tr>';
		foreach (array_keys($fields) as $fieldName)
		{
			echo '<td>';
			echo Admin::getFieldValue($row, $fieldName, array('account_no'), array('created_at'), array('updated_at'));
			echo '</td>';
		}
		echo '</tr>';
	}

	echo '</table>';

	die;
});

Route::get('presence', function()
{
	return Site::greet();
});
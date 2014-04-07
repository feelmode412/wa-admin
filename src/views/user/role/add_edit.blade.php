@extends('admin::layouts.add_edit')
@section('content')
	<tr>
		<td>Name *</td>
		<td>:</td>
		<td>{{ Form::text('name', @$row->name, array('class' => 'required')) }}</td>
	</tr>

	@if ( ! Input::get('id') || Input::get('id') > 1)
	<tr>
		<td style="vertical-align: top">Menu Access</td>
		<td style="vertical-align: top">:</td>
		<td>
			<?php $currentItems = \Webarq\Admin\User\Role\Menu::whereAdminRoleId(Input::get('id'))->lists('item_id', 'id') ?>
			@foreach (Admin::getMenuItems() as $id => $name)
				<div>
					{{ Form::checkbox('item_ids[]', $id, in_array($id, $currentItems), array('style' => 'width: 20px')).'&nbsp;'.$name }}
				<div/><br/>
			@endforeach
		</td>
	</tr>
	@endif

@stop
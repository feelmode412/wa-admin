@extends('admin::layouts.add_edit')
@section('content')
	<tr>
		<td>Username *</td>
		<td>:</td>
		<td>{{ Form::text('username', @$row->user->username, array('class' => 'required')) }}</td>
	</tr>
	<tr>
		<td>Email *</td>
		<td>:</td>
		<td>
			@if (Input::get('id'))
				{{ $row->user->email }}
				{{ Form::hidden('email', $row->user->email) }}
			@else
				{{ Form::text('email', null, array('class' => 'required')) }}
			@endif
		</td>
	</tr>
	<tr>
		<td>Role *</td>
		<td>:</td>
		<td>{{ Site\Form::select('role_id', Webarq\Admin\User\Role::lists('name', 'id'), @$row->role_id, array('class' => 'required')) }}</td>
	</tr>
@stop
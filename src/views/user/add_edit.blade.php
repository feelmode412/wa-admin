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
	<td>Username *</td>
	<td>:</td>
	<td>{{ Form::text('username', @$row->user->username, array('class' => 'required')) }}</td>
</tr>
<tr>
	<td>Password *</td>
	<td>:</td>
	<td>
		<!-- jQuery Validate does not work on password input -->
		{{ Form::password('password') }}
		@if (Input::get('id'))
			<br/><i>{{ __("Leave this empty if you don't want to change the password.") }}</i>
		@endif
	</td>
</tr>
<tr>
	<td>Confirm Password *</td>
	<td>:</td>
	<td>
		{{ Form::password('password_confirmation') }}
		@if (Input::get('id'))
			<br/><i>{{ __("Leave this empty if you don't want to change the password.") }}</i>
		@endif
	</td>
</tr>
<tr>
	<td>Role *</td>
	<td>:</td>
	<td>{{ Site\Form::select('role_id', Webarq\Admin\User\Role::lists('name', 'id'), @$row->role_id, array('class' => 'required')) }}</td>
</tr>
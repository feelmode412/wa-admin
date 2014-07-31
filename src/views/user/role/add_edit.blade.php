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
			<?php $currentRoutes = \Webarq\Admin\User\Role\Route::whereAdminRoleId(Input::get('id'))->lists('route', 'id') ?>
			@foreach (Admin::getMenuRoutes() as $route => $name)
				<div>
					{{ Form::checkbox('routes[]', $route, in_array($route, $currentRoutes), array('style' => 'width: 20px')).'&nbsp;'.$name }}
				<div/><br/>
			@endforeach
		</td>
	</tr>
@endif
@extends('admin::layouts.add_edit')
@section('content')
	<tr>
		<td>Name *</td>
		<td>:</td>
		<td>{{ Form::text('name', @$row->name, array('class' => 'required')) }}</td>
	</tr>
@stop
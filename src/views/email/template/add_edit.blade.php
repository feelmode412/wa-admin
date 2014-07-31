<tr>
	<td>Code</td>
	<td>:</td>
	<td>{{ $row->code }}</td>
</tr>
<tr>
	<td>Title *</td>
	<td>:</td>
	<td>{{ Form::text('title', $row->title, array('class' => 'required')) }}</td>
</tr>
<tr>
	<td>Content *</td>
	<td>:</td>
	<td><textarea name="content" class="required ckeditor">{{ $row->content }}</textarea></td>
</tr>
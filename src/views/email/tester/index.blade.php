<form action="{{ URL::full() }}" method="post" class="form2 validated" enctype="multipart/form-data">
	<table width="100%" border="0" cellpadding="0" class="form_input">
		<tr>
			<td>From</td>
			<td>:</td>
			<td>{{ $fromName }}&nbsp;&lt;{{ $fromEmail }}&gt;</td>
		</tr>
		<tr>
			<td>To</td>
			<td>:</td>
			<td>{{ Form::email('to', null, array('class' => 'required')) }}</td>
		</tr>
		<tr>
			<td>Subject</td>
			<td>:</td>
			<td>{{ $subject }}</td>
		</tr>
		<tr>
			<td>Content</td>
			<td>:</td>
			<td>{{ $content }}</td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td>
				<input name="save" type="submit" class="btn-save" value=""/>
			</td>
		</tr>
		<tr>
			<td>Configurations</td>
			<td>:</td>
			<td><pre><?php var_dump(Config::get('mail')) ?></pre></td>
		</tr>
	</table>
</form>

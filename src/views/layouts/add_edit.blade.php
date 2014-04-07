<form action="{{ URL::full() }}" method="post" class="form2 validated" enctype="multipart/form-data">
	<table width="100%" border="0" cellpadding="0" class="form_input">
		<tr>
			<td width="170">
				<h3>{{ \Admin::getAddEditTitle() }}</h3>
			</td>
			<td width="5">&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		
		@yield('content')
		
		<tr>
			<td><i>* = Required fields</i></td>
			<td>&nbsp;</td>
			<td>
				@if ( ! Input::get('id'))
					{{ Form::checkbox('add_more', '1', Session::get('addMore'), array('style' => 'width: 20px; margin: 0px 3px 15px 0px')) }}Add more after saving
					<br/>
				@endif
				<input name="save" type="submit" class="btn-save" value=""/>
				<input name="reset" type="reset" class="btn-cancel" value="" onclick="location = '{{ ( ! Input::get('id') || Session::get('addMore')) ? admin_url($section) : URL::previous() }}'"/>
			</td>
		</tr>
	</table>
</form>
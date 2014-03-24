<form action="{{ URL::current() }}" method="post" class="form2 validated" enctype="multipart/form-data">
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
				<input name="save" type="submit" class="btn-save" value=""/>
				<input name="reset" type="reset" class="btn-cancel" value="" onclick="location = '{{ admin_url($section) }}'"/>
			</td>
		</tr>
	</table>
</form>
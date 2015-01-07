<div id="app_login">
	<div class="wrapper">
		<div class="head">
			<img src="{{ asset('packages/webarq/admin/images/anim-logo_transparent.png') }}"/>
		</div>
		<form action="{{ URL::current() }}" method="post" class="login">
			@if ($message)
				<p class="msg login {{ $message['type'] }}">{{ $message['content'] }}</p>
			@endif

			<table border="0" cellpadding="0" class="login" summary="blah blah">
				<tr>
					<td>
						<div class="txt_input" id="email">
							<input type="text" name="username" id="textfield" />
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="txt_input" id="password">
							<input type="password" name="password" id="textfield2"/>
						</div>
					</td>
				</tr>
				<tr>
					<td class="right"><input type="submit" name="button" id="button" value=" " class="btn-login"/></td>
				</tr>
			</table>
		</form>
		<div class="footer">WEBARQ Admin for {{ Config::get('app.name') }}</div>
	</div>
	<div class="copyright">
		<p>&copy; 2014 PT Web Architect Technology. All rights reserved.</p>
		<img src="{{ asset('packages/webarq/admin/images/general/logo-webarq.png') }}" width="55" height="23" alt="" />
	</div>
</div>
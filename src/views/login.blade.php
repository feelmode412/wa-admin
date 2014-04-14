<div id="app_login">
	<div class="wrapper">
		<div class="head">
			<div class="fl logo"><img src="{{ asset('admin/images/logo.png') }}"/></div>
			<div class="fr"><img src="{{ asset('packages/webarq/admin/images/general/logo-login.png') }}" width="82" height="40" alt="" class="logo-login"/> </div>
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
		<div class="footer"><img src="{{ asset('packages/webarq/admin/images/icon/header-icon.png') }}" width="13" height="13" alt="" />&nbsp;{{ $websiteName }} Admin Panel</div>
	</div>
	<div class="copyright">
		<p>&copy; 2014 {{ $websiteName }}. All rights reserved.</p>
		<img src="{{ asset('packages/webarq/admin/images/general/logo-webarq.png') }}" width="55" height="23" alt="" />
	</div>
</div>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-us">
	<head>
		<title>{{ isset($pageTitle) ? $pageTitle.' | ' : null }}{{ $websiteName }} Admin Panel</title>
		<link rel="icon" type="images/x-icon" href="{{ asset('admin') }}/favicon.ico"/>
		<link rel="stylesheet" type="text/css" href="{{ asset('admin') }}/css/reset.css"/>
		<link rel="stylesheet" type="text/css" href="{{ asset('admin') }}/css/main.css"/>
		<link rel="stylesheet" type="text/css" href="{{ asset('admin') }}/css/style_content.css"/>
		<link rel="stylesheet" type="text/css" href="{{ asset('admin') }}/css/jquery.selectBox_category.css"/>
		<link rel="stylesheet" type="text/css" href="{{ asset('admin') }}/css/date_input.css"/>
		<link rel="stylesheet" type="text/css" href="{{ asset('admin') }}/css/dialog_box.css"/>
		<!--[if IE 7]>
		<link rel="stylesheet" type="text/css" href="{{ asset('admin') }}/css/style_ie7.css"/>
		<![endif]-->
		<!--[if IE 8]>
		<link rel="stylesheet" type="text/css" href="{{ asset('admin') }}/css/style_ie8.css"/>
		<![endif]-->
		<!--[if IE 9]>
		<link rel="stylesheet" type="text/css" href="{{ asset('admin') }}/css/style_ie9.css"/>
		<![endif]-->
		<link rel="stylesheet" type="text/css" href="{{ asset('admin') }}/css/indogrosir.css"/>
	</head>
	<body>
		@if ($isLoginPage)
			@include('admin::login')
		@else
		<div id="app_header">
			<span id="icon">{{ $websiteName }} Admin Panel</span>   
			<div id="welcome-message">Welcome, {{ Auth::user()->username }}
				<span id="logout"><a href="{{ admin_url('auth/logout') }}">Logout</a></span>
			</div>
		</div>
		<div id="app_shorcut">
			<div>
				<img src="{{ asset('admin') }}/images/general/logo.png" alt="{{ $websiteName }}" class="logo"/>
			</div>
		</div>
		
		{{ $menu }}
		
		<div class="breadcrumb">
			<ul>
				@yield($i = 1)
				@foreach ($breadcrumbs as $title => $url)
					<li>
						<a href="{{ $url }}">{{ $title }}</a>{{ ($i < count($breadcrumbs)) ? '&nbsp;&raquo;' : null }}
					</li>
					@yield($i++)
				@endforeach
			</ul>
		</div>
		
		<div id="wrapper" style="margin-top:30px;">
			<div id="app_content" style="overflow:hidden;">

				@if ($message)
					<p class="msg {{ $message['type'] }}">{{ $message['content'] }}</p>
				@endif
				
				<div id="content_header">
					<h3 class="manage_news">{{ $pageTitle }}</h3>
				</div>
				<div id="content_body">
					{{ $content }}
				</div>
				<div class="clear"></div>
			</div>
		</div>
		
		<div id="app_footer">
			<ul>
				<li>&copy; 2014 {{ $websiteName }}. All rights reserved.</li>
			</ul>
			<div class="logo">Design and development by <a href="http://www.webarq.com" class="">WEBARQ</a></div>
		</div>
		@endif
		
		<script type="text/javascript" src="{{ asset('admin') }}/js/jquery-1.7.2.min.js"></script>
		<script type="text/javascript" src="{{ asset('admin') }}/js/jquery.date_input.js"></script>
		<script type="text/javascript" src="{{ asset('admin') }}/js/jquery.selectbox_category.js"></script>
		<script type="text/javascript" src="{{ asset('admin') }}/js/jquery.validate.min.js"></script>
		<script type="text/javascript">
			var admin_url = function(suffix) {
				suffix = (typeof suffix !== "undefined") ? suffix : "";
				return "{{ admin_url() }}/" + suffix;
			};
			var admin_asset = function(suffix) {
				return "{{ asset('admin') }}/" + suffix;
			};
		</script>
		<script type="text/javascript" src="{{ asset('admin') }}/ckeditor/ckeditor.js"></script>
		<script type="text/javascript" src="{{ asset('admin') }}/js/scripts.js"></script>
		<script type="text/javascript" src="{{ asset('admin') }}/js/indogrosir.js"></script>
		
</body>
</html>
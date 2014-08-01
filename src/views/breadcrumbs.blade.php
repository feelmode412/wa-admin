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
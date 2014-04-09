<div id="app_navigation">
	<ul>
		@foreach (Config::get('admin::menu') as $id => $values)
			<?php if ( ! in_array($values['route'], $menuRoutes)): continue; endif ?>
			<li>
				<a href="{{ admin_url($values['route']) }}" class="{{ ($activeMainMenu === $id) ? 'active' : null }}">
					<img src="{{ asset('packages/webarq/admin') }}/images/icon/{{ $values['img'] }}" width="12" height="10" alt="" />
					<span>{{ $values['title'] }}</span>
				</a>
				@if (isset($values['subs']))
					<ul>
						@yield($i = 1)
						@foreach ($values['subs'] as $values2)
							<?php if ( ! in_array($values2['route'], $menuRoutes)): continue; endif ?>
							<li class="{{ ($i == count($values['subs'])) ? 'last' : null }}">
								<a href="{{ admin_url($values2['route']) }}">{{ $values2['title'] }}</a>
								@if (isset($values2['subs']))
									<span class="arrow">&rsaquo;</span>
									<ul>
										@yield($j = 1)
										@foreach ($values2['subs'] as $values3)
											<?php if ( ! in_array($values3['route'], $menuRoutes)): continue; endif ?>
											<li class="{{ ($j == count($values2['subs'])) ? 'last' : null }}">
												<a href="{{ admin_url($values3['route']) }}">{{ $values3['title'] }}</a>
												@if (isset($values3['subs']))
													<span class="arrow">&rsaquo;</span>
													<ul>
														@yield($k = 1)
														@foreach ($values3['subs'] as $id4 => $values4)
															<li class="{{ ($k == count($values3['subs'])) ? 'last' : null }}">
																<a href="{{ admin_url($values4['route']) }}">{{ $values4['title'] }}</a>
															</li>
															@yield($k++)
														@endforeach
													</ul>
												@endif
											</li>
											@yield($j++)
										@endforeach
									</ul>
								@endif
							</li>
							@yield($i++)
						@endforeach
					</ul>
				@endif
			</li>
		@endforeach
	</ul>
	<div class="clear"></div>
	<div id="app_header_shadowing"></div>
</div>
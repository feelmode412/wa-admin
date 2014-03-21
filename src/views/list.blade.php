<div class="top_table">
	<div class="left">
		@if ( ! isset($disabledActions) || ! in_array('addNew', $disabledActions))
			<a class="button-add" href="{{ admin_url($section.'/addedit') }}"><span>Add New</span></a>
		@endif
		@if ( ! isset($disabledActions) || ! in_array('delete', $disabledActions))
			<a id="list-delete" class="button-delete list-action" href="#"><span>Delete</span></a>
		@endif
	</div>
	<div class="right">
		@if ( ! isset($disabledActions) || ! in_array('search', $disabledActions))
			<form method="get" action="{{ admin_url($section) }}" class="form1">
				<fieldset>
					<input name="search" id="search" type="text" class="ip_search search" value="{{ ($search) ?: 'Search' }}"/>
					@if ($search)
						<div class="x_remove"><a href="{{ admin_url($section) }}">x</a></div>
					@endif
				</fieldset>
			</form>
		@endif
	</div>
</div>

<form method="post" action="{{ URL::current() }}" id="list-form">
	<input id="list-action" name="list-action" type="hidden" value=""/>
	<table cellpadding="0" cellspacing="0" width="100%" class="t2">
		<thead>
			<tr>
				@if ( ! isset($disabledActions) || ! in_array('delete', $disabledActions))
					<th width="15"><label class="c_box"><input type="checkbox" id="list-check-all"/></label></th>
				@endif
				<th width="15">#</th>
				@foreach ($fields as $fieldName => $fieldTitle)
					<th>
						@if ( ! isset($disabledSorts) || ! in_array($fieldName, $disabledSorts))
							@yield($nextSortType = 'asc')
							@if ($sort == $fieldName && $sortType == 'asc')
								@yield($nextSortType = 'desc')
							@endif
							<a href="{{ $sortUrl.$fieldName.'&sort_type='.$nextSortType }}" class="{{ ($sort == $fieldName) ? 'sorted-'.$sortType : null }}">{{ $fieldTitle }}</a>
						@else
							{{ $fieldTitle }}
						@endif
					</th>
				@endforeach
				<th></th>
			</tr>
		</thead>
		<tbody>
			@yield($i = $rows->getFrom())
			@foreach ($rows as $row)
				<tr>
					@if ( ! isset($disabledActions) || ! in_array('delete', $disabledActions))
						<td align="center"><label class="c_box"><input type="checkbox" class="list-checkbox" name="list-check[{{ $row->id }}]"></label></td>
					@endif
					
					<td align="center">{{ $i }}</td>

					@foreach (array_keys($fields) as $fieldName)
						<td>{{ Admin::getFieldValue($row, $fieldName) }}</td>
					@endforeach
					
					<td align="center" width="100">
						@yield($primaryKey = (isset($primaryKey)) ? $primaryKey : 'id')
						@if (isset($enabledActions) && in_array('detail', $enabledActions))
							<a href="{{ admin_url($section.'/addedit?id='.$row->id) }}" class="btn_action" title="Detail">
								<img src="{{ asset('admin/images/icon/icon-action-02.png') }}" width="22" height="22" alt="" />
							</a>
						@endif
						@if ( ! isset($disabledActions) || ! in_array('edit', $disabledActions))
							<a href="{{ admin_url($section.'/addedit?id='.$row->{$primaryKey}) }}" class="btn_action" title="Edit">
								<img src="{{ asset('admin/images/icon/icon-action-02.png') }}" width="22" height="22" alt="" />
							</a>
						@endif
						@if ( ! isset($disabledActions) || ! in_array('delete', $disabledActions))
							<a href="{{ admin_url($section.'/delete?id='.$row->id) }}" class="btn_action delete" title="Delete">
								<img src="{{ asset('admin/images/icon/icon-action-03.png') }}" width="22" height="22" alt="" />
							</a>
						@endif

					</td>
				</tr>
				@yield($i++)
			@endforeach
		</tbody>
	</table>
</form>

<div class="pagination">
	@yield(parse_str($_SERVER['QUERY_STRING'], $parsedStr))
	<?php unset($parsedStr['page']) ?>
	{{ $rows->appends($parsedStr)->links() }}
</div>
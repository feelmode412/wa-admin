<div class="pagination">
	@yield(parse_str($_SERVER['QUERY_STRING'], $parsedStr))
	<?php unset($parsedStr['page']) ?>
	{{ $rows->appends($parsedStr)->links() }}
</div>
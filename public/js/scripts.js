$(document).ready(function() {

	//selecbox Categories
	$(".select_01, .select_02, .select_03").selectbox();

	//dateinput
	$(":date").dateinput();

	$('a.btn_action').hover(function() {
		$(this).append('<div class="tooltip"><span>' + $(this).attr('title') + '</span></div>');
		var margin_left = $(this).children('.tooltip').children('span').width() / 2;
		$(this).children('.tooltip').css('width', $(this).children('.tooltip').children('span').width() + 'px');
		$(this).children('.tooltip').css('marginLeft', -margin_left + 'px');
	}, function() {
		$(this).children('.tooltip').remove();

	})
	$('.txt_input input').blur(function(e) {
		if ($(this).val() == '')
		{
			$(this).css('opacity', '0');
		}
		else
		{
			$(this).css('opacity', '1');
		}
	});
	$('.txt_input input').focus(function(e) {
		$(this).css('opacity', '1');
	});

	$("input.list-checkbox, input#list-check-all").each(function() {
		if ($(this).is(':checked'))
		{
			$(this).parent().css('background-image', 'url(' + admin_asset('') + 'images/general/bg-checkbox-active.jpg)');
		}
		else
		{
			$(this).parent().css('background-image', 'url(' + admin_asset('') + 'images/general/bg-checkbox.jpg)');
		}
	});

	$("input.list-checkbox").click(function(e) {
		if ($(this).is(':checked'))
		{
			$(this).parent().css('background-image', 'url(' + admin_asset('') + 'images/general/bg-checkbox-active.jpg)');
		}
		else
		{
			$(this).parent().css('background-image', 'url(' + admin_asset('') + 'images/general/bg-checkbox.jpg)');
		}
	});

	$("a.delete").click(function(e) {
		e.preventDefault();
		var c = window.confirm('Are you sure you want to delete this item?');
		if (c) {
			window.location = this.href;
		}
	});

	$("input#list-check-all").change(function() {
		if (this.checked) {
			$("input.list-checkbox").attr("checked", "checked"); // ?
			$("input.list-checkbox, input#list-check-all").parent().css('background-image', 'url(' + admin_asset('') + 'images/general/bg-checkbox-active.jpg)');
			
			$(".list-action").fadeIn("slow");
		} else {
			$("input.list-checkbox").removeAttr("checked");
			$("input.list-checkbox, input#list-check-all").parent().css('background-image', 'url(' + admin_asset('') + 'images/general/bg-checkbox.jpg)');
			
			$(".list-action").fadeOut();
		}
	});

	$("input.list-checkbox").change(function() {
		if (this.checked)
			$(".list-action").fadeIn("slow");
		else {
			var clr = true;
			$("input.list-checkbox").each(function() {
				if (this.checked)
					clr = false;
			})
			if (clr)
				$(".list-action").fadeOut();
		}
	});

	$("#list-delete").click(function(e) {
		e.preventDefault();
		$("input#list-action").val("delete");
		c = window.confirm("Delete the selected item(s) ?");
		if (c) {
			$("form#list-form").submit();
		}
	});

	$("form.validated").validate({});

	$("input.search").blur(function() {
		if ($(this).val() == "") {
			$(this).val("Search");
		}
	});

	$("input.search").focus(function() {
		if ($(this).val() == "Search") {
			$(this).val("");
		}
	});

	$("select.list-filter").change(function() {
		window.location = $(this).val();
	});

	$('#checkboxtree').checkboxTree();

});
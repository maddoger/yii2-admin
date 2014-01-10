$(document).ready(function(){
	docReady();
});

function docReady() {
	$("table.table-sorter").tablesorter(/*{debug: true}*/);
	$('.select2').select2();
	$('.date-editor').datepicker({
		format: 'dd.mm.yyyy'
	});
	$('.checkbox-hidden').each(function(){
		var label = $(this);
		var input = $(this).find('input');
		if (input.val()==1) {
			label.addClass('checked');
		}
		label.off('click').click(function(){
			if (label.hasClass('checked')) {
				input.val(0);
				label.removeClass('checked');
			} else {
				input.val(1);
				label.addClass('checked');
			}
			return false;
		});
	});
}
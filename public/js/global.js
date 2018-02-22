$(function(){
	$("input[type='checkbox'].checkall").change(function(){
		children = $(this).parents('table:first').find("input[type='checkbox'].index");
		if($(this).is(':checked')){
			children.attr('checked','checked');
		}else{
			children.removeAttr('checked');
		}
	});

	$(".delete-selection").click(function(){
		form_id = $(this).attr('form');
		if(form_id == null){
			console.log('delete-selection: Please specify form to look');
			return;
		}

		form = $("#"+form_id);
		if(form.length == 0){
			console.log('delete-selection: Form '+form_id+' not found');
			return;
		}

		form.attr('onSubmit',"return validateSelection($(this))").submit();
	});

	$(".has-tooltip").tooltip();

	$(".delete-row").click(function(){
		row = $(this).parents('tr:first');
		if(row.length == 0){
			console.log('delete-row: parent row not found');
			return;
		}
		index = row.find("input[type='checkbox'].index");
		if(index.length != 1){
			console.log('delete-row: index element not found or found morethan 1');
			return;
		}

		form = row.parents('form:first');
		if(form.length == 0){
			console.log('delete-row: unable to find form element');
			return;
		}

		if(confirm('Delete this record?')){
			index.attr('checked','checked');
			form.submit();
		}else{
			index.removeAttr('checked');
		}
	});
});

function validateSelection(f){
	children = f.find("input[type='checkbox'].index");
	if(children.length == 0){
		return false;
	}
	if(f.find("input[type='checkbox'].index:checked").length == 0){
		alert("Please select record to delete!");
		return false
	}
	if(confirm('Delete selected records?') == true){
		return true;
	}
	return false;
}

function calculate_age(birth_month,birth_day,birth_year){
	today_date = new Date();
	today_year = today_date.getFullYear();
	today_month = today_date.getMonth();
	today_day = today_date.getDate();
	age = today_year - birth_year;
	if ( today_month < (birth_month - 1))
	{
		age--;
	}
	if (((birth_month - 1) == today_month) && (today_day < birth_day))
	{ 
		age--;
	}
	return age;
}
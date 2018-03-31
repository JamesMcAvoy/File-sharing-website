$(() => {

	//Register form
	$('#register').on('click', (e) => {
		if($('#name').val()===''||$('#email').val()===''||$('#pass').val()==='') {
			alert('Some fields are empty.');
			e.preventDefault();
		} else if($('#pass').val() !=$('#passc').val()) {
			alert('Passwords do not match.');
			e.preventDefault();
		} else $('#form').submit();
	});

	//Login form
	$('#login').on('click', (e) => {
		if($('#name').val()===''||$('#pass').val()==='') {
			alert('Some fields are empty.');
			e.preventDefault();
		} else $('#form').submit();
	});

});
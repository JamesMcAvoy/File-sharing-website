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

	//Upload
	$('#upload-trigger').on('click', (e) => {
		$('.info-upload').show(250);
		$('header, div.inner.cover, footer').css('filter', 'blur(5px)');
		return false;
	});

	$('#close').on('click', (e) => {
        $('.info-upload').hide(250);
        $('header, div.inner.cover, footer').css('filter', '');
	});

	$(document).click((e) => { 
		if(!$(e.target).closest('.info-upload').length) {
			if($('.info-upload').is(":visible")) {
				$('.info-upload').hide(250);
				$('header, div.inner.cover, footer').css('filter', '');
			}
		}
	});

});
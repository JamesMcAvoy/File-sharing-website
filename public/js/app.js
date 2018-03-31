$(() => {

	/**
	 * Shake animation
	 * @see https://jsfiddle.net/12aueufy/1/
	 */
	var shakingElements = [];

	const shake = function(element, magnitude = 16) {
  
		var tiltAngle = 1;
		var counter = 1;
		var numberOfShakes = 15;

		var startX = 0,
			startY = 0,
			startAngle = 0;

		var magnitudeUnit = 16 / numberOfShakes;

		var randomInt = (min, max) => {
			return Math.floor(Math.random() * (max - min + 1)) + min;
		};

		if(shakingElements.indexOf(element) === -1) {

			shakingElements.push(element);
			upAndDownShake();

		}

		function upAndDownShake() {

			if(counter < numberOfShakes) {
				element.css('transform', 'translate(' + startX + 'px, ' + startY + 'px)');

				magnitude -= magnitudeUnit;

				var randomX = randomInt(-magnitude, magnitude);
				var randomY = randomInt(-magnitude, magnitude);

				element.css('transform', 'translate(' + randomX + 'px, ' + randomY + 'px)');

				counter += 1;

				requestAnimationFrame(upAndDownShake);
			}

			if(counter >= numberOfShakes) {
				element.css('transform', 'translate(' + startX + ', ' + startY + ')');
				shakingElements.splice(shakingElements.indexOf(element), 1);
			}
		}

	};

	/**
	 * Register form
	 */
	$('#register').on('click', (e) => {
		if($('#name').val()===''||$('#email').val()===''||$('#pass').val()==='') {
			alert('Some fields are empty.');
			e.preventDefault();
		} else if($('#pass').val() !=$('#passc').val()) {
			alert('Passwords do not match.');
			e.preventDefault();
		} else $('#form').submit();
	});

	/**
	 * Login form
	 */
	$('#login').on('click', (e) => {
		if($('#name').val()===''||$('#pass').val()==='') {
			alert('Some fields are empty.');
			e.preventDefault();
		} else $('#form').submit();
	});

	/**
	 * Upload + drag system
	 */
	const upload = function(f) {
		(f.name.length < 50) ? $('#upload-name').text(f.name) : $('#upload-name').text(f.name.substring(0, 47) + '...');

		var reader = new FileReader();

		reader.onloadstart = function(e) {
			$('#upload-bar').css('display', 'inline-block');
		};

		reader.onprogress = function(e) {
			if(e.lengthComputable) {
				let percent = Math.round((e.loaded/e.total)*1000)/10;
				$('#upload-bar div').css('width', percent+'%').text(percent+'%');
			}
		};

		reader.onload = function(e) {
			data = e.target.result;
			$('#upload-msg').html('<a href="#">url to image</a>');
		};

		reader.onloadend = function(e) {
			$('#upload-bar div').css('width', '100%').text('100%');
		};

		reader.readAsDataURL(f);
	};

	const removeUploadPopup = function() {
		$('.info-upload').hide(250);
		$('header, div.inner.cover, footer').css('filter', '');
		$('#upload-name').empty();
		$('#upload-msg').empty();
		$('#upload-bar').css('display', 'none');
	};

	$('#upload-display').on('click', (e) => {
		$('.info-upload').show(250);
		$('header, div.inner.cover, footer').css('filter', 'blur(5px)');
		return false;
	});

	$('#close').on('click', (e) => {
		removeUploadPopup();
	});

	$(document).on('click', (e) => { 
		if(!$(e.target).closest('.info-upload').length) {
			if($('.info-upload').is(":visible")) {
				removeUploadPopup();
			}
		}
	});

	//Drag/drop events
	$('#upload-trigger').on('dragenter', (e) => {
		shake($('#upload-trigger'));
		return false;
	});

	$('#upload-trigger').on('dragover', (e) => {
		e.preventDefault();
		return false;
	});

	$('#upload-trigger').on('dragleave', (e) => {
		shake($('#upload-trigger'), 10);
		e.preventDefault();
		return false;
	});

	$('#upload-trigger').on('drop', (e) => {
		if(e.originalEvent.dataTransfer) {
			if(e.originalEvent.dataTransfer.files.length) {
				e.preventDefault();
				//Upload
				upload(e.originalEvent.dataTransfer.files[0]);
			}
		}
		return false;
	});

	//Input file event
	$('#inputFile').on('change', (e) => {
		//Upload
		upload($('#inputFile')[0].files[0]);
	});

});
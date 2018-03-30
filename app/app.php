<?php

/**
 * Return a stream from a page
 */
function render(String $_path, $_response, Array $_vars = []) {

	$_path = __DIR__.'/view/'.$_path;

	if(!file_exists($_path)) return;

	foreach($_vars as $key => $value) {
		if(!is_array($value))
			${$key} = $value;
	}

	ob_start();
    require $_path;
    $stream = ob_get_clean();

    $body = $_response->getBody();
    $body->write($stream);

    return $_response->withBody($body);

}

/**
 * Convert to byte from config file
 */
function convertToByte(String $value) {

	$number = substr($value, 0, -2);

	switch(strtoupper(substr($value, -2))) {
		case 'KB': return $number*1024;
		case 'MB': return $number*pow(1024, 2);
		case 'GB': return $number*pow(1024, 3);
		case 'TB': return $number*pow(1024, 4);
		default:   return false;
	}

}
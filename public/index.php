<?php

/**
 * Setup
 */

//Error handling
# error_reporting(E_ALL);
# ini_set('display_errors', 1);

//Date timezone
date_default_timezone_set('Europe/Paris');

//Config
if(file_exists(__DIR__.'/../config.json')) {
	$config = json_decode(file_get_contents(__DIR__.'/../config.json'), true);
} else die('Config file not found');

//Run the app
use Zend\Diactoros\ServerRequestFactory as Request,
	Zend\Diactoros\Response as Response,
	Zend\Diactoros\Response\SapiEmitter as Emitter;

require __DIR__.'/../vendor/autoload.php';
require __DIR__.'/../app/routing.php';

(new Emitter())->emit($app->run(
	Request::fromGlobals(
	    $_SERVER,
	    $_GET,
	    $_POST,
	    $_COOKIE,
	    $_FILES
	),
	new Response(
		'php://memory',
		200,
		[
			'Content-Type' => 'text/html'
		]
	)
));
<?php

/**
 * Routing page
 */

use PsrRouter\PsrRouter as Router;

require 'app.php';

$app = new Router();

/**
 * 404 error page
 */
$app->setParam('404', function($req, $res) use($config) {

	return render(
		'404.php',
		$res,
		array_merge(
			$config,
			['path' => $req->getRequestTarget()]
		)
	)->withStatus(404);
	
});

/**
 * Index page
 */
$app->get('/', function($req, $res) use($config) {

	return render(
		'index.php',
		$res,
		array_merge(
			$config,
			['path' => $req->getRequestTarget()]
		)
	);
	
});

/**
 * About page
 */
$app->get('/about', function($req, $res) use($config) {

	return render(
		'about.php',
		$res,
		array_merge(
			$config,
			['path' => $req->getRequestTarget()]
		)
	);
	
});
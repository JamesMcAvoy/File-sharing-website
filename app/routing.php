<?php

/**
 * Routing file
 */

use PsrRouter\PsrRouter as Router;

//controllers functions
require_once __DIR__.'/controllers.php';

$app = new Router();

/**
 * 404 error page + /login page + route sensitive
 */
$app->setParam('404', function($req, $res) use($config) {

	return error404($req, $res, $config);
	
});

$app->get('/login', function($req, $res) use($config) {

	return error404($req, $res, $config);
	
});

$app->setParam('CASE_SENSITIVE', true);

/**
 * Index page
 */
$app->get('/', function($req, $res) use($config) {

	return indexController($req, $res, $config);
	
});

/**
 * About page
 */
$app->get('/about', function($req, $res) use($config) {

	return aboutController($req, $res, $config);
	
});

/**
 * Register page
 */
$app->get('/register', function($req, $res) use($config) {

	return registerController($req, $res, $config);

});

/**
 * Logout page
 */
$app->get('/logout', function($req, $res) use($config) {

	return logoutController($req, $res, $config);

});

/**
 * POST
 */

/**
 * Register user
 */
$app->post('/register', function($req, $res) use($config) {

	return registerPostController($req, $res, $config);

});

/**
 * Login page
 */
$app->post('/login', function($req, $res) use($config) {

	return loginPostController($req, $res, $config);

});

/**
 * Route for getting a file
 */
$app->get('/{file}', function($req, $res, $slug) use($config) {

	return getFileController($req, $res, array_merge($config, ['file' => $slug['file']]));

})->regex('/[a-zA-Z0-9.]{6,10}/');

//API routes included
require_once __DIR__.'/api/api_routes.php';
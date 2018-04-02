<?php

//API controllers
require __DIR__.'/api_actions.php';

/**
 * POST request
 * Post a file
 * Content-type : multipart/form-data
 * Parameters : file, apikey
 */
$app->post('/api/upload', function($req, $res) use($config) {

	return apiUpload($req, $res, $config);

});

/**
 * GET request
 * Return infos on files
 * Parameter : offset, apikey
 */
$app->get('/api/getUploads', function($req, $res) use($config) {

	return apiGetUploads($req, $res, $config);

});

/**
 * Handle errors related to the api
 */
$app->get('/api/upload', function($req, $res) {
	return apiError($res);
});
$app->delete('/api/upload', function($req, $res) {
	return apiError($res);
});
$app->post('/api/getUploads', function($req, $res) {
	return apiError($res);
});
$app->delete('/api/getUploads', function($req, $res) {
	return apiError($res);
});
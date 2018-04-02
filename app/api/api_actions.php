<?php

/**
 * Return an error related to the API
 */
function apiError($res, $code = 405, $msg = 'Method not allowed') {

	$json = json_encode(array(
		'success' => false,
		'msg'	  => $msg
	));

	$body = $res->getBody();
	$body->write($json);

	return $res->withBody($body)->withStatus($code)->withHeader('Content-Type', 'application/json');

}

/**
 * POST API upload controller
 */
function apiUpload($req, $res, $config) {

	$db = connect($config);
	if(is_string($db)) return apiError($res, 503, $db);

	$apikey = $req->getParsedBody();
	if(!(!empty($apikey['apikey']) && apikeyExists($db, $apikey['apikey'])))
		return apiError($res, 400, 'Invalid apikey');
	else $apikey = $apikey['apikey'];

	if(empty($req->getUploadedFiles()['file']))
		return apiError($res, 400, 'No file provided');

	$file = $req->getUploadedFiles()['file'];

	//Errors from file : http://php.net/manual/en/features.file-upload.errors.php
	if(in_array($file->getError(), [1,2]))
		return apiError($res, 413, $file::ERROR_MESSAGES[$file->getError()]);

	elseif($file->getError() != 0)
		return apiError($res, 400, $file::ERROR_MESSAGES[$file->getError()]);

	//User not allowed
	if(!isAllowedToUpload($db, $apikey))
		return apiError($res, 403, 'You are not allowed to upload');

	$last = getLastTimestamp($db, $apikey);
	$last = ($last+$config['timeBeforeNewUpload']/1000)-microtime(true);
	if($last > 0)
		return apiError($res, 429, 'Please wait '.round($last, 2).'s before a new upload');

	//Vars
	$size = $file->getSize();
	$name = $file->getClientFilename();
	$media = $file->getClientMediaType();
	$stream = $file->getStream()->getContents();

	//Max sizes supported from config file
	$websiteMaxSize = convertToBytes($config['websiteMaxSize']);
	$accountMaxSize = convertToBytes($config['accountMaxSize']);
	$uploadMaxSize  = convertToBytes($config['uploadMaxSize']);

	if($size > $uploadMaxSize)
		return apiError($res, 413, 'The uploaded file exceeds the uploadMaxSize directive from the website');

	elseif($size + getSizeUsedFromApikey($db, $apikey) > $accountMaxSize)
		return apiError($res, 413, 'You have reached your limit. Please delete some of your files');

	elseif($size + getTotalSizeUsed($db) > $websiteMaxSize)
		return apiError($res, 413, 'The website has reached its limit');

	$hash = md5($stream);
	$filename = createFilename($db, $name);

	if(strlen($name) > 40) $name = substr($name, 0, 37).'...';

	upload($db, $stream, $hash, $apikey, $filename, $media, $size, $name);

	$body = $res->getBody();
	$body->write(json_encode(array(
		'success' => true,
		'msg'	  => $filename
	)));

	//Return 201 code (created)
	return $res->withBody($body)->withStatus(201)->withHeader('Content-Type', 'application/json');

}

/**
 * GET API uploads controller
 */
function apiGetUploads($req, $res, $config) {

	$params = $req->getQueryParams();

	if(!isset($params['offset']) || empty($params['apikey']))
		return apiError($res, 400, 'Empty fields');

	$offset = (int) $params['offset'];

	if($offset<0)
		return apiError($res, 400, 'Invalid offset');

	$db = connect($config);
	if(is_string($db)) return apiError($res, 503, $db);

	if($offset >= 1) $offset--;

	$offset *= $config['limitFilesPerPage'];

	//Return list
	$data = getUploads($db, $params['apikey'], $offset, $config['limitFilesPerPage']);

	$body = $res->getBody();
	$body->write(json_encode(array(
		'success' => true,
		'msg'	  => $data
	)));
	
	return $res->withBody($body)->withHeader('Content-Type', 'application/json');

}
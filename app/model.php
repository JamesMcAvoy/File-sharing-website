<?php

/**
 * Connection to the DB
 */
function connect($config) {
	try {
		return new PDO('mysql:host='.$config['dbHost'].';port='.$config['dbPort'].';dbname='.$config['dbName'].';charset=utf8', $config['dbUser'], $config['dbPass']);
	} catch(Exception $e) {
		return 'Error : '.$e->getMessage();
	}
}

/**
 * Return if name and email are not used
 */
function areCorrectNameEmail($db, $name, $email) {

	$req = $db->prepare('CALL do_name_email_exist(:name, :email, @response)');
	$req->bindParam(':name', $name, PDO::PARAM_STR, 64);
	$req->bindParam(':email', $email, PDO::PARAM_STR, 64);
	$req->execute();
	$req->closeCursor();
	return ($db->query('SELECT @response AS response')->fetch(PDO::FETCH_ASSOC)['response'] == '0');

}

/**
 * Return if name is in the DB
 */
function nameExists($db, $name) {

	$req = $db->prepare('CALL does_name_exist(:name, @response)');
	$req->bindParam(':name', $name, PDO::PARAM_STR, 64);
	$req->execute();
	$req->closeCursor();
	return ($db->query('SELECT @response AS response')->fetch(PDO::FETCH_ASSOC)['response'] == '1');

}

/**
 * Return if filename is in the DB
 */
function filenameExists($db, $filename) {

	$req = $db->prepare('CALL does_filename_exist(:filename, @response)');
	$req->bindParam(':filename', $filename, PDO::PARAM_STR, 128);
	$req->execute();
	$req->closeCursor();
	return ($db->query('SELECT @response AS response')->fetch(PDO::FETCH_ASSOC)['response'] == '1');

}

/**
 * Return if an api key is in the DB
 */
function apikeyExists($db, $apikey) {

	$req = $db->prepare('CALL does_apikey_exist(:apikey, @response)');
	$req->bindParam(':apikey', $apikey, PDO::PARAM_STR, 256);
	$req->execute();
	$req->closeCursor();
	return ($db->query('SELECT @response AS response')->fetch(PDO::FETCH_ASSOC)['response'] == '1');

}

/**
 * Return the pass of the username to validate it with password_verify()
 */
function getPassFromUser($db, $name) {

	$req = $db->prepare('CALL get_password_from_name(:name, @response)');
	$req->bindParam(':name', $name, PDO::PARAM_STR, 64);
	$req->execute();
	$req->closeCursor();
	return $db->query('SELECT @response AS response')->fetch(PDO::FETCH_ASSOC)['response'];

}

/**
 * Return the api key of the username
 */
function getApikeyFromUser($db, $name) {

	$req = $db->prepare('CALL get_apikey_from_name(:name, @response)');
	$req->bindParam(':name', $name, PDO::PARAM_STR, 64);
	$req->execute();
	$req->closeCursor();
	return $db->query('SELECT @response AS response')->fetch(PDO::FETCH_ASSOC)['response'];

}

/**
 * Return the size used in bytes of the user
 */
function getSizeUsedFromApikey($db, $apikey) {

	$req = $db->prepare('CALL get_size_used_from_apikey(:apikey, @response)');
	$req->bindParam(':apikey', $apikey, PDO::PARAM_STR, 256);
	$req->execute();
	$req->closeCursor();
	return $db->query('SELECT @response AS response')->fetch(PDO::FETCH_ASSOC)['response'];

}

/**
 * Return the total size used
 */
function getTotalSizeUsed($db) {

	$req = $db->prepare('CALL get_total_size_used(@response)');
	$req->execute();
	$req->closeCursor();
	return $db->query('SELECT @response AS response')->fetch(PDO::FETCH_ASSOC)['response'];

}

/**
 * Return the timestamp of the last upload
 */
function getLastTimestamp($db, $apikey) {

	$req = $db->prepare('CALL get_last_upload_from_apikey(:apikey, @response)');
	$req->bindParam(':apikey', $apikey, PDO::PARAM_STR, 256);
	$req->execute();
	$req->closeCursor();
	$return = $db->query('SELECT @response AS response')->fetch(PDO::FETCH_ASSOC)['response'];
	return is_null($return) ? 0 : strtotime($return); 

}

/**
 * Return if the user is allowed to upload
 */
function isAllowedToUpload($db, $apikey) {

	$req = $db->prepare('CALL is_allowed(:apikey, @response)');
	$req->bindParam(':apikey', $apikey, PDO::PARAM_STR, 256);
	$req->execute();
	$req->closeCursor();
	return ($db->query('SELECT @response AS response')->fetch(PDO::FETCH_ASSOC)['response'] == '1');

}

/**
 * Create an account
 */
function createAccount($db, $name, $email, $pass, $apikey) {

	$req = $db->prepare('CALL create_user(:name, :email, :pass, :apikey)');
	$req->bindParam(':name', $name, PDO::PARAM_STR, 64);
	$req->bindParam(':email', $email, PDO::PARAM_STR, 64);
	$req->bindParam(':pass', $pass, PDO::PARAM_STR, 128);
	$req->bindParam(':apikey', $apikey, PDO::PARAM_STR, 256);
	$req->execute();
	$req->closeCursor();

}

/**
 * Upload a file
 */
function upload($db, $stream, $hash, $apikey, $filename, $mediaType, $size, $name) {

	$req = $db->prepare('CALL upload_file(:stream, :hash, :apikey, :filename, :mediaType, :size, :name)');
	$req->bindParam(':stream', $stream, PDO::PARAM_LOB);
	$req->bindParam(':hash', $hash, PDO::PARAM_STR, 128);
	$req->bindParam(':apikey', $apikey, PDO::PARAM_STR, 256);
	$req->bindParam(':filename', $filename, PDO::PARAM_STR, 64);
	$req->bindParam(':mediaType', $mediaType, PDO::PARAM_STR, 64);
	$req->bindParam(':size', $size, PDO::PARAM_INT, 11);
	$req->bindParam(':name', $name, PDO::PARAM_STR, 64);
	$req->execute();
	$req->closeCursor();

}

/**
 * Return a file and its blob/mime type
 */
function getFile($db, $filename) {

	$req = $db->prepare('CALL  get_stream_media_date_size_from_filename(:filename, @r_stream, @r_media, @r_date, @r_size)');
	$req->bindParam(':filename', $filename, PDO::PARAM_STR, 64);
	$req->execute();
	$req->closeCursor();
	$stream = $db->query('SELECT @r_stream AS r_stream')->fetch(PDO::FETCH_ASSOC)['r_stream'];
	$media = $db->query('SELECT @r_media AS r_media')->fetch(PDO::FETCH_ASSOC)['r_media'];
	$date = $db->query('SELECT @r_date AS r_date')->fetch(PDO::FETCH_ASSOC)['r_date'];
	$size = $db->query('SELECT @r_size AS r_size')->fetch(PDO::FETCH_ASSOC)['r_size'];
	return [$stream, $media, $date, $size];

}

/**
 * Return infos from a user (size used/files uploaded)
 */
function getInfosUser($db, $apikey) {

	$req = $db->prepare('CALL get_infos_user(:apikey, @r_size, @r_number)');
	$req->bindParam(':apikey', $apikey, PDO::PARAM_STR, 256);
	$req->execute();
	$req->closeCursor();
	$size = $db->query('SELECT @r_size AS r_size')->fetch(PDO::FETCH_ASSOC)['r_size'];
	$number = $db->query('SELECT @r_number AS r_number')->fetch(PDO::FETCH_ASSOC)['r_number'];
	return [$size, $number]; 

}

/**
 * Return file list from offset
 */
function getUploads($db, $apikey, $offset, $limit) {

	$req = $db->prepare('CALL get_uploads_list_from_apikey_offset(:apikey, :offset, :limit)');
	$req->bindParam(':apikey', $apikey, PDO::PARAM_STR, 256);
	$req->bindParam(':offset', $offset, PDO::PARAM_INT, 11);
	$req->bindParam(':limit', $limit, PDO::PARAM_INT, 11);
	$req->execute();
	$tmp = $req->fetchAll(PDO::FETCH_ASSOC);
	$req->closeCursor();
	$return = [];

	foreach($tmp as $key => $value) {
		$return[$key] = array(
			'origin' => htmlentities($value['origin_name']),
			'filename' => $value['file_name'],
			'mediatype' => $value['media_type'],
			'timestamp' => strtotime($value['date'])
		);
	}

	return $return;

}
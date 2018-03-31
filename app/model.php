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
 * Return if api key is in the DB
 */
function apikeyExists($db, $apikey) {

	$req = $db->prepare('CALL does_apikey_exist(:apikey, @response)');
	$req->bindParam(':apikey', $apikey, PDO::PARAM_STR, 64);
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
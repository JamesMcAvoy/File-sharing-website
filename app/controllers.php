<?php

//database connection
require __DIR__.'/model.php';

/**
 * Return a stream from a page
 */
function render(String $_path, $_res, Array $_vars = []) {

	$_path = __DIR__.'/view/'.$_path;

	if(!file_exists($_path)) return;

	foreach($_vars as $key => $value) {
		if(!is_array($value))
			${$key} = $value;
	}

	ob_start();
	require $_path;
	$stream = ob_get_clean();

	$_body = $_res->getBody();
	$_body->write($stream);

	return $_res->withBody($_body);

}

/**
 * Convert to bytes from config file
 */
function convertToBytes(String $value) {

	$number = substr($value, 0, -2);

	switch(strtoupper(substr($value, -2))) {
		case 'KB': return $number*1024;
		case 'MB': return $number*pow(1024, 2);
		case 'GB': return $number*pow(1024, 3);
		case 'TB': return $number*pow(1024, 4);
		default:   return false;
	}

}

/**
 * Generate an api key
 */
function createToken() {
	return base64_encode(openssl_random_pseudo_bytes(64));
}

/**
 * Generate a filename to store in the DB and to access to the file
 */
function createFilename($db, $oldfilename) {

	if(isset(pathinfo($oldfilename)['extension']) && strlen(pathinfo($oldfilename)['extension']) <= 4) {
		do {
			$new = substr(str_shuffle('azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN0123456789'), 0, 5).'.'.pathinfo($oldfilename)['extension'];
		} while(filenameExists($db, $new));
	} else {
		do {
			$new = substr(str_shuffle('azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN0123456789'), 0, 6);
		} while(filenameExists($db, $new));
	}
	return $new;

}

/**
 * 404 error renderer
 */
function error404($req, $res, $config) {

	$session = $req->getCookieParams();

	$db = connect($config);

	if(is_string($db)) {
		return render('404.php', $res, array_merge(
				$config,
				['path' => $req->getRequestTarget()]
			)
		)->withStatus(404);
	}

	if(!empty($session[$config['cookieName']]) && apikeyExists($db, $session[$config['cookieName']])) {
		return render('404.php', $res, array_merge(
				$config,
				[
					'path' => $req->getRequestTarget(),
					'logged' => true
				]
			)
		)->withStatus(404);
	}

	return render('404.php', $res, array_merge(
			$config,
			['path' => $req->getRequestTarget()]
		)
	)->withStatus(404);

}

/**
 * Index controller
 */
function indexController($req, $res, $config) {

	$session = $req->getCookieParams();

	$db = connect($config);

	if(is_string($db)) {
		return render('index.php', $res, array_merge($config,
			[
				'path' => '/',
				'errorLogin' => $db
			]
		));
	}

	if(!empty($session[$config['cookieName']]) && apikeyExists($db, $session[$config['cookieName']])) {
		return render('user.php', $res, array_merge(
				$config,
				[
					'path' => $req->getRequestTarget(),
					'logged' => true,
					'cookie' => $session[$config['cookieName']]
				]
			)
		);
	}

	return render(
		'index.php',
		$res,
		array_merge(
			$config,
			['path' => $req->getRequestTarget()]
		)
	);

}

/**
 * About page renderer
 */
function aboutController($req, $res, $config) {

	$session = $req->getCookieParams();

	$db = connect($config);

	if(is_string($db)) {
		return render('about.php', $res, array_merge(
				$config,
				['path' => $req->getRequestTarget()]
			)
		);
	}

	if(!empty($session[$config['cookieName']]) && apikeyExists($db, $session[$config['cookieName']])) {
		return render('about.php', $res, array_merge(
				$config,
				[
					'path' => $req->getRequestTarget(),
					'logged' => true
				]
			)
		);
	}

	return render('about.php', $res, array_merge(
			$config,
			['path' => $req->getRequestTarget()]
		)
	);

}

/**
 * Register page renderer
 */
function registerController($req, $res, $config) {

	$session = $req->getCookieParams();

	$db = connect($config);

	if(is_string($db)) {
		return render('error.php', $res, array_merge($config,
			[
				'errorMsg' => $db,
				'path'	   => $req->getRequestTarget()
			]
		));
	}

	if(!empty($session[$config['cookieName']]) && apikeyExists($db, $session[$config['cookieName']])) {
		return $res->withStatus(302)->withHeader('Location', '/');
	}

	return render(
		'register.php',
		$res,
		array_merge(
			$config,
			[
				'path' => $req->getRequestTarget(),
				'displayCaptcha' => true
			]
		)
	);

}

/**
 * Logout controller
 */
function logoutController($req, $res, $config) {

	return $res->withStatus(302)->withHeader('Location', '/')->withHeader('Set-Cookie', "{$config['cookieName']}=; expires=Thu, 01 Jan 1970 00:00:00 GMT");

}

/**
 * Register : post controller
 */
function registerPostController($req, $res, $config) {

	//If new account not allowed
	if(!$config['allowNewAccounts']) {
		return render('error.php', $res, array_merge($config,
			[
				'errorMsg' => 'New accounts are disabled.',
				'path'	   => $req->getRequestTarget(),
				'displayCaptcha' => true
			]
		));
	}

	$db = connect($config);

	//If database error
	if(is_string($db)) {
		return render('error.php', $res, array_merge($config,
			[
				'errorMsg' => $db,
				'path'	   => $req->getRequestTarget(),
				'displayCaptcha' => true
			]
		));
	}

	//Check errors
	$post = $req->getParsedBody();
	$errors = array();

	if(empty($post['name'])) {
		$post['name'] = '';
		$errors[] = 'Empty name';
	} elseif(!preg_match('/^[a-z0-9-_]{3,15}$/i', $post['name'])) {
		$post['name'] = htmlentities($post['name']);
		$errors[] = 'Invalid name';
	}

	if(!filter_var($post["email"], FILTER_VALIDATE_EMAIL)) {
		$post['email'] = htmlentities($post['email']);
		$errors[] = 'Invalid email';
	}

	if(empty($post['pass'])) {
		$errors[] = 'Empty pass';
	}

	if(empty($post['g-recaptcha-response'])) {
		$errors[] = 'Invalid captcha';
	} else {
		//Captcha verification
		$verify = file_get_contents(
			 'https://www.google.com/recaptcha/api/siteverify?secret='
			.$config['recaptcha_private_key']
			.'&response='
			.$_POST['g-recaptcha-response']
		);
		$res_captcha = json_decode($verify);
		if(!$res_captcha->success) $errors[] = 'Invalid captcha';
	}

	if(!empty($errors)) {
		return render('error.php', $res, array_merge($config,
			[
				'errorMsg' => implode('<br />', $errors),
				'path'	   => $req->getRequestTarget(),
				'displayCaptcha' => true,
				'name' => $post['name'],
				'email' => $post['email']
			]
		));
	}

	//If name/email taken
	if(!areCorrectNameEmail($db, $post['name'], $post['email'])) {
		return render('error.php', $res, array_merge($config,
			[
				'errorMsg' => 'Email or name already taken',
				'path'	   => $req->getRequestTarget(),
				'displayCaptcha' => true,
				'name' => $post['name'],
				'email' => $post['email']
			]
		));
	}

	$pass = password_hash($post['pass'], PASSWORD_BCRYPT);
	$token = createToken();
	createAccount($db, $post['name'], $post['email'], $pass, $token);

	return render('index.php', $res, array_merge($config,
		[
			'path' => $req->getRequestTarget(),
			'accountCreated' => 'true'
		]
	));

}

/**
 * Login : post controller
 */
function loginPostController($req, $res, $config) {

	$db = connect($config);

	//If database error
	if(is_string($db)) {
		return render('index.php', $res, array_merge($config,
			[
				'errorLogin' => $db,
				'path'	   => '/'
			]
		));
	}

	$post = $req->getParsedBody();

	//Empty name
	if(empty($post['name'])) {
		return render('index.php', $res, array_merge($config,
			[
				'path' => '/',
				'errorLogin' => 'Empty name'
			]
		));
	}

	//Invalid name
	if(!nameExists($db, $post['name'])) {
		return render('index.php', $res, array_merge($config,
			[
				'path' => '/',
				'errorLogin' => 'Wrong username'
			]
		));
	}

	//Invalid password
	if(!password_verify($post['pass'], getPassFromUser($db, $post['name']))) {
		return render('index.php', $res, array_merge($config,
			[
				'path' => '/',
				'errorLogin' => 'Wrong password'
			]
		));
	}

	$token = getApikeyFromUser($db, $post['name']);

	return $res->withStatus(302)->withHeader('Location', '/')->withHeader('Set-Cookie', "{$config['cookieName']}=$token");

}

/**
 * Controller for getting files
 */
function getFileController($req, $res, $config) {

	$db = connect($config);

	//If database error
	if(is_string($db)) {
		return render('404.php', $res, array_merge($config,
			[
				'path'	   => '/'
			]
		));
	}

	//If filename does not exist
	if(!filenameExists($db, $config['file'])) {
		return render('404.php', $res, array_merge($config,
			[
				'path'	   => '/'
			]
		));
	}

	list($stream, $media) = getFile($db, $config['file']);
	$body = $res->getBody();
	$body->write($stream);

	$cacheTime = 60*60*24;
	$t = gmdate('D, d M Y H:i:s ', time() + $cacheTime).'GMT';

	return $res->withBody($body)
			    ->withHeader('Content-Type', $media)
			    ->withHeader('Expires', $t)
			    ->withHeader('Pragma', 'cache')
			    ->withHeader('Cache-control', "max-age=$cacheTime");

}
<?php

require __DIR__.'/../vendor/autoload.php';

$request = Zend\Diactoros\ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);

$response = new Zend\Diactoros\Response('php://memory', 200, [
	'Content-Type' => 'text/html'
]);

$emitter = new Zend\Diactoros\Response\SapiEmitter();

$app = new PsrRouter\PsrRouter();

$app->get('/', function($req, $res) {
	$body = $res->getBody();
	$body->write("Index\n");
	return $res->withBody($body);

});

$emitter->emit($app->run($request, $response));
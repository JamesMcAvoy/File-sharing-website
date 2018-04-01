<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="description" content="File sharing website">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= $websiteName ?></title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" />
        <?= isset($logged) ? '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />'.PHP_EOL : '' ; ?>

        <link rel="stylesheet" href="/css/style.css" />
    </head>
    <body class="text-center">
    
    	<div class="cover-container d-flex h-100 p-3 mx-auto flex-column">
			<header class="masthead mb-auto">
				<div class="inner">
					<h3 class="masthead-brand"><?= ucfirst($websiteName) ?></h3>
					<nav class="nav nav-masthead justify-content-center">
						<a class="nav-link<?= ($path == '/') ? ' active' : '' ?>" href="/">Home</a>
						<a class="nav-link<?= ($path == '/about') ? ' active' : '' ?>" href="/about">About</a>
                        <?= isset($logged) ? '<a class="nav-link" href="/logout">Logout</a>'.PHP_EOL : '' ; ?>

					</nav>
				</div>
			</header>

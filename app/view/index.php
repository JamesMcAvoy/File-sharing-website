<?php include 'head.include.php'; ?>

			<div role="main" class="inner cover">
				<?= isset($accountCreated) ? '<p>Account created. You can log in.</p>'.PHP_EOL : '' ; ?>

				<?= isset($errorLogin) ? "<p>$errorLogin</p>".PHP_EOL : '' ; ?>

				<h2 class="cover-index cover-heading">Log in</h2>
				<form id="form" action="login" method="post">
					<input type="text" id="name" name="name" placeholder="Enter your name" class="form-control" required />
					<input type="password" id="pass" name="pass" placeholder="Enter your pass" class="form-control" required />
					<input type="submit" id="login" value="Submit" class="form-control btn btn-md btn-secondary" required />
				</form>

				<?= !isset($accountCreated) ? '<h4 class="cover-index cover-heading">Or <a href="/register">create an account</a></h4>'.PHP_EOL : '' ; ?>
			
			</div>

<?php include 'foot.include.php'; ?>
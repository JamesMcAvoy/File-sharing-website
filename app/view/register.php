<?php include 'head.include.php'; ?>

			<div role="main" class="inner cover">
				<p class="lead">Here you can fill a form to create an account.
				<br />Each user can upload up to <?= $accountMaxSize; ?>. After that, your old files get deleted as you upload new files.
				<br />Your name must have between 3 and 15 alphanumeric characters.</p>
				<h2 class="cover-index cover-heading">Create a new account</h2>
				<form id="form" action="register" method="post">
					<input type="text" id="name" name="name" placeholder="Choose a name" class="form-control" required />
					<input type="email" id="email" name="email" placeholder="Enter your email" class="form-control" required />
					<input type="password" id="pass" name="pass" placeholder="Choose a password" class="form-control" required />
					<input type="password" id="passc" placeholder="Confirm your password" class="form-control" required />
					<div class="register-captcha">
						<div class="g-recaptcha" data-sitekey="<?= $recaptcha_public_key; ?>"></div>
					</div>
					<input type="submit" id="register" value="Register" class="form-control btn btn-md btn-secondary" required />
				</form>
			</div>

<?php include 'foot.include.php'; ?>
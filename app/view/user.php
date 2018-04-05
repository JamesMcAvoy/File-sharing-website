<?php include 'head.include.php'; ?>

			<div role="main" class="inner cover logged">
				<h1 class="cover-heading">Your uploads</h1>
				<p>Showing files <span id="start"></span> to <span id="end"></span> (total: <span id="total"></span>, per page <span id="each"></span>)</p>
				<div class="pages">
					<span id="first" class="clickable inPages"><i class="fa fa-angle-double-left"></i></span>
					<span id="prev" class="clickable inPages"><i class="fa fa-angle-left"></i></span>
					<div id="act" class="inPages">
						<input id="pageInput" class="pageInput" type="text" maxlength="4" />
					</div>
					<span id="next" class="clickable inPages"><i class="fa fa-angle-right"></i></span>
					<span id="last" class="clickable inPages"><i class="fa fa-angle-double-right"></i></span>
				</div>

				<div id="uploads">
					
				</div>

				<script>
					filesPerPage = <?= $limitFilesPerPage ?>;
					page = 1;
					total = <?= $number ?>;
				</script>

				<p class="lead">
					<button id="upload-display" class="btn btn-lg btn-secondary">Upload a file</button>
				</p>
				<button id="infos-user-display" class="btn btn-md btn-secondary">Access my infos</button>
			</div>

			<div class="info-upload" style="display:none">
				<div id="upload-trigger"></div>
				<i class="fa fa-close" id="close-upload"></i>
				<div class="info-upload-footer">
					<div class="info-upload-footer-top">
						<input type="file" name="file" id="inputFile" class="inputfile" />
						<input type="hidden" id="cookie" value="<?= $cookie ?>" />
						<label for="inputFile" class="labelfile">Browse</label>
						<span>or drag a file</span>
					</div>
					<div class="info-upload-footer-bottom">
						<div id="upload-name"></div>
						<div id="upload-bar" class="progress" style="display:none">
							<div class="progress-bar progress-dark" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">0%</div>
						</div>
						<div id="upload-msg"></div>
					</div>
				</div>
			</div>

			<div class="infos-file" style="display: none">
				<i class="fa fa-close" id="close-infos-file"></i>
				<table class="infos-file-table">
					<tbody>
						<tr>
							<td>Name</td>
							<td id="file-name"></td>
						</tr>
						<tr>
							<td>Url</td>
							<td id="file-url"></td>
						</tr>
						<tr>
							<td>Type</td>
							<td id="file-type"></td>
						</tr>
						<tr>
							<td>Size</td>
							<td id="file-size"></td>
						</tr>
						<tr>
							<td>Date</td>
							<td id="file-date"></td>
						</tr>
						<tr>
							<td>Important</td>
							<td id="file-important"></td>
						</tr>
					</tbody>
				</table>
				<input type="hidden" id="file-tmp-id" />
				<p class="m-3">
					Important files are only deleted after regular files when you reach your upload limit.<br />
					<a href="#" id="make-important"></a>
				</p>
				<p class="m-2">
					<a href="#" id="delete-file">Delete this file (WORK IN PROGRESS) <i class="fa fa-trash" style="color:#b82525"></i></a>
				</p>
			</div>

			<div class="infos-user" style="display: none">
				<i class="fa fa-close" id="close-infos"></i>
				<p>
					Size limit : <span id="size-limit"><?= $accountMaxSize ?></span><br />
					Limit per upload : <span id="limit-upload"><?= $uploadMaxSize ?></span><br />
					Current size used : <span id="size-used"><?= $size ?></span><br />
					Files uploaded : <span id="files-uploaded"><?= $number ?></span><br />
					Your API key : <code class="apikey-copy"><?= $cookie ?></code> (click to copy)
				</p>
				<button id="reset-apikey" class="btn btn-md btn-secondary">Reset API key (working on)</button>
				<form action="api/changePassword" method="post">
					<div class="formContainer">
						<div class="inForm">Old password</div>
						<div class="inFormInput">
							<input type="password" name="old" />
						</div>
						<div class="inForm">New password</div>
						<div class="inFormInput">
							<input type="password" name="new" id="pwd">
						</div>
						<div class="inForm">Please Confirm</div>
						<div class="inFormInput">
							<input type="password" id="pwdc">
						</div>
					</div>
					<input type="submit" id="reset-password" class="btn btn-md btn-secondary" value="Change your password (working on)" />
				</form>
			</div>

<?php include 'foot.include.php'; ?>
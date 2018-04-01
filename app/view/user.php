<?php include 'head.include.php'; ?>

			<div role="main" class="inner cover">
				<h1 class="cover-heading">Your uploads</h1>
				<p>Showing files 0 to 0 (total: 0)</p>
				<div class="pages">
					<span id="first" class="clickable inPages"><i class="fa fa-angle-double-left"></i></span>
					<span id="prev" class="clickable inPages"><i class="fa fa-angle-left"></i></span>
					<div id="act" class="inPages">
						<input id="pageInput" class="pageInput" type="text" maxlength="4" />
					</div>
					<span id="next" class="clickable inPages"><i class="fa fa-angle-right"></i></span>
					<span id="last" class="clickable inPages"><i class="fa fa-angle-double-right"></i></span>
				</div>

				<p class="lead">
					<button id="upload-display" class="btn btn-lg btn-secondary">Upload a file</button>
				</p>
			</div>

			<div class="info-upload" style="display:none">
				<div id="upload-trigger"></div>
				<i class="fa fa-close" id="close"></i>
				<div class="info-upload-footer">
					<div class="info-upload-footer-top">
						<input type="file" name="file" id="inputFile" class="inputfile" />
						<input type="hidden" id="cookie" value="<?= isset($cookie) ? $cookie : '' ; ?>" />
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

<?php include 'foot.include.php'; ?>
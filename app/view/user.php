<?php include 'head.include.php'; ?>

			<div role="main" class="inner cover">
				<h1 class="cover-heading">Your uploads</h1>
				<p>Showing files 0 to 0 (total: 0)</p>
				<div class="pages">
					<span id="first" class="clickable inPages">‹‹</span>
					<span id="prev" class="clickable inPages">‹</span>
					<div id="act" class="inPages">
						<input id="pageInput" class="pageInput" type="text" maxlength="4" />
					</div>
					<span id="next" class="clickable inPages">›</span>
					<span id="last" class="clickable inPages">››</span>
				</div>

				<p class="lead">
					<button id="upload-trigger" class="btn btn-lg btn-secondary">Upload a file</button>
				</p>
			</div>

			<div class="info-upload" style="display:none">
				<div class="info-upload-dotted"></div>
				<i class="fa fa-close" id="close"></i>
				<div class="info-upload-footer">
					<input type="file" name="file" id="file" class="inputfile" />
					<label for="file" class="labelfile">Browse</label>
					<span>or drag a file</span>
				</div>
			</div>

<?php include 'foot.include.php'; ?>
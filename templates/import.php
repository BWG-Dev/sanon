<?php
include get_stylesheet_directory() . '/templates/import-request.php'
?>

<div class="container-fluid">
	<form action="" method="post" enctype="multipart/form-data">
		<br><br>
		<div class="form-group row">
			<label style="margin-top: 10px" class="form-control-sm col-sm-2 mt-2">Upload file</label>
			<input type="file" class=" col-sm-9" name="file_import" id="file_import" >
		</div>


		<div class="form-group">
			<input type="submit" class="btn btn-info btn-sm" name="import_meeting" id="import_meeting_btn" value="Import Meeting">
		</div>

	</form>


</div>

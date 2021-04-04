
<div class="container">
	<div class="row">
		<div class="col-sm-12">
			<h1><i class="fas fa-upload"></i> View/Upload Spreadsheet<br>
			<small>In 3 simple steps!</small></h1>
		</div>
	</div><form id="form_selector" enctype="multipart/form-data">
	<div class="row" id="steps_row">
		
		<div class="col-lg-4" id="choose_file">

			<div class="card">
				<div class="card-header">
					Choose File
				</div>
				<div class="card-body">
					<div class="form-group">
						
							<input type="file" name="files_compiled" id="file_uploader" class="form-control">
						

					</div>
					<div class="form-group">
						<label>Allowed File Types:</label>
						<ul>
							<li>Excel Sheet (.xls,.xlsx)</li>
							
						</ul>
					</div>
				</div>
				</div>
			</div>
			<div class="col-lg-4" id="choose_template">
			<div class="card">
				<div class="card-header">
				Choose Template
				</div>
				<div class="card-body">
					<div id="template_selector">
						<ul class="nav nav-pills flex-column">
						  <li class="nav-item">
						    <a class="nav-link active" href="#">Air Blade</a>
						  </li>
						</ul>
					</div>
				</div>
			</div>
		</div>


		<div class="col-lg-4" id="change_header_row_settings">
			<div class="card">
				<div class="card-header">
				Choose Header Settings!
				</div>
				<div class="card-body">
					<div class="form-group">
						<label>Does this spreadsheet have a header</label>
						<select name="has_header" class="form-control" id="header_row">
							<option value="true" selected>Yes</option>
							<option value="false">No</option>
						</select>
					</div>
					<div class="form-group">
						<button class="btn btn-success" value="Add New Spreadsheet!" id="submit_btn"><i class="fas fa-plus-circle"></i> Add New Spreadsheet!</button>
					</div>
				</div>
			</div>
		</div>

		

		</div>

</form>



		




	</div>
</div>
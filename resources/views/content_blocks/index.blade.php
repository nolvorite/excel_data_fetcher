<div id="main_jumbo_wrap" class="main_jumbo_wrap">
<div class="jumbotron" id="index_jumbotron">
  <h1 class="display-4">{{ env('APP_NAME') }}</h1>
  <p class="lead">{{ env('APP_DESCRIPTION') }}</p>
  <hr class="my-4">
  <p class="lead">You can either:</p>
  <ul class="lead">
  	<li>Add a <a href='{{ link2("upload") }}'>new spreadsheet</a>, OR</li>
  	<li>View the list of all the <a href='{{ link2("file_list") }}'>spreadsheet files</a>.</li>
  </ul>

</div>
</div>

<div id="smd_wrapper" class="main_jumbo_wrap">
	<div class="jumbotron" id="index_jumbotron">
	  <h1 class="display-4">Manage Your Excel Files</h1>
	  <p class="lead">Quickly and Easily</p>
	  <hr class="my-4">
	  <ul class="lead">
	  	<li>Choose From Many Templates</li>
	  	<li>Choose From Your Favorite Header</li>
	  </ul>

	  <p class="lead"><a href="{{ link2('upload') }}">Upload a new spreadsheet!</a></p>

	</div>
</div>
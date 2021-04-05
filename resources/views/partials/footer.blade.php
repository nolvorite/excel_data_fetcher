</div>

@if($mode !== "iframe")

<div id="copyright" class="container-fluid">
	<div class="row">
	@if( Auth::user() )
	<div class="col-lg-6">
		<div class="form-group">
			<label>Sitemap</label>
			<ul>
		      <li>
		        <a class="nav-link" href="{{ env('APP_URL') }}"><i class="fas fa-home"></i>  Home</a>
		      </li>

		      @if(Auth::check())

		      <li >
		        <a class="nav-link" href="{{ link2('upload') }}"><i class="fas fa-upload"></i> Upload</a>
		      </li>
		      <li class="nav-item{{ $request->segment(1) === 'spreadsheets' ? ' active' : '' }}">
		        <a class="nav-link" href="{{ link2('spreadsheets') }}"><i class="far fa-chart-bar"></i> Spreadsheets</a>
		      </li>

		      @else

		       <li >
		        <a class="nav-link" href="{{ link2('login') }}"><i class="fas fa-lock"></i> Log In</a>
		      </li>

		      @endif

		        

		    </ul>
		</div>
	</div>
	@endif
	<div class="{{ Auth::user() ? 'col-lg-6' : 'col-lg-12'}}" id="copyright_text">
		Copyright © 2021 <a href='{{ link2('') }}'>{{ env('APP_NAME') }}</a>. Made By Hans Marcon.
	</div>
</div></div>

@endif

@include('partials.javascript')

</body>
</html>
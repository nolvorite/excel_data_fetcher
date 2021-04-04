<div id="spreadsheet_view" class="container-fluid">
	<div class="row">
		<div class="col-lg-3 colz" id="spreadsheet_menu">
			<h4><i class="far fa-chart-bar"></i> Spreadsheet Menu</h4>
			<ul class="nav nav-pills flex-column" id="spreadsheet_selector_ul">
				@foreach($ssList as $ss)
				  <li class="nav-item">
				    <a class="nav-link{{ ($ss->id.'' === $ssId.'') ? ' active' : '' }}" href="{{ link2('spreadsheets/'.$ss->id) }}">{{ $ss->original_name }}</a>
				  </li>
				 @endforeach
			</ul>
		</div>
		<div class="col-lg-9 colz" id="spreadsheet_block">
			<h3 id='loading_notice'>Loading Spreadsheet... Please Wait.</h3>
			<iframe src="{{ link2('file/view/'.$ssId) }}" id="spreadsheet_view_iframe"></iframe>
		</div>
	</div>
</div>
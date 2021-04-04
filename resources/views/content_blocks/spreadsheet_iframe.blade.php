
@include('partials/table/sheet_browser')

<div class="tab-content" id="myTabContent">

@foreach($sheets as $index => $sheet)

@include('partials/table/table')

@endforeach	

</div>
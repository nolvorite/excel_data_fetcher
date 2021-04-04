<ul class="nav nav-pills" id="sheet_browser" role="tablist">
  @foreach($sheets as $index => $sheet)
    <li class="nav-item">
      <a 
      class="nav-link{{ ($index === 0) ? ' active' : '' }}" 
      id="for_sheet{{ $sheet['meta']['sheet_index'] }}" 
      href="#sheet{{ $sheet['meta']['sheet_index'] }}"
      role="tab"
      data-toggle="tab"
      aria-controls="sheet{{ $sheet['meta']['sheet_index'] }}"
      aria-selected="{{ ($index === 0) ? 'true' : 'false' }}"
      ><i class="fas fa-file-excel"></i> {{ $sheet['meta']['name'] }}</a>
    </li>
  @endforeach
</ul>
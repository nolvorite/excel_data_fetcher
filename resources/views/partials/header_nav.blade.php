<nav class="navbar navbar-expand-lg navbar-dark bg-success">
  <a class="navbar-brand" href="{{ env('APP_URL') }}">{{ env('APP_NAME') }}</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item{{ $request::segment(1) === null ? ' active' : '' }}">
        <a class="nav-link" href="{{ env('APP_URL') }}"><i class="fas fa-home"></i>  Home</a>
      </li>
      <li class="nav-item{{ $request::segment(1) === 'upload' ? ' active' : '' }}">
        <a class="nav-link" href="{{ link2('upload') }}"><i class="fas fa-upload"></i> Upload</a>
      </li>
      <li class="nav-item{{ $request::segment(1) === 'file_list' ? ' active' : '' }}">
        <a class="nav-link" href="{{ link2('file_list') }}"><i class="far fa-chart-bar"></i> Spreadsheets</a>
      </li>
    </ul>

  </div>
</nav>
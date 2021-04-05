<nav class="navbar navbar-expand-lg navbar-dark bg-success" id="main_nav">
  <a class="navbar-brand" href="{{ env('APP_URL') }}"><i class="fas fa-chart-area"></i> {{ env('APP_NAME') }}</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item{{ $request->segment(1) === null ? ' active' : '' }}">
        <a class="nav-link" href="{{ env('APP_URL') }}"><i class="fas fa-home"></i>  Home</a>
      </li>

      @if(Auth::check())

      <li class="nav-item{{ $request->segment(1) === 'upload' ? ' active' : '' }}">
        <a class="nav-link" href="{{ link2('upload') }}"><i class="fas fa-upload"></i> Upload</a>
      </li>
      <li class="nav-item{{ $request->segment(1) === 'spreadsheets' ? ' active' : '' }}">
        <a class="nav-link" href="{{ link2('spreadsheets') }}"><i class="far fa-chart-bar"></i> Spreadsheets</a>
      </li>

      <li class="nav-item">
      <a class="nav-link" href="http://localhost:81/excel_data_fetcher/logout" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"><i class="fas fa-door-open"></i> Logout</a>

          <form id="logout-form" action="{{ link2('logout') }}" method="POST" class="d-none">
              <input type="hidden" name="_token" value="{{ csrf_token() }}"></form>
      </li>

      @else

       <li class="nav-item">
        <a class="nav-link" href="{{ link2('login') }}"><i class="fas fa-lock"></i> Log In</a>
      </li>

      @endif

        

    </ul>

  </div>
</nav>
    <nav class="navbar nav navbar-expand-lg bg-darkblue">
      <div class="container">
        <a class="navbar-brand" href="{{ route('index') }}"><img src="{{ URL::asset('assets/images/logo.png') }}" class="avatar-sm" alt=""></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-collapse collapse" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item {{ request()->routeIs('index') ? 'active-link' : '' }}">
              <a class="nav-link" aria-current="page" href="{{ route('index') }}">@lang('app.navbar.home')</a>
            </li>

            <li class="nav-item {{ request()->routeIs('contacts.index') ? 'active-link' : '' }}">
              <a class="nav-link" href="{{ route('contacts.index') }}">@lang('app.navbar.contact_us')</a>
            </li>
            
            {{-- REMOVE 🧹 --}}
            @if (config('app.env') == 'local' || config('app.env') == 'dev')
              <li class="nav-item">
                <a href="{{ route('admin.index') }}" class="nav-link" target="_blank">@lang('app.navbar.admin')</a>
              </li>
            @endif
          </ul>

          {{-- language changer --}}
          <div class="dropdown d-inline-block ms-auto">
            <button type="button" class="lang-btn" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              @switch(App::getLocale())
                @case('sv')
                  Svenska
                  {{-- <img src="{{ URL::asset('/assets/images/flags/kurdistan.jpg') }}" alt="کوردی" height="16"> --}}
                @break

                @default
                  English
                  {{-- <img src="{{ URL::asset('/assets/images/flags/us.jpg') }}" alt="english" height="16"> --}}
              @endswitch
            </button>
            <div class="dropdown-menu dropdown-menu-start lang-dropdown">
              <!-- item-->
              <a href="{{ url('index/en') }}" class="dropdown-item notify-item language" data-lang="en">
                <img src="{{ URL::asset('/assets/images/flags/us.png') }}" alt="user-image" class="me-1" height="12">
                <span class="align-middle">English</span>
              </a>
              <!-- item-->
              <a href="{{ url('index/sv') }}" class="dropdown-item notify-item language" data-lang="ku">
                <img src="{{ URL::asset('/assets/images/flags/se.png') }}" alt="swedish flag" class="me-1" height="12"> <span class="align-middle">Svenska</span>
              </a>
            </div>
          </div>
        </div>
      </div>
      </div>
    </nav>

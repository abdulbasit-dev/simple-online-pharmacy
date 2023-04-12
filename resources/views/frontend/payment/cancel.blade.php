@extends('layouts.app')

@section('title')
  @lang('Payment Cancel')
@endsection

@section('content')
  {{-- Main --}}
  <div class="my-5">
    <div class="container mt-5">
      <div class="row justify-content-center mt-5">
        {{-- card detail --}}
        <div class="col-xl-6 mt-5">
          <div class="card d-flex flex-column card-shadow">
            <div class="card-body">
              <div class="text-center">
                <svg width="100px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 507.2 507.2" xml:space="preserve" fill="#000000">
                  <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                  <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                  <g id="SVGRepo_iconCarrier">
                    <circle style="fill:#F15249;" cx="253.6" cy="253.6" r="253.6"></circle>
                    <path style="fill:#AD0E0E;" d="M147.2,368L284,504.8c115.2-13.6,206.4-104,220.8-219.2L367.2,148L147.2,368z"></path>
                    <path style="fill:#FFFFFF;" d="M373.6,309.6c11.2,11.2,11.2,30.4,0,41.6l-22.4,22.4c-11.2,11.2-30.4,11.2-41.6,0l-176-176 c-11.2-11.2-11.2-30.4,0-41.6l23.2-23.2c11.2-11.2,30.4-11.2,41.6,0L373.6,309.6z"></path>
                    <path style="fill:#D6D6D6;" d="M280.8,216L216,280.8l93.6,92.8c11.2,11.2,30.4,11.2,41.6,0l23.2-23.2c11.2-11.2,11.2-30.4,0-41.6 L280.8,216z"></path>
                    <path style="fill:#FFFFFF;" d="M309.6,133.6c11.2-11.2,30.4-11.2,41.6,0l23.2,23.2c11.2,11.2,11.2,30.4,0,41.6L197.6,373.6 c-11.2,11.2-30.4,11.2-41.6,0l-22.4-22.4c-11.2-11.2-11.2-30.4,0-41.6L309.6,133.6z"></path>
                  </g>
                </svg>
              </div>
              <div class="mt-3 text-center">
                <h1 class="mb-2">@lang("app.payment_cancel")</h1>
                <p class="lead mb-1">@lang("app.payment_cancel_txt")</p>
                <p>@lang("app.redirect_message") <span class="timer">5</span> @lang("app.seconds")</p>
                <a href="{{ route('index') }}" class="btn btn-primary waves-effect waves-light w-lg">@lang("buttons.go_to_home")</a>
              </div>
            </div>
            <!-- end card body -->
          </div>
          <!-- end card -->

        </div>
      </div>
    </div>
  </div>
@endsection
@section('script')
  <script>
    const redirectPageRoute = "{{ route('index') }}";
    // update timer
    const timer = document.querySelector('.timer');

    //  after 5s redirect to redirectPageRoute
    let redirectTimer = 5;
    const redirectInterval = setInterval(() => {
      redirectTimer--;
      const seconds = redirectTimer % 60;
      timer.textContent = seconds;
      console.log(redirectTimer);
      if (redirectTimer === 0) {
        clearInterval(redirectInterval);
        window.open(redirectPageRoute, '_self');
      }
    }, 1000);
  </script>
@endsection

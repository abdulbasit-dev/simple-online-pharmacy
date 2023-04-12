@extends('layouts.app')

@section('title')
  @lang('Payment Success')
@endsection

@section('content')
  {{-- Main --}}
  <div class="my-5">
    <div class="container mt-3">
      <div class="row justify-content-center">
        {{-- card detail --}}
        <div class="col-xl-6">
          <div class="card d-flex flex-column shadow-lg" data-aos="zoom-in">
            <div class="card-body">
              <div class="text-center">
                <svg viewBox="0 0 24 24" class="text-success" width="100">
                  <path fill="currentColor" d="M12,0A12,12,0,1,0,24,12,12.014,12.014,0,0,0,12,0Zm6.927,8.2-6.845,9.289a1.011,1.011,0,0,1-1.43.188L5.764,13.769a1,1,0,1,1,1.25-1.562l4.076,3.261,6.227-8.451A1,1,0,1,1,18.927,8.2Z">
                  </path>
                </svg>
              </div>
              <div class="mt-3 text-center">
                <h1 class="mb-2">@lang("app.payment_success")</h1>
                <p class="lead mb-4">@lang("app.payment_success_txt")</p>
                <p class="timerTxt d-none">@lang("app.redirect_message") <span class="timer">5</span> @lang("app.seconds")</p>
                @if (request()->get('order_id'))
                  <p class="text-muted" id="downloadTxt">@lang("app.download_ticket")</p>
                  <div class="downloadBtns mb-5">
                    @foreach ($orders as $order)
                      @if (!$order->is_ticket_downloaded)
                        <a href="{{ route('payment.ticketDownload', $order->id) }}" class="text-darkblue downloadBtn mx-2"><i class='bx bx-cloud-download bx-border-circle bx-md bx-fade-down-hover'></i></a>
                      @endif
                    @endforeach
                  </div>
                @endif

                <a href="{{ route('index') }}" class="btn btn-primary waves-effect waves-light w-lg d-none" id="goHomeBtn">@lang("buttons.go_to_home")</a>
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
    // when the page is load create a anchor tag and click on it to download the pdf using the pdf url(payment.ticketDownload)
    const routeHasOrderID = "{{ request()->get('order_id') }}";
    const downloadBtn = document.querySelectorAll('.downloadBtn');
    const downloadBtns = document.querySelector('.downloadBtns');
    const redirectPageRoute = "{{ route('index') }}";
    const timer = document.querySelector('.timer');

    $(document).ready(function() {
      if (!downloadBtns || downloadBtns.childElementCount === 0) {
          $("#goHomeBtn").removeClass('d-none');
          $(".timerTxt").removeClass('d-none');
          $("#downloadTxt").addClass('d-none');
          redirectTimer();
      }
    });

    const redirectTimer = () => {
      let count = 4;
      //   timer.classList.remove('d-none');
      $("#goHomeBtn").removeClass('d-none');
      $(".timerTxt").removeClass('d-none');
      $("#downloadTxt").addClass('d-none');

      //  after 5s redirect to redirectPageRoute
      let redirectTimer = 5;
      const redirectInterval = setInterval(() => {
        redirectTimer--;
        const seconds = redirectTimer % 60;
        timer.textContent = seconds;
        if (redirectTimer === 0) {
          clearInterval(redirectInterval);
          window.open(redirectPageRoute, '_self');
        }
      }, 1000);
    }

    if (downloadBtns) {
      downloadBtn.forEach(btn => {
        btn.addEventListener('click', function(e) {
          e.preventDefault();
          // create a anchor tag
          let a = document.createElement('a');
          // set the href to the download url
          a.href = btn.href;
          // click on it
          a.click();

          // remove the download button
          btn.remove();

          // if there is no download button left then redirect to match detail page
          if (downloadBtns.childElementCount === 0) {
            redirectTimer();
          }
        });
      });
    }
  </script>
@endsection

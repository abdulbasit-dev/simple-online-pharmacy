@extends('layouts.app')

@section('title')
  @lang('Checkout')
@endsection

@section('content')
  <div class="my-5">
    <div class="container">
      <div class="row justify-content-center">
        {{-- payment method --}}
        <div class="col-xl-6">
          <div class="card h-100 d-flex flex-column checkout-card">
            <div class="card-body">
              {{-- title --}}
              <div class="border-bottom border-darkblue mb-3 border-2">
                <h2>@lang('app.your_information')</h2>
                <p class="lead mb-2">@lang('app.your_information_txt')</p>
              </div>

              <div class="d-flex">
                <div>
                  <div class="mb-3">
                    <label for="name" class="form-label">@lang('app.name')</label>
                    <p class="fw-bold">{{ $ticketSession['name'] }}</p>
                  </div>

                  <div class="mb-3">
                    <label for="email" class="form-label">@lang('app.email')</label>
                    <p class="fw-bold">{{ $ticketSession['email'] }}</p>
                  </div>

                  <div class="mb-3">
                    <label for="email" class="form-label">@lang('app.phone')</label>
                    <p class="fw-bold">{{ $ticketSession['phone'] }}</p>
                  </div>
                </div>
                <div class="ms-auto">

                  @if ($ticketSession['ticket_type'] == 0)
                    <a href="{{ route('seasonTicket') }}" class="btn btn-light waves-effect waves-light">
                      <i class="bx bx-edit-alt font-size-16 me-2 align-middle"></i>@lang('buttons.edit')
                    </a>
                  @else
                    <a href="#" class="btn btn-light waves-effect waves-light">
                      <i class="bx bx-edit-alt font-size-16 me-2 align-middle"></i>@lang('buttons.edit')
                    </a>
                  @endif
                </div>
              </div>

            </div>
            <!-- end card body -->
          </div>
          <!-- end card -->
        </div>

        {{-- card detail --}}
        <div class="col-xl-6">
          <div class="card h-100 d-flex flex-column checkout-card">
            <div class="card-body">

              {{-- title --}}
              <div class="border-bottom border-darkblue border-2">
                <h2>@lang('app.ticket_summary')</h2>
                <p class="lead mb-2">@lang('app.ticket_detail')</p>
              </div>

              @php
                $qty = $ticketSession['qty'];
                $price = $ticketSession['price'] ?? 1000;
                $total = $qty * $price;
              @endphp

              {{-- ticket information --}}
              <div class="table-responsive">
                <table class="table-borderless table">
                  <tbody>
                    <tr>
                      <th scope="row">@lang('app.ticket_privilege'):</th>
                      <td></td>
                      <td></td>
                      <td class="text-end">{{ $ticketSession['category'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                      <th scope="row">@lang('app.supporter')</th>
                      <td></td>
                      <td></td>
                      <td class="text-end">{{ $ticketSession['supporter'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                      <th scope="row">@lang('app.age_group')</th>
                      <td></td>
                      <td></td>
                      <td class="text-end">{{ $ticketSession['age_group'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                      <th scope="row">@lang('app.ticket_section'):</th>
                      <td></td>
                      <td></td>
                      <td class="text-end">@lang('app.section') {{ $ticketSession['section'] }}</td>
                    </tr>
                    <tr>
                      <th scope="row">@lang('app.ticket_quantity'):</th>
                      <td></td>
                      <td></td>
                      <td class="ticket_qty text-end">{{ $qty }}</td>
                    </tr>
                    <tr>
                      <td></td>
                      <td></td>
                      <th scope="row">@lang('app.ticket_price')</th>
                      <td class="price fw-bold text-end">{{ formatPrice($price) }}</td>
                    </tr>
                    <tr>
                      <td></td>
                      <td></td>
                      <th scope="row">@lang('app.total_amount')</th>
                      <td colspan="1" class="fw-bold total text-end">{{ formatPrice($total) }}</td>
                    </tr>

                  </tbody>
                </table>
              </div>

              {{-- select payment method --}}
              <form class="checkout-form">
                <div class="d-flex">
                  @forelse ($accounts as $account)
                    <div class="form-radio-success paymentMethods">
                      <input class="form-check-input" hidden type="radio" id="account_{{ $account->id }}" value="{{ $account->paymentMethod->id }}" data-name="{{ strtolower($account->paymentMethod->name) }}" required>
                      <label class="me-3 form-check-label shadow-sm" for="account_{{ $account->id }}" data-bs-toggle="" data-bs-placement="top" data-bs-target="#rightModal2" data-bs-original-title="{{ $account->paymentMethod->name }}">
                        <img src="{{ getFile($account->paymentMethod) }}" alt="{{ $account->paymentMethod->name }}" class="img-fluid">
                      </label>
                    </div>

                  @empty
                    <div class="col-md-12">
                      <div class="alert alert-warning text-center" role="alert">
                        No Payment Account Found <span><a href="{{ route('index') }}" class="fw-bold text-dark">Go Home</a></span>
                      </div>
                    </div>
                  @endforelse
                </div>

                <div>
                  <button type="submit" class="btn btn-primary waves-effect waves-light w-lg">
                    <i class="bx bx-loader bx-spin font-size-16 me-2 d-none align-middle"></i>@lang('buttons.pay_now')
                  </button>
                </div>
              </form>
            </div>
            <!-- end card body -->
          </div>
          <!-- end card -->
        </div>
      </div>
    </div>
  </div>

  {{-- swish modal --}}
  <div class="modal fade" id="swish_payment_modal" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header px-4">
          <h4 class="modal-title">@lang('app.pay_with_swish')</h4>
        </div>
        <div class="modal-body px-4">
          <div class="row">
            {{-- qr img --}}
            <div class="col-md-12 my-md-0 mt-3" id="qrContainer">
              <p class="lead fw-medium m-0 mb-2 pt-0 text-center">@lang('app.scan_qr_code')</p>
              <img class="img-fluid d-block bg-light swish_qrcode mx-auto rounded border border-2 p-3" src="{{ URL::asset('assets/images/placeholder.png') }}" alt="swish payment qrcode">
            </div>
            <small class="mt-2 text-center">@lang('app.cancel_payment_text') <span class="" id="cancel_timer">05:00</span> @lang('app.min')</small>
          </div>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
@endsection

@section('script')
  <script>
    $(document).ready(() => {
      // show modal
      // $('#swish_payment_modal').modal('show');

      const checkoutBtnLoader = status => {
        if (status) {
          $('.checkout-form button[type="submit"]').attr('disabled', true);
          $('.checkout-form button[type="submit"] .bx-spin').removeClass('d-none');
        } else {
          $('.checkout-form button[type="submit"]').attr('disabled', false);
          $('.checkout-form button[type="submit"] .bx-spin').addClass('d-none');
        }
      }

      const callCancelApi = (orderId) => {
        window.location.href = `{{ route('payment.cancel', ['order_id' => '']) }}` + orderId;
      }

      $('.paymentMethods').on('click', function() {
        $(this).find('input').prop('checked', true);
        const label = $(this).find('label');
        // add border to selected payment method
        $('.paymentMethods').find('label').removeClass('border border-success');
        label.addClass('border border-success');
      });

      // select the first payment method by default
      $('.paymentMethods').first().click();

      // CREATE ORDER
      $('.checkout-form').on('submit', function(e) {
        e.preventDefault();
        if (!$('.paymentMethods').find('input').is(':checked')) {
          e.preventDefault();
          swal.fire({
            title: "@lang('app.messages.error')",
            text: "@lang('app.messages.select_payment_method')",
            icon: "error",
            confirmButtonText: "@lang('buttons.ok')",
            timer: 3000,
          });
          return;
        }
        console.log('create order');

        // check input name token is not empty
        if ($('input[name="_token"]').val() == '') {
          // generate new token
          $('input[name="_token"]').val('{{ csrf_token() }}');
        }

        // get payment method id
        let paymentMethodId = $('.paymentMethods').find('input:checked').val();
        let paymentMethodName = $('.paymentMethods').find('input:checked').data('name');

        // loader
        checkoutBtnLoader(true);

        $.ajax({
          url: "{{ route('checkout.createOrder') }}",
          method: "POST",
          data: {
            paymentMethodId,
            paymentMethodName
          },
          headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
          },
          success: function(response) {
            console.log(response);
            checkoutBtnLoader(false)

            // check for error
            if (response.status == 500) {
              swal.fire({
                title: "@lang('app.messages.error')",
                text: "@lang('app.messages.error_message')",
                icon: 'error',
                timer: 3000
              });

              return 0;
            }

            $orderId = response.data.orderId;

            if (paymentMethodName == "stripe") {
              // redirect page to stripeUrl
              console.log(response.data.stripeUrl);
              window.location.href = response.data.stripeUrl;
            } else if (paymentMethodName == "swish") {
              // show swish modal
              $('#swish_payment_modal').modal('show');
              $('.swish_qrcode').attr('src', response.data.qrcode);

              // CHECK ORDER STATUS
              // send ajax request to check if payment is successful
              setTimeout(() => {
                setInterval(() => {
                  $.ajax({
                    url: "{{ route('payment.fib.checkStatus') }}",
                    method: "POST",
                    headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                      payment_id: paymentId
                    },
                    success: function(response) {
                      const orderId = response.data.order_id;
                      if (response.data.payment_status == 1) {
                        // close fib modal
                        $("#fib_payment_modal").modal("hide")
                        // show success alert
                        swal.fire({
                          title: "@lang('app.messages.success')",
                          text: "@lang('app.messages.success_message')",
                          icon: 'success',
                          timer: 3000
                        });
                        // redirect user to dashboard
                        setTimeout(() => {
                          window.location.href = `{{ route('payment.success', ['order_id' => '']) }}` + orderId;
                        }, 2000);
                      } else {
                        console.log("payment not successful");
                      }
                    },
                    error: function(error) {
                      console.log(error);
                    }
                  });
                }, 2000);
              }, 7000);

              // CANCEL ORDER TIMER
              // after 5 min cancel order
              let cancelTimer = 300;
              const cancelTimerInterval = setInterval(() => {
                cancelTimer--;
                const minutes = Math.floor(cancelTimer / 60);
                const seconds = cancelTimer % 60;
                $('#cancel_timer').text(`${minutes}:${seconds}`);
                if (cancelTimer == 0) {
                  clearInterval(cancelTimerInterval);
                  callCancelApi($orderId);
                }
              }, 1000);
            }
          },
          error: function(error) {
            checkoutBtnLoader(false)

            swal.fire({
              title: "@lang('app.messages.error')",
              text: error.responseJSON.message,
              icon: 'error',
              timer: 5000
            });

            if (error.status == 500) {
              swal.fire({
                title: "@lang('app.messages.error')",
                text: "@lang('app.messages.error_message')",
                icon: 'error',
                timer: 3000
              });
            }

            if (error.status == 419) {
              swal.fire({
                title: "@lang('app.messages.error')",
                text: "@lang('app.messages.page_expired')",
                icon: 'error',
                timer: 3000
              });
            }


            if (error.status == 422) {
              swal.fire({
                title: "@lang('app.messages.error')",
                text: error.responseJSON.message,
                icon: 'error',
                timer: 3000
              });
            }
          }
        });
      });
    });
  </script>
@endsection

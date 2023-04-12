@extends('layouts.master')

@section('title')
  @lang('translation.resource_info', ['resource' => __('attributes.order')])
@endsection

@section('css')
  <!-- Lightbox css -->
  <link href="{{ URL::asset('/assets/libs/magnific-popup/magnific-popup.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
  @component('components.breadcrumb')
    @slot('li_1')
      @lang('translation.sold_ticket.sold_ticket')
    @endslot
    @slot('li_2')
      {{ route('admin.orders.index') }}
    @endslot
    @slot('title')
      @lang('translation.resource_info', ['resource' => __('attributes.order')])
    @endslot
  @endcomponent

  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <div class="row align-items-center">

            <div class="col-xl-6 border-xl-end border-3">
              <div>
                <div class="table-responsive">
                  <table class="table-borderless mb-0 table">
                    <tbody>
                      <tr>
                        <th scope="row">#</th>
                        <td>{{ $order->id }}</td>
                      </tr>

                      <tr>
                        <th scope="row">Order Id</th>
                        <td>{{ $order->order_id }}</td>
                      </tr>

                      <tr>
                        <th scope="row">Ticket Number:</th>
                        <td>{{ $order->ticket_number }}</td>
                      </tr>
                      <tr>
                        <th scope="row">Serial Number:</th>
                        <td>{{ $order->serial_number }}</td>
                      </tr>
                      <tr>
                        <th scope="row">Transaction Id:</th>
                        <td>{{ $order->transaction_id ?? '-----' }}</td>
                      </tr>
                      <tr>
                        <th scope="row">Phone:</th>
                        <td>{{ $order->phone ?? '---' }}</td>
                      </tr>
                      <tr>
                        <th scope="row">Sold On:</th>
                        @if (strtolower($order->store->name) == 'fastpay')
                          <td><span class="badge badge-pill badge-fastpay font-size-13 p-2">{{ ucfirst($order->store->name) }}
                            </span></td>
                        @elseif (strtolower($order->store->name) == 'website')
                          <td><span class="badge badge-pill badge-website font-size-13 p-2">{{ Str::title(str_replace('-', ' ', $order->store->name)) }}</span>
                          </td>
                        @else
                          <td><span class="badge badge-pill badge-card-selling font-size-13 p-2">{{ Str::title(str_replace('-', ' ', $order->store->name)) }}</span>
                          </td>
                        @endif
                      </tr>
                      <tr>
                        <th scope="row">Ticket Privilege:</th>
                        <td>
                          <span class="badge badge-pill badge-soft-info font-size-13 p-2">{{ $order->ticket->category->name ?? '---' }}</span>
                        </td>
                      </tr>
                      <tr>
                        <th scope="row">Seat Category:</th>
                        <td>
                          <span class="badge badge-pill badge-soft-info font-size-13 p-2">{{ Str::length($order->ticket->seat->name) > 0 ? $order->ticket->seat->name : '---' }}</span>
                        </td>
                      </tr>

                      <tr>
                        <th scope="row">Gate No:</th>
                        <td><span class="badge badge-pill badge-soft-info font-size-13 p-2">{{ $order->gate_no ?? '---' }}</span>
                        </td>
                      </tr>

                      <tr>
                        <th scope="row">Price:</th>
                        <td>{{ formatPrice($order->price) }}</td>
                      </tr>

                      <tr>
                        <th scope="row">Is Used:</th>
                        <td>
                          @if ($order->is_used)
                            <span class="badge badge-pill badge-soft-info font-size-13 p-2">@lang('translation.yes')</span>
                          @else
                            <span class="badge badge-pill badge-soft-danger font-size-13 p-2">@lang('translation.no')</span>
                          @endif
                        </td>
                      </tr>

                      <tr>
                        <th scope="row">Is Ticket Downloaded:</th>
                        <td>
                          @if ($order->is_ticket_downloaded)
                            <span class="badge badge-pill badge-soft-success font-size-13 p-2">@lang('translation.yes')</span>
                          @else
                            <span class="badge badge-pill badge-soft-danger font-size-13 p-2">@lang('translation.no')</span>
                          @endif
                        </td>
                      </tr>

                      <tr>
                        <th scope="row">Match Time:</th>
                        <td>{{ formatDateWithTimezone($order->match->match_time) }}</td>
                      </tr>
                      <tr>
                        <th scope="row">Order At:</th>
                        <td>{{ formatDateWithTimezone($order->created_at) }}</td>
                      </tr>
                      <tr>
                        <th scope="row">Expires At:</th>
                        <td>{{ formatDateWithTimezone($order->expire_at) }}</td>
                        <td>{{ $order->expires_at }}</td>
                      </tr>
                      <tr>
                        <th scope="row">Used At:</th>
                        <td>{{ formatDateWithTimezone($order->used_at) }}</td>
                      </tr>

                      @if ($order->expire_at < now())
                        <tr>
                          <th scope="row">@lang('translation.status')</th>
                          <td><span class="badge badge-pill badge-soft-danger font-size-13 p-2">@lang('translation.expired')</span>
                          </td>
                        </tr>
                      @endif

                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <div class="col-xl-6">
              <div class="mb-5">
                <div class="d-flex align-items-center justify-content-center">
                  <div class="d-flex flex-column align-items-center justify-content-center">
                    <img src="{{ getFile($order->match->home) }}" class="img-thumbnail avatar-lg">
                    <span class="text-info mt-2">{{ $order->match->home->name }}</span>
                  </div>
                  <span class="text-danger mx-5">{{ __('translation.vs') }}</span>
                  <div class="d-flex flex-column align-items-center justify-content-center">
                    <img src="{{ getFile($order->match->away) }}" class="img-thumbnail avatar-lg">
                    <span class="text-info mt-2">{{ $order->match->away->name }}</span>
                  </div>
                </div>

                <div class="d-flex justify-content-center">
                  <div>
                    <p class="text-uppercase font-size-20 fw-bold mb-0">
                      {{ Carbon\Carbon::parse($order->match->match_time)->format('l') }}</p>
                    <p class="font-size-14 fw-bold">{{ Carbon\Carbon::parse($order->match->match_time)->format('d-M') }}
                    </p>
                  </div>
                </div>
              </div>

              <div class="bg-light w-100" style="height: 3px; border-radius:15px"></div>
              {{-- Qr Image --}}
              <div class="d-flex justify-content-center mt-5">
                <a class="qrImageLightBox" href="{{ URL::asset($order->qr_image) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Click to Expand" data-bs-original-title="Click">
                  <img alt="qr image" src="{{ URL::asset($order->qr_image) }}">
                </a>
              </div>
            </div>

          </div>
          <!-- end row -->

        </div>
      </div>
      <!-- end card -->
    </div>
  </div>
  <!-- end row -->
@endsection
@section('script')
  <!-- Magnific Popup-->
  <script src="{{ URL::asset('/assets/libs/magnific-popup/magnific-popup.min.js') }}"></script>

  <script>
    $(".qrImageLightBox").magnificPopup({
      type: "image",
      closeOnContentClick: !0,
      closeBtnInside: !1,
      fixedContentPos: !0,
      mainClass: "mfp-no-margins mfp-with-zoom",
      image: {
        verticalFit: !0
      },
      zoom: {
        enabled: !0,
        duration: 300
      }
    })
  </script>
@endsection

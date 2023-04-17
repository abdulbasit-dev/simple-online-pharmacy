@extends('layouts.app')

@section('title')
  {{ Str::title($medicine->name) }}
@endsection

@section('css')
  <!-- Lightbox css -->
  <link href="{{ URL::asset('/assets/libs/magnific-popup/magnific-popup.min.css') }}" rel="stylesheet" type="text/css" />
@endsection


@section('content')
  {{-- Main --}}
  <div class="main my-5" id="match-ticket">
    <div class="container">
      <div class="row justify-content-start">
        <div class="col-12 mb-2" data-aos="fade-up">
          <h2 class="about_contact-title">Medicine Detail</h2>
          <p class="lead mt-3">See full Information about the medicine and easily order it</p>
        </div>

        <div class="col-md-6" data-aos="fade-right">
          <div class="card h-100 d-flex flex-column checkout-card">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table-borderless mb-0 table">
                  <tbody>
                    <tr>
                      <th scope="row" style="width: 100px;">#</th>
                      <td>{{ $medicine->id }}</td>
                    </tr>
                    <tr>
                      <th scope="row" style="width: 100px;">Name</th>
                      <td>{{ $medicine->name }}</td>
                    </tr>
                    <tr>
                      <th scope="row" style="width: 100px;">Description</th>
                      <td>{{ $medicine->description }}</td>
                    </tr>

                    <tr>
                      <th scope="row" style="width: 100px;">Origin</th>
                      <td><span class="badge badge-pill badge-soft-info font-size-13 m-1 p-2"> {{ $medicine->origin->name }}</span></td>
                    </tr>

                    <tr>
                      <th scope="row" style="width: 100px;">Type</th>
                      <td><span class="badge badge-pill badge-soft-info font-size-13 m-1 p-2"> {{ $medicine->type->name }}</span></td>
                    </tr>

                    <tr>
                      <th scope="row" style="width: 100px;">Price</th>
                      <td>{{ formatPrice($medicine->price) }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <button class="btn btn-success waves-effect waves-light w-md mt-3" data-bs-toggle="modal" data-bs-target="#orderModal">Order Now</button>
            </div>
          </div>
        </div>

        {{-- medicne image --}}
        <div class="col-md-6 mt-lg-0 mt-5" data-aos="fade-left">
          <div class="card h-100 d-flex flex-column checkout-card">
            <div class="card-body d-flex align-items-center justify-content-center">
              <a class="medicineImageLightBox" href="{{ getFile($medicine) }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Click to Expand" data-bs-original-title="Click">
                <img alt="{{ $medicine->name }}" src="{{ getFile($medicine) }}" class="img-fluid rounded border">
              </a>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  {{-- order modal --}}
  <div class="modal fade" id="orderModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="myLargeModalLabel">Fill the information to complete the order</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          @if ($errors->any())
            <div class="alert alert-danger">
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif
          <form class="needs-validation" novalidate action="{{ route('createOrder', $medicine) }}" method="POST">
            @csrf
            <div class="mb-3">
              <label for="name" class="col-form-label">Your name</label>
              <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required placeholder="Enter your name">
              <div class="valid-feedback">
                @lang('validation.good')
              </div>
              <div class="invalid-feedback">
                @lang('validation.required', ['attribute' => 'Name'])
              </div>
            </div>

            <div class="mb-3">
              <label for="phone" class="col-form-label">Your phone</label>
              <input type="tel" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" required placeholder="Enter your phone number">
              <div class="valid-feedback">
                @lang('validation.good')
              </div>
              <div class="invalid-feedback">
                @lang('validation.required', ['attribute' => 'Phone'])
              </div>
            </div>

            <div class="mb-3">
              <label for="address" class="col-form-label">Your address</label>
              <input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}" required placeholder="Enter your address">
              <div class="valid-feedback">
                @lang('validation.good')
              </div>
              <div class="invalid-feedback">
                @lang('validation.required', ['attribute' => 'Address'])
              </div>
            </div>

            <div class="mb-3">
              <label for="quantity" class="col-form-label">Medicine Quantity</label>
              <input type="number" class="form-control" id="quantity" name="quantity" value="{{ old('quantity') }}" required min="1" max="10" placeholder="Enter your desired quantity">
              <div class="valid-feedback">
                @lang('validation.good')
              </div>
              <div class="invalid-feedback">
                @lang('validation.required', ['attribute' => 'Medicine Quantity'])
              </div>
            </div>

            <div class="d-grid mt-3">
              <button class="btn btn-primary waves-effect waves-light" type="submit">Submit</button>
            </div>
          </form>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
@endsection

@section('script')
  <!-- Magnific Popup-->
  <script src="{{ URL::asset('/assets/libs/magnific-popup/magnific-popup.min.js') }}"></script>

  @if ($errors->any())
    <script>
      // show modal
      $('#orderModal').modal('show');
    </script>
  @endif

  <script>
    // init magnific popup
    $(".medicineImageLightBox").magnificPopup({
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

    $(document).ready(function() {
      // show modal
    //   $('#orderModal').modal('show');
    })
  </script>
@endsection

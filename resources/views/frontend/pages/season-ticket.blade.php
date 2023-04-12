@extends('layouts.app')

@section('title')
  @lang('app.season_ticket')
@endsection

@section('css')
  <!-- bootstrap-touchspin css -->
  <link href="{{ URL::asset('/assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.css') }}" rel="stylesheet" />
@endsection

@section('content')
  {{-- hero image --}}
  {{-- <header>
    <div class="hero-image seat_hero-image">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-8 col-xl-10 mb-3">
          </div>
        </div>
      </div>
    </div>
  </header> --}}

  {{-- Carosel --}}
  <section>
    <div id="carouselExampleCaptions" class="carousel carousel-light slide" data-bs-ride="carousel" data-bs-pause="false">
      <div class="carousel-indicators">
        @foreach ($banners as $banner)
          <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="{{ $loop->index }}" class="{{ $loop->iteration == 1 ? 'active' : '' }}"></button>
        @endforeach
      </div>
      <div class="carousel-inner">
        @foreach ($banners as $banner)
          <div class="carousel-item {{ $loop->iteration == 1 ? 'active' : '' }}" data-bs-interval="5000">
            <img src="{{ $banner->image }}" class="d-block w-100" alt="banner" style="background-position: center">
            <div class="dark-overlay d-flex justify-content-center align-items-center">
              <div class="container" data-aos="fade-up">
                <div class="row">
                  <div class="col-xl-10 col-lg-8 text-left">
                    <div class="hero-text">
                      <h1 class="text-primary title">@lang("app.banner_title")</h1>
                      <p class="subtitle text-white">@lang("app.banner_subtitle")</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
      {{-- <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button> --}}
    </div>
  </section>

  {{-- Main --}}
  <div class="main my-5" id="season-ticket">
    <div class="container">
      <div class="row justify-content-start">
        <div class="col-12 mb-4" data-aos="fade-up">
          <h2 class="about_contact-title">@lang('app.season_ticket')</h2>
          <p class="lead mt-3">@lang('app.season_ticket_text')</p>
        </div>
        <div class="col-md-6" data-aos="fade-right">
          <div class="card h-100 d-flex flex-column checkout-card">
            <div class="card-body">
              @if ($errors->any())
                <div class="alert alert-danger">
                  <ul>
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
              @endif
              <form class="checkout-form needs-validatio custom-validation" novalidate action="{{ route('seasonTicketStore') }}" method="POST">
                @csrf
                <div class="mb-3">
                  <label for="name" class="form-label">@lang('app.name')<span class="required-astrict"></span></label>
                  <input type="text" class="form-control" id="name" name="name" required data-parsley-minLength="3" placeholder="@lang('app.name_placeholder')">
                  <div class="valid-feedback">
                    @lang('validation.good')
                  </div>
                  <div class="invalid-feedback">
                    @lang('validation.required', ['attribute' => __('app.name')])
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-6">
                    <div class="mb-3">
                      <label for="email" class="form-label">@lang('app.email')<span class="required-astrict"></span></label>
                      <input type="email" class="form-control" id="email" name="email" required parsley-type="email" placeholder="@lang('app.email_placeholder')">
                      <div class="valid-feedback">
                        @lang('validation.good')
                      </div>
                      <div class="invalid-feedback">
                        @lang('validation.required', ['attribute' => __('app.email')])
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="mb-3">
                      <label for="phone" class="form-label">@lang('app.phone')<span class="required-astrict"></span></label>
                      <input type="tel" class="form-control" id="phone" name="phone" required parsley-type="tel" data-parsley-length="[9,13]" placeholder="46xxxxxxxxx">
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-6">
                    <div class="mb-3">
                      <label for="supporter_id" class="form-label">@lang('app.supporter')<span class="required-astrict"></span></label>
                      <input type="hidden" name="supporter_id" value="1">
                      <select id="supporter_id" name="supporter_id" class="form-select select2" disabled required>
                        <option selected="" value="">@lang('app.supporter_placeholder')</option>
                        @foreach ($supporters as $key => $value)
                          <option value="{{ $key }}" @selected($key == 1)>{{ $value }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>

                  <div class="col-lg-6">
                    <div class="mb-3">
                      <label for="age_group_id" class="form-label">@lang('app.age_group')<span class="required-astrict"></span></label>
                      <select id="age_group_id" name="age_group_id" class="form-select select" required>
                        <option selected="" value="">@lang('app.age_group_placeholder')</option>
                        @foreach ($ageGroups as $key => $value)
                          <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                      </select>
                      <div class="valid-feedback">
                        @lang('validation.good')
                      </div>
                      <div class="invalid-feedback">
                        @lang('validation.required', ['attribute' => __('attributes.age_group')])
                      </div>
                    </div>
                  </div>
                </div>

                <div class="mb-3">
                  <label for="category_id" class="form-label">@lang('app.ticket_privilege')<span class="required-astrict"></span></label>
                  <input type="hidden" name="category_id" value="{{ $normaLCategoryId }}">
                  <select id="category_id" name="category_id" class="form-select select2" disabled required>
                    <option selected="" value="">@lang('app.ticket_privilege_placeholder')</option>
                    @foreach ($categories as $key => $value)
                      <option value="{{ $key }}" @selected(strtolower($value) == 'normal')>{{ $value }}</option>
                    @endforeach

                  </select>
                  <div class="valid-feedback">
                    @lang('validation.good')
                  </div>
                  <div class="invalid-feedback">
                    @lang('validation.required', ['attribute' => __('attributes.privilege')])
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-4">
                    <div class="mb-3">
                      <label for="section_id" class="form-label">@lang('app.section')<span class="required-astrict"></span></label>
                      <input type="hidden" name="section_id" value="3">
                      <select id="section_id" name="section_id" class="form-select select2" disabled required>
                        <option selected="" value="">@lang('app.section_placeholder')</option>
                        @foreach ($sections as $key => $value)
                          <option value="{{ $key }}" @selected($key == 3)>{{ $value }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>

                  <div class="col-lg-4">
                    <div class="mb-3">
                      <label for="qty" class="form-label">@lang('app.ticket_quantity')<span class="required-astrict"></span></label>
                      <input type="text" class="text-center" value="01" id="qty" name="qty" min="1">
                    </div>
                  </div>

                  <div class="col-lg-4">
                    <div class="mb-3">
                      <label for="price" class="form-label">@lang('app.ticket_price')<span class="required-astrict"></span></label>
                      <div class="input-group">
                        <input type="hidden" id="init_price">
                        <input type="text" class="form-control text-center" id="price" name="price" value="0" readonly>
                        <div class="input-group-text">SEK</div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="mt-3">
                  <button type="submit" class="btn btn-primary waves-effect waves-light w-lg">
                    <i class="bx bx-loader bx-spin font-size-16 me-2 d-none align-middle"></i> @lang('buttons.continue')
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>

        {{-- stadium map --}}
        <div class="col-md-6" data-aos="fade-left">
          <div class="card h-100 d-flex flex-column checkout-card">
            <div class="card-body d-flex align-items-center">
              <img src="{{ URL::asset('assets/frontend/images/stadium-map.jpg') }}" class="img-fluid rounded border" alt="">
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
@endsection

@section('script')
  <!-- bootstrap-touchspin -->
  <script src="{{ URL::asset('/assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.js') }}"></script>

  <script>
    let qty = 1;
    $(document).ready(function() {
      $("input[name='qty']").val(qty);

      // set ticket price if both age group and category selected
      let ageGroupId = $("#age_group_id").val();
      let categoryId = $("#category_id").val();

      if (ageGroupId && categoryId) {
        setTicketPrice(ageGroupId, categoryId);
      }
    })

    // init touchspin
    $("input[name='qty']").TouchSpin({
      buttondown_class: "btn btn-darkblue",
      buttonup_class: "btn btn-darkblue",
      verticalbuttons: 0,
      min: 1,
      max: 5,
    });

    const updateTicketPrice = (qty) => {
      let initPrice = $("#init_price").val();
      let total = qty * initPrice;
      $('#price').val(total);
    }

    const setTicketPrice = (ageGroupId, categoryId) => {
      $.ajax({
        url: "{{ route('getTicketPrice') }}",
        data: {
          age_group_id: ageGroupId,
          category_id: categoryId,
        },
        success: function(response) {
          if (response.status == 200) {
            // get qty value
            qty = $("input[name='qty']").val();
            let price = qty * response.data.price
            $('#price').val(price);
            $('#init_price').val(response.data.price);
          }
        },
        error: function(error) {
          console.log(error);
        }
      })
    }

    // prevent user from typing raither than number in phone number input
    $('#phone').on('keypress', function(e) {
      if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        return false;
      }
    });

    // update ticket price & check quantity
    $("input[name='qty']").on('change', function() {
      qty = $(this).val();
      let ageGroupId = $('#age_group_id').val();
      let categoryId = $('#category_id').val();
      let sectionId = $('#section_id').val();
      // check quantity
      if (ageGroupId && categoryId) {
        $.ajax({
          url: "{{ route('checkTicketQuantity') }}",
          data: {
            age_group_id: ageGroupId,
            category_id: categoryId,
            section_id: sectionId,
            qty
          },
          success: function(response) {
            if (response.status == 200) {
              if (response.data.remainQty < qty) {
                // don't allow to change qty input pul old one back
                $("input[name='qty']").val(1);
                swal.fire({
                  title: "@lang('app.messages.info')",
                  text: "@lang('app.messages.only_amount_ticket_left', ['amount' => ':amount'])".replace(':amount', response.data.remainQty),
                  icon: "info",
                  timer: 3000,
                });
              } else {
                updateTicketPrice(qty);
              }
            }
          },
          error: function(error) {
            swal.fire({
              title: "@lang('app.messages.error')",
              text: error.responseJSON.message,
              icon: "error",
              timer: 3000,
              showConfirmButton: false
            });
          }
        })
      }
    });

    // GET TICKET PRICE
    // if both age group and ticket privilege are selected, send ajax request to get price
    $('#age_group_id, #category_id').on('change', function() {
      let ageGroupId = $('#age_group_id').val();
      let categoryId = $('#category_id').val();

      if (ageGroupId && categoryId) {
        setTicketPrice(ageGroupId, categoryId);
      }
    })
  </script>
@endsection

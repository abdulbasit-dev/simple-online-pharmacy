@extends('layouts.app')

@section('title')
  Match Ticket
@endsection

@section('css')
  <!-- bootstrap-touchspin css -->
  <link href="{{ URL::asset('/assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.css') }}" rel="stylesheet" />
@endsection

@section('content')
  <header>
    {{-- hero image --}}
    <div class="hero-image match_hero-image">
      <div class="container">
        @if ($match && $match->tickets()->count())
          <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-10 mb-3">
              <div data-aos="zoom-in-up" data-aos-duration="1400">
                <p class="match_league text-center">{{ $match->league->name }}</p>
              </div>

              <div class="teams d-flex justify-content-around align-items-center">
                <div class="match_team d-flex flex-column align-items-center" data-aos="fade-right" style="flex: 1">
                  <img src="{{ getFile($match->home) }}" alt="team logo" class="avatar-lg">
                  <p class="h3 text-primary mt-3">{{ $match->home->name }}</p>
                </div>

                <div class="align-self-start" data-aos="zoom-in" data-aos-duration="1200">
                  <span class="text-gradient match_time">
                    {{ Carbon\Carbon::parse($match->match_time)->format('H:m') }}
                  </span>
                </div>

                <div class="match_team d-flex flex-column align-items-center" data-aos="fade-left" style="flex: 1">
                  <img src="{{ getFile($match->away) }}" alt="team logo" class="avatar-lg">
                  <p class="h3 text-primary mt-3">{{ $match->away->name }}</p>
                </div>
              </div>

              <div class="d-flex flex-column align-items-center" data-aos="flip-up" data-aos-duration="1400">
                <div class="match_date text-uppercase ps-2 m-0 py-1">
                  <span class="text-gradient day d-block">{{ Carbon\Carbon::parse($match->match_time)->translatedFormat('l') }}</span>
                  <span class="month">{{ Carbon\Carbon::parse($match->match_time)->translatedFormat('d') }} -
                    {{ Carbon\Carbon::parse($match->match_time)->translatedFormat('M') }}</span>
                </div>

                {{-- countdown --}}
                <div class="countdown__clock d-non mt-3 mb-2" data-aos="zoom-in">
                  <div class="countdown__clock-item days">
                    <div class="countdown__count">
                      <span class="countdown__value js-countdown-days">00</span>
                      <span class="countdown__separator">:</span>
                    </div>
                    <div class="countdown__count-label">days</div>
                  </div>

                  <div class="countdown__clock-item hours">
                    <div class="countdown__count">
                      <span class="countdown__value js-countdown-hours">00</span>
                      <span class="countdown__separator">:</span>
                    </div>
                    <div class="countdown__count-label">hours</div>
                  </div>

                  <div class="countdown__clock-item minutes">
                    <div class="countdown__count">
                      <span class="countdown__value js-countdown-minutes">00</span>
                      <span class="countdown__separator">:</span>
                    </div>
                    <div class="countdown__count-label">mins</div>
                  </div>

                  <div class="countdown__clock-item seconds">
                    <div class="countdown__count">
                      <span class="countdown__value js-countdown-seconds">00</span>
                    </div>
                    <div class="countdown__count-label">secs</div>
                  </div>
                </div>

                <p class="text-gradient match_stadium mt-3">{{ 'Studenternas IP' }}</p>
              </div>

              <div class="d-flex justify-content-center" data-aos="fade-up">
                <a href="#match-ticket" class="btn btn-primary match_header-btn w-xl mt-3">Get Ticket</a>
              </div>
            </div>
          </div>
        @endif
      </div>
    </div>
  </header>

  {{-- Main --}}
  <div class="main my-5" id="match-ticket">
    <div class="container">
      @if ($match && $match->tickets()->count())
        <div class="row justify-content-start">
          <div class="col-12 mb-4" data-aos="fade-up">
            <h2 class="about_contact-title">@lang('app.match_ticket')</h2>
            <p class="lead mt-3">@lang('app.match_ticket_text')</p>
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
                <form class="checkout-form needs-validatio custom-validation" novalidate action="{{ route('match-ticket.store') }}" method="POST">
                  @csrf
                  <input type="hidden" name="match_id" value="{{ $match->id }}">
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
                        <select id="supporter_id" name="supporter_id" class="form-select select" required>
                          <option selected="" value="">@lang('app.supporter_placeholder')</option>
                          @foreach ($supporters as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
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
                    <input type="hidden" name="category_id" value="{{ $normalCategoryId }}">
                    <select id="category_id" name="category_id" class="form-select select" disabled required>
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
                        <select id="section_id" name="section_id" class="form-select select" required>
                          <option selected="" value="">@lang('app.section_placeholder')</option>
                          @foreach ($sections as $key => $value)
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
          <div class="col-md-6 mt-lg-0 mt-5" data-aos="fade-left">
            <div class="card h-100 d-flex flex-column checkout-card">
              <div class="card-body d-flex align-items-center">
                <img src="{{ URL::asset('assets/frontend/images/stadium-map.jpg') }}" class="img-fluid rounded border" alt="">
              </div>
            </div>
          </div>

        </div>
      @else
        <div>
          <div class="text-center">
            <h3 class="mb-4">@lang('app.no_match_added')</h3>
            <img src="{{ URL::asset('assets/images/no-match.svg') }}" class="img-fluid w-50" alt="">
          </div>
        </div>
      @endif
    </div>
  </div>
@endsection

@if ($match && $match->tickets()->count())
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
        let supporterId = $('#supporter_id').val();

        if (ageGroupId && categoryId && supporterId) {
          setTicketPrice(ageGroupId, categoryId, supporterId);
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

      const setTicketPrice = (ageGroupId, categoryId, supporterId) => {
        $.ajax({
          url: "{{ route('match-ticket.getTicketPrice') }}",
          data: {
            age_group_id: ageGroupId,
            category_id: categoryId,
            supporter_id: supporterId
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

      const getSections = (supporterId) => {
        $.ajax({
          url: "{{ route('match-ticket.getSections') }}",
          data: {
            supporter_id: supporterId
          },
          success: function(response) {

            const {
              sections
            } = response.data

            const select2Data = []

            //   add none option to data
            select2Data.unshift({
              id: '',
              text: "Select Section",
            });

            // if (sections.length > 0) {
            //   sections.forEach(({
            //     id,
            //     name
            //   }) => {
            //     select2Data.push({
            //       id: id,
            //       text: name,
            //     })
            //   })
            // }

            // not using select2
            let html = '';
            html += '<option value="">Select Section</option>';
            sections.forEach(({
              id,
              name
            }) => {
              html += `<option value="${id}">${name}</option>`;
            })

            // clear select2 sections and add sections
            $('#section_id').empty();
            $('#section_id').append(html);
            // $('#section_id').select2({
            //   data: select2Data
            // });
          },
        });
      }

      // set section based on selected supporter
      $('#supporter_id').on('change', function() {
        let supporterId = $('#supporter_id').val();
        getSections(supporterId);
      })

      // prevent user from typing raither than number in phone number input
      $('#phone').on('keypress', function(e) {
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
          return false;
        }
      });

      // update ticket price & check quantity
      $("input[name='qty']").on('change', function() {
        qty = $(this).val();
        console.log(qty);
        let ageGroupId = $('#age_group_id').val();
        let categoryId = $('#category_id').val();
        let sectionId = $('#section_id').val();
        // check quantity
        if (ageGroupId && categoryId && sectionId) {
          $.ajax({
            url: "{{ route('match-ticket.checkTicketQuantity') }}",
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
      $('#age_group_id, #category_id , #supporter_id').on('change', function() {
        let ageGroupId = $('#age_group_id').val();
        let categoryId = $('#category_id').val();
        let supporterId = $('#supporter_id').val();

        if (ageGroupId && categoryId && supporterId) {
          setTicketPrice(ageGroupId, categoryId, supporterId);
        }
      })

      // COUNTDOWN
      // match time countdown
      let addZero = (num) => {
        if (num < 10) {
          return '0' + num;
        } else {
          return num;
        }
      }

      const matchTime = new Date("{{ $match->match_time }}").getTime();

      const matchTimeCountdown = setInterval(() => {
        const now = new Date().getTime();
        const distance = matchTime - now;

        const days = addZero(Math.floor(distance / (1000 * 60 * 60 * 24)));
        const hours = addZero(Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)));
        const minutes = addZero(Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)));
        const seconds = addZero(Math.floor((distance % (1000 * 60)) / 1000));

        $('.js-countdown-days').text(days);
        $('.js-countdown-hours').text(hours);
        $('.js-countdown-minutes').text(minutes);
        $('.js-countdown-seconds').text(seconds);
      }, 1000);
    </script>
  @endsection
@endif

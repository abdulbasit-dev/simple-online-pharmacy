@extends('layouts.app')

@section('title')
  @lang('app.navbar.home')
@endsection

@section('content')
  {{-- Main --}}
  <div class="main mt-4">
    <div class="container" id="matchs">
      {{-- filters --}}
      {{-- <div class="row justify-content-center align-items-center mb-5 mt-5" data-aos="fade-down" data-aos-duration="1000">

        <div class="col-md-3">
          <input type="text" class="form-control" id="search" placeholder="search here...">
        </div>
        <div class="col-md-3">
          <select class="form-select select2" id="team">
            <option selected="" value="">All Teams</option>
            @forelse ($teams as $team)
              <option value="{{ $team->id }}">{{ $team->name }}</option>
            @empty
              <option value="">No Teams</option>
            @endforelse
          </select>
        </div>
        <div class="col-md-4">
          <div class="input-daterange input-group" id="datepicker" data-date-format="dd M, yyyy" data-date-autoclose="true" data-provide="datepicker" data-date-container='#datepicker'>
            <input type="text" class="form-control filter-input" id="start" name="startDate" placeholder="Start Date" />
            <input type="text" class="form-control filter-input" id="end" name="endDate" placeholder="End Date" />
          </div>
        </div>
        <div class="col-md-2">
          <button class="btn btn-outline-darkblue resetBtn">Reset</button>
        </div>
      </div> --}}

      {{-- matchs --}}
      {{-- <div class="matches-conatiner">
        @include('frontend.includes.matches')
      </div> --}}

      <div class="pt-sm-5 my-5">
        <div class="container">
          <div class="row">
            <div class="col-lg-12">
              <div class="text-center">
                {{-- <a href="index" class="d-block auth-logo">
                  <img src="{{ URL::asset('/assets/images/logo.png') }}" alt="" height="100" class="auth-logo-light mx-auto">
                </a> --}}
                <div class="row justify-content-center mt-5">
                  <div class="col-sm-4">
                    <div class="maintenance-img">
                      <img src="{{ URL::asset('/assets/images/coming-soon.svg') }}" alt="" class="img-fluid d-block mx-auto">
                    </div>
                  </div>
                </div>
                <h4 class="mt-5">Let's get started with {{config("app.name")}}</h4>
                <p class="text-muted">It will be as simple as Occidental in fact it will be Occidental.</p>

                <div class="row justify-content-center mt-5">
                  <div class="col-md-8">
                    <div data-countdown="2023/04/01" class="counter-number"></div>
                  </div> <!-- end col-->
                </div> <!-- end row-->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection


@section('script')
  {{-- bootstrap-datepicker --}}
  <script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
  <!-- Plugins -->
  <script src="{{ URL::asset('/assets/libs/jquery-countdown/jquery-countdown.min.js') }}"></script>

  <!-- oming-soon init -->
  <script src="{{ URL::asset('/assets/js/coming-soon.init.js') }}"></script>

  <script type="text/javascript">
    //datepicker Init
    $("#datepicker").datepicker({
      todayHighlight: true,
      autoclose: true,
      startDate: new Date(),
      clearBtn: true,
    });

    const getMatches = (data = {}) => {
      $.ajax({
        url: "{{ route('index') }}",
        method: "GET",
        data: data,
        success: function(data) {
          $('.matches-conatiner').html(data);
        }
      });
    }
    // filter
    $(document).on('change', '#team', function() {
      data = {
        teamId: $(this).val()
      }
      getMatches(data);
    });

    // search
    $(document).on('keyup', '#search', function() {
      data = {
        search: $(this).val()
      }
      getMatches(data);
    });

    // datepicker
    $(document).on('change', '.filter-input', function() {
      data = {
        startDate: $('#start').val(),
        endDate: $('#end').val()
      }
      getMatches(data);
    });

    //when click the reset button
    $(document).on('click', '.resetBtn', function() {
      getMatches();
    });
  </script>
@endsection

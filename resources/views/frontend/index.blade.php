@extends('layouts.app')

@section('title')
  @lang('app.navbar.home')
@endsection

@section('content')
  <header>
    {{-- hero image --}}
    <div class="hero-image home_hero-image">
      <div class="container">
        <div class="row">
          <div class="col-xl-10 col-lg-8 text-left">
            <div class="hero-text" data-aos="fade-up" data-aos-duration="1000">
              <h1 class="text-primary title">Getting Tickets Easyer Than Ever</h1>
              <p class="subtitle text-white">
                Search for your favorite team and get tickets for the next match
              </p>
            </div>
            <div data-aos="fade-right" data-aos-duration="1000">
              <a href="#matchs" class="btn btn-primary mt-4">Get Match Ticket</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>

  {{-- Main --}}
  <div class="main mt-4">
    <div class="container" id="matchs">
      {{-- filters --}}
      <div class="row justify-content-center  align-items-center mb-4 mt-5" data-aos="fade-down" data-aos-duration="1000">

        <div class="col-md-3 mb-3 ">
          <input type="text" class="form-control" id="search" placeholder="search here...">
        </div>
        <div class="col-md-3 mb-3 ">
          <select class="form-select select2" id="team">
            <option selected="" value="">All Teams</option>
            @forelse ($teams as $team)
              <option value="{{ $team->id }}">{{ $team->name }}</option>
            @empty
              <option value="">No Teams</option>
            @endforelse
          </select>
        </div>
        <div class="col-md-4 mb-3 ">
          <div class="input-daterange input-group" id="datepicker" data-date-format="dd M, yyyy" data-date-autoclose="true" data-provide="datepicker" data-date-container='#datepicker'>
            <input type="text" class="form-control filter-input" id="start" name="startDate" placeholder="Start Date" />
            <input type="text" class="form-control filter-input" id="end" name="endDate" placeholder="End Date" />
          </div>
        </div>
        <div class="col-md-2 mb-3">
          <button class="btn btn-outline-darkblue resetBtn">Reset</button>
        </div>
      </div>

      {{-- matchs --}}
      <div class="matches-conatiner mb-5">
        @include('frontend.includes.matches')
      </div>
    </div>
  </div>
@endsection


@section('script')
  {{-- bootstrap-datepicker --}}
  <script src="{{ URL::asset('assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

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

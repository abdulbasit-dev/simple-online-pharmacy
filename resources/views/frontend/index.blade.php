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
              <h1 class="text-primary title">Getting Medicine Easyer Than Ever</h1>
              {{-- <p class="subtitle text-white">
                Search for your favorite team and get tickets for the next match
              </p> --}}
            </div>
            <div data-aos="fade-right" data-aos-duration="1000">
              <a href="#matchs" class="btn btn-primary mt-4">Medicines</a>
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
      <div class="row justify-content-center align-items-center mb-4 mt-5" data-aos="fade-down" data-aos-duration="1000">

        <div class="col-md-3 mb-3">
          <input type="text" class="form-control" id="search" placeholder="search here...">
        </div>
        <div class="col-md-3 mb-3">
          <select class="form-select select2" id="type">
            <option selected="" value="">Select Medicine Type</option>
            @forelse ($types as $key=>$value)
              <option value="{{ $key }}">{{ $value }}</option>
            @empty
              <option value="">No Type</option>
            @endforelse
          </select>
        </div>

        <div class="col-md-3 mb-3">
          <select class="form-select select2" id="origin">
            <option selected="" value="">Select Medicine Origin</option>
            @forelse ($origins as $key=>$value)
              <option value="{{ $key }}">{{ $value }}</option>
            @empty
              <option value="">No Oirgin</option>
            @endforelse
          </select>
        </div>
        <div class="col-md-2 mb-3">
          <button class="btn btn-outline-darkblue resetBtn">Reset</button>
        </div>
      </div>

      {{-- matchs --}}
      <div class="medicines-conatiner mb-5">
        @include('frontend.includes.medicines')
      </div>
    </div>
  </div>
@endsection


@section('script')
  <script type="text/javascript">
    const getMedicines = (data = {}) => {
      $.ajax({
        url: "{{ route('home') }}",
        method: "GET",
        data: data,
        success: function(data) {
          $('.medicines-conatiner').html(data);
        }
      });
    }

    // filter
    $(document).on('change', '#type', function() {
      data = {
        typeId: $(this).val()
      }
      getMedicines(data);
    });

    // filter
    $(document).on('change', '#origin', function() {
      data = {
        originId: $(this).val()
      }
      getMedicines(data);
    });

    // search
    $(document).on('keyup', '#search', function() {
      data = {
        search: $(this).val()
      }
      getMedicines(data);
    });


    //when click the reset button
    $(document).on('click', '.resetBtn', function() {
      getMedicines();
    });
  </script>
@endsection

@extends('layouts.master')

@section('title')
  @lang('sidebar.dashboard')
@endsection

@section('css')
  <!-- Lightbox css -->
  <link href="{{ URL::asset('/assets/libs/magnific-popup/magnific-popup.min.css') }}" rel="stylesheet" type="text/css" />
  <!-- DataTables -->
  <link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
  @component('components.breadcrumb')
    @slot('li_1')
      @lang('sidebar.dashboard')
    @endslot
    @slot('li_2')
      {{ route('admin.index') }}
    @endslot
    @slot('title')
      @lang('sidebar.dashboard')
    @endslot
  @endcomponent


  <div class="row">
    <div class="col-xl-12">
      <div class="row">
        <div class="col-md-3">
          <div class="card mini-stats-wid">
            <div class="card-body">
              <div class="d-flex">
                <div class="flex-grow-1">
                  <p class="text-muted fw-medium">@lang('translation.dashboard.total_matches')</p>
                  <h4 class="mb-0">{{ $data['totalMatch'] }}</h4>
                </div>

                <div class="align-self-center flex-shrink-0">
                  <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                    <span class="avatar-title rounded-circle bg-primary">
                      <i class="bx bx-football font-size-24"></i>
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card mini-stats-wid">
            <div class="card-body">
              <div class="d-flex">
                <div class="flex-grow-1">
                  <p class="text-muted fw-medium">@lang('translation.dashboard.total_teams')</p>
                  <h4 class="mb-0">{{ $data['totalTeams'] }}</h4>
                </div>

                <div class="align-self-center flex-shrink-0">
                  <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                    <span class="avatar-title rounded-circle bg-primary">
                      <i class="bx bx-group font-size-24"></i>
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card mini-stats-wid">
            <div class="card-body">
              <div class="d-flex">
                <div class="flex-grow-1">
                  <p class="text-muted fw-medium">@lang('translation.dashboard.ticket_privilege')</p>
                  <h4 class="mb-0">{{ $data['totalCategories'] }}</h4>
                </div>

                <div class="align-self-center flex-shrink-0">
                  <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                    <span class="avatar-title rounded-circle bg-primary">
                      <i class="bx bx-layer font-size-24"></i>
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card mini-stats-wid">
            <div class="card-body">
              <div class="d-flex">
                <div class="flex-grow-1">
                  <p class="text-muted fw-medium">@lang('translation.dashboard.available_tickets')</p>
                  <h4 class="mb-0">{{ $data['availableTickets'] }}</h4>
                </div>

                <div class="align-self-center flex-shrink-0">
                  <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                    <span class="avatar-title rounded-circle bg-primary">
                      <i class="dripicons-ticket font-size-22 pt-2"></i>
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- end row -->
    </div>
  </div>
  <!-- end row -->

  <div class="row">
    {{-- match select --}}
    <div class="col-xl-4">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              <div class="mb-3">
                <label for="matchId" class="form-label">Select Match</label>
                <select class="form-select select2 filter-input" id="matchId">
                  <option value="" selected>@lang('translation.none')</option>
                  @forelse ($data["matches"] as $match)
                    <option value="{{ $match['id'] }}" @selected($match['id'] == $data['latestMatchId']) home_name="{{ $match['home_name'] }}"
                      away_name="{{ $match['away_name'] }}" home_logo="{{ $match['home_logo'] }}"
                      away_logo="{{ $match['away_logo'] }}">
                      {{ $match['name'] }}</option>
                  @empty
                    <option value="">@lang('translation.none')</option>
                  @endforelse
                </select>
              </div>
            </div>
            <div class="col-lg-12">
              <div class="d-flex align-items-center justify-content-center">

                <div class="d-flex flex-column align-items-center justify-content-center" id="homeDiv">
                  <img src="{{ URL::asset('assets/images/placeholder.png') }}" class="img-thumbnail avatar-md">
                  <span class="text-info mt-2"></span>
                </div>

                <span class="text-danger mx-4" id="vs"> @lang('translation.vs')</span>

                <div class="d-flex flex-column align-items-center justify-content-center" id="awayDiv">
                  <img src="{{ URL::asset('assets/images/placeholder.png') }}" class="img-thumbnail avatar-md">
                  <span class="text-info mt-2"></span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>

    {{-- ticket data --}}
    <div class="col-xl-8">
      <div class="row">
        <div class="col-md-4">
          <div class="card mini-stats-wid">
            <div class="card-body">
              <div class="d-flex">
                <div class="flex-grow-1">
                  <p class="text-muted fw-medium">Total Tickets</p>
                  <h4 class="mb-0" id="sumTotalTickets">0</h4>
                </div>

                <div class="align-self-center flex-shrink-0">
                  <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                    <span class="avatar-title">
                      <i class="dripicons-ticket font-size-22 pt-2"></i>
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card mini-stats-wid">
            <div class="card-body">
              <div class="d-flex">
                <div class="flex-grow-1">
                  <p class="text-muted fw-medium">Sold Tickets</p>
                  <h4 class="mb-0" id="soldTickets">0</h4>
                </div>

                <div class="align-self-center flex-shrink-0">
                  <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                    <span class="avatar-title">
                      <i class="dripicons-ticket font-size-22 pt-2"></i>
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card mini-stats-wid">
            <div class="card-body">
              <div class="d-flex">
                <div class="flex-grow-1">
                  <p class="text-muted fw-medium">Used Tickets</p>
                  <h4 class="mb-0" id="usedTickets">0</h4>
                </div>

                <div class="align-self-center flex-shrink-0">
                  <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                    <span class="avatar-title rounded-circle bg-primary">
                      <i class="dripicons-ticket font-size-22 pt-2"></i>
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        {{-- <div class="col-md-3">
          <div class="card mini-stats-wid">
            <div class="card-body">
              <div class="d-flex">
                <div class="flex-grow-1">
                  <p class="text-muted fw-medium">Unused Tickets</p>
                  <h4 class="mb-0" id="unUsedTickets">0</h4>
                </div>

                <div class="align-self-center flex-shrink-0">
                  <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                    <span class="avatar-title rounded-circle bg-primary">
                      <i class="dripicons-ticket font-size-22 pt-2"></i>
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div> --}}
      </div>
      <!-- end row -->

      <div class="row">
        <div class="col-md-6">
          <div class="card mini-stats-wid">
            <div class="card-body">
              <div class="d-flex">
                <div class="flex-grow-1">
                  <p class="text-muted fw-medium">Sold in Arena</p>
                  <h4 class="mb-0" id="soldInArena">0</h4>
                </div>

                <div class="align-self-center flex-shrink-0">
                  <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                    <span class="avatar-title">
                      <i class='bx bx-mobile-alt font-size-24'></i>
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card mini-stats-wid">
            <div class="card-body">
              <div class="d-flex">
                <div class="flex-grow-1">
                  <p class="text-muted fw-medium">Sold in Website</p>
                  <h4 class="mb-0" id="soldInWebsite">0</h4>
                </div>

                <div class="align-self-center flex-shrink-0">
                  <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                    <span class="avatar-title rounded-circle bg-primary">
                      <i class='bx bx-card font-size-24'></i>
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- end row -->

    </div>
  </div>

  {{-- last 5 matches --}}
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title mb-4">@lang('translation.dashboard.latest_matches')</h4>
          <div class="table-responsive">
            <table class="table-hover mb-0 table align-middle">
              <thead class="table-light">
                <tr>
                  <th>#</th>
                  <th class="text-center">@lang('translation.match.home_team')</th>
                  <th class="text-center">@lang('translation.match.away_team')</th>
                  <th class="text-center">@lang('translation.match.match_time')</th>
                  <th class="text-center">No of Ticket Type</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($data['lastMatches'] as $match)
                  <tr class="text-cente align-middle">
                    <td><span href class="text-body fw-bold">{{ $loop->iteration }}</span> </td>
                    <td>
                      <div class="d-flex flex-column align-items-center justify-content-center">
                        <img src="{{ getFile($match->home) }}" class="img-thumbnail avatar-md">
                        <span class="text-info fw-bold mt-3"> {{ $match->home->name }} </span>
                      </div>
                    </td>
                    <td>
                      <div class="d-flex flex-column align-items-center justify-content-center">
                        <img src="{{ getFile($match->away) }}" class="img-thumbnail avatar-md">
                        <span class="text-info fw-bold mt-3"> {{ $match->away->name }} </span>
                      </div>
                    </td>
                    <td>
                      @php
                        $statusClass = '';

                        if ($match->match_time < Carbon\Carbon::today()) {
                            $statusClass = 'badge badge-pill badge-soft-danger font-size-13';
                        } elseif (Carbon\Carbon::today()->eq(Carbon\Carbon::parse($match->match_time)->startOfDay())) {
                            $statusClass = 'badge badge-pill badge-soft-info font-size-13 ';
                        } else {
                            $statusClass = '';
                        }
                      @endphp
                      <div class="d-flex flex-column align-items-center justify-content-center">
                        <span
                          class="{{ $statusClass }}">{{ Carbon\Carbon::parse($match->match_time)->format('D, M d') }}</span>
                        <span
                          class="{{ $statusClass }} mt-1">{{ Carbon\Carbon::parse($match->match_time)->format('h:i A') }}</span>
                      </div>
                    </td>

                    <td class="text-center">
                      @if ($match->tickets->count() > 0)
                        <span
                          class="badge badge-pill badge-soft-success font-size-13">{{ $match->tickets_count }}</span>
                      @else
                        <span class="badge badge-pill badge-soft-danger font-size-13">{{ $match->tickets_count }}</span>
                      @endif

                    </td>

                  </tr>
                @empty
                  <tr>
                    <td colspan="10" class="text-center">@lang('translation.emptyTable')</td>
                  </tr>
                @endforelse

              </tbody>
            </table>
          </div>
          <!-- end table-responsive -->
        </div>
      </div>
    </div>
  </div>
  <!-- end row -->
@endsection
@section('script')
  <!-- Chart JS -->
  {{-- <script src="{{ URL::asset('/assets/libs/chart-js/chart-js.min.js') }}"></script> --}}

  <script>
    const getMatchData = (matchId) => {
      $.ajax({
        url: "{{ route('admin.matchData') }}",
        type: "GET",
        data: {
          matchId: matchId
        },
        success: function(data) {
            console.log(data);
          $("#sumTotalTickets").text(data.totalTickets);
          $("#soldTickets").text(data.soldTickets);
          $("#usedTickets").text(data.usedTicket)
          $("#soldInArena").text(data.soldInArena)
          $("#soldInWebsite").text(data.soldInWebsite)
        }
      });
    }

    const showMatchInfo = (data) => {
      $('#homeDiv img').attr('src', data.homeLogo);
      $('#homeDiv span').text(data.homeName);
      $('#awayDiv img').attr('src', data.awayLogo);
      $('#awayDiv span').text(data.awayName);
    }

    $(document).ready(function() {

      $('#matchId :selected').each(function(i, selected) {
        let data = {
          homeLogo: $(selected).attr('home_logo'),
          awayLogo: $(selected).attr('away_logo'),
          homeName: $(selected).attr('home_name'),
          awayName: $(selected).attr('away_name'),
        }

        showMatchInfo(data);
      });

      latestMatchId = "{{ $data['latestMatchId'] }}";
      getMatchData(latestMatchId);

    });

    // on matchId change send ajax request to get match data
    $('#matchId').on('select2:select', function(e) {
      let matchId = $(this).val();

      let data = {
        homeLogo: $(e.params.data.element).attr('home_logo'),
        awayLogo: $(e.params.data.element).attr('away_logo'),
        homeName: $(e.params.data.element).attr('home_name'),
        awayName: $(e.params.data.element).attr('away_name'),
      }

      showMatchInfo(data);
      getMatchData(matchId);
    });


    // GET CHART DATA FROM BACKEND
  </script>
@endsection

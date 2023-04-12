<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">

<head>
  <meta charset="utf-8" />
  <title>{{ config('app.name') }} | @yield('title')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
  <meta content="Themesbrand" name="author" />
  <meta name="_token" content="{{ csrf_token() }}">
  <!-- App favicon -->
  <link rel="shortcut icon" href="{{ URL::asset('/assets/images/logo.png') }}">
  @include('layouts.head-css')
</head>

@section('body')

  <body data-sidebar="dark">
  @show
  <!-- Begin page -->
  <div id="layout-wrapper">
    @include('layouts.topbar')
    @include('layouts.sidebar')
    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content">
      <div class="page-content">
        <div class="container-fluid">
          @yield('content')
        </div>
        <!-- container-fluid -->
      </div>
      <!-- End Page-content -->
      @include('layouts.footer')
    </div>
    <!-- end main content-->
  </div>
  <!-- END layout-wrapper -->

  <!-- Right Sidebar -->
  {{-- @include('layouts.right-sidebar') --}}
  <!-- /Right-bar -->

  {{-- js file transaltion --}}
  <div class="d-none">
    <p id="maxFileMessage">@lang('messages.max_files')</p>
    <p id="noneOption">@lang('translation.none')</p>
    <p id="noSeatAvailable">@lang('translation.ticket.no_seat_available')</p>
    <p id="noSeatAvailable">@lang('translation.gate.no_gate_available')</p>
    <p id="duplicateSelectedTeam">@lang('messages.home_and_away_team_cannot_be_same')</p>
    <p id="storeTempFile">{{ route('admin.storeTempFile') }}</p>
    <p id="deleteTempFile">{{ route('admin.deleteTempFile') }}</p>
    {{-- <p id="getSeatsByCategory">{{ route('admin.seats.getSeatsByCategory', '') }}</p> --}}
  </div>

  <!-- JAVASCRIPT -->
  @include('layouts.vendor-scripts')
</body>

</html>

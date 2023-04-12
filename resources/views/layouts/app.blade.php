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

  {{-- Include Css --}}
  @include('layouts.app-css')
</head>

<body>
  @include('layouts.app-navbar')
  @yield('content')
  @include('layouts.app-footer')
  @include('layouts.app-scripts')

</body>

</html>

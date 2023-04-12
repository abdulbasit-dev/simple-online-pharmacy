@extends('layouts.master')

@section('title')
  @lang('sidebar.dashboard')
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


  <div class="">
    <div class="col-xl-12">
      {!! QrCode::style('round')->color(18,152,144)->size(300)->generate(Request::url()) !!}
    </div>
  </div>
  <!-- end row -->
@endsection

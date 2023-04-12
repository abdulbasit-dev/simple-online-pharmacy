@extends('layouts.master')

@section('title')
  Medicines
@endsection

@section('css')
  <!-- DataTables -->
  <link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
  @component('components.breadcrumb')
    @slot('li_1')
      Medicines
    @endslot
    @slot('li_2')
      {{ route('admin.medicines.index') }}
    @endslot
    @slot('title')
      {{-- showing the title according to the status of match --}}
      @switch(request()->get('status'))
        @case('today')
          {{ __('sidebar.today_matches') }}
        @break

        @case('upcoming')
          {{ __('sidebar.upcoming_matches') }}
        @break

        @case('finished')
          {{ __('sidebar.finished_matches') }}
        @break

        @case(null)
          All Medicines
        @break

        @default
      @endswitch
    @endslot
  @endcomponent

  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-end mb-4" id="action_btns">
            @can('match_add')
              <a href="{{ route('admin.medicines.create') }}" class="btn btn-success btn-rounded waves-effect waves-light ms-2"><i
                  class="mdi mdi-plus me-1"></i>Add New Medicine</a>
            @endcan
          </div>
          <table id="datatable" class="table-hover table-bordered nowrap w-100 table">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th class="text-center">Image</th>
                <th>Name</th>
                {{-- <th>Medicine Info</th> --}}
                <th>Type</th>
                <th>Origin</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Expire At</th>
                <th>@lang('translation.created_at')</th>
                <th>@lang('translation.actions')</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>

        </div>
      </div>
    </div> <!-- end col -->
  </div> <!-- end row -->
@endsection
@section('script')
  <!-- Required datatable js -->
  <script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>

  {{-- datatable init --}}
  <script type="text/javascript">
    $(function() {
      let table = $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        lengthChange: true,
        lengthMenu: [10, 20, 50, 100],
        pageLength: 10,
        scrollX: true,
        order: [
          [0, "desc"]
        ],
        // text transalations
        language: {
          search: "@lang('translation.search')",
          lengthMenu: "@lang('translation.lengthMenu1') _MENU_ @lang('translation.lengthMenu2')",
          processing: "@lang('translation.processing')",
          info: "@lang('translation.infoShowing') _START_ @lang('translation.infoTo') _END_ @lang('translation.infoOf') _TOTAL_ @lang('translation.infoEntries')",
          emptyTable: "@lang('translation.emptyTable')",
          paginate: {
            "first": "@lang('translation.paginateFirst')",
            "last": "@lang('translation.paginateLast')",
            "next": "@lang('translation.paginateNext')",
            "previous": "@lang('translation.paginatePrevious')"
          },
        },
        ajax: "{{ route('admin.medicines.index', ['status' => request()->get('status')]) }}",
        columns: [{
            data: 'id'
          },
          {
            data: 'image',
          },
          {
            data: 'name',
          },
        //   {
        //     data: 'information',
        //   },
          {
            data: 'type',
          },
          {
            data: 'origin',
          },
          {
            data: 'quantity',
          },
          {
            data: 'price',
          },
          {
            data: 'expire_at',
          },
          {
            data: 'created_at',
          },
          {
            data: 'action',
            orderable: false,
            searchable: false
          },
        ],
      })

      // select dropdown for change the page length
      $('.dataTables_length select').addClass('form-select form-select-sm');

      // add margin top to the pagination and info
      $('.dataTables_info, .dataTables_paginate').addClass('mt-3');
    });
  </script>
@endsection

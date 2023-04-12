@extends('layouts.master')

@section('title')
  Banners
@endsection

@section('css')
  <!-- DataTables -->
  <link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
  <!-- Lightbox css -->
  <link href="{{ URL::asset('/assets/libs/magnific-popup/magnific-popup.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
  @component('components.breadcrumb')
    @slot('li_1')
      Banners
    @endslot
    @slot('li_2')
      {{ route('admin.banners.index') }}
    @endslot
    @slot('title')
      @lang('translation.resource_list', ['resource' => __('attributes.banner')])
    @endslot
  @endcomponent

  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-end mb-4" id="action_btns">
            @can('banner_add')
              <a href="{{ route('admin.banners.create') }}" class="btn btn-success btn-rounded waves-effect waves-light ms-2"><i class="mdi mdi-plus me-1"></i>@lang('buttons.add_resource', ['resource' => __('attributes.banner')])</a>
            @endcan
          </div>
          <table id="datatable" class="table-hover table-bordered nowrap w-100 table">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th> Name</th>
                <th> Image</th>
                <th> Status</th>
                <th> @lang('translation.created_by')</th>
                <th> @lang('translation.created_at')</th>
                <th> @lang('translation.actions')</th>
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
  <!-- Magnific Popup-->
  <script src="{{ URL::asset('/assets/libs/magnific-popup/magnific-popup.min.js') }}"></script>

  <script type="text/javascript">
    // Lightbox init
    $(document).on('click', '.bannerLightBox', function() {
      let image = $(this).attr('src');
      let html = '<img src="' + image + '" class="img-fluid" />';
      $.magnificPopup.open({
        items: {
          src: html,
          type: 'inline'
        },
        closeOnContentClick: true,
        closeBtnInside: true,
        fixedContentPos: true,
        mainClass: 'mfp-no-margins mfp-with-zoom',
        image: {
          verticalFit: true
        },
        zoom: {
          enabled: true,
          duration: 300
        }
      });
    })

    //  datatable init
    $(function() {
      let table = $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        lengthChange: true,
        lengthMenu: [10, 20, 50, 100],
        pageLength: 10,
        scrollX: true,
        order: [
          [2, "desc"]
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
        ajax: "{{ route('admin.banners.index') }}",

        columns: [{
            data: 'id'
          },
          {
            data: 'name'
          },
          {
            data: 'banner'
          },
          {
            data: 'status'
          },
          {
            data: 'created_by',
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

      //init buttons
      new $.fn.dataTable.Buttons(table, {
        buttons: [{
          extend: 'colvis',
          text: "@lang('translation.colvisBtn')"
        }]
      });

      // select dropdown for change the page length
      $('.dataTables_length select').addClass('form-select form-select-sm');

      // add margin top to the pagination and info
      $('.dataTables_info, .dataTables_paginate').addClass('mt-3');
    });
  </script>
@endsection

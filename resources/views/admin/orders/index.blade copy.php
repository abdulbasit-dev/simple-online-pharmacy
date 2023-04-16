@extends('layouts.master')

@section('title')
  Orders
@endsection

@section('css')
  <!-- DataTables -->
  <link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
  @component('components.breadcrumb')
    @slot('li_1')
      Orders
    @endslot
    @slot('li_2')
      {{ route('admin.orders.index') }}
    @endslot
    @slot('title')
      @switch(request()->get('status'))
        @case('current-match-order')
          Current Match Orders
        @break

        @case('expired-order')
          Expired Match Orders
        @break

        @default
          All Orders
      @endswitch
    @endslot
  @endcomponent

  {{-- filter --}}
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <h4 class="mb-4">Filters</h4>
          {{-- first row --}}
          <div class="row">
            <div class="col-lg-4">
              <div class="mb-3">
                <label for="matchId" class="form-label">@lang('translation.match.match')</label>
                <select class="form-select select2 filter-input" id="matchId" onchange="dateChanged()">
                  <option value="" selected>@lang('translation.none')</option>
                  @forelse ($matches as $match)
                    <option value="{{ $match['id'] }}">{{ $match['name'] }}</option>
                  @empty
                    <option value="">@lang('translation.none')</option>
                  @endforelse
                </select>
              </div>
            </div>

            <div class="col-lg-4">
              <div class="mb-3">
                <label for="store">Sold On:</label>
                <select class="form-select select2 filter-input" id="store" onchange="dateChanged()">
                  <option value="" selected>@lang('translation.none')</option>
                  @forelse ($stores as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                  @empty
                    <option value="">@lang('translation.none')</option>
                  @endforelse
                </select>
              </div>
            </div>

            <div class="col-lg-4">
              <div class="mb-3">
                <label for="categoryId">Ticket Privilege:</label>
                <select class="form-select select2 filter-input" id="categoryId" onchange="dateChanged()">
                  <option value="" selected>@lang('translation.none')</option>
                  @forelse ($categories as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                  @empty
                    <option value="">@lang('translation.none')</option>
                  @endforelse
                </select>
              </div>
            </div>
          </div>

          {{-- second row --}}
          <div class="row">
            <div class="col-lg-4">
              <div class="mb-3">
                <label for="gateNo">Gate Number:</label>
                <select class="form-select select2 filter-input" id="gateNo" onchange="dateChanged()">
                  <option value="" selected>@lang('translation.none')</option>
                  @forelse ($gates as $gate)
                    <option value="{{ $gate }}">{{ $gate }}</option>
                  @empty
                    <option value="">@lang('translation.none')</option>
                  @endforelse
                </select>
              </div>
            </div>

            <div class="col-lg-4">
              <div class="mb-3">
                <label for="used">Used:</label>
                <select class="form-select select2 filter-input" id="used" onchange="dateChanged()">
                  <option value="" selected>@lang('translation.none')</option>
                  <option value="used">@lang('translation.yes')</option>
                  <option value="not_used">@lang('translation.no')</option>
                </select>
              </div>
            </div>

            <div class="col-lg-4">
              <div class="mb-3">
                <label for="expired">Expired:</label>
                <select class="form-select select2 filter-input" id="expired" onchange="dateChanged()">
                  <option value="" selected>@lang('translation.none')</option>
                  <option value="expired">@lang('translation.yes')</option>
                  <option value="not_expired">@lang('translation.no')</option>
                </select>
              </div>
            </div>
          </div>

          {{-- third row --}}
          <div class="row">
            <div class="col-lg-4">
              <div class="mb-3">
                <label for="ticketNumber">Ticket Number:</label>
                <input type="text" class="form-control filter-input" id="ticketNumber" onchange="dateChanged()">
              </div>
            </div>

            <div class="col-lg-4">
              <div class="mb-3">
                <label for="serialNumber">Serial Number:</label>
                <input type="text" class="form-control filter-input" id="serialNumber" onchange="dateChanged()">
              </div>
            </div>

            <div class="col-lg-4">
              <div class="mb-3">
                <label for="transactionId">Transaction Id:</label>
                <input type="text" class="form-control filter-input" id="transactionId" onchange="dateChanged()">
              </div>
            </div>
          </div>

          <div>
            <button class="btn btn-light w-sm" onclick="reset()">@lang('buttons.reset')</button>
          </div>
        </div>

      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <table id="datatable" class="table-hover table-bordered nowrap w-100 table">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Serial Number</th>
                <th>Ticket Privilege</th>
                <th>Amount</th>
                <th>Sold On</th>
                {{-- <th>Sold At</th> --}}
                <th>Used</th>
                <th>Payment Status</th>
                <th>Expired</th>
                <th>Gate</th>
                <th class="text-center">@lang('translation.actions')</th>
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

  <script type="text/javascript">
    let table;

    function dateChanged() {
      table.draw();
    }

    function reset() {
      $('.filter-input').val('').trigger('change')
      table.draw();
    }

    $(function() {
      table = $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        lengthChange: true,
        lengthMenu: [10, 20, 50, 100],
        pageLength: 20,
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
        ajax: {
          url: "{{ route('admin.orders.index', ['status' => request()->get('status')]) }}",
          method: "GET",
          data: function(d) {
            d.matchId = $("#matchId").find(":selected").val();
            d.categoryId = $("#categoryId").find(":selected").val();
            d.gateNo = $("#gateNo").find(":selected").val();
            d.store = $("#store").find(":selected").val();
            d.used = $("#used").find(":selected").val();
            d.expired = $("#expired").find(":selected").val();
            d.ticketNumber = $("#ticketNumber").val();
            d.serialNumber = $("#serialNumber").val();
            d.transactionId = $("#transactionId").val();
          }
        },
        columns: [{
            data: 'id'
          },
          {
            data: 'serial_number'
          },
          {
            data: 'privilege',
            searchable: false,
          },
          {
            data: 'price'
          },
          {
            data: 'store_id',
            searchable: false,
          },
          //   {
          //     data: 'created_at',
          //   },
          {
            data: 'is_used',
            searchable: false,
          },
          {
            data: 'payment_status',
          },
          {
            data: 'is_expired',
            searchable: false,
          },
          {
            data: 'gate_no',
          },
          {
            data: 'action',
            orderable: false,
            searchable: false
          },
        ]
      })


      // select dropdown for change the page length
      $('.dataTables_length select').addClass('form-select form-select-sm');

      // add margin top to the pagination and info
      $('.dataTables_info, .dataTables_paginate').addClass('mt-3');
    });
  </script>
@endsection

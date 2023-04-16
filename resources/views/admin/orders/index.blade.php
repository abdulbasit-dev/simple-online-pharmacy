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

  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <table id="datatable" class="table-hover table-bordered nowrap w-100 table">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Medicine Name</th>
                <th>Customer Name</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Order At</th>
                <th class="text-center">@lang('translation.actions')</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>

          <div class="modal fade" id="changeStatusModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <form method="POST" class="w-100" id="changeStatusForm" action="">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Change Order Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    @csrf
                    <div class="mt-4">
                      <div class="form-check form-radio-outline form-radio-success mb-3">
                        <input class="form-check-input" type="radio" name="status" value="accept" id="accept" checked="">
                        <label class="form-check-label" for="accept">
                          Accept
                        </label>
                      </div>
                      <div class="form-check form-radio-outline form-radio-danger">
                        <input class="form-check-input" type="radio" name="status" value="cancel" id="cancel">
                        <label class="form-check-label" for="cancel">
                          Cancel
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('buttons.close')</button>
                    <button type="submit" class="btn btn-primary submit-btn">@lang('buttons.submit')</button>
                  </div>
                </div>
              </form>
            </div>
          </div>

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
            data: 'medicine.name'
          },
          {
            data: 'customer.name',
          },
          {
            data: 'quantity',
          },
          {
            data: 'total',
          },
          {
            data: 'status',
            searchable: false,
          },
          {
            data: 'created_at',
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

    // change status modal
    let changeStatusModal = document.getElementById('changeStatusModal')

    changeStatusModal.addEventListener('show.bs.modal', function(event) {
      // Button that triggered the modal
      let button = event.relatedTarget

      // Update the modal's content.
      document.getElementById('changeStatusForm').action = button.getAttribute('data-url');
    });

    // on form submit send ajax request
    $(".submit-btn").click(function(e) {
      e.preventDefault();
      let form = $("#changeStatusForm");
      let url = form.attr('action');
      let data = form.serialize();

      $.ajax({
        url: url,
        type: 'POST',
        data: data,
        success: function(data) {
          $('#changeStatusModal').modal('hide');
          Swal.fire({
            timer: "1000",
            text: data.message,
            icon: "success"
          }).then(function() {
            table.draw();
          });

        },
        error: function(data) {
          if (data.responseJSON.status === 500) {
            Swal.fire({
              timer: "20000",
              title: data.responseJSON.message,
              text: data.responseJSON.errors,
              customClass: "swal-error",
              icon: "error",
            })
          }

          Swal.fire({
            timer: "2000",
            text: data.responseJSON.message,
            icon: "warning",
          });
        }
      });
    });
  </script>
@endsection

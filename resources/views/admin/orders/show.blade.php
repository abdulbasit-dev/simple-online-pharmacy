@extends('layouts.master')

@section('title')
  @lang('translation.resource_info', ['resource' => __('attributes.order')])
@endsection

@section('css')
  <!-- Lightbox css -->
  <link href="{{ URL::asset('/assets/libs/magnific-popup/magnific-popup.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
  @component('components.breadcrumb')
    @slot('li_1')
      @lang('translation.sold_ticket.sold_ticket')
    @endslot
    @slot('li_2')
      {{ route('admin.orders.index') }}
    @endslot
    @slot('title')
      @lang('translation.resource_info', ['resource' => __('attributes.order')])
    @endslot
  @endcomponent

  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">

          <div class="row justify-content-between">
            <div class="col-xl-6 pe-5">
              <h3 class="border-bottom border-3 text-primary pb-2">Order Detail</h3>
              <div>
                <div class="table-responsive">
                  <table class="table-borderless mb-0 table">
                    <tbody>
                      <tr>
                        <th scope="row">#</th>
                        <td>{{ $order->id }}</td>
                      </tr>

                      <tr>
                        <th scope="row">Medicine Name:</th>
                        <td>{{ $order->medicine->name }}</td>
                      </tr>

                      <tr>
                        <th scope="row">Medicine Price:</th>
                        <td>{{ formatPrice($order->medicine->price) }}</td>
                      </tr>

                      <tr>
                        <th scope="row">Quantity:</th>
                        <td>{{ $order->quantity }}</td>
                      </tr>

                      <tr>
                        <th scope="row">Total Price:</th>
                        <td>{{ formatPrice($order->total) }}</td>
                      </tr>

                      <tr>
                        <th scope="row">Status:</th>
                        <td>
                          {!! $order->status->getLabelHtml() !!}
                        </td>
                      </tr>

                      <tr>
                        <th scope="row">Order At:</th>
                        <td>{{ formatDateWithTimezone($order->created_at) }}</td>
                      </tr>

                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <div class="col-xl-6 ps-5">
              <h3 class="border-bottom border-3 text-primary pb-2">Customer Information</h3>
              <div>
                <div class="table-responsive">
                  <table class="table-borderless mb-0 table">
                    <tbody>
                      <tr>
                        <th scope="row">Customer ID:</th>
                        <td>#{{ $order->customer->id }}</td>
                      </tr>

                      <tr>
                        <th scope="row">Customer Name:</th>
                        <td>{{ $order->customer->name }}</td>
                      </tr>

                      <tr>
                        <th scope="row">Customer Address:</th>
                        <td>{{ $order->customer->address }}</td>
                      </tr>
                      <tr>
                        <th scope="row">Customer Phone:</th>
                        <td>{{ $order->customer->phone }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <!-- end row -->

          {{-- action btn --}}
          <div class="d-flex justify-content-start mt-4">
            <div>
              <button type="button" class="btn btn-primary waves-effect waves-light me-1 cancel-btn" data-bs-toggle="modal" data-bs-target="#changeStatusModal" data-url="{{ route('admin.orders.changeStatus', $order->id) }}">Change Status</button>
            </div>
          </div>
        </div>

      </div>
      <!-- end card -->
    </div>
  </div>

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


  <!-- end row -->
@endsection
@section('script')
  <script>
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
            location.reload();
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

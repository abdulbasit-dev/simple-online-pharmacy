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
  {{-- first row: orders --}}
  <div class="row">
    <div class="col-xl-12">
      <div class="row">
        <div class="col-md-3">
          <div class="card mini-stats-wid">
            <div class="card-body">
              <div class="d-flex">
                <div class="flex-grow-1">
                  <p class="text-muted fw-medium">Total Orders</p>
                  <h4 class="mb-0">{{ $data['totalOrders'] }}</h4>
                </div>

                <div class="align-self-center flex-shrink-0">
                  <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                    <span class="avatar-title rounded-circle bg-primary">
                      <i class="bx bx-receipt font-size-24"></i>
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
                  <p class="text-muted fw-medium">Total Accepted Orders</p>
                  <h4 class="mb-0">{{ $data['totalAcceptedOrders'] }}</h4>
                </div>

                <div class="align-self-center flex-shrink-0">
                  <div class="avatar-sm rounded-circle bg-success mini-stat-icon">
                    <span class="avatar-title rounded-circle bg-success">
                      <i class="bx bx-receipt font-size-24"></i>
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
                  <p class="text-muted fw-medium">Total Pending Orders</p>
                  <h4 class="mb-0">{{ $data['totalPendingOrders'] }}</h4>
                </div>

                <div class="align-self-center flex-shrink-0">
                  <div class="avatar-sm rounded-circle bg-warning mini-stat-icon">
                    <span class="avatar-title rounded-circle bg-warning">
                      <i class="bx bx-receipt font-size-24"></i>
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
                  <p class="text-muted fw-medium">Total Cancled Order</p>
                  <h4 class="mb-0">{{ $data['totalCanceledOrders'] }}</h4>
                </div>

                <div class="align-self-center flex-shrink-0">
                  <div class="avatar-sm rounded-circle bg-danger mini-stat-icon">
                    <span class="avatar-title rounded-circle bg-danger">
                      <i class="bx bx-receipt font-size-24"></i>
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

    {{-- second row: medicines --}}
  <div class="row">
    <div class="col-xl-12">
      <div class="row">
        <div class="col-md-3">
          <div class="card mini-stats-wid">
            <div class="card-body">
              <div class="d-flex">
                <div class="flex-grow-1">
                  <p class="text-muted fw-medium">Total Medicines</p>
                  <h4 class="mb-0">{{ $data['totalMedicines'] }}</h4>
                </div>

                <div class="align-self-center flex-shrink-0">
                  <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                    <span class="avatar-title rounded-circle bg-primary">
                      <i class="bx bx-plus-medical font-size-24"></i>
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
                  <p class="text-muted fw-medium">Min Stock Medicins</p>
                  <h4 class="mb-0">{{ $data['totalMinStockMedicines'] }}</h4>
                </div>

                <div class="align-self-center flex-shrink-0">
                  <div class="avatar-sm rounded-circle bg-danger mini-stat-icon">
                    <span class="avatar-title rounded-circle bg-danger">
                      <i class="bx bx-package font-size-24"></i>
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
                  <p class="text-muted fw-medium">Medicine Types</p>
                  <h4 class="mb-0">{{ $data['totalMedicineTypes'] }}</h4>
                </div>

                <div class="align-self-center flex-shrink-0">
                  <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                    <span class="avatar-title rounded-circle bg-primary">
                      <i class="bx bx-extension font-size-24"></i>
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
                  <p class="text-muted fw-medium">Total Origins</p>
                  <h4 class="mb-0">{{ $data['totalOrigins'] }}</h4>
                </div>

                <div class="align-self-center flex-shrink-0">
                  <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                    <span class="avatar-title rounded-circle bg-primary">
                      <i class="bx bx-map-pin font-size-24"></i>
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

  {{-- last 10 orders --}}
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
                  <th>Medicine Name</th>
                  <th>Customer Name</th>
                  <th>Quantity</th>
                  <th>Total Price</th>
                  <th>Status</th>
                  <th>Order At</th>
                  <th class="text-center">@lang('translation.actions')</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($data['latestOrders'] as $order)
                  <tr class="text-cente align-middle">
                    <td><span href class="text-body fw-bold">{{ $order->id }}</span> </td>
                    <td>{{ $order->medicine->name }}</td>
                    <td>{{ $order->customer->name }}</td>
                    <td>{{ $order->quantity }}</td>
                    <td>{{ $order->total }}</td>
                    <td>{!! $order->status->getLabelHtml() !!}</td>
                    <td>{{ formatDateWithTimezone($order->created_at) }}</td>
                    <td>
                      <div class="d-flex justify-content-center">
                        <a href="{{ route('admin.orders.show', $order->id) }}" type="button" class="btn btn-sm btn-primary waves-effect waves-light me-1">View Detail</a>
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="10" class="text-center">No data found</td>
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

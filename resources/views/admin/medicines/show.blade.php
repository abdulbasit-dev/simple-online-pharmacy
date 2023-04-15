@extends('layouts.master')

@section('title')
  Medicine Information
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
      Medicine Information
    @endslot
  @endcomponent

  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-body">
          <div class="row align-items-center justify-content-between">
            <div class="col-xl-6">
              <div class="table-responsive">
                <table class="table-borderless mb-0 table">
                  <tbody>
                    <tr>
                      <th scope="row" style="width: 400px;">#</th>
                      <td>{{ $medicine->id }}</td>
                    </tr>
                    <tr>
                      <th scope="row" style="width: 400px;">Name</th>
                      <td>{{ $medicine->name }}</td>
                    </tr>
                    <tr>
                      <th scope="row" style="width: 400px;">Description</th>
                      <td>{{ $medicine->description }}</td>
                    </tr>

                    <tr>
                      <th scope="row" style="width: 400px;">Origin</th>
                      <td><span class="badge badge-pill badge-soft-info font-size-13 m-1 p-2"> {{ $medicine->origin->name }}</span></td>
                    </tr>

                    <tr>
                      <th scope="row" style="width: 400px;">Type</th>
                      <td><span class="badge badge-pill badge-soft-info font-size-13 m-1 p-2"> {{ $medicine->type->name }}</span></td>
                    </tr>

                    <tr>
                      <th scope="row" style="width: 400px;">Price</th>
                      <td>{{ formatPrice($medicine->price) }}</td>
                    </tr>

                    <tr>
                      <th scope="row" style="width: 400px;">Quantity</th>
                      <td>{{ $medicine->quantity }}</td>
                    </tr>

                    <tr>
                      <th scope="row" style="width: 400px;">Sold Quantity</th>
                      <td>{{ $medicine->orders()->count() }}</td>
                    </tr>

                    <tr>
                      <th scope="row" style="width: 400px;">Remain Quantity</th>
                      <td>{{ $medicine->quantity - $medicine->orders()->count() }}</td>
                    </tr>

                    <tr>
                      <th scope="row">Expire At:</th>
                      <td>{{ formatDateWithTimezone($medicine->expire_at) }}</td>
                    </tr>

                    @if ($medicine->expire_at < now())
                      <tr>
                        <th scope="row" style="width: 400px;">@lang('translation.status')</th>
                        <td><span class="badge badge-pill badge-soft-danger font-size-13">@lang('translation.expired')</span></td>
                      </tr>
                    @endif

                    <tr>
                      <th scope="row">Manufacture At:</th>
                      <td>{{ formatDateWithTimezone($medicine->manufacture_at) }}</td>
                    </tr>

                    <tr>
                      <th scope="row">Created At:</th>
                      <td>{{ formatDateWithTimezone($medicine->created_at) }}</td>
                    </tr>

                  </tbody>
                </table>
              </div>
            </div>

            <div class="col-xl-6 text-center">
              <img src="{{ getFile($medicine) }}" class="img-fluid rounded" alt="">
            </div>
          </div>
          <!-- end row -->

        </div>
      </div>
      <!-- end card -->
    </div>
  </div>
  <!-- end row -->
@endsection

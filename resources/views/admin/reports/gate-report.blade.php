@extends('layouts.master')

@section('title')
    Gate Report
@endsection

@section('content')
  @component('components.breadcrumb')
    @slot('li_1')
      Reports
    @endslot
    @slot('li_2')
      {{ route('admin.reports.gate') }}
    @endslot
    @slot('title')
        Gate Report
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
                <th>Gate Number</th>
                <th>Privilege</th>
                <th>Limit</th>
                <th>Sold</th>
                <th>Remain</th>
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
 
  

@endsection

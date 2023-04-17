@extends('layouts.master')

@section('title')
  Add Type
@endsection

@section('content')
  @component('components.breadcrumb')
    @slot('li_1')
      Origins
    @endslot
    @slot('li_2')
      {{ route('admin.types.index') }}
    @endslot
    @slot('title')
      Add Type
    @endslot
  @endcomponent

  <div class="row">
    <div class="col-xl-12">
      <div class="card">
        <div class="card-body">
          @if ($errors->any())
            <div class="alert alert-danger">
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif
          <form class="needs-validation" novalidate action="{{ route('admin.types.store') }}" method="POST">
            @csrf
            <div class="row">
              <div class="col-8">

                <div class="row mb-4">
                  <label for="name" class="col-sm-3 col-form-label">Name</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                    <div class="valid-feedback">
                      @lang('validation.good')
                    </div>
                    <div class="invalid-feedback">
                      @lang('validation.required', ['attribute' => 'Name'])
                    </div>
                  </div>
                </div>
                <div class="row justify-content-start">
                  <div class="col-sm-9">
                    <div>
                      <button class="btn btn-primary w-md" type="submit">@lang('buttons.submit')</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </form>

        </div>
      </div>
      <!-- end card -->
    </div> <!-- end col -->
  </div>
@endsection

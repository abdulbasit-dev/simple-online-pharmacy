@extends('layouts.master')

@section('title')
  Add Medicine
@endsection

@section('plugin-css')
  <!-- Dropzone -->
  <link href="{{ URL::asset('/assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
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
      Add Medicine
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
          <div class="row align-items-center">
            <div class="col-lg-8">
              <form class="needs-validation" novalidate action="{{ route('admin.medicines.store') }}" method="POST">
                @csrf

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

                <div class="row mb-4">
                  <label for="name" class="col-sm-3 col-form-label">Description</label>
                  <div class="col-sm-9">
                    <textarea name="description" class="form-control" id="" cols="30" rows="5">{{ old('description') }}</textarea>
                    <div class="valid-feedback">
                      @lang('validation.good')
                    </div>
                    <div class="invalid-feedback">
                      @lang('validation.required', ['attribute' => 'Description'])
                    </div>
                  </div>
                </div>

                <div class="row mb-4">
                  <label for="origin" class="col-sm-3 col-form-label">Origin</label>
                  <div class="col-sm-9">
                    <select class="form-select select2" id="origin" name="origin_id" required>
                      <option value="" selected>@lang('translation.none')</option>
                      @foreach ($origins as $key => $value)
                        <option value="{{ $key }}" @selected($key == old('origin_id'))>{{ $value }}</option>
                      @endforeach
                    </select>
                    <div class="valid-feedback">
                      @lang('validation.good')
                    </div>
                    <div class="invalid-feedback">
                      @lang('validation.required', ['attribute' => 'Origin'])
                    </div>
                  </div>
                </div>

                <div class="row mb-4">
                  <label for="type" class="col-sm-3 col-form-label">Type</label>
                  <div class="col-sm-9">
                    <select class="form-select select2" id="type" name="type_id" required>
                      <option value="" selected>@lang('translation.none')</option>
                      @foreach ($types as $key => $value)
                        <option value="{{ $key }}" @selected($key == old('origin_id'))>{{ $value }}</option>
                      @endforeach
                    </select>
                    <div class="valid-feedback">
                      @lang('validation.good')
                    </div>
                    <div class="invalid-feedback">
                      @lang('validation.required', ['attribute' => 'Type'])
                    </div>
                  </div>
                </div>

                <div class="row mb-4">
                  <label for="price" class="col-sm-3 col-form-label">Price</label>
                  <div class="col-sm-9">
                    <div class="input-group">
                      <input type="text" class="form-control" id="price" name="price" value="{{ old('price') }}">
                      <div class="input-group-text">IQD</div>
                    </div>
                    <div class="valid-feedback">
                      @lang('validation.good')
                    </div>
                    <div class="invalid-feedback">
                      @lang('validation.required', ['attribute' => 'Name'])
                    </div>
                  </div>
                </div>

                <div class="row mb-4">
                  <label for="quantity" class="col-sm-3 col-form-label">Quantity</label>
                  <div class="col-sm-9">
                    <input type="number" class="form-control" id="quantity" min="1" max="100" name="quantity" value="{{ old('quantity') }}" required>
                    <div class="valid-feedback">
                      @lang('validation.good')
                    </div>
                    <div class="invalid-feedback">
                      @lang('validation.required', ['attribute' => 'Quantity'])
                    </div>
                  </div>
                </div>

                <div class="row mb-4">
                  <label for="manufacture_at" class="col-sm-3 col-form-label">Manufacture At</label>
                  <div class="col-sm-9">
                    <input type="datetime-local" class="form-control" id="manufacture_at" name="manufacture_at" required>
                    <div class="valid-feedback">
                      @lang('validation.good')
                    </div>
                    <div class="invalid-feedback">
                      @lang('validation.required', ['attribute' => 'Manufacture At'])
                    </div>
                  </div>
                </div>

                <div class="row mb-4">
                  <label for="expire_at" class="col-sm-3 col-form-label">Expire At</label>
                  <div class="col-sm-9">
                    <input type="datetime-local" class="form-control" id="expire_at" name="expire_at" required>
                    <div class="valid-feedback">
                      @lang('validation.good')
                    </div>
                    <div class="invalid-feedback">
                      @lang('validation.required', ['attribute' => 'Expire At'])
                    </div>
                  </div>
                </div>

                <div class="row mb-4">
                  <label for="logo" class="col-sm-3 col-form-label">Medicine Image</label>
                  <div class="col-sm-9" id="upload">
                    <div id="myDropzone" class="dropzone">
                      <div class="dz-message needsclick">
                        <div class="mb-3">
                          <i class="display-4 text-muted bx bxs-cloud-upload"></i>
                        </div>
                        <h4>@lang('translation.drop_here')</h4>
                      </div>
                    </div>
                    <div class="valid-feedback">
                      @lang('validation.good')
                    </div>
                    <div class="invalid-feedback">
                      @lang('validation.required', ['attribute' => __('translation.team.logo')])
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
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- end card -->
    </div> <!-- end col -->
  </div>
@endsection

@section('script')
  <!-- Dropzone js -->
  <script src="{{ URL::asset('/assets/libs/dropzone/dropzone.min.js') }}"></script>
  {{-- Dropzone Config --}}
  <script src="{{ URL::asset('assets/js/dropzone-config.js') }}"></script>

  <script>
    // set today as default value for match_time
    let today = new Date().toISOString().slice(0, 16);
    // today + 1 year
    let expire_at = new Date();
    expire_at.setFullYear(expire_at.getFullYear() + 1);
    expire_at = expire_at.toISOString().slice(0, 16);

    $('#manufacture_at').val(today);
    $('#expire_at').val(expire_at);
  </script>
@endsection

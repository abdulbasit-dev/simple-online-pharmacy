@extends('layouts.master')

@section('title')
  @lang('translation.add_resource', ['resource' => __('attributes.user')])
@endsection

@section('content')
  @component('components.breadcrumb')
    @slot('li_1')
      @lang('translation.user.user')
    @endslot
    @slot('li_2')
      {{ route('admin.users.index') }}
    @endslot
    @slot('title')
      @lang('translation.add_resource', ['resource' => __('attributes.user')])
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
          <form class="needs-validation" novalidate action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="row">
              <div class="col-xl-8">

                <div class="row mb-4">
                  <label for="name" class="col-sm-3 col-form-label">@lang('translation.user.name')</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}"
                      required>
                    <div class="valid-feedback">
                      @lang('validation.good')
                    </div>
                    <div class="invalid-feedback">
                      @lang('validation.required', ['attribute' => __('translation.user.name')])
                    </div>
                  </div>
                </div>

                <div class="row mb-4">
                  <label for="email" class="col-sm-3 col-form-label">@lang('translation.user.email')</label>
                  <div class="col-sm-9">
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}"
                      required>
                    <div class="valid-feedback">
                      @lang('validation.good')
                    </div>
                    <div class="invalid-feedback">
                      @lang('validation.required', ['attribute' => __('translation.user.email')])
                    </div>
                  </div>
                </div>

                <div class="row mb-4">
                  <label for="password" class="col-sm-3 col-form-label">@lang('translation.user.password')</label>
                  <div class="col-sm-9">
                    <input type="password" class="form-control" id="password" name="password" required>
                    <div class="valid-feedback">
                      @lang('validation.good')
                    </div>
                    <div class="invalid-feedback">
                      @lang('validation.required', ['attribute' => __('translation.user.password')])
                    </div>
                  </div>
                </div>
                
                <div class="row mb-4">
                  <label for="role" class="col-sm-3 col-form-label">@lang('translation.user.role')</label>
                  <div class="col-sm-9">
                    <select class="form-control select2" id="role" name="role" required>
                      <option value="" selected>@lang('translation.none')</option>
                      @foreach ($roles as $key => $value)
                        <option value="{{ $value }}">{{ $value }}</option>
                      @endforeach
                    </select>
                    <div class="valid-feedback">
                      @lang('validation.good')
                    </div>
                    <div class="invalid-feedback">
                      @lang('validation.required', ['attribute' => __('translation.user.role')])
                    </div>
                  </div>
                </div>

                <div class="row mb-4">
                  <label for="phone" class="col-sm-3 col-form-label">@lang('translation.user.phone')</label>
                  <div class="col-sm-9">
                    <input type="tel" class="form-control" id="phone" name="phone" value="{{ old('phone') }}"
                      required>
                    <div class="valid-feedback">
                      @lang('validation.good')
                    </div>
                    <div class="invalid-feedback">
                      @lang('validation.required', ['attribute' => __('translation.user.phone')])
                    </div>
                  </div>
                </div>

                <div class="row mb-4">
                  <label for="phone_alt" class="col-sm-3 col-form-label">@lang('translation.user.phone_alt')</label>
                  <div class="col-sm-9">
                    <input type="tel" class="form-control" id="phone_alt" name="phone_alt"
                      value="{{ old('phone_alt') }}">
                    <div class="valid-feedback">
                      @lang('validation.good')
                    </div>
                    <div class="invalid-feedback">
                      @lang('validation.required', ['attribute' => __('translation.user.phone_alt')])
                    </div>
                  </div>
                </div>


                <div class="row mb-4">
                  <label for="address" class="col-sm-3 col-form-label">@lang('translation.user.address')</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}"
                      required>
                    <div class="valid-feedback">
                      @lang('validation.good')
                    </div>
                    <div class="invalid-feedback">
                      @lang('validation.required', ['attribute' => __('translation.user.address')])
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
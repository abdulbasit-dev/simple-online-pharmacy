@extends('layouts.master')

@section('title')
  @lang('translation.edit_resource', ['resource' => __('attributes.role')])
@endsection

@section('content')
  @component('components.breadcrumb')
    @slot('li_1')
      @lang('translation.role.role')
    @endslot
    @slot('li_2')
      {{ route('admin.roles.index') }}
    @endslot
    @slot('title')
      @lang('translation.edit_resource', ['resource' => __('attributes.role')])
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
          <form class="needs-validation" novalidate action="{{ route('admin.roles.update', $role->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
              <div class="col-8">

                <div class="row mb-4">
                  <label for="name" class="col-sm-3 col-form-label">@lang('translation.role.name')</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="name" name="name"
                      value="{{ old('name', $role->name) }}" required>
                    <div class="valid-feedback">
                      @lang('validation.good')
                    </div>
                    <div class="invalid-feedback">
                      @lang('validation.required', ['attribute' => __('translation.role.name')])
                    </div>
                  </div>
                </div>

                <div class="row mb-4">
                  <label for="permission" class="col-sm-3 col-form-label">@lang('translation.role.permissions')</label>
                  <div class="col-sm-9">
                    <span class="btn btn-info btn-sm mb-2 select-all">{{ trans('buttons.select_all') }}</span>
                    <span class="btn btn-info btn-sm deselect-all mb-2">{{ trans('buttons.deselect_all') }}</span></label>
                    <select class="form-control select2" id="permission" name="permission[]" multiple="multiple" required>
                      @foreach ($permissions as $id => $permissions)
                        <option value="{{ $id }}" @selected(in_array($id, old('permission', [])) || (isset($role) && $role->permissions->pluck('name')->contains($id)))>{{ $permissions }}</option>
                      @endforeach
                    </select>
                    <div class="valid-feedback">
                      @lang('validation.good')
                    </div>
                    <div class="invalid-feedback">
                      @lang('validation.required', ['attribute' => __('translation.role.permissions')])
                    </div>
                  </div>
                </div>

                <div class="row justify-content-start">
                  <div class="col-sm-9">
                    <div>
                      <button class="btn btn-info w-md" type="submit">@lang('buttons.update')</button>
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

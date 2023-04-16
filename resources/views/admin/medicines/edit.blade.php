@extends('layouts.master')

@section('title')
  Edit Medicine
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
      Edit Medicine
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
          @if ($medicine->orders_count > 0)
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
              <i class="mdi mdi-alert-outline me-2"></i>
              Medicine is already in sale, you can not edit it.
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif
          <div class="row align-items-center">

            <div class="col-lg-8">
              <form class="needs-validation" novalidate action="{{ route('admin.medicines.update', $medicine) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-4">
                  <label for="name" class="col-sm-3 col-form-label">Name</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $medicine->name) }}" required>
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
                    <textarea name="description" class="form-control" id="" cols="30" rows="5">{{ old('description', $medicine->description) }}</textarea>
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
                        <option value="{{ $key }}" @selected($key == old('origin_id', $medicine->origin_id))>{{ $value }}</option>
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
                        <option value="{{ $key }}" @selected($key == old('origin_id', $medicine->type_id))>{{ $value }}</option>
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
                      <input type="text" class="form-control" id="price" name="price" value="{{ old('price', $medicine->price) }}">
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
                    <input type="number" class="form-control" id="quantity" min="1" max="100" name="quantity" value="{{ old('quantity', $medicine->quantity) }}" required>
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
                      <button class="btn btn-info w-md" type="submit">@lang('buttons.submit')</button>
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
  {{-- Moment Js --}}
  <script src="{{ asset('assets/libs/moment/moment.min.js') }}"></script>
  <!-- Dropzone js -->
  <script src="{{ URL::asset('/assets/libs/dropzone/dropzone.min.js') }}"></script>


  <script>
    // init the expiry date and manufacture date
    $('#expire_at').val(moment('{{ $medicine->expire_at }}').format('YYYY-MM-DDTHH:mm'));
    $('#manufacture_at').val(moment('{{ $medicine->manufacture_at }}').format('YYYY-MM-DDTHH:mm'));

    //  Dropzone Config
    let image = "{{ getFile($medicine) }}";
    let fileSize = "{{ getFileSize($medicine) }}";
    let uploadedDocumentMap = {};
    Dropzone.options.myDropzone = {
      url: $('#storeTempFile').text(),
      maxFilesize: 10, // MB
      uploadMultiple: false,
      maxFiles: 1,
      addRemoveLinks: true,
      acceptedFiles: '.jpeg,.jpg,.png',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
      },
      success: function(file, response) {
        $('#upload').append('<input type="hidden" name="file" value="' + response.name + '">');
        uploadedDocumentMap[file.name] = response.name;
      },
      removedfile: function(file) {
        file.previewElement.remove();
        let name = '';
        if (typeof file.file_name !== 'undefined') {
          name = file.file_name;
        } else {
          name = uploadedDocumentMap[file.name];
        }

        if (name) {
          //send ajax request to delete file
          $.ajax({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
            },
            type: 'POST',
            url: $('#deleteTempFile').text(),
            data: {
              fileName: name,
            },
            success: function(data) {},
            error: function(e) {
              console.log(e);
            },
          });
        }

        $('#upload')
          .find('input[name="file"][value="' + name + '"]')
          .remove();
      },
      init: function() {
        let myDropzone = this;

        //load existing file from server
        let mockFile = {
          name: "{{ $medicine->name }}",
          size: fileSize,
        };
        // handle cross origin error
        myDropzone.options.addedfile.call(myDropzone, mockFile);
        myDropzone.options.thumbnail.call(myDropzone, mockFile, image);
        myDropzone.emit('complete', mockFile);
        mockFile.previewElement.querySelector("img").style.width = "100%";


        this.on('maxfilesexceeded', function(file) {
          Swal.fire({
            text: "{{ __('messages.max_files') }}",
            icon: 'error',
          });
        });
      },
    };
  </script>
@endsection

@extends('layouts.master')

@section('title')
  @lang('translation.add_resource', ['resource' => __('attributes.banner')])
@endsection

@section('plugin-css')
  <!-- Dropzone -->
  <link href="{{ URL::asset('/assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
  @component('components.breadcrumb')
    @slot('li_1')
      Banners
    @endslot
    @slot('li_2')
      {{ route('admin.banners.index') }}
    @endslot
    @slot('title')
      @lang('translation.add_resource', ['resource' => __('attributes.banner')])
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
          <form class="needs-validation" novalidate action="{{ route('admin.banners.update', $banner) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
              <div class="col-8">

                <div class="row d-none mb-4">
                  <label for="name" class="col-sm-3 col-form-label">@lang('translation.category.name')</label>
                  <div class="col-sm-9">
                    <input type="hidden" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                    <div class="valid-feedback">
                      @lang('validation.good')
                    </div>
                    <div class="invalid-feedback">
                      @lang('validation.required', ['attribute' => __('translation.category.name')])
                    </div>
                  </div>
                </div>

                <div class="row mb-4">
                  <label for="logo" class="col-sm-3 col-form-label">Banner</label>
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

                <div class="row justify-content-start mt-5">
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

@section('script')
  <!-- Dropzone js -->
  <script src="{{ URL::asset('/assets/libs/dropzone/dropzone.min.js') }}"></script>

  <script>
    // fill the name input
    $("#name").val("season-ticket")
  </script>

  {{-- Dropzone Config --}}
  <script>
    let image = "{{ getFile($banner, 'season-ticket') }}";
    let fileSize = "{{ getFileSize($banner, 'season-ticket') }}";
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
          name: "{{ $banner->name }}",
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

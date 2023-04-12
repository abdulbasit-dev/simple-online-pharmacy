@extends('layouts.app')

@section('title')
  @lang('app.navbar.about')
@endsection

@section('content')
  {{-- Main --}}
  <div class="main my-5">
    <div class="container">
      {{-- contact --}}
      <div class="row align-items-center justify-content-between" id="contact-us">
        <div class="col-12 mb-2" data-aos="fade-up">
          <h2 class="about_contact-title">@lang("app.navbar.contact_us")</h2>
          <p class="lead mt-3">@lang("app.contact_text")</p>
        </div>
        <div class="col-md-6" data-aos="fade-right">
          @if ($errors->any())
            <div class="alert alert-danger">
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif
          <form class="needs-validation" novalidate action="{{ route('contacts.store') }}" method="POST">
            @csrf
            <div class="mb-3">
              <label for="name" class="form-label">@lang("app.name")</label>
              <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Enter Your First Name" required>
              <div class="valid-feedback">
                @lang('validation.good')
              </div>
              <div class="invalid-feedback">
                @lang('validation.required', ['attribute' => __('app.name')])
              </div>
            </div>

            <div class="mb-3">
              <label for="email" class="form-label">@lang("app.email")</label>
              <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Enter Your Email" required>
              <div class="valid-feedback">
                @lang('validation.good')
              </div>
              <div class="invalid-feedback">
                @lang('validation.required', ['attribute' => __('app.email')])
              </div>
            </div>

            <div class="mb-3">
              <label for="subject" class="form-label">@lang("app.subject")</label>
              <input type="text" class="form-control" id="subject" name="subject" value="{{ old('subject') }}" placeholder="Enter Your Subject" required>
              <div class="valid-feedback">
                @lang('validation.good')
              </div>
              <div class="invalid-feedback">
                @lang('validation.required', ['attribute' => __('app.subject')])
              </div>
            </div>

            <div class="mb-3">
              <label for="message" class="form-label">@lang("app.message")</label>
              <textarea class="form-control" name="message" id="message" cols="4" rows="6" placeholder="Enter Your Message" required>{{ old('message') }}</textarea>
              <div class="valid-feedback">
                @lang('validation.good')
              </div>
              <div class="invalid-feedback">
                @lang('validation.required', ['attribute' => __('app.message')])
              </div>
            </div>

            <div>
              <button type="submit" class="btn btn-primary waves-effect waves-light w-lg">
                @lang("buttons.send")
                <i class="bx bx-send font-size-16 ms-2 align-middle" style="padding-bottom: 1.2px"></i>
              </button>
            </div>
          </form>
        </div>
        <div class="d-none d-md-block col-md-6" data-aos="fade-left">
          <img src="https://images.unsplash.com/photo-1516387938699-a93567ec168e?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1171&q=80" alt="about us" class="img-fluid rounded">
        </div>

      </div>
    </div>
  </div>
@endsection

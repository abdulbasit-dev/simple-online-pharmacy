@extends('layouts.app')

@section('title')
  @lang('app.navbar.about')
@endsection

@section('content')
  <header>
    {{-- hero image --}}
    <div class="hero-image about_hero-image">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-8 col-xl-10 mb-3">
            <div class="d-flex">
              <div class="mx-4" data-aos="fade-right" data-aos-duration="1000">
                <a href="#goals" class="btn btn-primary mt-4 ">Our Goal</a>
              </div>
              <div data-aos="fade-left" data-aos-duration="1000">
                <a href="#contact-us" class="btn btn-darkblue mt-4">Contact Us</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>

  {{-- Main --}}
  <div class="main my-5">
    <div class="container">
      {{-- about --}}
      <div class="row align-items-center" id="about">
        <div class="col-md-6">
          {{-- <h2 class="mb-2 border-bottom border-3 pb-2 w-25 about_title">{{ config('app.name') }}</h2> --}}
          <h2 class="about_title mb-4" data-aos="fade-up">{{ config('app.name') }}</h2>
          <p class="lead" data-aos="fade-right">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Tempore ut quis perspiciatis sint corrupti autem est consectetur aspernatur dicta iste voluptates alias veritatis ex quas enim voluptas deserunt, laboriosam voluptatibus illo harum debitis incidunt ab repellendus? Enim dignissimos
            accusamus harum,
            repellendus perferendis eaque nam inventore. Voluptatibus amet alias aperiam quam commodi! Id, dolores accusamus? Temporibus eius molestias expedita iure voluptas explicabo ab voluptatem veniam totam, voluptates, error inventore debitis provident beatae harum obcaecati omnis? Aperiam,ducimus? Consectetur
            earum, at eos magnam pariatur fugit!</p>
        </div>
        <div class="col-md-6" data-aos="fade-left">
          <img src="https://images.unsplash.com/photo-1489944440615-453fc2b6a9a9?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1323&q=80" alt="about us" class="img-fluid rounded">
        </div>
      </div>

      {{-- goals --}}
      <div class="row align-items-center justify-content-between" id="goals">
        <div class="col-12 mb-4">
          <h2 class="about_goal-title text-center" data-aos="fade-up">Our Goal And Mession</h2>
        </div>
        <div class="col-md-3 text-center"  data-aos="zoom-in">
          <h3>Lorem, ipsum dolor.</h3>
          <p class="lead">Lorem ipsum dolor sit amet consectetur adipisicing elit. Commodi, quos amet est provident quidem numquam necessitatibus reprehenderit impedit inventore ad.</p>
        </div>
        <div class="col-md-3 text-center"  data-aos="zoom-in">
          <h3>Lorem, ipsum dolor.</h3>
          <p class="lead">Lorem ipsum dolor sit amet consectetur adipisicing elit. Commodi, quos amet est provident quidem numquam necessitatibus reprehenderit impedit inventore ad.</p>
        </div>
        <div class="col-md-3 text-center"  data-aos="zoom-in">
          <h3>Lorem, ipsum dolor.</h3>
          <p class="lead">Lorem ipsum dolor sit amet consectetur adipisicing elit. Commodi, quos amet est provident quidem numquam necessitatibus reprehenderit impedit inventore ad.</p>
        </div>
      </div>

      {{-- contact --}}
      <div class="row align-items-center justify-content-between" id="contact-us">
        <div class="col-12 mb-2" data-aos="fade-up">
          <h2 class="about_contact-title">Conatct Us</h2>
          <p class="lead mt-3">Lorem ipsum dolor sit amet consectetur adipisicing elit. Amet, consequatur.</p>
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
          <form class="needs-validation" novalidate action="{{ route('storeContact') }}" method="POST">
            @csrf
            <div class="mb-3">
              <label for="name" class="form-label">Name</label>
              <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Enter Your First Name" required>
              <div class="valid-feedback">
                @lang('validation.good')
              </div>
              <div class="invalid-feedback">
                @lang('validation.required', ['attribute' => __('app.name')])
              </div>
            </div>

            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Enter Your Email" required>
              <div class="valid-feedback">
                @lang('validation.good')
              </div>
              <div class="invalid-feedback">
                @lang('validation.required', ['attribute' => __('app.email')])
              </div>
            </div>

            <div class="mb-3">
              <label for="subject" class="form-label">Subject</label>
              <input type="text" class="form-control" id="subject" name="subject" value="{{ old('subject') }}" placeholder="Enter Your Subject" required>
              <div class="valid-feedback">
                @lang('validation.good')
              </div>
              <div class="invalid-feedback">
                @lang('validation.required', ['attribute' => __('app.subject')])
              </div>
            </div>

            <div class="mb-3">
              <label for="message" class="form-label">Message</label>
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
                Send
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

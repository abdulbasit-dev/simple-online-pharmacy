<footer class="text-lg-start text-white">
  {{-- <section class="pt-2">
    <div class="text-md-start container mt-5">
      <div class="row mt-3n">
        <div class="col-md-4 col-lg-4 col-xl-4 mx-auto mb-4 justify-self-center">
          <!-- Content -->
          <h5 class="text-uppercase fw-bold text-primary">{{ config('app.name') }}</h5>
          <hr class="d-inline-block mx-auto mb-2 mt-0" style="width: 60px; background-color: #7c4dff; height: 2px" />
          <p class="footer-about">
            @lang('app.footer.site_description')
          </p>
        </div>

        <div class="col-md-4 col-lg-4 col-xl-4 mx-auto mb-4" style="padding-left:8rem ">
          <!-- Links -->
          <h5 class="text-uppercase fw-bold text-primary">@lang('app.footer.site_map')</h5>
          <hr class="d-inline-block mx-auto mb-2 mt-0" style="width: 60px; background-color: #7c4dff; height: 2px" />

          <a class="site-map-link text-decoration-none text-uppercase text-white" href="{{ route('index') }}">@lang('app.navbar.home')</a>
          <a class="site-map-link text-decoration-none text-uppercase text-white" href="{{ route('seasonTicket') }}">@lang('app.navbar.season_ticket')</a>
          <a class="site-map-link text-decoration-none text-uppercase text-white" href="{{ route('seasonTicket') }}">@lang('app.navbar.match_tickets')</a>
          <a class="site-map-link text-decoration-none text-uppercase text-white" href="https://www.dalkurd.se/index.php/klubben/om-oss" target="_blank">@lang('app.navbar.about')</a>
          <a class="site-map-link text-decoration-none text-uppercase text-white" href="{{ route('contacts.index') }}">@lang('app.navbar.contact')</a>

        </div>


        <div class="col-md-4 col-lg-4 col-xl-4 mb-md-0 mx-auto mb-4">
          <!-- Links -->
          <h5 class="text-uppercase fw-bold text-primary">@lang('app.navbar.contact')</h5>
          <hr class="d-inline-block mx-auto mb-2 mt-0" style="width: 60px; background-color: #7c4dff; height: 2px" />
          <div class="d-flex mb-2">
            <div style="margin-top: -6px">
              <span class="footer-contact">@lang('app.footer.email'): <span dir="ltr"><a class="text-white" href="mailto:support@dalkurd.se">support@dalkurd.se</a></span></span><br>
              <span class="footer-contact">@lang('app.footer.phone'): <span dir="ltr">+964 750 453 3323</span></span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section> --}}

  <div class="footer_copy-right p-3 text-center" dir="ltr">
    {{ date('Y') }} © {{ config('app.name') }}. @lang('app.footer.all_rights_reserved').
  </div>
</footer>

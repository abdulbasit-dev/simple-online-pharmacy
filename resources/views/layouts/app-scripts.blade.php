<!-- JAVASCRIPT -->
<script src="{{ URL::asset('assets/libs/jquery/jquery.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap/bootstrap.min.js') }}"></script>
<!-- Sweet Alerts js -->
<script src="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
{{-- Select2 --}}
<script src="{{ URL::asset('/assets/libs/select2/select2.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/metismenu/metismenu.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/node-waves/node-waves.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/parsleyjs/parsleyjs.min.js') }}"></script>
<script src="{{ URL::asset('/assets/js/form-validation.init.js') }}"></script>
{{-- AOS --}}
<script src="{{ URL::asset('/assets/frontend/libs/aos/aos.js') }}"></script>

@yield('script')

<!-- App js -->
<script src="{{ URL::asset('assets/js/app.min.js') }}"></script>

{{-- sweetalert2 message --}}
@if (Session::has('message'))
  <script>
    Swal.fire({
      timer: "{{ Session::get('icon') === 'error' ? (Session::get('timer') ? Session::get('timer') : 20000) : (Session::get('timer') ? Session::get('timer') : 3000) }}",
      customClass: "{{ Session::get('icon') === 'error' ? 'swal-error' : null }}",
      icon: "{{ Session::get('icon') }}",
      title: "{{ Session::get('title') }}",
      text: "{{ Session::get('message') }}",
    })
  </script>
@endif

<script>
  $(document).ready(function() {
    // INIT SELECT2
    $(".select2").select2();

    // Select2 while open fouces on search input
    $(document).on('select2:open', () => {
      document.querySelector('.select2-search__field').focus();
    });

    // AOS CONFIG
    AOS.init({
      once: true,
      //   time
      duration: 1000,
    });

  });
</script>

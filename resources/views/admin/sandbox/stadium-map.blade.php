@extends('layouts.master')

@section('title')
  @lang('sidebar.dashboard')
@endsection

@section('css')
  <style>
    .seat {
      border: 1px solid #dee2e6 !important;
    }

    .seat:hover {
      background: rgba(98, 157, 52, .3) !important;
      cursor: pointer !important;
    }

    .hover {
      background: rgba(98, 157, 52, .3) !important;
    }
  </style>
@endsection

@section('content')
  @component('components.breadcrumb')
    @slot('li_1')
      @lang('sidebar.dashboard')
    @endslot
    @slot('li_2')
      {{ route('admin.index') }}
    @endslot
    @slot('title')
      @lang('sidebar.dashboard')
    @endslot
  @endcomponent

  <div class="row">
    <div class="col-md-6">
      <div class="card">
        <svg viewBox="0 0 1920 1080" fill="none" xmlns="http://www.w3.org/2000/svg" class="stadium-map">
          <rect width="1920" height="1080" fill="white" />
          <g class="category-1" data-catagory-name="normal" data-map-catagory="1" opacity="1">
            <!-- 1 -->
            <g class="seat-1">
              <path d="M960 287V187L1178 188.176V287H960Z" fill="#E3B702" stroke="#333333" stroke-width="0.02" />
              <path
                d="M1179 187.5V361.5L1245.5 362.5L1276 371.5L1301 385.5L1415.5 211.5L1388.5 201.5L1342 191C1315.45 187.815 1300.56 187.034 1274 187.5H1179Z"
                fill="#6C9FD9" stroke="#333333" stroke-width="0.4" />
              <path
                d="M1523 292L1494.5 263.5L1456 234.5L1415 213L1301 386C1306.67 390.833 1319.6 405.9 1334 427.5C1348.4 449.1 1347.67 456.833 1345.5 458L1565 356.5L1554 334.5L1523 292Z"
                fill="#E3B702" stroke="#333333" stroke-width="0.4" />
            </g>

            <!-- 2 -->
            <g class="seat-4">
              <path d="M756 288V188L959 189.176V288H756Z" fill="#6C9FD9" stroke="#333333" stroke-width="0.02" />
              <path d="M609 187.5L574 191.5L544.5 198.5L517.5 207.5L630.5 380.5L664 366L691 361.5H755V187.5H650.5H609Z"
                fill="#E3B702" stroke="#333333" stroke-width="0.4" />
              <path
                d="M482.5 223.5L517.5 207.5L629 380L601 402.5L586.5 423L571.5 458.5L344.5 385C345.833 381 350.278 368.977 357.878 351.777C367.378 330.277 370.878 329.277 392.378 299.277C395.085 295.5 409.333 277.333 407 281.5L438.5 252.5L458.5 238L482.5 223.5Z"
                fill="#6C9FD9" stroke="#333333" stroke-width="0.4" />
            </g>

            <!-- 3 -->
            <g class="seat-7">
              <path d="M756 789V889L959 887.824V789H756Z" fill="#6C9FD9" stroke="#333333" stroke-width="0.02" />

              <path d="M608.5 889L573.5 885L544 878L535 874L645.5 706.5L663.5 710.5L690.5 715H754.5V889H650H608.5Z"
                fill="#E3B702" stroke="#333333" stroke-width="0.4" />
              <path
                d="M500 861L535 874L646 705.5L615 687.5L596.5 667L577.5 636.5L370.5 746C373 749.5 370 749 380.878 763.223C383.855 767.115 381 760.5 402.5 790.5C405.207 794.277 423.333 812.167 421 808L453 834L475 848.5L500 861Z"
                fill="#6C9FD9" stroke="#333333" stroke-width="0.4" />
            </g>

            <!-- 4 -->
            <g class="seat-10">
              <path d="M960 790V890L1178 888.824V790H960Z" fill="#E3B702" stroke="#333333" stroke-width="0.02" />
              <path
                d="M1180 888.876V714.985L1241.3 713.986L1269.5 706.5L1286 699.5L1399.5 869.5L1374 878.5L1330.5 886.5C1305 888.5 1292.05 889.342 1267.57 888.876H1180Z"
                fill="#6C9FD9" stroke="#333333" stroke-width="0.4" />
              <path
                d="M1513 795L1483.5 822.5L1442.5 850.5L1400.5 870L1286 698.5C1291.67 693.667 1313.1 683.6 1327.5 662C1341.9 640.4 1343.67 636.167 1341.5 635L1563.5 719L1546.5 753L1513 795Z"
                fill="#E3B702" stroke="#333333" stroke-width="0.4" />
            </g>

          </g>
          <g class="category-2" data-catagory-name="maqsora" data-map-catagory="2" opacity="1">
            <g class="seat-8">
              <path
                d="M335.5 413L344 384.5L572.5 459.5L569.5 485.5V557V599.5L572.5 617.5L578.5 635.5L370.5 746L359.5 726.5L344.5 691.5L335.5 663L329.5 634.5L327.5 609L326 587V510V477C326 476.2 327.667 457.667 328.5 448.5L332 428.5L335.5 413Z"
                fill="#E3B702" stroke="black" />
            </g>
            <g class="seat-11">
              <path
                d="M1580.61 391.923L1565.5 358.5L1348.42 458.341L1350.5 487.553L1350.5 556L1348.42 598.5L1348.42 614L1344 636L1565.5 719.5L1572.5 702L1585.5 668.5L1591 639L1594.5 611L1595.51 586.5L1594.5 564.5L1595.51 487.553C1594.6 476.948 1595.58 455.539 1595.51 454.744C1595.44 453.948 1592.21 435.664 1590.6 426.622L1585.41 407.035L1580.61 391.923Z"
                fill="#6C9FD9" stroke="black" />
            </g>
          </g>
          <g class="category-3" data-catagory-name="vip" data-map-catagory="3" opacity="1">
            <g class="seat-9">
              <path d="M756 716V792L959 791.106V716H756Z" fill="#6C9FD9" stroke="#333333" stroke-width="0.02" />
              <path d="M959 715V791L1179 790.106V715H959Z" fill="#E3B702" stroke="#333333" stroke-width="0.02" />
            </g>

            <g class="seat-12">
              <path d="M756 361V285L959 285.894V361H756Z" fill="#6C9FD9" stroke="#333333" stroke-width="0.02" />
              <path d="M959 362V286L1179 286.894V362H959Z" fill="#E3B702" stroke="#333333" stroke-width="0.02" />
            </g>
          </g>
          <g class="boundries">
            <rect x="326.2" y="187.2" width="1268.6" height="701.6" rx="299.8" stroke="#333333"
              stroke-width="0.4" />
            <rect x="410.2" y="233.2" width="1100.6" height="608.6" rx="259.8" stroke="#333333"
              stroke-width="0.4" />
            <rect x="502.2" y="285.2" width="915.6" height="505.6" rx="220.8" stroke="#333333"
              stroke-width="0.4" />
            <rect x="569.5" y="361.5" width="781" height="353" rx="124.5" fill="white"
              stroke="black" />
          </g>
          <g class="field lines">
            <path
              d="M569 484L572 459L576 444.5L579 438L585 425.5L595 410L612 391.5L625.5 381.5L640.5 373L656.5 366.5L679.5 361H1225L1244 362.5L1279 374L1301 386L1323 406.5L1332 419.5L1339 432.5L1346 457L1349.5 465V482V571.5V598.5L1346 623.5L1335 648L1320 669.5L1304.5 685L1285 698.5L1266 706.5L1241 713.5H713.5H676.5L658 710L638 702L618 688.5L603.5 676L586.5 653.5L577.5 635L572 617.5L569 600V484Z"
              fill="url(#paint0_linear_201_3)" stroke="black" />
            <path d="M643.5 490.5H701.5V547V603.5H643.5V490.5Z" stroke="white" />
            <path d="M643.5 518.5H670.5V576.5H643.5V518.5Z" stroke="white" />
            <path d="M1285.5 490.5H1227.5V547V603.5H1285.5V490.5Z" stroke="white" />
            <path d="M1285.5 518.5H1258.5V576.5H1285.5V518.5Z" stroke="white" />
            <path d="M643.5 441.5H1285.5V649.5H643.5V441.5Z" stroke="white" />
            <path d="M961.5 442L961.5 648" stroke="white" />
            <path
              d="M991.5 547C991.5 562.74 978.74 575.5 963 575.5C947.26 575.5 934.5 562.74 934.5 547C934.5 531.26 947.26 518.5 963 518.5C978.74 518.5 991.5 531.26 991.5 547Z"
              stroke="white" />

            <path
              d="M705.2 186.999L706.195 361.999M711.005 717.5L712 892.5M674.005 714L622.5 890.5M603 676L451.5 831M412.5 277.5L593 413M326.5 499L567 508M326.5 623.5L567 578.5M1243 361.999L1310.5 186.999M1333 413L1506 275M1356.5 514L1594.5 466M1349.5 570.5L1594.5 584M1324.5 676L1506 801.5M1243 714L1285 890.5"
              stroke="black" stroke-width="0.4" />
          </g>
          <g class="lines">
            <line y1="-0.2" x2="175.003" y2="-0.2"
              transform="matrix(0.00568749 0.999984 -0.999983 0.00574102 754 714)" stroke="black" stroke-width="0.4" />
            <line y1="-0.2" x2="175.003" y2="-0.2"
              transform="matrix(0.00568749 0.999984 -0.999983 0.00574102 1179.01 715)" stroke="black"
              stroke-width="0.4" />
            <line y1="-0.2" x2="175.003" y2="-0.2"
              transform="matrix(0.00568749 0.999984 -0.999983 0.00574102 755 184)" stroke="black" stroke-width="0.4" />
            <line y1="-0.2" x2="175.003" y2="-0.2"
              transform="matrix(0.00568749 0.999984 -0.999983 0.00574102 754 714)" stroke="black" stroke-width="0.4" />
            <line y1="-0.2" x2="175.003" y2="-0.2"
              transform="matrix(0.00568749 0.999984 -0.999983 0.00574102 1179.01 715)" stroke="black"
              stroke-width="0.4" />
            <line y1="-0.2" x2="175.003" y2="-0.2"
              transform="matrix(0.00568749 0.999984 -0.999983 0.00574102 755 184)" stroke="black" stroke-width="0.4" />
            <line x1="614.191" y1="187.939" x2="669.992" y2="363.642" stroke="black" stroke-width="0.4" />
            <line x1="517.168" y1="207.891" x2="629.168" y2="378.891" stroke="black" stroke-width="0.4" />
            <line x1="1415.17" y1="213.111" x2="1301.17" y2="385.111" stroke="black" stroke-width="0.4" />
            <line y1="-0.2" x2="206.349" y2="-0.2"
              transform="matrix(0.552462 0.833538 0.833538 -0.552461 1287 700)" stroke="black" stroke-width="0.4" />
            <line y1="-0.2" x2="200.808" y2="-0.2"
              transform="matrix(0.547786 -0.836619 -0.836618 -0.547786 535 875)" stroke="black" stroke-width="0.4" />
            <line y1="-0.2" x2="175.003" y2="-0.2"
              transform="matrix(0.00568749 0.999984 -0.999983 0.00574102 959.001 187)" stroke="black"
              stroke-width="0.4" />
            <line y1="-0.2" x2="175.003" y2="-0.2"
              transform="matrix(0.00568749 0.999984 -0.999983 0.00574102 959.001 722)" stroke="black"
              stroke-width="0.4" />
            <line y1="-0.2" x2="175.003" y2="-0.2"
              transform="matrix(0.00568749 0.999984 -0.999983 0.00574102 1178 187)" stroke="black"
              stroke-width="0.4" />
            <line y1="-0.2" x2="175.003" y2="-0.2"
              transform="matrix(0.00568749 0.999984 -0.999983 0.00574102 1122 187)" stroke="black"
              stroke-width="0.4" />
            <line y1="-0.2" x2="175.003" y2="-0.2"
              transform="matrix(0.00568749 0.999984 -0.999983 0.00574102 1069 187)" stroke="black"
              stroke-width="0.4" />
            <line y1="-0.2" x2="175.003" y2="-0.2"
              transform="matrix(0.00568749 0.999984 -0.999983 0.00574102 1015 187)" stroke="black"
              stroke-width="0.4" />
            <line y1="-0.2" x2="175.003" y2="-0.2"
              transform="matrix(0.00568749 0.999984 -0.999983 0.00574102 910 188)" stroke="black" stroke-width="0.4" />
            <line y1="-0.2" x2="175.003" y2="-0.2"
              transform="matrix(0.00568749 0.999984 -0.999983 0.00574102 857 188)" stroke="black" stroke-width="0.4" />
            <line y1="-0.2" x2="175.003" y2="-0.2"
              transform="matrix(0.00568749 0.999984 -0.999983 0.00574102 803 188)" stroke="black" stroke-width="0.4" />
            <line y1="-0.2" x2="175.003" y2="-0.2"
              transform="matrix(0.00568749 0.999984 -0.999983 0.00574102 1122 714)" stroke="black"
              stroke-width="0.4" />
            <line y1="-0.2" x2="175.003" y2="-0.2"
              transform="matrix(0.00568749 0.999984 -0.999983 0.00574102 1069 714)" stroke="black"
              stroke-width="0.4" />
            <line y1="-0.2" x2="175.003" y2="-0.2"
              transform="matrix(0.00568749 0.999984 -0.999983 0.00574102 1015 714)" stroke="black"
              stroke-width="0.4" />
            <line y1="-0.2" x2="175.003" y2="-0.2"
              transform="matrix(0.00568749 0.999984 -0.999983 0.00574102 910 715)" stroke="black" stroke-width="0.4" />
            <line y1="-0.2" x2="175.003" y2="-0.2"
              transform="matrix(0.00568749 0.999984 -0.999983 0.00574102 857 715)" stroke="black" stroke-width="0.4" />
            <line y1="-0.2" x2="175.003" y2="-0.2"
              transform="matrix(0.00568749 0.999984 -0.999983 0.00574102 803 715)" stroke="black" stroke-width="0.4" />
            <line x1="570.97" y1="458.095" x2="342.606" y2="384.271" stroke="#333333" stroke-width="0.2" />
            <line x1="579.296" y1="635.088" x2="367.254" y2="747.509" stroke="#333333" stroke-width="0.2" />
            <line x1="1344.97" y1="636.377" x2="1569.35" y2="721.527" stroke="#333333" stroke-width="0.2" />
            <line x1="1345.5" y1="459.189" x2="1562.9" y2="357.511" stroke="#333333" stroke-width="0.2" />
          </g>
          <defs>
            <linearGradient id="paint0_linear_201_3" x1="1349.5" y1="537.5" x2="573.5" y2="537.5"
              gradientUnits="userSpaceOnUse">
              <stop stop-color="#49DC26" />
              <stop offset="1" stop-color="#57AD43" />
            </linearGradient>
          </defs>
        </svg>
        {{-- {!! file_get_contents('assets/images/stadium.svg') !!} --}}
      </div>
    </div>

    <div class="col-md-6">
      <div class="card">
        @foreach ($seats as $seat)
          <div class="rounded-pill seat my-2 mx-2 border py-2 px-3" data-seat="{{ $seat['seat_id'] }}"
            data-category="{{ $seat['category_id'] }}">
            <h5 class="mb-0" data-seat="{{ $seat['seat_id'] }}" data-category="{{ $seat['category_id'] }}"><span
                class="text-primary me-1">Category:</span><span
                class="text-muted">{{ $seat['category_name'] }}-{{ $seat['category_id'] }}</span> |
              <span class="text-primary me-1">Seat:</span><span class="text-muted">
                {{ $seat['seat_name'] }}-{{ $seat['seat_id'] }}</span>
            </h5>
          </div>
        @endforeach
      </div>
    </div>
  </div>
@endsection
@section('script')
  <script>
    // while hover on seat class get data-seat and data-category
    $('.seat').hover(function() {
      let seat = $(this).data('seat');
      let category = $(this).data('category');

      let categoryClassName = "category-" + category;
      let seatClassName = "seat-" + seat;
      // find g tag with class name category-1 and modify opacity to 0.4
      //  check if seatClassName is exist in categoryClassName
      if ($('.' + categoryClassName).find('.' + seatClassName).length) {
        $('.' + categoryClassName).find('.' + seatClassName).attr('opacity', '0.4');
      } else {
        $('.' + categoryClassName).attr('opacity', '0.4');
      }

    });

    // when mouse leave seat class return opacity to 1
    $('.seat').mouseleave(function() {
      let seat = $(this).data('seat');
      let category = $(this).data('category');

      let categoryClassName = "category-" + category;
      let seatClassName = "seat-" + seat;
      // find g tag with class name category-1 and modify opacity to 0.4
      //  check if seatClassName is exist in categoryClassName
      if ($('.' + categoryClassName).find('.' + seatClassName).length) {
        $('.' + categoryClassName).find('.' + seatClassName).attr('opacity', '1');
      } else {
        $('.' + categoryClassName).attr('opacity', '1');
      }
    });

    // while click on svg g tag reduce opacity to 0.4
    $('svg g g').hover(function() {
      $(this).attr('opacity', '0.4');
      //get this class list
      let seat = $(this).attr('class').split("-")[1];
      //find div with  data-seat equal to seat
      let div = $('.seat[data-seat="' + seat + '"]');
      //   add class hover to div
      div.addClass('hover');
    });

    // while click on svg g tag reduce opacity to 1
    $('svg g g').mouseleave(function() {
      $(this).attr('opacity', '1');
      //get this class list
      let seat = $(this).attr('class').split("-")[1];
      //find div with  data-seat equal to seat
      let div = $('.seat[data-seat="' + seat + '"]');
      //   add class hover to div
      div.removeClass('hover');
    });
  </script>
@endsection

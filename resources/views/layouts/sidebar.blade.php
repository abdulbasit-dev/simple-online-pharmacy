<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

  <div data-simplebar class="h-100">

    <!--- Sidemenu -->
    <div id="sidebar-menu">
      <!-- Left Menu Start -->
      <ul class="metismenu list-unstyled" id="side-menu">

        <li>
          <a href="{{ route('admin.index') }}" class="waves-effect">
            <i class="bx bx-home-circle"></i>
            <span key="t-contact">@lang('sidebar.dashboard')</span>
          </a>
        </li>

        {{-- origins --}}
          <li>
            <a href="{{ route('admin.origins.index') }}" class="waves-effect">
              <i class='bx bx-map-pin'></i>
              <span key="t-contact">Origins</span>
            </a>
          </li>

        {{-- types --}}
          <li>
            <a href="{{ route('admin.types.index') }}" class="waves-effect">
              <i class='bx bx-extension'></i>
              <span key="t-contact">Medicine Types</span>
            </a>
          </li>

        {{-- medicines --}}
          <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect {{ request()->routeIs('admin.medicines.*') ? 'mm-active' : '' }}">
              <i class='bx bx-plus-medical'></i>
              <span key="t-ecommerce">Medicines</span>
            </a>
            <ul class="{{ request()->routeIs('admin.medicines.*') ? 'sub-menu mm-collapse mm-show' : 'sub-menu' }} aria-expanded="false">
              <li>
                <a href="{{ route('admin.medicines.index') }}">All Medicines
                  <span class="badge rounded-pill bg-info float-end status-badge d-none" id="totalMedicine"></span>
                </a>
              </li>
              <li>
                <a href="{{ route('admin.medicines.index', ['status' => 'expired']) }}" class="{{ request()->get('status') == 'expired' ? 'active' : '' }}" key="t-products">Expired Medicines
                  <span class="badge rounded-pill bg-info float-end status-badge d-none" id="expiredMedicine"></span>
                </a>
              </li>

                <li><a href="{{ route('admin.medicines.create') }}" key="t-products">Add Medicines</a></li>

            </ul>
          </li>

        {{-- orders --}}
          <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect {{ request()->routeIs('admin.orders.*') ? 'mm-active' : '' }}">
              <i class="bx bx-receipt"></i>
              <span key="t-ecommerce">Orders</span>
            </a>
            <ul class="{{ request()->routeIs('admin.orders.*') ? 'sub-menu mm-collapse mm-show' : 'sub-menu' }}" aria-expanded="false">
              <li>
                <a href="{{ route('admin.orders.index') }}">All Orders
                  <span class="badge rounded-pill bg-info float-end status-badge d-none" id="totalOrders"></span>
                </a>
              </li>

              <li>
                <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="{{ request()->get('status') == 'pending' ? 'active' : '' }}">Pending Orders
                  <span class="badge rounded-pill bg-info float-end status-badge d-none" id="pendingOrders"></span>
                </a>
              </li>

              <li>
                <a href="{{ route('admin.orders.index', ['status' => 'accepted']) }}" class="{{ request()->get('status') == 'accepted' ? 'active' : '' }}">Accepted Orders
                  <span class="badge rounded-pill bg-info float-end status-badge d-none" id="acceptedOrders"></span>
                </a>
              </li>

              <li>
                <a href="{{ route('admin.orders.index', ['status' => 'canceled']) }}" class="{{ request()->get('status') == 'canceled' ? 'active' : '' }}">Canceled Orders
                  <span class="badge rounded-pill bg-info float-end status-badge d-none" id="canceledOrders"></span>
                </a>
              </li>

            </ul>
          </li>

        {{-- users & role mangement --}}
          <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect {{ request()->routeIs('admin.users.*') ? 'mm-active' : '' }}">
              <i class="bx bxs-user-detail"></i>
              <span key="t-ecommerce">@lang('sidebar.user_management')</span>
            </a>
            <ul class="sub-menu {{ request()->routeIs('admin.users.*') ? 'mm-show' : '' }}" aria-expanded="false">
              <li><a href="{{ route('admin.users.index') }}" key="t-products">@lang('translation.resource_list', ['resource' => __('attributes.user')])</a></li>

                <li><a href="{{ route('admin.users.create') }}" key="t-products">@lang('translation.add_resource', ['resource' => __('attributes.user')])</a></li>

            </ul>
          </li>

      </ul>
    </div>
    <!-- Sidebar -->
  </div>
</div>
<!-- Left Sidebar End -->
@push('script')
  <script>
    $(document).ready(function() {
      // remove d-none class from the badge
      $('.status-badge').removeClass('d-none');
        getMedicineStatusCount()
        getOrderStatusCount()
    });

    const getMedicineStatusCount = () => {
      $.ajax({
        url: "{{ route('admin.getMedicineStatusCount') }}",
        type: "GET",
        dataType: "json",
        success: function(data) {
          $("#totalMedicine").html(data.totalMedicine);
          $("#expiredMedicine").html(data.expiredMedicine);
        }
      });
    }

    const getOrderStatusCount = () => {
      $.ajax({
        url: "{{ route('admin.orderStatusCount') }}",
        type: "GET",
        dataType: "json",
        success: function(data) {
          $("#totalOrders").html(data.totalOrders);
          $("#pendingOrders").html(data.pendingOrders);
          $("#acceptedOrders").html(data.acceptedOrders);
          $("#canceledOrders").html(data.canceledOrders);
        }
      });
    }
  </script>
@endpush

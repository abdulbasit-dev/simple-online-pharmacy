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
        @can('origin_view')
          <li>
            <a href="{{ route('admin.origins.index') }}" class="waves-effect">
              <i class='bx bx-extension'></i>
              <span key="t-contact">Origins</span>
            </a>
          </li>
        @endcan

        {{-- types --}}
        @can('type_view')
          <li>
            <a href="{{ route('admin.types.index') }}" class="waves-effect">
              <i class='bx bx-extension'></i>
              <span key="t-contact">Medicine Types</span>
            </a>
          </li>
        @endcan

        {{-- medicines --}}
        @can('medicine_view')
          <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect {{ request()->routeIs('admin.medicines.*') ? 'mm-active' : '' }}">
              <i class='bx bx-football'></i>
              <span key="t-ecommerce">Medicines</span>
            </a>
            <ul class="{{ request()->routeIs('admin.medicines.*') ? 'sub-menu mm-collapse mm-show' : 'sub-menu' }} aria-expanded="false">
              <li>
                <a href="{{ route('admin.medicines.index') }}">All Medicines
                  <span class="badge rounded-pill bg-info float-end status-badge d-none" id="totalMatchs"></span>
                </a>
              </li>
              <li>
                <a href="{{ route('admin.medicines.index', ['status' => 'today']) }}" class="{{ request()->get('status') == 'today' ? 'active' : '' }}" key="t-products">Expired Medicines
                  <span class="badge rounded-pill bg-info float-end status-badge d-none" id="todayMatchs"></span>
                </a>
              </li>
              @can('match_add')
                <li><a href="{{ route('admin.medicines.create') }}" key="t-products">Add Medicines</a></li>
              @endcan
            </ul>
          </li>
        @endcan

        {{-- orders --}}
        @can('order_view')
          <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect {{ request()->routeIs('admin.season-orders.*') ? 'mm-active' : '' }}">
              <i class="bx bx-receipt"></i>
              <span key="t-ecommerce">Orders</span>
            </a>
            <ul class="{{ request()->routeIs('admin.season-orders.*') ? 'sub-menu mm-collapse mm-show' : 'sub-menu' }}" aria-expanded="false">
              <li>
                <a href="{{ route('admin.season-orders.index') }}">All Orders
                  <span class="badge rounded-pill bg-info float-end status-badge d-none" id="totalOrders"></span>
                </a>
              </li>

              <li>
                <a href="{{ route('admin.season-orders.index', ['status' => 'pending']) }}" class="{{ request()->get('status') == 'pending' ? 'active' : '' }}">Pending Orders
                  <span class="badge rounded-pill bg-info float-end status-badge d-none" id="expiredOrders"></span>
                </a>
              </li>

              <li>
                <a href="{{ route('admin.season-orders.index', ['status' => 'accepted']) }}" class="{{ request()->get('status') == 'accepted' ? 'active' : '' }}">Accepted Orders
                  <span class="badge rounded-pill bg-info float-end status-badge d-none" id="expiredOrders"></span>
                </a>
              </li>

              <li>
                <a href="{{ route('admin.season-orders.index', ['status' => 'canceled']) }}" class="{{ request()->get('status') == 'canceled' ? 'active' : '' }}">Canceled Orders
                  <span class="badge rounded-pill bg-info float-end status-badge d-none" id="expiredOrders"></span>
                </a>
              </li>

            </ul>
          </li>
        @endcan


        @if (config('app.sandbox'))
          {{-- SandBox --}}
          <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect {{ request()->routeIs('admin.sandbox.*') ? 'mm-active' : '' }}">
              <i class='bx bx-package'></i>
              <span>SandBox</span>
            </a>
            <ul class="sub-menu {{ request()->routeIs('admin.teams.*') ? 'mm-show' : '' }}" aria-expanded="false">
              <li><a href="{{ route('admin.sandbox.qrcodeViewer') }}" key="t-products">QrCode Viewer</a></li>
              <li><a href="{{ route('admin.sandbox.stadiumMap') }}" key="t-products">Stadium Map</a></li>
              <li><a href="{{ route('admin.sandbox.mail.renderSeasonTicket') }}" key="t-products">Render Season Ticket Mail</a></li>
              <li><a href="{{ route('admin.sandbox.mail.sendSeasonTicket') }}" key="t-products">Send Season Ticket Mail</a></li>
              <li><a href="{{ route('admin.sandbox.invoice') }}" key="t-products">Ticket Invoice</a></li>
            </ul>
          </li>
        @endif

        {{-- contatcs --}}
        @can('contact_view')
          <li>
            <a href="{{ route('admin.contacts.index') }}" class="waves-effect">
              <i class='bx bx-mail-send'></i>
              <span key="t-contact">@lang('sidebar.contacts')</span>
            </a>
          </li>
        @endcan

        {{-- banners --}}
        @can('banner_view')
          <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect {{ request()->routeIs('admin.banners.*') ? 'mm-active' : '' }}">
              <i class='bx bx-package'></i>
              <span>Banners</span>
            </a>
            <ul class="sub-menu {{ request()->routeIs('admin.banners.*') ? 'mm-show' : '' }}" aria-expanded="false">
              <li><a href="{{ route('admin.banners.index') }}" key="t-products">Season Ticket Banner</a></li>
              <li><a href="{{ route('admin.banners.create') }}" key="t-products">Add New Banner</a></li>
            </ul>
          </li>
        @endcan

        {{-- users & role mangement --}}
        @can('user_view')
          <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect {{ request()->routeIs('admin.users.*') ? 'mm-active' : '' }}">
              <i class="bx bxs-user-detail"></i>
              <span key="t-ecommerce">@lang('sidebar.user_management')</span>
            </a>
            <ul class="sub-menu {{ request()->routeIs('admin.users.*') ? 'mm-show' : '' }}" aria-expanded="false">
              <li><a href="{{ route('admin.users.index') }}" key="t-products">@lang('translation.resource_list', ['resource' => __('attributes.user')])</a></li>
              @can('user_add')
                <li><a href="{{ route('admin.users.create') }}" key="t-products">@lang('translation.add_resource', ['resource' => __('attributes.user')])</a></li>
              @endcan
            </ul>
          </li>
        @endcan

        @can('role_view')
          <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect {{ request()->routeIs('admin.roles.*') ? 'mm-active' : '' }}">
              <i class="bx bx-shield-quarter"></i>
              <span key="t-ecommerce">@lang('sidebar.role_management')</span>
            </a>
            <ul class="sub-menu {{ request()->routeIs('admin.roles.*') ? 'mm-show' : '' }}" aria-expanded="false">
              <li><a href="{{ route('admin.roles.index') }}" key="t-products">@lang('translation.resource_list', ['resource' => __('attributes.role')])</a></li>
              @can('role_add')
                <li><a href="{{ route('admin.roles.create') }}" key="t-products">@lang('translation.add_resource', ['resource' => __('attributes.role')])</a></li>
              @endcan
            </ul>
          </li>
        @endcan

        {{-- Reports --}}
        {{-- @can('report_view')
          <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect">
              <i class="bx bx-receipt"></i>
              <span key="t-invoices">@lang('sidebar.report.report')</span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
              @can('gate_report_view')
                <li><a href="{{ route('admin.reports.gate') }}" key="t-invoice-list">@lang('sidebar.report.gate')</a></li>
              @endcan
            </ul>
          </li>
        @endcan --}}

        {{-- settings --}}
        {{-- @can('settings_view')
          <li>
            <a href="{{ route('admin.settings.index') }}" class="waves-effect">
              <i class='bx bx-cog'></i>
              <span key="t-contact">@lang('sidebar.settings')</span>
            </a>
          </li>
        @endcan --}}

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
      //   getMatchStatusCount()
      //   getTicketStatusCount()
      //   getOrderStatusCount()
    });

    const getMatchStatusCount = () => {
      $.ajax({
        url: "{{ route('admin.matchStatusCount') }}",
        type: "GET",
        dataType: "json",
        success: function(data) {
          $("#totalMatchs").html(data.totalMatches);
          $("#todayMatchs").html(data.todayMatches);
          $("#upcomingMatchs").html(data.upcomingMatches);
          $("#finishedMatchs").html(data.finishedMatches);
        }
      });
    }

    const getTicketStatusCount = () => {
      $.ajax({
        url: "{{ route('admin.ticketStatusCount') }}",
        type: "GET",
        dataType: "json",
        success: function(data) {
          $("#totalTickets").html(data.totalTickets);
          $("#currentMatcheTickets").html(data.currentMatcheTickets);
          $("#expiredTickets").html(data.expiredTickets);
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
          $("#currentMatcheOrders").html(data.currentMatcheOrders);
          $("#expiredOrders").html(data.expiredOrders);
        }
      });
    }
  </script>
@endpush

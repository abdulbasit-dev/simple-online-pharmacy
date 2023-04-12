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

        <li class="menu-title" key="t-components">Match</li>

        {{-- teams --}}
        @can('team_view')
          <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect {{ request()->routeIs('admin.teams.*') ? 'mm-active' : '' }}">
              <i class='bx bx-group'></i>
              <span key="t-ecommerce">@lang('sidebar.teams')</span>
            </a>
            <ul class="sub-menu {{ request()->routeIs('admin.teams.*') ? 'mm-show' : '' }}" aria-expanded="false">
              <li><a href="{{ route('admin.teams.index') }}" key="t-products">@lang('translation.resource_list', ['resource' => __('attributes.team')])</a></li>
              @can('team_add')
                <li><a href="{{ route('admin.teams.create') }}" key="t-products">@lang('translation.add_resource', ['resource' => __('attributes.team')])</a></li>
              @endcan
            </ul>
          </li>
        @endcan

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

        <li class="menu-title" key="t-components">Location</li>

        {{-- privileges --}}
        @can('privilege_view')
          <li>
            <a href="{{ route('admin.categories.index') }}" class="waves-effect">
              <i class='bx bx-layer'></i>
              <span key="t-contact">@lang('sidebar.categories')</span>
            </a>
          </li>
        @endcan

        {{-- ageGroups --}}
        @can('age_group_view')
          <li>
            <a href="{{ route('admin.ageGroups.index') }}" class="waves-effect">
              <i class='bx bx-hive'></i>
              <span key="t-contact">Age Group</span>
            </a>
          </li>
        @endcan

        {{-- sections --}}
        @can('section_view')
          <li>
            <a href="{{ route('admin.sections.index') }}" class="waves-effect">
              <i class='bx bx-map'></i>
              <span key="t-contact">Sections</span>
            </a>
          </li>
        @endcan

        {{-- gates --}}
        {{-- @can('gate_view')
          <li>
            <a href="{{ route('admin.gates.index') }}" class="waves-effect">
              <i class='bx bx-map-pin'></i>
              <span key="t-contact">@lang('sidebar.gates')</span>
            </a>
          </li>
        @endcan --}}

        <li class="menu-title" key="t-components">Tickets</li>

        {{-- season tickets --}}
        @can('season_ticket_view')
          <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect {{ request()->routeIs('admin.seasonTickets.*') ? 'mm-active' : '' }}">
              <i class="dripicons-wallet"></i>
              <span key="t-ecommerce">@lang('sidebar.season_tickets')</span>
            </a>
            <ul class="{{ request()->routeIs('admin.seasonTickets.*') ? 'sub-menu mm-collapse mm-show' : 'sub-menu' }}" aria-expanded="false">
              <li>
                <a href="{{ route('admin.seasonTickets.index') }}">All Season Ticktes
                  <span class="badge rounded-pill bg-info float-end status-badge d-none" id="totalTickets"></span>
                </a>
              </li>

              @can('match_add')
                <li><a href="{{ route('admin.seasonTickets.create') }}">@lang('translation.add_resource', ['resource' => __('attributes.ticket')])</a></li>
              @endcan
            </ul>
          </li>
        @endcan

        {{-- match tickets --}}
        @can('match_ticket_view')
          <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect {{ request()->routeIs('admin.matchTickets.*') ? 'mm-active' : '' }}">
              <i class="dripicons-wallet"></i>
              <span key="t-ecommerce">Match Tickets</span>
            </a>
            <ul class="{{ request()->routeIs('admin.matchTickets.*') ? 'sub-menu mm-collapse mm-show' : 'sub-menu' }}" aria-expanded="false">
              <li>
                <a href="{{ route('admin.matchTickets.index') }}">All Match Ticktes
                  <span class="badge rounded-pill bg-info float-end status-badge d-none" id="totalTickets"></span>
                </a>
              </li>

              @can('match_add')
                <li><a href="{{ route('admin.matchTickets.create') }}">@lang('translation.add_resource', ['resource' => __('attributes.ticket')])</a></li>
              @endcan
            </ul>
          </li>
        @endcan

        {{-- Match tickets --}}
        {{-- @can('season_ticket_view')
          <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect {{ request()->routeIs('admin.seasonTickets.*') ? 'mm-active' : '' }}">
              <i class="dripicons-wallet"></i>
              <span key="t-ecommerce">Match Ticket</span>
            </a>
            <ul class="{{ request()->routeIs('admin.seasonTickets.*') ? 'sub-menu mm-collapse mm-show' : 'sub-menu' }}" aria-expanded="false">
              <li>
                <a href="{{ route('admin.seasonTickets.index') }}">All Match Ticktes
                  <span class="badge rounded-pill bg-info float-end status-badge d-none" id="totalTickets"></span>
                </a>
              </li>

              @can('match_add')
                <li><a href="{{ route('admin.seasonTickets.create') }}">@lang('translation.add_resource', ['resource' => __('attributes.ticket')])</a></li>
              @endcan
            </ul>
          </li>
        @endcan --}}

        <li class="menu-title" key="t-components">Orders</li>

        {{-- season orders --}}
        @can('season_order_view')
          <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect {{ request()->routeIs('admin.season-orders.*') ? 'mm-active' : '' }}">
              <i class="bx bx-receipt"></i>
              <span key="t-ecommerce">Season Orders</span>
            </a>
            <ul class="{{ request()->routeIs('admin.season-orders.*') ? 'sub-menu mm-collapse mm-show' : 'sub-menu' }}" aria-expanded="false">
              <li>
                <a href="{{ route('admin.season-orders.index') }}">All Orders
                  <span class="badge rounded-pill bg-info float-end status-badge d-none" id="totalOrders"></span>
                </a>
              </li>

              <li>
                <a href="{{ route('admin.season-orders.index', ['status' => 'expired-order']) }}" class="{{ request()->get('status') == 'expired-order' ? 'active' : '' }}">Expired Orders
                  <span class="badge rounded-pill bg-info float-end status-badge d-none" id="expiredOrders"></span>
                </a>
              </li>

            </ul>
          </li>
        @endcan

        {{-- match orders --}}
        @can('match_order_view')
          <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect {{ request()->routeIs('admin.match-orders.*') ? 'mm-active' : '' }}">
              <i class="bx bx-receipt"></i>
              <span key="t-ecommerce">Match Orders</span>
            </a>
            <ul class="{{ request()->routeIs('admin.match-orders.*') ? 'sub-menu mm-collapse mm-show' : 'sub-menu' }}" aria-expanded="false">
              <li>
                <a href="{{ route('admin.match-orders.index') }}">All Orders
                  <span class="badge rounded-pill bg-info float-end status-badge d-none" id="totalOrders"></span>
                </a>
              </li>

              <li>
                <a href="{{ route('admin.match-orders.index', ['status' => 'expired-order']) }}" class="{{ request()->get('status') == 'expired-order' ? 'active' : '' }}">Expired Orders
                  <span class="badge rounded-pill bg-info float-end status-badge d-none" id="expiredOrders"></span>
                </a>
              </li>

            </ul>
          </li>
        @endcan

        {{-- local orders --}}
        @can('local_order_view')
          <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect {{ request()->routeIs('admin.local-orders.*') ? 'mm-active' : '' }}">
              <i class="bx bx-receipt"></i>
              <span key="t-ecommerce">Local Order</span>
            </a>
            <ul class="{{ request()->routeIs('admin.local-orders.*') ? 'sub-menu mm-collapse mm-show' : 'sub-menu' }}" aria-expanded="false">
              <li>
                <a href="{{ route('admin.local-orders.index', ['ticket_type' => 'season_ticket']) }}">Season Ticket Orders
                  <span class="badge rounded-pill bg-info float-end status-badge d-none" id="totalLocalOrders"></span>
                </a>
              </li>

              <li>
                <a href="{{ route('admin.local-orders.index', ['ticket_type' => 'match_ticket']) }}">Match Ticket Orders
                  <span class="badge rounded-pill bg-info float-end status-badge d-none" id="totalLocalOrders"></span>
                </a>
              </li>


              <li>
                <a href="{{ route('admin.local-orders.index', ['status' => 'expired-order']) }}" class="{{ request()->get('status') == 'expired-order' ? 'active' : '' }}">Expired Orders
                  <span class="badge rounded-pill bg-info float-end status-badge d-none" id="expiredLocalOrders"></span>
                </a>
              </li>

              @can('sell_local_ticket')
                <li><a href="{{ route('admin.local-orders.create') }}">Sell New Ticket</a></li>
              @endcan
            </ul>
          </li>
        @endcan


        <li class="menu-title" key="t-components">Settings</li>

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

        {{-- payment methods --}} @can('payment_method_view')
          <li>
            <a href="{{ route('admin.paymentMethods.index') }}" class="waves-effect">
              <i class='bx bx-credit-card'></i>
              <span key="t-contact">@lang('sidebar.payment_methods')</span>
            </a>
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

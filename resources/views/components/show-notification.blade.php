     <div class="dropdown d-inline-block">
       <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
         @if ($hasNotification)
           <i class="bx bx-bell bx-tada" id="bellShake"></i>
           <span class="badge bg-danger rounded-pill" id="notificationCounter">{{ count($notifications) }}</span>
         @else
           <i class="bx bx-bell"></i>
         @endif
       </button>
       <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-notifications-dropdown">
         <div class="p-3">
           <div class="row align-items-center">
             <div class="col">
               <h6 class="m-0" key="t-notifications"> @lang('translation.notification') </h6>
             </div>

             {{-- <div class="col-auto">
               <a href="#!" class="small" key="t-view-all"> @lang('translation.View_All')</a>
             </div> --}}
           </div>
         </div>
         <div data-simplebar style="max-height: 230px;">
           @forelse ($notifications as $notification)
             <a href="" class="text-reset notification-item">
               <div class="d-flex">
                 <div class="avatar-xs me-3">
                   <span class="avatar-title bg-primary rounded-circle font-size-16">
                     <i class="{{ $notification->data['icon'] }}"></i>
                   </span>
                 </div>

                 <div class="flex-grow-1">
                   <h6 class="mt-0 mb-1" key="t-your-order">{{ $notification->data['message'] }}</h6>
                   <div class="font-size-12 text-muted">
                     <p class="mb-1" key="t-grammer">{{ $notification->data['desc'] }}</p>
                     <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span key="t-min-ago">{{ $notification->created_at->diffForHumans() }}</span></p>
                   </div>
                 </div>
               </div>
             </a>
           @empty
             <div class="d-flex justify-content-center">
               <h6 class="text-muted mt-0 mb-3" key="t-your-order">@lang('translation.no_notification')</h6>
             </div>
           @endforelse
           <div class="d-flex justify-content-center d-none" id="noNotification">
             <h6 class="text-muted mt-0 mb-3" key="t-your-order">@lang('translation.no_notification')</h6>
           </div>
         </div>
         @if ($hasNotification)
           <div class="border-top d-grid p-2" id="markAllBtn">
             <a class="btn btn-sm btn-link font-size-14 text-center" href="javascript:void(0)" id="mark-all">
               <i class="mdi mdi-arrow-right-circle me-1"></i> <span key="t-view-more">@lang('translation.mark_as_read')</span>
             </a>
           </div>
         @endif
       </div>
     </div>
     {{-- ORIGINAL NOTIFICATION FROM TEMPLATE --}}
     {{-- <div class="dropdown d-inline-block">
       <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-notifications-dropdown"
         data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
         <i class="bx bx-bell bx-tada"></i>
         <span class="badge bg-danger rounded-pill">3</span>
       </button>
       <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
         aria-labelledby="page-header-notifications-dropdown">
         <div class="p-3">
           <div class="row align-items-center">
             <div class="col">
               <h6 class="m-0" key="t-notifications"> @lang('translation.Notifications') </h6>
             </div>
             <div class="col-auto">
               <a href="#!" class="small" key="t-view-all"> @lang('translation.View_All')</a>
             </div>
           </div>
         </div>
         <div data-simplebar style="max-height: 230px;">
           <a href="" class="text-reset notification-item">
             <div class="d-flex">
               <div class="avatar-xs me-3">
                 <span class="avatar-title bg-primary rounded-circle font-size-16">
                   <i class="bx bx-cart"></i>
                 </span>
               </div>
               <div class="flex-grow-1">
                 <h6 class="mt-0 mb-1" key="t-your-order">@lang('translation.Your_order_is_placed')</h6>
                 <div class="font-size-12 text-muted">
                   <p class="mb-1" key="t-grammer">@lang('translation.If_several_languages_coalesce_the_grammar')</p>
                   <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span
                       key="t-min-ago">@lang('translation.3_min_ago')</span></p>
                 </div>
               </div>
             </div>
           </a>
           <a href="" class="text-reset notification-item">
             <div class="d-flex">
               <img src="{{ URL::asset('/assets/images/users/avatar-3.jpg') }}" class="me-3 rounded-circle avatar-xs"
                 alt="user-pic">
               <div class="flex-grow-1">
                 <h6 class="mt-0 mb-1">@lang('translation.James_Lemire')</h6>
                 <div class="font-size-12 text-muted">
                   <p class="mb-1" key="t-simplified">@lang('translation.It_will_seem_like_simplified_English')</p>
                   <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span
                       key="t-hours-ago">@lang('translation.1_hours_ago')</span></p>
                 </div>
               </div>
             </div>
           </a>
           <a href="" class="text-reset notification-item">
             <div class="d-flex">
               <div class="avatar-xs me-3">
                 <span class="avatar-title bg-success rounded-circle font-size-16">
                   <i class="bx bx-badge-check"></i>
                 </span>
               </div>
               <div class="flex-grow-1">
                 <h6 class="mt-0 mb-1" key="t-shipped">@lang('translation.Your_item_is_shipped')</h6>
                 <div class="font-size-12 text-muted">
                   <p class="mb-1" key="t-grammer">@lang('translation.If_several_languages_coalesce_the_grammar')</p>
                   <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span
                       key="t-min-ago">@lang('translation.3_min_ago')</span></p>
                 </div>
               </div>
             </div>
           </a>

           <a href="" class="text-reset notification-item">
             <div class="d-flex">
               <img src="{{ URL::asset('/assets/images/users/avatar-4.jpg') }}" class="me-3 rounded-circle avatar-xs"
                 alt="user-pic">
               <div class="flex-grow-1">
                 <h6 class="mt-0 mb-1">@lang('translation.Salena_Layfield')</h6>
                 <div class="font-size-12 text-muted">
                   <p class="mb-1" key="t-occidental">@lang('translation.As_a_skeptical_Cambridge_friend_of_mine_occidental')</p>
                   <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span
                       key="t-hours-ago">@lang('translation.1_hours_ago')</span></p>
                 </div>
               </div>
             </div>
           </a>
         </div>
         <div class="border-top d-grid p-2">
           <a class="btn btn-sm btn-link font-size-14 text-center" href="javascript:void(0)">
             <i class="mdi mdi-arrow-right-circle me-1"></i> <span key="t-view-more">@lang('translation.View_More')</span>
           </a>
         </div>
       </div>
     </div> --}}

     @push('script')
       <script>
         $(document).ready(function() {
           const sendMarkRequest = (id = null) => {
             //send ajax request to mark notification as read
             return $.ajax({
               url: "{{ route('admin.markNotification') }}",
               type: "POST",
               data: {
                 "_token": "{{ csrf_token() }}",
                 "id": id
               },
             })
           }

           $("#mark-all").on("click", function(event) {
             sendMarkRequest();
             //dom manipulation
             $("#noNotification").addClass('d-flex justify-content-center').removeClass('d-none');
             $(".notification-item").remove();
             $("#notificationCounter").remove();
             $("#markAllBtn").remove();
             $("#bellShake").removeClass('bx-tada');
           });
         });
       </script>
     @endpush

     {{-- match cards --}}
     <div class="row gy-2">
       @forelse ($matches as $match)
         <div class="col-lg-4">
           <div class="card ">
             <div class="card-header border-bottom text-uppercase bg-darkblue p-1">
               <h3 class="match-date ps-2 m-0 py-1">
                 <span class="day">{{ Carbon\Carbon::parse($match->match_time)->translatedFormat('d') }}</span>
                 <span class="info">
                   <span class="weekday">{{ Carbon\Carbon::parse($match->match_time)->translatedFormat('l') }}</span>
                   <span class="month">{{ Carbon\Carbon::parse($match->match_time)->translatedFormat('F') }}</span>
                 </span>
               </h3>
             </div>
             <div class="card-body card-shadow">
               {{-- match detail --}}
               <div class="my-5">
                 {{-- teams logos --}}
                 <div class="d-flex justify-content-around align-items-center">
                   <div>
                     <img src="{{ getFile($match->home) }}" alt="team logo" class="avatar-sm">
                   </div>
                   <div>
                     <span class="text-gradient home_time">
                       {{ Carbon\Carbon::parse($match->match_time)->translatedFormat('H:m') }}
                     </span>
                   </div>
                   <div>
                     <img src="{{ getFile($match->away) }}" alt="team logo" class="avatar-sm">
                   </div>
                 </div>

                 {{-- team names --}}
                 <div class="team-names">
                   {{ $match->home->name }}
                   <span class="vs">vs</span>
                   {{ $match->away->name }}
                 </div>
                 <p class="text-gradient stadium">
                   {{ $match->club->stadium->name ?? "No Stadium"}}
                 </p>

               </div>

               <div class="d-flex justify-content-start">
                   <a href="{{ route('matchDetail',$match) }}" class="btn btn-primary w-xs">Get Ticket</a>
               </div>
             </div>
           </div>
         </div>
       @empty
         <div class="col-md-12">
           <div class="alert alert-danger text-center" role="alert">
             No Matches Found
           </div>
         </div>
       @endforelse
     </div>

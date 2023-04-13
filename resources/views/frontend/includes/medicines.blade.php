     {{-- match cards --}}
     <div class="row gy-2">
       @forelse ($medicines as $medicine)
         <div class="col-lg-4">
           <div class="card">
             <div class="card-body">
               <h4 class="card-title">{{ $medicine->name }}</h4>
               <h6 class="card-subtitle font-14 text-muted">{{ $medicine->type->name }}</h6>
             </div>
             <img class="img-fluid" src="https://th.bing.com/th/id/OIP.pIaPlko7bsYW2fmCZPElHgHaHa?w=207&h=207&c=7&r=0&o=5&pid=1.7" alt="Card image cap">
             <div class="card-body">
               <p class="card-text">{{ $medicine->description }}</p>
               <a href="javascript: void(0);" class="card-link">View Datail</a>
             </div>
           </div>
         </div>
       @empty
         <div class="col-md-12">
           <div class="alert alert-danger text-center" role="alert">
             No Medicine Found
           </div>
         </div>
       @endforelse
     </div>

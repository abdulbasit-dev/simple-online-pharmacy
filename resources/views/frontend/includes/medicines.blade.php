     {{-- match cards --}}
     <div class="row gy-2">
       @forelse ($medicines as $medicine)
         <div class="col-lg-4">

           <div class="card card-shadow">
             <img class="card-img-top img-fluid medicine-image" src="{{ getFile($medicine) }}" alt="{{ $medicine->name }}">
             <div class="card-body">
               <h4 class="card-title">{{ $medicine->name }}</h4>
               <h6 class="card-subtitle font-14 text-muted">{{ $medicine->type->name }}</h6>
               <a href="{{ route('medicineDetail', $medicine) }}" class="btn btn-primary mt-4 waves-effect waves-light">View Detail</a>
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

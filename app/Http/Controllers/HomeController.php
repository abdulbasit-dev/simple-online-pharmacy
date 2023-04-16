<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\Customer;
use App\Models\Medicine;
use App\Models\User;
use App\Notifications\NewOrderNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $medicinesQuery = Medicine::query()
            ->notExpire()
            ->with(
                [
                    "type:id,name",
                    "origin:id,name",
                ]
            );

        if ($request->ajax()) {
            $medicinesQuery->when($request->typeId, function ($query, $typeId) {
                return $query->where("type_id", $typeId);
            });
            $medicinesQuery->when($request->originId, function ($query, $originId) {
                return $query->where("origin_id", $originId);
            });
            $medicinesQuery->when($request->search, function ($query, $search) {
                return $query->whereHas("home", function ($query) use ($search) {
                    $query->where("name", "like", "%$search%");
                })->orWhereHas("away", function ($query) use ($search) {
                    $query->where("name", "like", "%$search%");
                });
            });

            $medicines = $medicinesQuery->get();
            return view('frontend.includes.medicines', compact('medicines'));
        }

        $medicines = $medicinesQuery->get();

        // $types = Type::pluck('name', 'id');
        $types = Medicine::select('type_id', 'name')->distinct()->pluck('name', 'type_id');
        // $origins = Origin::pluck('name', 'id');
        $origins = Medicine::select('origin_id', 'name')->distinct()->pluck('name', 'origin_id');
        return view('frontend.index', compact('types', 'origins', 'medicines'));
    }

    public function medicineDetail(Medicine $medicine)
    {
        $medicine->load("type:id,name", "origin:id,name");
        return view('frontend.pages.medicine-detail', compact('medicine'));
    }

    public function createOrder(OrderRequest $request, Medicine $medicine)
    {
        // TODO:
        // - wrap in try catch ✅
        // - check if medicine quantity is enough ✅
        // - use DB transaction ✅
        // - fire an event to send notification to admin and check medicines quantity

        // begin transaction
        DB::beginTransaction();

        try {
            $validated = $request->validated();

            // check if medicine quantity is enough
            if ($medicine->quantity < $validated['quantity']) {
                return redirect()->back()->with([
                    "message" =>  "Medicine quantity is not enough, remaining quantity is $medicine->quantity",
                    "icon" => "warning",
                    "timer" => 3000,
                ]);
            }

            $customer = Customer::firstOrCreate(
                ['phone' => $validated['phone']],
                ['name' => $validated['name'], 'address' => $validated['address']]
            );

            $customer->orders()->create([
                'medicine_id' => $medicine->id,
                'quantity' => $validated['quantity'],
                'total' => $medicine->price * $validated['quantity'],
                'status' => 0,
            ]);

            // reduce medicine quantity
            $medicine->update([
                "quantity" => $medicine->quantity - $validated['quantity'],
            ]);
            // send notification to admins
            $notifiableUsers = User::role(['admin'])->get();
            Notification::send($notifiableUsers, new NewOrderNotification());

            // commit transaction
            DB::commit();

            return redirect()->route('home')->with([
                "message" => "Thank you for your Purchase",
                "icon" => "success",
            ]);
        } catch (\Throwable $th) {
            // rollback transaction
            DB::rollback();

            // throw $th;
            return redirect()->back()->with([
                "message" =>  "Something went wrong",
                "icon" => "error",
                "timer" => 3000,
            ]);
        }
    }

    /*Language Translation*/
    public function lang($locale)
    {
        if ($locale) {
            App::setLocale($locale);
            Session::put('lang', $locale);
            Session::save();
            return redirect()->back()->with('locale', $locale);
        } else {
            return redirect()->back();
        }
    }
}

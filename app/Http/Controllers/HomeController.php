<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Medicine;
use App\Models\Origin;
use App\Models\Team;
use App\Models\Type;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
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
        return $medicine;
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

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
            $medicinesQuery->when($request->teamId, function ($query, $teamId) {
                return $query->where("home_id", $teamId)
                    ->orWhere("away_id", $teamId);
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

        $types = Type::pluck('name', 'id');
        $origins = Origin::pluck('name', 'id');
        return view('frontend.index', compact('types', 'origins', 'medicines'));
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

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $matchesQuery = Game::query()
            ->notExpire()
            ->whereHas("tickets")
            ->with(
                [
                    "home:id,name",
                    "away:id,name",
                    "league:id,name",
                ]
            )
            ->orderBy("match_time");

        if ($request->ajax()) {
            $matchesQuery->when($request->teamId, function ($query, $teamId) {
                return $query->where("home_id", $teamId)
                    ->orWhere("away_id", $teamId);
            });
            $matchesQuery->when($request->search, function ($query, $search) {
                return $query->whereHas("home", function ($query) use ($search) {
                    $query->where("name", "like", "%$search%");
                })->orWhereHas("away", function ($query) use ($search) {
                    $query->where("name", "like", "%$search%");
                });
            });

            if (strtotime($request->startDate) && strtotime($request->endDate)) {
                $matchesQuery->whereBetween("match_time", [
                    Carbon::parse($request->startDate)->startOfDay(),
                    Carbon::parse($request->endDate)->endOfDay(),
                ]);
            }

            $matches = $matchesQuery->get();
            return view('frontend.includes.matches', compact('matches'));
        }

        $matches = $matchesQuery->get();
        $teams = Team::all();

        return view('frontend.index', compact('matches', 'teams',));
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

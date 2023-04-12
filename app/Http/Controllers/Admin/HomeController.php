<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Game;
use App\Models\Order;
use App\Models\Team;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function index()
    {
        $totalGames = Game::notExpire()->count();
        $totalTeams = Team::count();
        $totalCategories = Category::count();
        $availableTickets = Ticket::notExpire()->count();

        $matches = Game::all()->map(function ($match) {
            return [
                "id" => $match->id,
                "name" => $match->home->name . " " . __('translation.vs') . " " . $match->away->name . " | " . formatDateWithTimezone($match->match_time),
                "home_logo" => getFile($match->home),
                "away_logo" => getFile($match->away),
                "home_name" => $match->home->name,
                "away_name" => $match->away->name,
            ];
        });

        // get last 5 games
        $lastMatches = Game::query()->withCount('tickets')->orderBy("match_time", "desc")->take(5)->get();
        // check if there is any match
        if ($lastMatches->count() > 0) {
            $latestMatchId = $lastMatches->first()->id;
        } else {
            $latestMatchId = null;
        }

        $data = [
            'totalMatch' => $totalGames,
            "totalTeams" => $totalTeams,
            "totalCategories" => $totalCategories,
            "availableTickets" => $availableTickets,
            "lastMatches" => $lastMatches,
            "matches" => $matches,
            "latestMatchId" => $latestMatchId,
        ];

        return view('admin.index', compact('data'));
    }

    public function matchData(Request $request)
    {
        $tickets = Ticket::where('match_id', $request->matchId)->get();
        $orders = Order::where('match_id', $request->matchId)->get();

        $soldTickets = $tickets->sum("sold_qty");

        $usedTicket = $orders->where("is_used", 1)->count();
        // $unUsedTicket = $orders->where("is_used", 0)->count();


        $soldInArena = Order::where('match_id', $request->matchId)->whereHas("localSale")->count();
        $soldInWebsite = Order::where('match_id', $request->matchId)->whereDoesntHave('localSale')->count();

        $data = [
            "totalTickets" => $tickets->sum("quantity"),
            "soldTickets" => $soldTickets,
            "usedTicket" => $usedTicket,
            "soldInArena" => $soldInArena,
            "soldInWebsite" => $soldInWebsite,
        ];

        return response()->json($data);
    }

    public function markNotification(Request $request)
    {
        auth()->user()
            ->unreadNotifications
            ->when($request->id, function ($query) use ($request) {
                return $query->where("id", $request->id);
            })
            ->markAsRead();

        return response()->json(['success' => true]);
    }

    public function storeTempFile(Request $request)
    {

        $path = storage_path('tmp/uploads');

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $file = $request->file('file');

        $name = uniqid() . '_' . trim($file->getClientOriginalName());

        $file->move($path, $name);

        return response()->json([
            'name'          => $name,
            'original_name' => $file->getClientOriginalName(),
        ]);
    }

    public function deleteTempFile(Request $request)
    {
        $path = storage_path('tmp/uploads');
        if (file_exists($path . '/' . $request->fileName)) {
            unlink($path . '/' . $request->fileName);
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

    public function updateProfile(Request $request, $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:1024'],
        ]);

        $user = User::find($id);
        $user->name = $request->get('name');
        $user->email = $request->get('email');

        if ($request->file('avatar')) {
            $avatar = $request->file('avatar');
            $avatarName = time() . '.' . $avatar->getClientOriginalExtension();
            $avatarPath = public_path('/images/');
            $avatar->move($avatarPath, $avatarName);
            $user->avatar = '/images/' . $avatarName;
        }

        $user->update();
        if ($user) {
            Session::flash('message', 'User Details Updated successfully!');
            Session::flash('alert-class', 'alert-success');
            return response()->json([
                'isSuccess' => true,
                'Message' => "User Details Updated successfully!"
            ], 200); // Status code here
        } else {
            Session::flash('message', 'Something went wrong!');
            Session::flash('alert-class', 'alert-danger');
            return response()->json([
                'isSuccess' => true,
                'Message' => "Something went wrong!"
            ], 200); // Status code here
        }
    }

    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        if (!(Hash::check($request->get('current_password'), Auth::user()->password))) {
            return response()->json([
                'isSuccess' => false,
                'Message' => "Your Current password does not matches with the password you provided. Please try again."
            ], 200); // Status code
        } else {
            $user = User::find($id);
            $user->password = Hash::make($request->get('password'));
            $user->update();
            if ($user) {
                Session::flash('message', 'Password updated successfully!');
                Session::flash('alert-class', 'alert-success');
                return response()->json([
                    'isSuccess' => true,
                    'Message' => "Password updated successfully!"
                ], 200); // Status code here
            } else {
                Session::flash('message', 'Something went wrong!');
                Session::flash('alert-class', 'alert-danger');
                return response()->json([
                    'isSuccess' => true,
                    'Message' => "Something went wrong!"
                ], 200); // Status code here
            }
        }
    }
}

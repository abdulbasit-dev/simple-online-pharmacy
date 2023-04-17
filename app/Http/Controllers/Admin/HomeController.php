<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Game;
use App\Models\Medicine;
use App\Models\Order;
use App\Models\Origin;
use App\Models\Team;
use App\Models\Ticket;
use App\Models\Type;
use App\Models\User;
use App\Notifications\MedicineStockAlertNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Log;
use Notification;

class HomeController extends Controller
{
    public function index()
    {

        // check stock
        // check if any medicine quantity is less than or equal to stock alert
        // if so, send MinStockAlertNotification to admin
        $minStockMedicines = Medicine::where('quantity', '<=', config("app.stock_alert"))->get();

        if ($minStockMedicines->count() > 0) {
            Log::info("MinStockAlertNotification sent to admin");
            $notifiableUsers = User::all();

            foreach ($minStockMedicines as $medicine) {
                $desc = "Medicine {$medicine->name} is running out of stock.";
                //    check if there a notification with the same desc and read_at null
                //    if so, don't send notification, because it's already sent and not read yet
                $existNotification = DB::table('notifications')
                    ->where('data', 'like', "%{$desc}%")
                    ->where('read_at', '=', null)
                    ->get();

                if ($existNotification->count() > 0) {
                    continue;
                }

                Notification::send($notifiableUsers, new MedicineStockAlertNotification($desc));
            }
        }


        $totalOrders = Order::count();
        $totalPendingOrders = Order::where('status', OrderStatus::PENDING)->count();
        $totalAcceptedOrders = Order::where('status', OrderStatus::ACCEPTED)->count();
        $totalCanceledOrders = Order::where('status', OrderStatus::CANCELED)->count();

        $totalMedicines = Medicine::count();
        $totalMinStockMedicines = Medicine::where('quantity', '<=', config("app.stock_alert"))->count();
        $totalMedicineTypes = Type::count();
        $totalOrigins = Origin::count();

        // get last 10 orders
        $latestOrders = Order::query()
            ->with("customer:id,name", "medicine:id,name")
            ->latest()
            ->take(10)
            ->get();

        $data = [
            "totalOrders"         => $totalOrders,
            "totalPendingOrders"  => $totalPendingOrders,
            "totalAcceptedOrders" => $totalAcceptedOrders,
            "totalCanceledOrders" => $totalCanceledOrders,
            "totalMedicines"      => $totalMedicines,
            "totalMinStockMedicines" => $totalMinStockMedicines,
            "totalMedicineTypes"  => $totalMedicineTypes,
            "totalOrigins"        => $totalOrigins,
            "latestOrders"        => $latestOrders,
        ];

        return view('admin.index', compact('data'));
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

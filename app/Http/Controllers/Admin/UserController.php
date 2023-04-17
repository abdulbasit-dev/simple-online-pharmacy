<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Exports\GeneralExport;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Schema;
use DataTables;
use Illuminate\Support\Arr;
use Log;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::query()
                ->with('roles');
            return Datatables::of($data)->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $td = '<td>';
                    $td .= '<div class="d-flex">';
                        $td .= '<a href="' . route('admin.users.show', $row->id) . '" type="button" class="btn btn-sm btn-primary waves-effect waves-light me-1">' . __('buttons.view') . '</a>';
                        $td .= '<a href="' . route('admin.users.edit', $row->id) . '" type="button" class="btn btn-sm btn-info waves-effect waves-light me-1">' . __('buttons.edit') . '</a>';
                        $td .= '<a href="javascript:void(0)" data-id="' . $row->id . '" data-url="' . route('admin.users.destroy', $row->id) . '"  class="btn btn-sm btn-danger delete-btn">' . __('buttons.delete') . '</a>';
                    $td .= "</div>";
                    $td .= "</td>";
                    return $td;
                })
                ->editColumn("created_at", fn ($row) => formatDate($row->created_at))
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.users.index');
    }

    public function create()
    {
        $roles = Role::all()->pluck('id', 'name')->toArray();
        if (!auth()->user()->hasRole('super-admin')) {
            $roles = Arr::except($roles, 'super-admin');
        }

        $roles = array_flip($roles);
        return view('admin.users.create', compact('roles'));
    }

    public function store(UserRequest $request)
    {
        try {
            $validated = $request->safe()->except(['role']);
            $validated['password'] = bcrypt($request->password);

            // User::create($validated)->assignRole($request->role);
            User::create($validated)->assignRole("admin");

            return redirect()->route('admin.users.index')->with([
                "message" =>  __('messages.success'),
                "icon" => "success",
            ]);
        } catch (\Throwable $th) {
            return redirect()->back()->with([
                "message" =>  $th->getMessage(),
                "icon" => "error",
            ]);
        }
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact("user"));
    }

    public function edit(User $user)
    {
        $roles = Role::all()->pluck('id', 'name')->toArray();
        if (!auth()->user()->hasRole('super-admin')) {
            $roles = Arr::except($roles, 'super-admin');
        }

        $roles = array_flip($roles);
        return view('admin.users.edit', compact("user", 'roles'));
    }

    public function update(UserRequest $request, User $user)
    {
        try {

            $validated = $request->safe()->except(['role']);
            $validated['password'] = bcrypt($request->password);

            $user->update($validated);
            // $user->syncRoles($request->role);

            return redirect()->route('admin.users.index')->with([
                "message" =>  __('messages.update'),
                "icon" => "success",
            ]);
        } catch (\Throwable $th) {
            return redirect()->back()->with([
                "message" =>  $th->getMessage(),
                "icon" => "error",
            ]);
        }
    }

    public function destroy(Request $request, User $user)
    {
        if ($request->ajax()) {
            Log::info("ajax request");
            $user->delete();
            return 1;
        }
        Log::info("not ajax request");
        $user->delete();
        return redirect()->route('admin.users.index')->with([
            "message" =>  __('messages.delete'),
            "icon" => "success",
        ]);
    }
}

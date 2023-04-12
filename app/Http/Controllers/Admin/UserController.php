<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Exports\GeneralExport;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;
use DataTables;
use Illuminate\Support\Arr;
use Log;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        //check permission
        $this->authorize("user_view");

        if ($request->ajax()) {
            $data = User::query()
                ->with('roles');
            return Datatables::of($data)->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $td = '<td>';
                    $td .= '<div class="d-flex">';
                    // check user permission
                    if (auth()->user()->can("user_view"))
                        $td .= '<a href="' . route('admin.users.show', $row->id) . '" type="button" class="btn btn-sm btn-primary waves-effect waves-light me-1">' . __('buttons.view') . '</a>';
                    if (auth()->user()->can("user_edit"))
                        $td .= '<a href="' . route('admin.users.edit', $row->id) . '" type="button" class="btn btn-sm btn-info waves-effect waves-light me-1">' . __('buttons.edit') . '</a>';
                    if (auth()->user()->can("user_delete"))
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
        //check permission
        $this->authorize("user_add");

        $roles = Role::all()->pluck('id', 'name')->toArray();
        if (!auth()->user()->hasRole('super-admin')) {
            $roles = Arr::except($roles, 'super-admin');
        }

        $roles = array_flip($roles);
        return view('admin.users.create', compact('roles'));
    }

    public function store(UserRequest $request)
    {
        //check permission
        $this->authorize("user_add");

        try {
            $validated = $request->safe()->except(['role']);
            $validated['password'] = bcrypt($request->password);

            User::create($validated)->assignRole($request->role);

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
        //check permission
        $this->authorize("user_view");

        return view('admin.users.show', compact("user"));
    }

    public function edit(User $user)
    {
        //check permission
        $this->authorize("user_edit");

        $roles = Role::all()->pluck('id', 'name')->toArray();
        if (!auth()->user()->hasRole('super-admin')) {
            $roles = Arr::except($roles, 'super-admin');
        }

        $roles = array_flip($roles);
        return view('admin.users.edit', compact("user", 'roles'));
    }

    public function update(UserRequest $request, User $user)
    {
        //check permission
        $this->authorize("user_edit");

        try {

            $validated = $request->safe()->except(['role']);
            $validated['password'] = bcrypt($request->password);

            $user->update($validated);
            $user->syncRoles($request->role);

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
        //check permission
        $this->authorize("user_delete");

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

    public function export()
    {
        //check permission
        $this->authorize("user_export");

        // get the heading of your file from the table or you can created your own heading
        $table = "users";
        $headers = Schema::getColumnListing($table);

        // query to get the data from the table
        $query = User::all();

        // create file name  
        $fileName = "user_export_" .  date('Y-m-d_h:i_a') . ".xlsx";

        return Excel::download(new GeneralExport($query, $headers), $fileName);
    }
}

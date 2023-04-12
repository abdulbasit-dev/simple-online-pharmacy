<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RoleRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DataTables;
use Log;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //check permission
        $this->authorize("role_view");

        if ($request->ajax()) {
            $data = Role::query();
            return Datatables::of($data)->addIndexColumn()
                ->setRowClass(fn ($row) => "align-middle")
                ->addColumn('action', function ($row) {
                    $td = '<td>';
                    $td .= '<div class="d-flex">';
                    // check user permission
                    if (auth()->user()->can("user_edit"))
                        $td .= '<a href="' . route('admin.roles.edit', $row->id) . '" type="button" class="btn btn-sm btn-info waves-effect waves-light me-1">' . __('buttons.edit') . '</a>';
                    if (auth()->user()->can("user_delete"))
                        $td .= '<a href="javascript:void(0)" data-id="' . $row->id . '" data-url="' . route('admin.roles.destroy', $row->id) . '"  class="btn btn-sm btn-danger delete-btn">' . __('buttons.delete') . '</a>';
                    $td .= "</div>";
                    $td .= "</td>";
                    return $td;
                })
                ->addColumn('permissions', function ($row) {
                    $td = '<td>';
                    if (strtolower($row->name) === 'admin') {
                        $td .= '<span class="badge badge-pill badge-soft-success font-size-13 p-2 m-1">' . __('translation.role.admin_has_all_permissions') . '</span>';
                    } else {
                        foreach ($row->permissions as  $permission) {
                            $td .= '<span class="badge badge-pill badge-soft-info font-size-13 p-2 m-1">' . ucwords(str_replace("_", " ", $permission->name)) . '</span>';
                        }
                    }
                    $td .= "</td>";
                    return $td;
                })
                ->editColumn("created_at", fn ($row) => formatDate($row->created_at))
                ->rawColumns(['action', 'permissions'])
                ->make(true);
        }

        return view('admin.roles.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //check permission
        $this->authorize("role_add");

        $permissions = Permission::all()->map(function ($item, $key) {
            $item->name = snakeToTitle($item->name);
            return $item;
        })->pluck('name', 'id');
        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request)
    {
        //check permission
        $this->authorize("role_add");

        try {
            $validated = $request->safe()->except(['permission']);
            $role = Role::create($validated);
            $permissions = $request->input('permission') ? $request->input('permission') : [];
            $role->givePermissionTo($permissions);

            return redirect()->route('admin.roles.index')->with([
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        //check permission
        $this->authorize("role_edit");

        $permissions = Permission::get()->pluck('name', 'name');
        return view('admin.roles.edit', compact("role", "permissions"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(RoleRequest $request, Role $role)
    {
        //check permission
        $this->authorize("role_edit");

        try {
            $validated = $request->safe()->except(['permission']);
            $role->update($validated);
            $permissions = $request->input('permission') ? $request->input('permission') : [];
            $role->syncPermissions($permissions);

            return redirect()->route('admin.roles.index')->with([
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        //check permission
        $this->authorize("role_delete");

        $role->delete();
        return redirect()->route('admin.roles.index');
    }
}

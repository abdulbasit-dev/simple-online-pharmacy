<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Type;
use App\Http\Requests\TypeRequest;
use Illuminate\Http\Request;
use DataTables;

class TypeController extends Controller
{
    public function index(Request $request)
    {
        //check permission
        $this->authorize("type_view");

        if ($request->ajax()) {
            $data = Type::query();
            return Datatables::of($data)->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $td = '<td>';
                    $td .= '<div class="d-flex">';
                    if (auth()->user()->can("type_edit"))
                        $td .= '<a href="' . route('admin.types.edit', $row->id) . '" type="button" class="btn btn-sm btn-info waves-effect waves-light me-1">' . __('buttons.edit') . '</a>';
                    if (auth()->user()->can("type_delete"))
                        $td .= '<a href="javascript:void(0)" data-id="' . $row->id . '" data-url="' . route('admin.types.destroy', $row->id) . '"  class="btn btn-sm btn-danger delete-btn">' . __('buttons.delete') . '</a>';
                    $td .= "</div>";
                    $td .= "</td>";
                    return $td;
                })
                ->editColumn("created_at", fn ($row) => formatDate($row->created_at))
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.types.index');
    }

    public function create()
    {
        //check permission
        $this->authorize("type_add");

        return view('admin.types.create');
    }

    public function store(TypeRequest $request)
    {
        //check permission
        $this->authorize("type_add");

        try {
            $validated = $request->validated();
            Type::create($validated);

            return redirect()->route('admin.types.index')->with([
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

    public function edit(Type $type)
    {
        //check permission
        $this->authorize("type_edit");

        return view('admin.types.edit', compact("type"));
    }

    public function update(TypeRequest $request, Type $type)
    {
        //check permission
        $this->authorize("type_edit");

        try {
            $validated = $request->validated();
            $type->update($validated);

            return redirect()->route('admin.types.index')->with([
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

    public function destroy(Type $type)
    {
        //check permission
        $this->authorize("type_delete");

        $type->delete();
        return redirect()->route('admin.types.index');
    }
}

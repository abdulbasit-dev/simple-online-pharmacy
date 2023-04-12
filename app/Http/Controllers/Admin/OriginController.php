<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Origin;
use App\Http\Requests\OriginRequest;
use Illuminate\Http\Request;
use DataTables;

class OriginController extends Controller
{
    public function index(Request $request)
    {
        //check permission
        $this->authorize("origin_view");

        if ($request->ajax()) {
            $data = Origin::query();
            return Datatables::of($data)->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $td = '<td>';
                    $td .= '<div class="d-flex">';
                    if (auth()->user()->can("origin_edit"))
                        $td .= '<a href="' . route('admin.origins.edit', $row->id) . '" type="button" class="btn btn-sm btn-info waves-effect waves-light me-1">' . __('buttons.edit') . '</a>';
                    if (auth()->user()->can("origin_delete"))
                        $td .= '<a href="javascript:void(0)" data-id="' . $row->id . '" data-url="' . route('admin.origins.destroy', $row->id) . '"  class="btn btn-sm btn-danger delete-btn">' . __('buttons.delete') . '</a>';
                    $td .= "</div>";
                    $td .= "</td>";
                    return $td;
                })
                ->editColumn("created_at", fn ($row) => formatDate($row->created_at))
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.origins.index');
    }

    public function create()
    {
        //check permission
        $this->authorize("origin_add");

        return view('admin.origins.create');
    }

    public function store(OriginRequest $request)
    {
        //check permission
        $this->authorize("origin_add");

        try {
            $validated = $request->validated();
            Origin::create($validated);

            return redirect()->route('admin.origins.index')->with([
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

    public function edit(Origin $origin)
    {
        //check permission
        $this->authorize("origin_edit");

        return view('admin.origins.edit', compact("origin"));
    }

    public function update(OriginRequest $request, Origin $origin)
    {
        //check permission
        $this->authorize("origin_edit");

        try {
            $validated = $request->validated();
            $origin->update($validated);

            return redirect()->route('admin.origins.index')->with([
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

    public function destroy(Origin $origin)
    {
        //check permission
        $this->authorize("origin_delete");

        $origin->delete();
        return redirect()->route('admin.origins.index');
    }
}

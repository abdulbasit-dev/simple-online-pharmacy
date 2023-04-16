<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Medicine;
use App\Http\Requests\medicineRequest;
use App\Models\Origin;
use App\Models\Type;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MedicineController extends Controller
{
    public function index(Request $request)
    {
        //check permission
        $this->authorize("medicine_view");

        if ($request->ajax()) {
            $data = Medicine::query()
                ->withCount('orders')
                ->when($request->status, function ($query) use ($request) {
                    switch ($request->status) {
                        case 'today':
                            return $query->whereDate('medicine_time', Carbon::today());
                            break;

                        case 'upcoming':
                            return $query->whereDate('medicine_time', '>', Carbon::today());
                            break;

                        case 'finished':
                            return  $query->whereDate('medicine_time', '<', Carbon::today());
                            break;
                        default:
                            return $query;
                            break;
                    }
                });

            return Datatables::of($data)->addIndexColumn()
                ->setRowClass(fn ($data) => 'align-middle')
                ->addColumn('action', function ($row) {
                    $td = '<td>';
                    $td .= '<div class="d-flex align-items-center justify-content-center">';
                    // check user permission
                    if (auth()->user()->can("season_ticket_view"))
                        $td .= '<a href="' . route('admin.medicines.show', $row->id) . '" type="button" class="btn btn-sm btn-primary waves-effect waves-light me-1">' . __('buttons.view') . '</a>';
                    if (auth()->user()->can("medicine_edit")) {
                        if ($row->orders_count > 0) {
                            $td .= '<a href="javascript:void(0)" type="button" class="btn btn-sm btn-info waves-effect waves-light me-1 disabled"  >' . __('buttons.edit') . '</a>';
                        } else {
                            $td .= '<a href="' . route('admin.medicines.edit', $row->id) . '" type="button" class="btn btn-sm btn-info waves-effect waves-light me-1">' . __('buttons.edit') . '</a>';
                        }
                    }

                    if (auth()->user()->can("medicine_delete")) {
                        if ($row->orders_count > 0) {
                            $td .= '<a href="javascript:void(0)" class="btn btn-sm btn-danger disabled">' . __('buttons.delete') . '</a>';
                        } else {
                            $td .= '<a href="javascript:void(0)" data-id="' . $row->id . '" data-url="' . route('admin.medicines.destroy', $row->id) . '"  class="btn btn-sm btn-danger delete-btn">' . __('buttons.delete') . '</a>';
                        }
                    }

                    $td .= "</div>";
                    $td .= "</td>";
                    return $td;
                })
                ->addColumn('image', function ($row) {
                    $td = '<td>';
                    $td .= '<div class="d-flex flex-column align-items-center justify-content-center">';
                    $td .= '<img src="' . getFile($row) . '" class="img-thumbnail avatar-lg">';
                    $td .= "</div>";
                    $td .= "</td>";
                    return $td;
                })
                // ->addColumn('information', function ($row) {
                //     $td = '<td>';
                //     $td .= '<div class="">';

                //     $td .= '<p class="fw-bold">Type: <span class="badge badge-pill badge-soft-primary font-size-13 p-1 ms-2">' . $row->type->name  ?? "---". '</span></p>';
                //     $td .= '<p class="fw-bold">Origin: <span class="badge badge-pill badge-soft-info font-size-13  px-2 py-1 ms-2">' . $row->origin->name ?? "---"  . '</span></p>';
                //     $td .= "</div>";
                //     $td .= "</td>";
                //     return $td;
                // })
                ->addColumn("type", fn ($row) => '<span class="badge badge-pill badge-soft-primary font-size-13 p-2">' . $row->type->name ?? "---" . '</span>')
                ->addColumn("origin", fn ($row) => '<span class="badge badge-pill badge-soft-warning font-size-13 p-2">' . $row->origin->name ?? "---" . '</span>')
                ->addColumn("quantity", fn ($row) => '<span class="badge badge-pill badge-soft-info font-size-13 p-2">' . $row->quantity . '</span>')
                ->editColumn("price", fn ($row) => formatPrice($row->price))
                ->editColumn("expire_at", fn ($row) => formatDate($row->expire_at))
                ->editColumn("created_at", fn ($row) => formatDate($row->created_at))
                ->rawColumns(['action', 'image', 'type', 'origin', 'information', 'quantity'])
                ->make(true);
        }

        return view('admin.medicines.index');
    }

    public function create()
    {
        //check permission
        $this->authorize("medicine_add");

        $origins = Origin::pluck("name", "id");
        $types = Type::pluck("name", "id");

        return view('admin.medicines.create', compact('origins', 'types'));
    }

    public function store(MedicineRequest $request)
    {
        //check permission
        $this->authorize("medicine_add");

        // begin transaction
        DB::beginTransaction();
        try {
            $validated = $request->validated();
            $validated["slug"] = Str::slug($request->name);
            $team = Medicine::create($validated);

            if ($request->has('file')) {

                // get file name from request and find this file in the storage
                $filePath = storage_path('tmp/uploads/' . $request->file);
                // this will move the file from its current path to the storage path
                $team->addMedia($filePath)->usingName($request->name)->toMediaCollection();
            }

            // commit transaction
            DB::commit();

            return redirect()->route('admin.medicines.index')->with([
                "message" =>  "Medicine created successfully",
                "icon" => "success",
            ]);
        } catch (\Throwable $th) {
            // rollback transaction
            DB::rollback();

            // throw $th;
            return redirect()->back()->with([
                "message" =>  $th->getMessage(),
                "icon" => "error",
            ]);
        }
    }

    public function show(Medicine $medicine)
    {
        //check permission
        $this->authorize("medicine_view");

        $medicine->load("type:id,name", "origin:id,name");
        $medicine->orders_count = $medicine->orders()->count();

        return view('admin.medicines.show', compact('medicine'));
    }

    public function edit(Medicine $medicine)
    {
        //check permission
        $this->authorize("medicine_edit");

        $medicine->orders_count = $medicine->orders()->count();
        $origins = Origin::pluck("name", "id");
        $types = Type::pluck("name", "id");

        return view('admin.medicines.edit', compact("medicine", "origins", "types"));
    }

    public function update(MedicineRequest $request, Medicine $medicine)
    {
        //check permission
        $this->authorize("medicine_edit");

        // begin transaction
        DB::beginTransaction();
        try {
            $validated = $request->validated();
            $validated["slug"] = Str::slug($request->name);
            $medicine->update($validated);

            if ($request->has('file')) {
                // delete old product image
                $mediaItems = $medicine->getMedia();
                if (count($mediaItems) > 0) {
                    $mediaItems->each(function ($item, $key) {
                        $item->delete();
                    });
                }

                // get file name from request and find this file in the storage
                $filePath = storage_path('tmp/uploads/' . $request->file);

                // this will move the file from its current path to the storage path
                $medicine->addMedia($filePath)->usingName($request->name)->toMediaCollection();
            }

            // commit transaction
            DB::commit();

            return redirect()->route('admin.medicines.index')->with([
                "message" =>  "Medicine updated successfully",
                "icon" => "success",
            ]);
        } catch (\Throwable $th) {
            // rollback transaction
            DB::rollback();

            return redirect()->back()->with([
                "message" =>  $th->getMessage(),
                "icon" => "error",
            ]);
        }
    }

    public function destroy(Medicine $medicine)
    {
        //check permission
        $this->authorize("medicine_delete");

        if ($medicine->orders()->count() > 0) {
            return redirect()->back()->with([
                "message" => "medicine Ticket is already in sale, you can not edit it.",
                "icon" => "warning",
                "timer" => 5000,
            ]);
        }

        //remove image
        $mediaItems = $medicine->getMedia();
        if (count($mediaItems) > 0) {
            $mediaItems->each(function ($item, $key) {
                $item->delete();
            });
        }

        $medicine->delete();
        return redirect()->route('admin.medicines.index');
    }
}

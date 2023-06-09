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
        if ($request->ajax()) {
            $data = Medicine::query()
                ->withCount('orders')
                ->when($request->status, function ($query) use ($request) {
                    switch ($request->status) {
                        case 'expired':
                            return  $query->whereDate('expire_at', '<', Carbon::today());
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
                    $td .= '<a href="' . route('admin.medicines.show', $row->id) . '" type="button" class="btn btn-sm btn-primary waves-effect waves-light me-1">' . __('buttons.view') . '</a>';
                    if ($row->orders_count > 0) {
                        $td .= '<a href="javascript:void(0)" type="button" class="btn btn-sm btn-info waves-effect waves-light me-1 disabled"  >' . __('buttons.edit') . '</a>';
                    } else {
                        $td .= '<a href="' . route('admin.medicines.edit', $row->id) . '" type="button" class="btn btn-sm btn-info waves-effect waves-light me-1">' . __('buttons.edit') . '</a>';
                    }

                    if ($row->orders_count > 0) {
                        $td .= '<a href="javascript:void(0)" class="btn btn-sm btn-danger disabled">' . __('buttons.delete') . '</a>';
                    } else {
                        $td .= '<a href="javascript:void(0)" data-id="' . $row->id . '" data-url="' . route('admin.medicines.destroy', $row->id) . '"  class="btn btn-sm btn-danger delete-btn">' . __('buttons.delete') . '</a>';
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
                ->editColumn('expire_at', function ($row) {
                    // check if one month remain to expire
                    $diff = getDayDiff($row->expire_at);
                    if ($diff <= 30 && $diff > 0) {
                        return '<span class="badge badge-pill badge-soft-warning font-size-13">' . formatDate($row->expire_at) . '</span>';
                    } elseif ($diff <= 0) {
                        return '<span class="badge badge-pill badge-soft-danger font-size-13">' . formatDate($row->expire_at) . '</span>';
                    } else {
                        return formatDate($row->expire_at);
                    }
                })
                ->editColumn('quantity', function ($row) {
                    $qty = $row->quantity;
                    $stockAlert = 10;
                    if ($qty <= $stockAlert && $qty > 0) {
                        return '<span class="badge badge-pill badge-soft-warning font-size-13 p-2">' . $qty . '</span>';
                    } elseif ($qty == 0) {
                        return '<span class="badge badge-pill badge-soft-danger font-size-13 p-2">' . $qty . '</span>';
                    } else {
                        return '<span class="badge badge-pill badge-soft-info font-size-13 p-2">' . $qty . '</span>';
                    }
                })
                ->addColumn("type", fn ($row) => '<span class="badge badge-pill badge-soft-primary font-size-13 p-2">' . $row->type->name ?? "---" . '</span>')
                ->addColumn("origin", fn ($row) => '<span class="badge badge-pill badge-soft-warning font-size-13 p-2">' . $row->origin->name ?? "---" . '</span>')
                ->editColumn("price", fn ($row) => formatPrice($row->price))
                ->editColumn("created_at", fn ($row) => formatDate($row->created_at))
                ->rawColumns(['action', 'image', 'type', 'origin', 'information', 'quantity', "expire_at"])
                ->make(true);
        }

        return view('admin.medicines.index');
    }

    public function create()
    {
        $origins = Origin::pluck("name", "id");
        $types = Type::pluck("name", "id");

        return view('admin.medicines.create', compact('origins', 'types'));
    }

    public function store(MedicineRequest $request)
    {
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
        $medicine->load("type:id,name", "origin:id,name");
        $medicine->orders_count = $medicine->orders()->count();

        return view('admin.medicines.show', compact('medicine'));
    }

    public function edit(Medicine $medicine)
    {
        $medicine->orders_count = $medicine->orders()->count();
        $origins = Origin::pluck("name", "id");
        $types = Type::pluck("name", "id");

        return view('admin.medicines.edit', compact("medicine", "origins", "types"));
    }

    public function update(MedicineRequest $request, Medicine $medicine)
    {
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

<?php

namespace App\Http\Controllers\Admin;

use App\Models\Banner;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;

class BannerController extends Controller
{
    public function index(Request $request)
    {
        //check permission
        $this->authorize("banner_view");

        if ($request->ajax()) {
            $data = Banner::query();
            return Datatables::of($data)
                ->addIndexColumn()
                ->setRowClass(fn ($row) => 'align-middle')
                ->addColumn('action', function ($row) {
                    $td = '<td>';
                    $td .= '<div class="d-flex">';
                    // if (auth()->user()->can("banner_view"))
                    //  $td .= '<a href="' . route('admin.banners.show', $row->id) . '" type="button" class="btn btn-sm btn-primary waves-effect waves-light me-1">' . __('buttons.view') . '</a>';
                    if (auth()->user()->can("banner_edit"))
                        $td .= '<a href="' . route('admin.banners.edit', $row->id) . '" type="button" class="btn btn-sm btn-info waves-effect waves-light me-1">' . __('buttons.edit') . '</a>';
                    if (auth()->user()->can("banner_delete"))
                        $td .= '<a href="javascript:void(0)" data-id="' . $row->id . '" data-url="' . route('admin.banners.destroy', $row->id) . '"  class="btn btn-sm btn-danger delete-btn">' . __('buttons.delete') . '</a>';
                    $td .= "</div>";
                    $td .= "</td>";
                    return $td;
                })
                ->addColumn('banner', function ($row) {
                    $banner = getFile($row, "season-ticket");
                    $td = '<td>';
                    $td .= '<div class="d-flex align-items-center justify-content-start">';
                    $td .= '<img src="' . $banner . '" class="img-thumbnail w-50 bannerLightBox cursor-pointer" alt="banner" data-bs-toggle="tooltip" data-bs-placement="top" title="Click to Expand">';
                    $td .= "</div>";
                    $td .= "</td>";
                    return $td;
                })
                ->addColumn('status', function ($row) {
                    $checked = $row->status ? "checked" : null;
                    $td = '<td>';
                    $td .= '<input type="checkbox" class="change-status-btn"  data-id="' . $row->id . '" data-url="' . route('admin.banners.changeStatus', $row->id) . '" id="banner-status' . $row->id . '" switch="none" ' . $checked . ' />';
                    $td .= '<label for="banner-status' . $row->id . '" data-on-label="On" data-off-label="Off"></label>';
                    $td .= "</td>";
                    return $td;
                })
                ->editColumn("created_at", fn ($row) => formatDate($row->created_at))
                ->editColumn("created_by", fn ($row) => '<span class="badge badge-pill badge-soft-info font-size-13 p-2">' . $row->createdBy->name ?? "---" . '</span>')
                ->rawColumns(['action', 'banner', 'created_by', "status"])
                ->make(true);
        }

        return view('admin.banners.index');
    }

    public function create()
    {
        //check permission
        $this->authorize("banner_add");

        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        //check permission
        $this->authorize("banner_add");

        // begin transaction
        DB::beginTransaction();
        try {
            $banner = Banner::create([
                "name" => $request->name,
            ]);

            if ($request->has('file')) {
                // get file name from request and find this file in the storage
                $filePath = storage_path('tmp/uploads/' . $request->file);
                // this will move the file from its current path to the storage path
                $banner->addMedia($filePath)->usingName($request->name)->toMediaCollection("season-ticket");
            }

            // commit transaction
            DB::commit();
            return redirect()->route('admin.banners.index')->with([
                "message" =>  __('messages.success'),
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

    public function edit(Banner $banner)
    {
        //check permission
        $this->authorize("banner_edit");

        return view('admin.banners.edit', compact("banner"));
    }

    public function update(Request $request, Banner $banner)
    {
        //check permission
        $this->authorize("banner_edit");

        // begin transaction
        DB::beginTransaction();
        try {
            $banner->update([
                "name" => $request->name,
            ]);

            if ($request->has('file')) {
                // delete old image
                $mediaItems = $banner->getMedia("season-ticket");
                if (count($mediaItems) > 0) {
                    $mediaItems->each(function ($item, $key) {
                        $item->delete();
                    });
                }

                // get file name from request and find this file in the storage
                $filePath = storage_path('tmp/uploads/' . $request->file);

                // this will move the file from its current path to the storage path
                $banner->addMedia($filePath)->usingName($request->name)->toMediaCollection("season-ticket");
            }

            // commit transaction
            DB::commit();

            return redirect()->route('admin.banners.index')->with([
                "message" =>  __('messages.update'),
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

    public function destroy(Banner $banner)
    {
        //check permission
        $this->authorize("banner_delete");

        //remove image
        $mediaItems = $banner->getMedia();
        if (count($mediaItems) > 0) {
            $mediaItems->each(function ($item, $key) {
                $item->delete();
            });
        }

        $banner->delete();
        return redirect()->route('admin.banners.index');
    }

    public function changeStatus(Banner $banner)
    {
        //check permission
        $this->authorize("banner_edit");

        $banner->update([
            "status" => !$banner->status
        ]);

        return redirect()->route('admin.banners.index');
    }
}

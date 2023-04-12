<?php

namespace App\Http\Controllers\Admin;

use App\Models\Contact;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        //check permission
        $this->authorize("contact_view");

        if ($request->ajax()) {
            $data = Contact::query();
            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $td = '<td>';
                $td .= '<div class="d-flex">';
                    if (auth()->user()->can("contact_view"))
                     $td .= '<a href="' . route('admin.contacts.show', $row->id) . '" type="button" class="btn btn-sm btn-primary waves-effect waves-light me-1">' . __('buttons.view') . '</a>';
                    if (auth()->user()->can("contact_delete"))
                    $td .= '<a href="javascript:void(0)" data-id="' . $row->id . '" data-url="' . route('admin.contacts.destroy', $row->id). '"  class="btn btn-sm btn-danger delete-btn">' . __('buttons.delete') . '</a>';
                $td .= "</div>";
                $td .= "</td>";
                return $td;
            })
            ->editColumn("created_at", fn($row) => formatDate($row->created_at))
            ->rawColumns(['action'])
            ->make(true);
        }

        return view('admin.contacts.index');
    }

    public function show(Contact $contact)
    {
        //check permission
        $this->authorize("contact_view");

        return view('admin.contacts.show', compact("contact"));
    }

    public function destroy(Contact $contact)
    {
        //check permission
        $this->authorize("contact_delete");

        $contact->delete();
        return redirect()->route('admin.contacts.index');
    }
}

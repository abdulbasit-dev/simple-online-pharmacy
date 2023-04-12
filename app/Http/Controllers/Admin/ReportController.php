<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gate;
use App\Models\Ticket;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Http\Response;

class ReportController extends Controller
{
    public function gate(Request $request)
    {
        //check permission
        // $this->authorize("gate_report_view");

        return view('admin.reports.gate-report');
    }
}
